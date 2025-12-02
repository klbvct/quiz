<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\QuizSession;
use App\Models\QuizResult;
use App\Models\QuizAnswer;
use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateQuizReport extends Command
{
    protected $signature = 'quiz:generate-report {email} {--output=}';
    protected $description = 'Генерация PDF-отчёта для пользователя по email';

    public function handle()
    {
        $email = $this->argument('email');
        $outputPath = $this->option('output');
        
        // Ищем пользователя
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Пользователь с email {$email} не найден!");
            return 1;
        }
        
        $this->info("Пользователь: {$user->name} (ID: {$user->id})");
        
        // Ищем последнюю завершённую сессию
        $session = QuizSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('id', 'desc')
            ->first();
        
        if (!$session) {
            $this->error('У пользователя нет завершённых тестов!');
            return 1;
        }
        
        $this->info("Сессия: #{$session->id}, дата: {$session->updated_at}");
        
        // Получаем результаты
        $result = QuizResult::where('session_id', $session->id)->first();
        
        if (!$result) {
            $this->warn('Результаты не найдены. Пересчитываю...');
            
            // Загружаем интерпретацию
            $interpretationPath = storage_path('app/ai_templates/interpretation.json');
            if (!file_exists($interpretationPath)) {
                $this->error('Файл interpretation.json не найден!');
                return 1;
            }
            
            $interpretation = json_decode(file_get_contents($interpretationPath), true);
            $answers = QuizAnswer::where('session_id', $session->id)->get();
            
            // Вызываем приватный метод через рефлексию
            $controller = app()->make(\App\Http\Controllers\QuizController::class);
            $method = new \ReflectionMethod($controller, 'calculateResults');
            $method->setAccessible(true);
            
            $result = $method->invoke($controller, $session);
            
            if (!$result) {
                $this->error('Ошибка при пересчёте результатов!');
                return 1;
            }
            
            $this->info('✓ Результаты пересчитаны');
        }
        
        // Получаем все ответы
        $answers = QuizAnswer::where('session_id', $session->id)->get();
        $totalAnswers = $answers->count();
        $scores = $result->module_scores;
        
        $this->info("Всего ответов: {$totalAnswers}");
        $this->info('---');
        
        // Генерируем PDF
        $this->info('Генерирую PDF...');
        
        $pdf = Pdf::loadView('reports.quiz-report', [
            'user' => $user,
            'session' => $session,
            'result' => $result,
            'scores' => $scores,
            'totalAnswers' => $totalAnswers
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        
        // Определяем путь сохранения
        if ($outputPath) {
            $filePath = $outputPath;
        } else {
            $fileName = 'quiz_report_' . $user->id . '_' . $session->id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = storage_path('app/reports/' . $fileName);
            
            // Создаём директорию если не существует
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        
        // Сохраняем файл
        $pdf->save($filePath);
        
        $this->info("✓ Отчёт сохранён: {$filePath}");
        
        // Показываем размер файла
        $fileSize = filesize($filePath);
        $this->info("Размер файла: " . round($fileSize / 1024, 2) . " KB");
        
        return 0;
    }
}
