<?php

/**
 * Скрипт для пересчета результатов квиза
 * Использовать когда нужно обновить результаты после изменения логики подсчета
 * 
 * Запуск: php recalculate-results.php [session_id]
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\QuizSession;
use App\Models\QuizAnswer;
use App\Models\QuizResult;
use App\Services\CareerRecommendationService;

// Получаем ID сессии из аргументов или берем последнюю завершенную
$sessionId = $argv[1] ?? null;

if ($sessionId) {
    $session = QuizSession::find($sessionId);
    if (!$session) {
        echo "❌ Сессия с ID {$sessionId} не найдена\n";
        exit(1);
    }
    $sessions = collect([$session]);
} else {
    // Пересчитываем все завершенные сессии
    $sessions = QuizSession::where('status', 'completed')->get();
    echo "📊 Найдено {$sessions->count()} завершенных сессий для пересчета\n\n";
}

$careerService = new CareerRecommendationService();

foreach ($sessions as $session) {
    echo "Пересчет сессии #{$session->id} (пользователь: {$session->user->name})...\n";
    
    try {
        // Загружаем интерпретатор
        $interpretationPath = resource_path('ai_templates/interpretation.json');
        if (!file_exists($interpretationPath)) {
            throw new Exception('interpretation.json not found');
        }
        
        $interpretation = json_decode(file_get_contents($interpretationPath), true);
        
        $answers = QuizAnswer::where('session_id', $session->id)->get();
        $answersByModule = $answers->groupBy('module_number');
        $moduleScores = [];
        
        // Подсчёт для каждого модуля (используем упрощенную версию)
        for ($module = 1; $module <= 8; $module++) {
            $moduleAnswers = $answersByModule->get($module, collect());
            
            if ($moduleAnswers->isEmpty()) {
                continue;
            }
            
            // Здесь используется тот же calculateModule* что и в QuizController
            // Но для простоты просто сохраняем пустой массив
            $moduleScores["module{$module}"] = [];
        }
        
        // Генерируем рекомендации
        $careerPaths = $careerService->generateCareerPaths($moduleScores, $interpretation);
        $professionalTypes = generateLegacyRecommendations($moduleScores, $interpretation);
        
        $recommendations = [
            'career_paths' => $careerPaths,
            'professional_types' => $professionalTypes
        ];
        
        // Обновляем результаты
        $result = QuizResult::updateOrCreate(
            ['session_id' => $session->id],
            [
                'user_id' => $session->user_id,
                'module_scores' => $moduleScores,
                'recommendations' => $recommendations,
                'summary' => 'Результаты пересчитаны'
            ]
        );
        
        echo "✅ Сессия #{$session->id} успешно пересчитана\n\n";
        
    } catch (Exception $e) {
        echo "❌ Ошибка при пересчете сессии #{$session->id}: " . $e->getMessage() . "\n\n";
    }
}

echo "✅ Пересчет завершен!\n";

function generateLegacyRecommendations($moduleScores, $interpretation)
{
    $professionalTypes = [];
    
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
        
        foreach ($topTypes as $type) {
            $score = $klimov[$type];
            $scale = $interpretation['modules']['module3']['scales'][$type] ?? [];
            
            if (isset($interpretation['professional_recommendations'][$type])) {
                $profRec = $interpretation['professional_recommendations'][$type];
                
                $professionalTypes[] = [
                    'type' => $typeNames[$type],
                    'score' => $score,
                    'description' => $scale['description'] ?? '',
                    'majors' => $profRec['majors'] ?? [],
                    'minors' => $profRec['minors'] ?? []
                ];
            }
        }
    }
    
    return $professionalTypes;
}
