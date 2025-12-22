<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CareerRecommendationService;

class TestAIRecommendations extends Command
{
    protected $signature = 'ai:test-recommendations';
    protected $description = 'Test AI career recommendations generation';

    protected $careerService;

    public function __construct(CareerRecommendationService $careerService)
    {
        parent::__construct();
        $this->careerService = $careerService;
    }

    public function handle()
    {
        $this->info('Testing AI Career Recommendations Service...');
        $this->newLine();

        // Перевірка конфігурації
        if (!env('GEMINI_API_KEY')) {
            $this->error('❌ GEMINI_API_KEY is not configured in .env file');
            $this->info('Please add your Gemini API key to the .env file:');
            $this->line('GEMINI_API_KEY=your_api_key_here');
            $this->newLine();
            $this->info('Get your API key from: https://aistudio.google.com/app/apikey');
            return 1;
        }

        $this->info('✅ Gemini API key is configured');
        $this->info('Model: ' . env('GEMINI_MODEL', 'gemini-1.5-flash'));
        $this->newLine();

        // Тестові дані
        $testScores = [
            'module7' => [
                'artistic' => 25,
                'investigative' => 22,
                'social' => 18,
                'enterprising' => 15,
                'realistic' => 12,
                'conventional' => 10
            ],
            'module3' => [
                'creative' => 8,
                'artistic' => 7,
                'theoretical' => 6,
                'practical' => 5,
                'analytical' => 4,
                'convergent' => 3,
                'intuitive' => 2
            ],
            'module5' => [
                'spatial' => 15,
                'linguistic' => 14,
                'musical' => 13,
                'interpersonal' => 12,
                'logical' => 11,
                'intrapersonal' => 10,
                'bodily' => 9,
                'naturalistic' => 8
            ],
            'module4' => [
                'creativity' => 1,
                'independence' => 2,
                'achievement' => 3,
                'intellect' => 4,
                'variety' => 5,
                'prestige' => 6,
                'altruism' => 7,
                'security' => 8,
                'balance' => 9,
                'power' => 10
            ],
            'module8' => [
                'visual' => 12,
                'kinesthetic' => 8,
                'auditory' => 6,
                'digital' => 4
            ]
        ];

        $this->info('🧪 Testing with sample profile:');
        $this->line('Holland Code: AIS (Artistic-Investigative-Social)');
        $this->line('Dominant Thinking: Creative, Artistic');
        $this->line('Intelligence Types: Spatial, Linguistic, Musical');
        $this->line('Top Values: Creativity, Independence, Achievement');
        $this->newLine();

        $this->info('🤖 Generating recommendations...');
        
        $startTime = microtime(true);
        $careerPaths = $this->careerService->generateCareerPaths($testScores, []);
        $endTime = microtime(true);
        
        $duration = round(($endTime - $startTime) * 1000);
        
        $this->newLine();
        
        if (empty($careerPaths)) {
            $this->error('❌ No career paths generated. Check logs for errors.');
            $this->info('Log file: storage/logs/laravel.log');
            return 1;
        }

        $this->info("✅ Successfully generated {count($careerPaths)} career path(s) in {$duration}ms");
        $this->newLine();

        // Відображення результатів
        foreach ($careerPaths as $index => $path) {
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info("Career Path #" . ($index + 1));
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            
            $this->line("<fg=cyan>Direction:</> " . ($path['direction'] ?? 'N/A'));
            $this->line("<fg=yellow>Type:</> " . ($path['type'] ?? 'N/A'));
            
            if (!empty($path['majors'])) {
                $this->newLine();
                $this->line('<fg=green>Major (Основний бакалавріат):</>');
                foreach ($path['majors'] as $major) {
                    $this->line("  • $major");
                }
            }
            
            if (!empty($path['minors'])) {
                $this->newLine();
                $this->line('<fg=magenta>Minor (Додаткове навчання):</>');
                foreach ($path['minors'] as $minor) {
                    $this->line("  • $minor");
                }
            }
            
            $this->newLine();
        }

        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('✨ Test completed successfully!');
        
        return 0;
    }
}
