<?php

namespace App\Console\Commands;

use App\Models\QuizSession;
use App\Models\QuizResult;
use App\Models\QuizAnswer;
use Illuminate\Console\Command;

class TestQuizCalculation extends Command
{
    protected $signature = 'quiz:test-calculation {session_id?}';
    protected $description = 'Тестирование подсчёта результатов теста';

    public function handle()
    {
        $sessionId = $this->argument('session_id');
        
        if (!$sessionId) {
            // Найдём любую завершённую сессию
            $session = QuizSession::where('status', 'completed')->first();
            
            if (!$session) {
                $this->error('Нет завершённых сессий тестирования!');
                $this->info('Создайте тестовую сессию или укажите session_id');
                return;
            }
            
            $sessionId = $session->id;
        }
        
        $session = QuizSession::find($sessionId);
        
        if (!$session) {
            $this->error("Сессия с ID {$sessionId} не найдена!");
            return;
        }
        
        $this->info("Анализ сессии #{$session->id}");
        $this->info("Пользователь: {$session->user->name}");
        $this->info("Статус: {$session->status}");
        $this->info("---");
        
        // Проверяем ответы
        $answers = QuizAnswer::where('session_id', $session->id)->get();
        $byModule = $answers->groupBy('module_number');
        
        $this->info("Ответы по модулям:");
        foreach ($byModule as $module => $moduleAnswers) {
            $this->line("  Модуль {$module}: {$moduleAnswers->count()} ответов");
        }
        $this->info("---");
        
        // Проверяем interpretation.json
        $interpretationPath = resource_path('ai_templates/interpretation.json');
        if (!file_exists($interpretationPath)) {
            $this->error('Файл interpretation.json не найден!');
            $this->info("Ожидаемый путь: {$interpretationPath}");
            return;
        }
        
        $interpretation = json_decode(file_get_contents($interpretationPath), true);
        if (!$interpretation) {
            $this->error('Ошибка чтения interpretation.json');
            return;
        }
        
        $this->info("✓ interpretation.json загружен");
        $this->info("  Модулей в конфиге: " . count($interpretation['modules']));
        $this->info("---");
        
        // Проверяем результаты
        $result = QuizResult::where('session_id', $session->id)->first();
        
        if ($result) {
            $this->info("Существующие результаты:");
            $this->line(json_encode($result->module_scores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("---");
            $this->info("Рекомендации:");
            $this->line(json_encode($result->recommendations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->info("---");
            $this->info("Резюме:");
            $this->line($result->summary);
        } else {
            $this->warn('Результатов пока нет');
        }
        
        $this->info("---");
        
        if ($this->confirm('Пересчитать результаты?', true)) {
            try {
                // Вызываем пересчёт через контроллер
                $controller = app()->make(\App\Http\Controllers\QuizController::class);
                $method = new \ReflectionMethod($controller, 'calculateResults');
                $method->setAccessible(true);
                
                $newResult = $method->invoke($controller, $session);
                
                if ($newResult) {
                    $this->info("✓ Результаты пересчитаны!");
                    $this->info("---");
                    $this->info("Новые баллы по модулям:");
                    $this->line(json_encode($newResult->module_scores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $this->info("---");
                    $this->info("Рекомендации:");
                    $this->line(json_encode($newResult->recommendations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                } else {
                    $this->error('Ошибка при пересчёте результатов!');
                }
                
            } catch (\Exception $e) {
                $this->error("Ошибка: " . $e->getMessage());
                $this->line($e->getTraceAsString());
            }
        }
    }
}
