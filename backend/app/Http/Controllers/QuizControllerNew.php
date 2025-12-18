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
                    // Типологія професійних інтересів за Голландом (RIASEC)
                    $moduleScores['module7'] = $this->calculateModule7($moduleAnswers, $interpretation);
                    break;
                case 8:
                    // Типологія сприйняття
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
     * Модуль 3: Домінуючі типи мислення
     */
    private function calculateModule3($answers, $interpretation)
    {
        // Поки що немає scoring_keys, використовуємо просту логіку
        // Якщо є ключі в інтерпретаторі - використовуємо їх
        $scores = [
            'artistic' => 0,         // Художнє
            'theoretical' => 0,      // Теоретичне
            'practical' => 0,        // Практичне
            'creative' => 0,         // Творче
            'convergent' => 0,       // Конвергентне
            'intuitive' => 0,        // Інтуїтивне
            'analytical' => 0        // Аналітичне
        ];

        // Якщо в інтерпретаторі є ключі - використовуємо їх
        if (isset($interpretation['modules']['module3']['scoring_keys'])) {
            $keys = $interpretation['modules']['module3']['scoring_keys'];
            
            foreach ($answers as $answer) {
                $q = (string)$answer->question_number;
                $a = $answer->answer; // 'a' або 'b'
                
                if (isset($keys[$q][$a])) {
                    $scale = $keys[$q][$a];
                    if (isset($scores[$scale])) {
                        $scores[$scale]++;
                    }
                }
            }
        } else {
            // Тимчасова логіка: розподіляємо відповіді по типах
            // Це буде працювати поки не налаштуємо scoring_keys
            $typesList = array_keys($scores);
            $totalTypes = count($typesList);
            
            foreach ($answers as $answer) {
                // Розподіляємо відповіді циклічно по типах
                $index = ($answer->question_number - 1) % $totalTypes;
                $type = $typesList[$index];
                
                // Додаємо бали в залежності від відповіді
                if ($answer->answer === 'a') {
                    $scores[$type]++;
                }
            }
        }

        return $scores;
    }
    
    /**
     * Модуль 4: Типологія сприйняття
     */
    private function calculateModule4($answers, $interpretation)
    {
        // Модуль 4: Ціннісні категорії (ранжування від 1 до 18)
        // Кожна відповідь містить ранг від 1 (найважливіше) до 18 (найменш важливе)
        $values = [];
        
        // Маппінг питань на назви цінностей
        $valueNames = [
            1 => 'Активне, діяльне життя',
            2 => 'Життєва мудрість',
            3 => 'Здоров\'я',
            4 => 'Цікава робота',
            5 => 'Краса природи та мистецтва',
            6 => 'Любов',
            7 => 'Матеріальна забезпеченість',
            8 => 'Наявність добрих друзів',
            9 => 'Впевненість у собі',
            10 => 'Пізнання',
            11 => 'Свобода',
            12 => 'Щасливе сімейне життя',
            13 => 'Творчість',
            14 => 'Суспільне визнання',
            15 => 'Розваги',
            16 => 'Продуктивне життя',
            17 => 'Розвиток',
            18 => 'Задоволеність собою'
        ];
        
        foreach ($answers as $answer) {
            $questionNum = $answer->question_number;
            $valueName = $valueNames[$questionNum] ?? "Цінність {$questionNum}";
            $rank = (int)$answer->answer; // Ранг від 1 до 18
            
            $values[$valueName] = $rank;
        }
        
        return $values;
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
        // Модуль 6: Мотиваційні фактори (шкала від -1 до 7)
        // -1 = абсолютне протиріччя, 7 = повна відповідність
        $motivations = [];
        
        // Групуємо по факторах (кожні 3 питання = 1 фактор)
        $factorNames = [
            'Високий заробіток',
            'Комфортні умови праці',
            'Можливість кар\'єрного зростання',
            'Визнання та повага',
            'Стабільність та надійність',
            'Цікава та різноманітна робота',
            'Самостійність та незалежність',
            'Креативність та інновації',
            'Можливість навчання',
            'Баланс роботи та життя',
            'Соціальна значущість',
            'Робота з людьми',
            'Можливість подорожувати',
            'Гнучкий графік',
            'Престижність професії',
            'Можливість самореалізації',
            'Безпечні умови праці',
            'Близькість до дому',
            'Дружній колектив'
        ];
        
        // Групуємо відповіді по 3
        $groupedAnswers = [];
        foreach ($answers as $answer) {
            $factorIndex = floor(($answer->question_number - 1) / 3);
            if (!isset($groupedAnswers[$factorIndex])) {
                $groupedAnswers[$factorIndex] = [];
            }
            $groupedAnswers[$factorIndex][] = (int)$answer->answer;
        }
        
        // Вираховуємо середній бал для кожного фактора
        foreach ($groupedAnswers as $index => $scores) {
            $factorName = $factorNames[$index] ?? "Фактор " . ($index + 1);
            $avgScore = array_sum($scores) / count($scores);
            $motivations[$factorName] = round($avgScore, 1);
        }
        
        return $motivations;
    }
    
    /**
     * Модуль 7: Типологія професійних інтересів за Голландом (RIASEC)
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
     * Модуль 8: Типологія сприйняття (ранжування 4 варіантів у кожному питанні)
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
        
        // ТИМЧАСОВО: Завжди перераховуємо результати для модуля 3
        // Видаляємо старий результат і рахуємо заново
        if ($result) {
            $result->delete();
        }
        $result = $this->calculateResults($session);
        
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
