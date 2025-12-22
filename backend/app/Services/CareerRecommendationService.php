<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CareerRecommendationService
{
    private $apiKey;
    private $model;
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model = env('GEMINI_MODEL', 'gemini-1.5-flash');
        $this->apiBaseUrl = env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models');
    }

    /**
     * Генерація професійних рекомендацій на основі результатів тестування
     */
    public function generateCareerPaths($moduleScores, $interpretation)
    {
        if (!$this->apiKey) {
            Log::warning('Gemini API key not configured, using fallback recommendations');
            return $this->getFallbackRecommendations($moduleScores);
        }

        try {
            $context = $this->prepareContext($moduleScores, $interpretation);
            $prompt = $this->buildPrompt($context);
            
            $apiUrl = "{$this->apiBaseUrl}/{$this->model}:generateContent?key={$this->apiKey}";
            
            $response = Http::timeout(60)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "Ти професійний кар'єрний консультант та експерт з профорієнтації. Твоя задача - надавати точні, персоналізовані рекомендації щодо професійних напрямків на основі результатів психологічного тестування.\n\n" . $prompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 2000,
                        'responseMimeType' => 'application/json'
                    ]
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if ($content) {
                    $careerPaths = json_decode($content, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && isset($careerPaths['career_paths'])) {
                        return $careerPaths['career_paths'];
                    }
                    
                    Log::warning('Invalid JSON response from Gemini', ['content' => $content]);
                }
            }

            Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getFallbackRecommendations($moduleScores);

        } catch (\Exception $e) {
            Log::error('Error generating career recommendations: ' . $e->getMessage());
            return $this->getFallbackRecommendations($moduleScores);
        }
    }

    /**
     * Підготовка контексту для AI
     */
    private function prepareContext($moduleScores, $interpretation)
    {
        $context = [];

        // Holland код (Модуль 7 - RIASEC)
        if (isset($moduleScores['module7'])) {
            $hollandScores = $moduleScores['module7'];
            arsort($hollandScores);
            $topThree = array_slice($hollandScores, 0, 3, true);
            
            $hollandTypes = [
                'realistic' => 'Практик (Realistic)',
                'investigative' => 'Дослідник (Investigative)',
                'artistic' => 'Творець (Artistic)',
                'social' => 'Помічник (Social)',
                'enterprising' => 'Лідер (Enterprising)',
                'conventional' => 'Організатор (Conventional)'
            ];
            
            $code = '';
            $types = [];
            foreach ($topThree as $type => $score) {
                $code .= strtoupper(substr($type, 0, 1));
                $types[] = $hollandTypes[$type] ?? $type;
            }
            
            $context['holland'] = [
                'code' => $code,
                'types' => $types,
                'scores' => $topThree
            ];
        }

        // Домінуючі типи мислення (Модуль 3)
        if (isset($moduleScores['module3'])) {
            $thinkingTypes = [
                'artistic' => 'Художнє (наочно-образне)',
                'theoretical' => 'Теоретичне',
                'practical' => 'Практичне',
                'creative' => 'Творче (продуктивне)',
                'convergent' => 'Конвергентне',
                'intuitive' => 'Інтуїтивне',
                'analytical' => 'Аналітичне'
            ];
            
            $thinking = $moduleScores['module3'];
            arsort($thinking);
            $topThinking = array_slice($thinking, 0, 3, true);
            
            $context['thinking'] = [];
            foreach ($topThinking as $type => $score) {
                $context['thinking'][] = $thinkingTypes[$type] ?? $type;
            }
        }

        // Типи інтелекту (Модуль 5 - Говард Гарднер)
        if (isset($moduleScores['module5'])) {
            $intelligenceTypes = [
                'linguistic' => 'Лінгвістичний',
                'logical' => 'Логіко-математичний',
                'spatial' => 'Просторовий',
                'bodily' => 'Тілесно-кінестетичний',
                'musical' => 'Музичний',
                'interpersonal' => 'Міжособистісний',
                'intrapersonal' => 'Внутрішньоособистісний',
                'naturalistic' => 'Натуралістичний'
            ];
            
            $intelligence = $moduleScores['module5'];
            arsort($intelligence);
            $topIntelligence = array_slice($intelligence, 0, 3, true);
            
            $context['intelligence'] = [];
            foreach ($topIntelligence as $type => $score) {
                $context['intelligence'][] = $intelligenceTypes[$type] ?? $type;
            }
        }

        // Ціннісні орієнтири (Модуль 4)
        if (isset($moduleScores['module4'])) {
            $valueNames = [
                'creativity' => 'Креативність',
                'independence' => 'Незалежність',
                'achievement' => 'Досягнення',
                'prestige' => 'Престиж',
                'altruism' => 'Альтруїзм',
                'security' => 'Безпека',
                'power' => 'Влада',
                'variety' => 'Різноманітність',
                'balance' => 'Баланс',
                'intellect' => 'Інтелект'
            ];
            
            $values = $moduleScores['module4'];
            asort($values); // Сортуємо за рангом (1 = найважливіше)
            $topValues = array_slice($values, 0, 3, true);
            
            $context['values'] = [];
            foreach (array_keys($topValues) as $value) {
                $context['values'][] = $valueNames[$value] ?? $value;
            }
        }

        // Мотиваційні фактори (Модуль 6)
        if (isset($moduleScores['module6'])) {
            $motivators = $moduleScores['module6'];
            arsort($motivators);
            $topMotivators = array_slice($motivators, 0, 3, true);
            
            $context['motivators'] = array_keys($topMotivators);
        }

        // Тип сприйняття (Модуль 8)
        if (isset($moduleScores['module8'])) {
            $perceptionTypes = [
                'visual' => 'Візуальний',
                'auditory' => 'Аудіальний',
                'kinesthetic' => 'Кінестетичний',
                'digital' => 'Дискретний/Дигітальний'
            ];
            
            $perception = $moduleScores['module8'];
            arsort($perception);
            $dominant = array_key_first($perception);
            
            $context['perception'] = $perceptionTypes[$dominant] ?? '';
        }

        // Професійні інтереси (Модуль 2)
        if (isset($moduleScores['module2'])) {
            $interests = $moduleScores['module2'];
            arsort($interests);
            $topInterests = array_slice($interests, 0, 5, true);
            
            $context['interests'] = array_keys($topInterests);
        }

        return $context;
    }

    /**
     * Побудова промпту для AI
     */
    private function buildPrompt($context)
    {
        $prompt = "На основі результатів комплексного профорієнтаційного тестування необхідно згенерувати персоналізовані рекомендації щодо професійних напрямків та фахів для навчання.\n\n";
        
        $prompt .= "**ПРОФІЛЬ КОРИСТУВАЧА:**\n\n";
        
        if (isset($context['holland'])) {
            $prompt .= "🎯 **Код Голланда (RIASEC):** {$context['holland']['code']}\n";
            $prompt .= "   Домінуючі типи: " . implode(', ', $context['holland']['types']) . "\n\n";
        }
        
        if (isset($context['thinking'])) {
            $prompt .= "🧠 **Домінуючі типи мислення:** " . implode(', ', $context['thinking']) . "\n\n";
        }
        
        if (isset($context['intelligence'])) {
            $prompt .= "💡 **Провідні типи інтелекту:** " . implode(', ', $context['intelligence']) . "\n\n";
        }
        
        if (isset($context['values'])) {
            $prompt .= "⭐ **Ключові цінності:** " . implode(', ', $context['values']) . "\n\n";
        }
        
        if (isset($context['perception'])) {
            $prompt .= "👁️ **Тип сприйняття:** {$context['perception']}\n\n";
        }
        
        if (isset($context['interests'])) {
            $prompt .= "📚 **Топ-інтереси:** " . implode(', ', $context['interests']) . "\n\n";
        }
        
        $prompt .= "---\n\n";
        $prompt .= "**ЗАВДАННЯ:**\n\n";
        $prompt .= "Згенеруй 3-4 професійні напрямки, які найбільше підходять цьому профілю. Для кожного напрямку визнач:\n";
        $prompt .= "- **Major** (основний бакалавріат) - 3-5 конкретних спеціальностей\n";
        $prompt .= "- **Minor** (додаткове навчання) - 2-4 суміжні дисципліни для розширення кар'єрних можливостей\n\n";
        
        $prompt .= "**ПРИКЛАД ФОРМАТУ ВІДПОВІДІ:**\n\n";
        $prompt .= "```json\n";
        $prompt .= "{\n";
        $prompt .= "  \"career_paths\": [\n";
        $prompt .= "    {\n";
        $prompt .= "      \"direction\": \"Креативні індустрії (major)\",\n";
        $prompt .= "      \"type\": \"creative\",\n";
        $prompt .= "      \"majors\": [\n";
        $prompt .= "        \"Аудіовізуальне мистецтво і виробництво (режисура)\",\n";
        $prompt .= "        \"Організація кінотелевиробництва\",\n";
        $prompt .= "        \"Multimedia Art\",\n";
        $prompt .= "        \"Art curation and Management\"\n";
        $prompt .= "      ],\n";
        $prompt .= "      \"minors\": [\n";
        $prompt .= "        \"Film production (minor)\",\n";
        $prompt .= "        \"Video Making (minor)\",\n";
        $prompt .= "        \"Digital Marketing (minor)\"\n";
        $prompt .= "      ]\n";
        $prompt .= "    },\n";
        $prompt .= "    {\n";
        $prompt .= "      \"direction\": \"Технології (major)\",\n";
        $prompt .= "      \"type\": \"technology\",\n";
        $prompt .= "      \"majors\": [\n";
        $prompt .= "        \"Транспортні технології\",\n";
        $prompt .= "        \"Логістика\",\n";
        $prompt .= "        \"Інженерія програмного забезпечення\"\n";
        $prompt .= "      ],\n";
        $prompt .= "      \"minors\": [\n";
        $prompt .= "        \"Data Analytics (minor)\",\n";
        $prompt .= "        \"Project Management (minor)\"\n";
        $prompt .= "      ]\n";
        $prompt .= "    }\n";
        $prompt .= "  ]\n";
        $prompt .= "}\n";
        $prompt .= "```\n\n";
        
        $prompt .= "**ВАЖЛИВО:**\n";
        $prompt .= "- Використовуй українські назви для major (основних напрямків)\n";
        $prompt .= "- Для minor можна використовувати англійські назви з поміткою (minor)\n";
        $prompt .= "- Назви мають бути конкретними та актуальними\n";
        $prompt .= "- Враховуй всі аспекти профілю користувача\n";
        $prompt .= "- Поверни тільки JSON без додаткового тексту\n";
        
        return $prompt;
    }

    /**
     * Резервні рекомендації на випадок відсутності AI
     */
    private function getFallbackRecommendations($moduleScores)
    {
        $recommendations = [];

        // Базові рекомендації на основі Holland типів
        if (isset($moduleScores['module7'])) {
            $hollandScores = $moduleScores['module7'];
            arsort($hollandScores);
            $topType = array_key_first($hollandScores);
            
            $fallbackMap = [
                'realistic' => [
                    'direction' => 'Інженерія та технології (major)',
                    'type' => 'technical',
                    'majors' => [
                        'Інженерія програмного забезпечення',
                        'Комп\'ютерна інженерія',
                        'Мехатроніка та робототехніка',
                        'Електротехніка та електроенергетика'
                    ],
                    'minors' => [
                        'Automation (minor)',
                        'IoT Technologies (minor)',
                        'Project Management (minor)'
                    ]
                ],
                'investigative' => [
                    'direction' => 'Наукові дослідження (major)',
                    'type' => 'research',
                    'majors' => [
                        'Прикладна математика',
                        'Фізика',
                        'Хімія',
                        'Біотехнології'
                    ],
                    'minors' => [
                        'Data Science (minor)',
                        'Research Methods (minor)',
                        'Scientific Computing (minor)'
                    ]
                ],
                'artistic' => [
                    'direction' => 'Креативні індустрії (major)',
                    'type' => 'creative',
                    'majors' => [
                        'Аудіовізуальне мистецтво і виробництво',
                        'Графічний дизайн',
                        'Multimedia Art',
                        'Дизайн середовища'
                    ],
                    'minors' => [
                        'Digital Marketing (minor)',
                        'UX/UI Design (minor)',
                        'Content Creation (minor)'
                    ]
                ],
                'social' => [
                    'direction' => 'Соціальні науки та освіта (major)',
                    'type' => 'social',
                    'majors' => [
                        'Психологія',
                        'Соціальна робота',
                        'Педагогіка',
                        'Соціологія'
                    ],
                    'minors' => [
                        'Counseling (minor)',
                        'HR Management (minor)',
                        'Community Development (minor)'
                    ]
                ],
                'enterprising' => [
                    'direction' => 'Бізнес та менеджмент (major)',
                    'type' => 'business',
                    'majors' => [
                        'Менеджмент',
                        'Маркетинг',
                        'Підприємництво',
                        'Міжнародні відносини'
                    ],
                    'minors' => [
                        'Digital Business (minor)',
                        'Leadership (minor)',
                        'Innovation Management (minor)'
                    ]
                ],
                'conventional' => [
                    'direction' => 'Економіка та фінанси (major)',
                    'type' => 'analytical',
                    'majors' => [
                        'Фінанси та банківська справа',
                        'Облік і оподаткування',
                        'Економіка',
                        'Бізнес-аналітика'
                    ],
                    'minors' => [
                        'Financial Analytics (minor)',
                        'Risk Management (minor)',
                        'Business Intelligence (minor)'
                    ]
                ]
            ];
            
            if (isset($fallbackMap[$topType])) {
                $recommendations[] = $fallbackMap[$topType];
            }
        }

        return $recommendations;
    }
}
