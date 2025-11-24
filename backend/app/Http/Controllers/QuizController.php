<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\QuizSession;
use App\Models\QuizAnswer;
use App\Models\QuizResult;

class QuizController extends Controller
{
    /**
     * Начать новый тест или продолжить существующий
     */
    public function start()
    {
        $user = Auth::user();
        
        // Проверяем текущее состояние сессий
        $inProgressSession = QuizSession::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();
        
        if (!$inProgressSession) {
            // Создаем новую сессию (если пользователь начинает впервые или повторно)
            $inProgressSession = QuizSession::create([
                'user_id' => $user->id,
                'current_module' => 1,
                'current_question' => 0,
                'status' => 'in_progress'
            ]);
        }
        
        return redirect()->route('quiz.module', ['module' => $inProgressSession->current_module]);
    }
    
    /**
     * Показать модуль теста
     */
    public function showModule($module)
    {
        $user = Auth::user();
        
        // Получаем или создаем сессию
        $session = QuizSession::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();
        
        if (!$session) {
            // Создаем новую сессию если её нет
            $session = QuizSession::create([
                'user_id' => $user->id,
                'current_module' => $module,
                'current_question' => 0,
                'status' => 'in_progress'
            ]);
        }
        
        // Проверяем, что пользователь не пытается перейти вперед
        if ($module > $session->current_module) {
            return redirect()->route('quiz.module', ['module' => $session->current_module]);
        }
        
        // Загружаем вопросы модуля из JSON
        $moduleData = $this->loadModuleData($module);
        
        if (!$moduleData) {
            abort(404, 'Модуль не найден');
        }
        
        // Получаем сохраненные ответы для этого модуля
        $savedAnswers = QuizAnswer::where('session_id', $session->id)
            ->where('module_number', $module)
            ->pluck('answer', 'question_number')
            ->toArray();
        
        return view('quiz.module', [
            'module' => $module,
            'moduleData' => $moduleData,
            'session' => $session,
            'savedAnswers' => $savedAnswers
        ]);
    }
    
    /**
     * Сохранить ответы модуля
     */
    public function saveAnswers(Request $request, $module)
    {
        $user = Auth::user();
        
        $session = QuizSession::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();
        
        $answers = $request->input('answers', []);
        
        // Сохраняем ответы
        foreach ($answers as $questionNumber => $answer) {
            QuizAnswer::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'module_number' => $module,
                    'question_number' => $questionNumber
                ],
                [
                    'answer' => is_array($answer) ? json_encode($answer) : $answer
                ]
            );
        }
        
        // Если это последний модуль, завершаем тест
        if ($module == 8) {
            $session->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
            
            // Подсчитываем результаты
            $this->calculateResults($session);
            
            return redirect()->route('quiz.results');
        }
        
        // Переходим к следующему модулю
        $nextModule = $module + 1;
        $session->update(['current_module' => $nextModule]);
        
        return redirect()->route('quiz.module', ['module' => $nextModule]);
    }
    
    /**
     * Показать результаты теста
     */
    public function results()
    {
        $user = Auth::user();
        
        $session = QuizSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->firstOrFail();
        
        $result = QuizResult::where('session_id', $session->id)->first();
        
        if (!$result) {
            // Если результаты еще не подсчитаны, подсчитываем их
            $result = $this->calculateResults($session);
        }
        
        return view('quiz.results', [
            'session' => $session,
            'result' => $result
        ]);
    }
    
    /**
     * Загрузить данные модуля из JSON
     */
    private function loadModuleData($module)
    {
        $path = storage_path("app/quiz/module{$module}.json");
        
        if (!file_exists($path)) {
            return null;
        }
        
        $json = file_get_contents($path);
        return json_decode($json, true);
    }
    
    /**
     * Подсчитать результаты теста
     */
    private function calculateResults($session)
    {
        // Получаем все ответы
        $answers = QuizAnswer::where('session_id', $session->id)->get();
        
        // Подсчитываем баллы по каждому модулю
        $moduleScores = [];
        
        // Модуль 1 - ДДО (Диференційно-діагностичний опитувальник)
        // Типы: Природа, Техніка, Людина, Знакова система, Художній образ
        $module1Scores = $this->calculateModule1($answers->where('module_number', 1));
        $moduleScores['module1'] = $module1Scores;
        
        // Модуль 2 - Карта интересов
        $module2Scores = $this->calculateModule2($answers->where('module_number', 2));
        $moduleScores['module2'] = $module2Scores;
        
        // Другие модули...
        
        // Формируем рекомендации
        $recommendations = $this->generateRecommendations($moduleScores);
        
        // Сохраняем результаты
        $result = QuizResult::updateOrCreate(
            ['session_id' => $session->id],
            [
                'user_id' => $session->user_id,
                'module_scores' => $moduleScores,
                'recommendations' => $recommendations,
                'summary' => $this->generateSummary($moduleScores)
            ]
        );
        
        return $result;
    }
    
    /**
     * Подсчет результатов модуля 1 (ДДО)
     */
    private function calculateModule1($answers)
    {
        // Ключи для подсчета баллов ДДО
        // a - вопросы: 1,3,6,10,11,13,16,20 = Природа
        // b - вопросы: 1,4,7,9,11,14,17,19,20 = Техніка
        // a - вопросы: 2,6,8,12,13,14,16,18 = Людина
        // b - вопросы: 2,5,9,10,12,15,19 = Знакова система
        // a - вопросы: 3,5,7,8,15,17,18 = Художній образ
        
        $scores = [
            'nature' => 0,      // Природа
            'technic' => 0,     // Техніка
            'human' => 0,       // Людина
            'sign' => 0,        // Знакова система
            'art' => 0          // Художній образ
        ];
        
        $natureA = [1, 3, 6, 10, 11, 13, 16, 20];
        $technicB = [1, 4, 7, 9, 11, 14, 17, 19, 20];
        $humanA = [2, 6, 8, 12, 13, 14, 16, 18];
        $signB = [2, 5, 9, 10, 12, 15, 19];
        $artA = [3, 5, 7, 8, 15, 17, 18];
        
        foreach ($answers as $answer) {
            $q = $answer->question_number;
            $a = $answer->answer;
            
            if ($a == 'a' && in_array($q, $natureA)) $scores['nature']++;
            if ($a == 'b' && in_array($q, $technicB)) $scores['technic']++;
            if ($a == 'a' && in_array($q, $humanA)) $scores['human']++;
            if ($a == 'b' && in_array($q, $signB)) $scores['sign']++;
            if ($a == 'a' && in_array($q, $artA)) $scores['art']++;
        }
        
        return $scores;
    }
    
    /**
     * Подсчет результатов модуля 2 (Карта интересов)
     */
    private function calculateModule2($answers)
    {
        // Карта интересов - подсчет баллов по направлениям
        $scores = [];
        
        foreach ($answers as $answer) {
            $value = $this->scaleToNumber($answer->answer);
            $scores[$answer->question_number] = $value;
        }
        
        return $scores;
    }
    
    /**
     * Преобразование шкалы в числа
     */
    private function scaleToNumber($scale)
    {
        $map = [
            '--' => -2,
            '-' => -1,
            '0' => 0,
            '+' => 1,
            '++' => 2
        ];
        
        return $map[$scale] ?? 0;
    }
    
    /**
     * Генерация рекомендаций
     */
    private function generateRecommendations($moduleScores)
    {
        $recommendations = [];
        
        // На основе модуля 1 (ДДО)
        if (isset($moduleScores['module1'])) {
            $ddo = $moduleScores['module1'];
            arsort($ddo);
            
            $topTypes = array_slice(array_keys($ddo), 0, 2);
            
            $typeNames = [
                'nature' => 'Людина-Природа',
                'technic' => 'Людина-Техніка',
                'human' => 'Людина-Людина',
                'sign' => 'Людина-Знакова система',
                'art' => 'Людина-Художній образ'
            ];
            
            $recommendations['professional_types'] = array_map(function($type) use ($typeNames) {
                return $typeNames[$type];
            }, $topTypes);
        }
        
        return $recommendations;
    }
    
    /**
     * Генерация краткого описания результатов
     */
    private function generateSummary($moduleScores)
    {
        $summary = "Ваш профіль професійних схильностей визначено на основі комплексної діагностики. ";
        
        if (isset($moduleScores['module1'])) {
            $ddo = $moduleScores['module1'];
            arsort($ddo);
            $topType = array_key_first($ddo);
            
            $descriptions = [
                'nature' => 'Ви маєте схильність до роботи з природою, рослинами та тваринами.',
                'technic' => 'Вам підходить робота з технікою, механізмами та технологіями.',
                'human' => 'Ви орієнтовані на роботу з людьми, навчання та комунікацію.',
                'sign' => 'Вам підходить робота зі знаковими системами, числами та інформацією.',
                'art' => 'Ви маєте схильність до творчості та художньої діяльності.'
            ];
            
            $summary .= $descriptions[$topType] ?? '';
        }
        
        return $summary;
    }
}
