@extends('layouts.admin')

@section('title', 'Результати тестування - ' . $user->name)

@section('content')
<div class="page-header">
    <div class="header-with-back">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-back">← Назад до користувача</a>
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <h1>Результати тестування: {{ $user->name }}</h1>
            <a href="{{ route('admin.users.quiz-results.export', $user->id) }}" class="btn-export">
                📥 Завантажити CSV
            </a>
        </div>
    </div>
</div>

<div class="results-container">
    <!-- Інформація про сесію -->
    <div class="section-card">
        <h2>Інформація про тестування</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Дата початку</div>
                <div class="info-value">{{ $completedSession->created_at->format('d.m.Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Дата завершення</div>
                <div class="info-value">{{ $completedSession->completed_at->format('d.m.Y H:i') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Тривалість</div>
                <div class="info-value">{{ $completedSession->created_at->diffForHumans($completedSession->completed_at, true) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">ID сесії</div>
                <div class="info-value">{{ $completedSession->id }}</div>
            </div>
        </div>
    </div>

    @if($quizResult)
        <!-- Загальні результати -->
        <div class="section-card">
            <h2>Загальні результати</h2>
            
            @if(isset($quizResult->module_scores) && is_array($quizResult->module_scores))
                <div class="scores-grid">
                    @foreach($quizResult->module_scores as $key => $score)
                        @php
                            $displayKey = is_array($key) ? json_encode($key, JSON_UNESCAPED_UNICODE) : $key;
                            if (is_array($score)) {
                                continue;
                            }
                            $displayScore = is_numeric($score) ? round($score, 2) : $score;
                        @endphp
                        <div class="score-card">
                            <div class="score-label">{!! e($displayKey) !!}</div>
                            <div class="score-value">{!! e($displayScore) !!}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($quizResult->summary)
                <div class="summary-section">
                    <h3>Підсумок</h3>
                    <div class="summary-text">
                        @if(is_array($quizResult->summary))
                            {!! e(json_encode($quizResult->summary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) !!}
                        @else
                            {{ $quizResult->summary }}
                        @endif
                    </div>
                </div>
            @endif

            @if(isset($quizResult->recommendations) && is_array($quizResult->recommendations) && count($quizResult->recommendations) > 0)
                <div class="recommendations-section">
                    <h3>Рекомендації</h3>
                    <ul class="recommendations-list">
                        @foreach($quizResult->recommendations as $recommendation)
                            <li>
                                @if(is_array($recommendation))
                                    {!! e(json_encode($recommendation, JSON_UNESCAPED_UNICODE)) !!}
                                @else
                                    {{ $recommendation }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Відповіді по модулях -->
    <div class="section-card">
        <h2>Відповіді користувача по модулях</h2>
        
        @foreach($answers as $moduleNumber => $moduleAnswers)
            <div class="module-answers">
                <h3 class="module-title module-toggle" data-module="{{ $moduleNumber }}">
                    <span class="toggle-icon">▶</span> Модуль {{ $moduleNumber }}
                </h3>
                
                @php
                    $answersArray = $moduleAnswers->values()->all();
                    $chunks = array_chunk($answersArray, 20);
                @endphp
                
                <div class="tables-grid module-content" id="module-{{ $moduleNumber }}" style="display: none;">
                    @foreach($chunks as $chunkIndex => $chunk)
                        <div class="table-column">
                            <table class="answers-table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">№</th>
                                        <th style="width: 45%;">Питання</th>
                                        <th style="width: 50%;">Відповідь</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($chunk as $answer)
                                        @php
                                            $moduleData = $modulesData[$moduleNumber] ?? null;
                                            $questionData = null;
                                            $questionText = '';
                                            
                                            if ($moduleData && isset($moduleData['questions'])) {
                                                foreach ($moduleData['questions'] as $q) {
                                                    if ($q['number'] == $answer->question_number) {
                                                        $questionData = $q;
                                                        break;
                                                    }
                                                }
                                            }
                                            
                                            // Формируем текст вопроса в зависимости от типа модуля
                                            
                                            // Для модуля 4 и 6 (ranking/scale) ищем текст ценности по номеру
                                            if (($moduleNumber == 4 || $moduleNumber == 6) && $moduleData && isset($moduleData['values'])) {
                                                foreach ($moduleData['values'] as $value) {
                                                    if ($value['number'] == $answer->question_number) {
                                                        $questionText = $value['text'];
                                                        break;
                                                    }
                                                }
                                            }
                                            // Для остальных модулей извлекаем из questions
                                            elseif ($questionData) {
                                                if (isset($questionData['question'])) {
                                                    $questionText = $questionData['question'];
                                                } elseif (isset($questionData['a']) && isset($questionData['b'])) {
                                                    // Для модуля 1, 3 и 7 (выбор A/B/C) показываем только выбранный вариант
                                                    if ($moduleNumber == 1 || $moduleNumber == 3 || $moduleNumber == 7) {
                                                        $userChoice = strtolower(trim($answer->answer));
                                                        
                                                        // Добавляем текст вопроса для модуля 3
                                                        $prefix = '';
                                                        if ($moduleNumber == 3 && isset($questionData['text'])) {
                                                            $prefix = $questionData['text'] . "\n";
                                                        }
                                                        
                                                        if ($userChoice === 'a' && isset($questionData['a'])) {
                                                            $questionText = $prefix . $questionData['a'];
                                                        } elseif ($userChoice === 'b' && isset($questionData['b'])) {
                                                            $questionText = $prefix . $questionData['b'];
                                                        } elseif ($userChoice === 'c' && isset($questionData['c'])) {
                                                            $questionText = $prefix . $questionData['c'];
                                                        } else {
                                                            $questionText = "A: {$questionData['a']}\nB: {$questionData['b']}";
                                                            if (isset($questionData['c'])) {
                                                                $questionText .= "\nC: {$questionData['c']}";
                                                            }
                                                        }
                                                    } else {
                                                        $questionText = "A: {$questionData['a']}\nB: {$questionData['b']}";
                                                        if (isset($questionData['c'])) {
                                                            $questionText .= "\nC: {$questionData['c']}";
                                                        }
                                                    }
                                                } elseif (isset($questionData['text'])) {
                                                    $questionText = $questionData['text'];
                                                } elseif (isset($questionData['statement'])) {
                                                    $questionText = $questionData['statement'];
                                                }
                                            }
                                            
                                            // Обрабатываем ответ
                                            $answerValue = $answer->answer;
                                            if (is_string($answerValue)) {
                                                $decoded = json_decode($answerValue, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                    $answerValue = $decoded;
                                                }
                                            }
                                            
                                            // Формируем текстовое представление ответа с текстом опций для модуля 8
                                            $answerText = '';
                                            if (is_array($answerValue)) {
                                                // Для модуля 8 добавляем текст опций из JSON
                                                if ($moduleNumber == 8 && $questionData && isset($questionData['options'])) {
                                                    $parts = [];
                                                    foreach ($answerValue as $k => $v) {
                                                        $optionIndex = (int)$k;
                                                        $optionText = $questionData['options'][$optionIndex] ?? "Опція $optionIndex";
                                                        if (is_array($v)) {
                                                            $parts[] = $optionText . ': ' . json_encode($v, JSON_UNESCAPED_UNICODE);
                                                        } else {
                                                            $parts[] = $optionText . ': ' . $v;
                                                        }
                                                    }
                                                    $answerText = implode("\n", $parts);
                                                } else {
                                                    // Для массивов показываем ключи и значения
                                                    $parts = [];
                                                    foreach ($answerValue as $k => $v) {
                                                        if (is_array($v)) {
                                                            $parts[] = $k . ': ' . json_encode($v, JSON_UNESCAPED_UNICODE);
                                                        } else {
                                                            $parts[] = $k . ': ' . $v;
                                                        }
                                                    }
                                                    $answerText = implode("\n", $parts);
                                                }
                                            } else {
                                                $answerText = (string)$answerValue;
                                            }
                                        @endphp
                                        <tr>
                                            <td class="question-number">{{ $answer->question_number }}</td>
                                            <td class="question-text">{{ $questionText }}</td>
                                            <td class="answer-cell">
                                                @if(is_array($answerValue) && $moduleNumber == 8 && $questionData && isset($questionData['options']))
                                                    {{-- Для модуля 8 показываем таблицу с опциями и оценками --}}
                                                    <table class="module8-answer-table">
                                                        @foreach($answerValue as $k => $v)
                                                            @php
                                                                $optionIndex = (int)$k;
                                                                $optionText = $questionData['options'][$optionIndex] ?? "Опція $k";
                                                            @endphp
                                                            <tr>
                                                                <td class="option-text">{{ $optionText }}</td>
                                                                <td class="option-score">{{ $v }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @elseif(is_array($answerValue))
                                                    <ul class="answer-list">
                                                        @foreach($answerValue as $k => $v)
                                                            <li>
                                                                @php
                                                                    $displayKey = is_array($k) ? json_encode($k, JSON_UNESCAPED_UNICODE) : $k;
                                                                    $displayValue = is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : (is_string($v) || is_numeric($v) ? $v : print_r($v, true));
                                                                @endphp
                                                                {!! e($displayKey) !!}: {!! e($displayValue) !!}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @elseif(is_string($answerValue) || is_numeric($answerValue))
                                                    <span class="answer-value">{{ $answerValue }}</span>
                                                @else
                                                    <span class="answer-value">{!! e(print_r($answerValue, true)) !!}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.results-container {
    max-width: 1200px;
    margin: 0 auto;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.scores-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.score-card {
    background: #f9fafb;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #0c68f5;
    text-align: center;
}

.score-label {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 8px;
}

.score-value {
    font-size: 28px;
    font-weight: 700;
    color: #0c68f5;
}

.summary-section, .recommendations-section {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e5e7eb;
}

.summary-section h3, .recommendations-section h3 {
    color: #0c68f5;
    margin-bottom: 15px;
}

.summary-text {
    background: #f9fafb;
    padding: 20px;
    border-radius: 10px;
    line-height: 1.6;
    color: #4b5563;
}

.recommendations-list {
    list-style: none;
    padding: 0;
}

.recommendations-list li {
    background: #f0fdf4;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 3px solid #10b981;
}

.module-answers {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid #e5e7eb;
}

.module-answers:last-child {
    border-bottom: none;
}

.module-title {
    color: #0c68f5;
    font-size: 20px;
    margin-bottom: 20px;
    padding: 15px;
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    border-radius: 10px;
}

.answers-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.answer-item {
    background: #f9fafb;
    padding: 20px;
    border-radius: 10px;
    border-left: 3px solid #d1d5db;
}

.answer-question {
    color: #1f2937;
    margin-bottom: 10px;
    line-height: 1.6;
}

.answer-response {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    flex-wrap: wrap;
}

.answer-label {
    color: #6b7280;
    font-weight: 600;
    font-size: 14px;
}

.answer-value {
    color: #0c68f5;
    font-weight: 600;
    background: white;
    padding: 5px 15px;
    border-radius: 5px;
    border: 1px solid #0c68f5;
}

.answer-response ul {
    margin: 0;
    padding-left: 20px;
    list-style: none;
}

.answer-response ul li {
    padding: 5px 0;
    color: #4b5563;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Получаем все заголовки модулей
    const moduleTitles = document.querySelectorAll('.module-toggle');
    
    moduleTitles.forEach(title => {
        title.addEventListener('click', function() {
            const moduleNumber = this.getAttribute('data-module');
            const content = document.getElementById('module-' + moduleNumber);
            const icon = this.querySelector('.toggle-icon');
            
            if (content.style.display === 'none') {
                content.style.display = 'grid';
                icon.textContent = '▼';
            } else {
                content.style.display = 'none';
                icon.textContent = '▶';
            }
        });
    });
});
</script>
@endsection
