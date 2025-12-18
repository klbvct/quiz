<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\QuizSession;
use App\Models\QuizAnswer;
use App\Models\QuizResult;
use Barryvdh\DomPDF\Facade\Pdf;

class QuizControllerNew extends Controller
{
    /**
     * Загрузка данных модуля
     */
    private function loadModuleData($module)
    {
        $path = storage_path("app/quiz_modules/module{$module}.json");
        
        if (!file_exists($path)) {
            return null;
        }
        
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    /**
     * Расчет результатов
     */
    public function calculateResults($session)
    {
        // Загружаем интерпретатор
        $interpretationPath = resource_path('ai_templates/interpretation.json');
        if (!file_exists($interpretationPath)) {
            \Log::error('interpretation.json not found');
            return null;
        }
        
        $interpretation = json_decode(file_get_contents($interpretationPath), true);
        
        // Получаем все ответы пользователя
        $answers = QuizAnswer::where('session_id', $session->id)->get();
        
        // Группируем ответы по модулям
        $answersByModule = $answers->groupBy('module_number');
        $moduleScores = [];
        
        // Подсчёт для каждого модуля
        for ($module = 1; $module <= 8; $module++) {
            $moduleAnswers = $answersByModule->get($module, collect());
            
            if ($moduleAnswers->isEmpty()) {
                continue;
            }
            
            switch ($module) {
                case 1:
                    // Домінуючі типи мислення
                    $moduleScores['module1'] = $this->calculateModule1($moduleAnswers, $interpretation);
                    break;
                case 2:
                    // Індивідуальні інтереси і здібності
                    $moduleScores['module2'] = $this->calculateModule2($moduleAnswers, $interpretation);
                    break;
                case 3:
                    // Типи професійної взаємодії (Климов)
                    $moduleScores['module3'] = $this->calculateModule3($moduleAnswers, $interpretation);
                    break;
                case 4:
                    // Типологія сприйняття
                    $moduleScores['module4'] = $this->calculateModule4($moduleAnswers, $interpretation);
                    break;
                case 5:
                    // Типи інтелекту за Гарднером
                    $moduleScores['module5'] = $this->calculateModule5($moduleAnswers, $interpretation);
                    break;
                case 6:
                    // Орієнтаційна анкета (Басс)
                    $moduleScores['module6'] = $this->calculateModule6($moduleAnswers, $interpretation);
                    break;
                case 7:
                    // Кар'єрні якоря (Шейн)
                    $moduleScores['module7'] = $this->calculateModule7($moduleAnswers, $interpretation);
                    break;
                case 8:
                    // Темперамент
                    $moduleScores['module8'] = $this->calculateModule8($moduleAnswers, $interpretation);
                    break;
            }
        }
        
        // Формируем рекомендации
        $recommendations = $this->generateRecommendations($moduleScores, $interpretation);
        
        // Сохраняем результаты
        $result = QuizResult::updateOrCreate(
            ['session_id' => $session->id],
            [
                'user_id' => $session->user_id,
                'module_scores' => $moduleScores,
                'recommendations' => $recommendations,
                'summary' => $this->generateSummary($moduleScores, $interpretation)
            ]
        );
        
        return $result;
    }
    
    /**
     * Модуль 1: Домінуючі типи мислення
     */
    private function calculateModule1($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module1']['scoring_keys'];
        $scores = [
            'artistic' => 0,         // Художнє
            'theoretical' => 0,      // Теоретичне
            'practical' => 0,        // Практичне
            'creative' => 0,         // Творче
            'convergent' => 0,       // Конвергентне
            'intuitive' => 0,        // Інтуїтивне
            'analytical' => 0        // Аналітичне
        ];

        foreach ($answers as $answer) {
            $q = (string)$answer->question_number;
            $a = $answer->answer; // 'a' або 'b'
            
            if (isset($keys[$q][$a])) {
                $scale = $keys[$q][$a];
                $scores[$scale]++;
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 2: Індивідуальні інтереси і здібності в навчанні
     */
    private function calculateModule2($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module2']['scoring_keys'];
        $scaleValues = $interpretation['modules']['module2']['scale_values'];
        
        $scores = [];
        
        // Инициализация всех сфер нулями
        foreach ($keys as $sphere => $questions) {
            $scores[$sphere] = 0;
        }

        foreach ($answers as $answer) {
            $q = $answer->question_number;
            $value = $scaleValues[$answer->answer] ?? 0;
            
            // Ищем, к какой сфере относится вопрос
            foreach ($keys as $sphere => $questions) {
                if (in_array($q, $questions)) {
                    $scores[$sphere] += $value;
                    break;
                }
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 3: Типи професійної взаємодії (Климов ДДО)
     */
    private function calculateModule3($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module3']['scoring_keys'];
        $scores = [
            'nature' => 0,   // Людина-Природа
            'technic' => 0,  // Людина-Техніка
            'human' => 0,    // Людина-Людина
            'sign' => 0,     // Людина-Знакова система
            'art' => 0       // Людина-Художній образ
        ];

        foreach ($answers as $answer) {
            $q = (string)$answer->question_number;
            $a = $answer->answer; // 'a' або 'b'
            
            if (isset($keys[$q][$a])) {
                $scale = $keys[$q][$a];
                $scores[$scale]++;
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 4: Типологія сприйняття
     */
    private function calculateModule4($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module4']['scoring_keys'];
        $scores = [
            'kinesthetic' => 0,  // Кінестетик
            'discrete' => 0,     // Дискрет
            'audial' => 0,       // Аудіал
            'visual' => 0        // Візуал
        ];

        foreach ($answers as $answer) {
            $q = (string)$answer->question_number;
            $a = $answer->answer; // 'a' або 'b'
            
            if (isset($keys[$q][$a])) {
                $scale = $keys[$q][$a];
                $scores[$scale]++;
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 5: Типи інтелекту за Говардом Гарднером
     */
    private function calculateModule5($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module5']['scoring_keys'];
        $scores = [
            'linguistic' => 0,              // Лінгвістичний
            'logical_mathematical' => 0,    // Логіко-математичний
            'spatial' => 0,                 // Просторовий
            'bodily_kinesthetic' => 0,      // Тілесно-кінестетичний
            'musical' => 0,                 // Музичний
            'interpersonal' => 0,           // Міжособистісний
            'intrapersonal' => 0,           // Внутрішньоособистісний
            'naturalistic' => 0             // Натуралістичний
        ];

        // Создаём массив ответов по номерам вопросов
        $answersByQuestion = [];
        foreach ($answers as $answer) {
            $answersByQuestion[$answer->question_number] = (int)$answer->answer;
        }

        // Подсчитываем баллы по каждому типу интеллекта
        foreach ($keys as $intelligenceType => $questions) {
            foreach ($questions as $q) {
                if (isset($answersByQuestion[$q])) {
                    $scores[$intelligenceType] += $answersByQuestion[$q];
                }
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 6: Орієнтаційна анкета (Басс)
     */
    private function calculateModule6($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module6']['scoring_keys'];
        $scores = [
            'self' => 0,         // На себе
            'interaction' => 0,  // На взаємодію
            'task' => 0          // На завдання
        ];

        // Создаём массив ответов по номерам вопросов
        $answersByQuestion = [];
        foreach ($answers as $answer) {
            $answersByQuestion[$answer->question_number] = (int)$answer->answer;
        }

        // Подсчитываем баллы по каждой направленности
        foreach ($keys as $direction => $directionKeys) {
            // Прямые вопросы
            foreach ($directionKeys['direct'] as $q) {
                if (isset($answersByQuestion[$q])) {
                    $scores[$direction] += $answersByQuestion[$q];
                }
            }
            // Обратные вопросы (вычитаем из 6)
            if (isset($directionKeys['reverse'])) {
                foreach ($directionKeys['reverse'] as $q) {
                    if (isset($answersByQuestion[$q])) {
                        $scores[$direction] += (6 - $answersByQuestion[$q]);
                    }
                }
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 7: Кар'єрні якоря (Шейн)
     */
    private function calculateModule7($answers, $interpretation)
    {
        $keys = $interpretation['modules']['module7']['scoring_keys'];
        $scores = [
            'professional_competence' => 0,  // Професійна компетентність
            'management' => 0,                // Менеджмент
            'autonomy' => 0,                  // Автономія
            'stability_place' => 0,           // Стабільність місця роботи
            'stability_residence' => 0,       // Стабільність місця проживання
            'service' => 0,                   // Служіння
            'challenge' => 0,                 // Виклик
            'entrepreneurship' => 0           // Підприємництво
        ];

        // Создаём массив ответов по номерам вопросов
        $answersByQuestion = [];
        foreach ($answers as $answer) {
            $answersByQuestion[$answer->question_number] = (int)$answer->answer;
        }

        // Суммируем баллы по каждому якорю
        foreach ($keys as $anchor => $questions) {
            foreach ($questions as $q) {
                if (isset($answersByQuestion[$q])) {
                    $scores[$anchor] += $answersByQuestion[$q];
                }
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 8: Темперамент (ранжирование 4 вариантов в каждом вопросе)
     */
    private function calculateModule8($answers, $interpretation)
    {
        $scores = [
            'choleric' => 0,     // Холерик
            'sanguine' => 0,     // Сангвінік
            'phlegmatic' => 0,   // Флегматик
            'melancholic' => 0   // Меланхолік
        ];

        // Модуль 8 использует ранжирование: [4,3,2,1] где 4=наиболее подходит, 1=наименее
        // Порядок типов в каждом вопросе: холерик, сангвиник, флегматик, меланхолик
        $types = ['choleric', 'sanguine', 'phlegmatic', 'melancholic'];

        foreach ($answers as $answer) {
            $ranking = json_decode($answer->answer, true);
            
            if (is_array($ranking) && count($ranking) === 4) {
                foreach ($ranking as $index => $rank) {
                    $type = $types[$index];
                    $scores[$type] += (int)$rank;
                }
            }
        }

        return $scores;
    }
    
    /**
     * Генерация рекомендаций
     */
    private function generateRecommendations($moduleScores, $interpretation)
    {
        $recommendations = [];
        
        // На основе модуля 3 (Типи професійної взаємодії - Климов)
        if (isset($moduleScores['module3'])) {
            $klimov = $moduleScores['module3'];
            arsort($klimov);
            
            $topTypes = array_slice(array_keys($klimov), 0, 2, true);
            
            $typeNames = [
                'nature' => 'Людина-Природа',
                'technic' => 'Людина-Техніка',
                'human' => 'Людина-Людина',
                'sign' => 'Людина-Знакова система',
                'art' => 'Людина-Художній образ'
            ];
            
            $recommendations['professional_types'] = [];
            
            foreach ($topTypes as $type) {
                $score = $klimov[$type];
                $scale = $interpretation['modules']['module3']['scales'][$type];
                
                // Берем рекомендации из professional_recommendations
                if (isset($interpretation['professional_recommendations'][$type])) {
                    $profRec = $interpretation['professional_recommendations'][$type];
                    
                    $recommendations['professional_types'][] = [
                        'type' => $typeNames[$type],
                        'score' => $score,
                        'description' => $scale['description'],
                        'majors' => $profRec['majors'] ?? [],
                        'minors' => $profRec['minors'] ?? []
                    ];
                }
            }
        }
        
        return $recommendations;
    }
    
    /**
     * Генерация краткого описания результатов
     */
    private function generateSummary($moduleScores, $interpretation)
    {
        $summary = "Ваш профіль професійних схильностей визначено на основі комплексної діагностики. ";
        
        // Анализ типов мышления (модуль 1)
        if (isset($moduleScores['module1'])) {
            $thinking = $moduleScores['module1'];
            arsort($thinking);
            $topThinking = array_key_first($thinking);
            
            $thinkingNames = [
                'artistic' => 'художнє (наочно-образне) мислення',
                'theoretical' => 'теоретичне мислення',
                'practical' => 'практичне мислення',
                'creative' => 'творче (продуктивне) мислення',
                'convergent' => 'конвергентне мислення (бізнес-мислення)',
                'intuitive' => 'інтуїтивне мислення',
                'analytical' => 'аналітичне мислення'
            ];
            
            $summary .= "У вас домінує " . ($thinkingNames[$topThinking] ?? $topThinking) . ". ";
        }
        
        // Анализ профессиональной взаємодії (модуль 3)
        if (isset($moduleScores['module3'])) {
            $klimov = $moduleScores['module3'];
            arsort($klimov);
            $topType = array_key_first($klimov);
            
            $typeNames = [
                'nature' => 'Людина-Природа',
                'technic' => 'Людина-Техніка',
                'human' => 'Людина-Людина',
                'sign' => 'Людина-Знакова система',
                'art' => 'Людина-Художній образ'
            ];
            
            $summary .= "Найбільша схильність до професійного типу: " . ($typeNames[$topType] ?? $topType) . ". ";
        }
        
        return $summary;
    }

    /**
     * Генерация PDF-отчёта для пользователя
     */
    public function generateReport($sessionId)
    {
        $session = QuizSession::with('user')->findOrFail($sessionId);
        
        // Проверяем, что пользователь имеет доступ к этому отчёту
        $user = Auth::user();
        if (!$user->is_admin && $session->user_id !== $user->id) {
            abort(403, 'Доступ заборонено');
        }
        
        // Получаем результаты
        $result = QuizResult::where('session_id', $sessionId)->first();
        
        if (!$result) {
            // Если результатов нет, пересчитываем
            $result = $this->calculateResults($session);
        }
        
        // Получаем все ответы
        $answers = QuizAnswer::where('session_id', $sessionId)->get();
        $totalAnswers = $answers->count();
        
        // Получаем баллы по модулям
        $scores = $result->module_scores;
        
        // Генерируем PDF
        $pdf = Pdf::loadView('reports.quiz-report-styled', [
            'user' => $session->user,
            'session' => $session,
            'result' => $result,
            'scores' => $scores,
            'totalAnswers' => $totalAnswers
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        // Формируем имя файла
        $fileName = 'quiz_report_' . $session->user->id . '_' . $session->id . '.pdf';
        
        return $pdf->download($fileName);
    }
    
    /**
     * Просмотр отчёта в браузере (HTML)
     */
    public function viewReport($sessionId)
    {
        $session = QuizSession::with('user')->findOrFail($sessionId);
        
        // Проверяем доступ
        $user = Auth::user();
        if (!$user->is_admin && $session->user_id !== $user->id) {
            abort(403, 'Доступ заборонено');
        }
        
        $result = QuizResult::where('session_id', $sessionId)->first();
        
        if (!$result) {
            $result = $this->calculateResults($session);
        }
        
        $answers = QuizAnswer::where('session_id', $sessionId)->get();
        $totalAnswers = $answers->count();
        $scores = $result->module_scores;
        
        return view('reports.quiz-report-styled', [
            'user' => $session->user,
            'session' => $session,
            'result' => $result,
            'scores' => $scores,
            'totalAnswers' => $totalAnswers
        ]);
    }
}
