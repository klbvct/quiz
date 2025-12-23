@extends('layouts.app')

@section('title', 'Результати тестування - Дизайн Освіти')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/results.css') }}">
@endsection

@section('content')
<div class="results-wrapper">
    <div class="results-header">
        <div class="logo">
            <img src="{{ asset('images/logo_education_design.svg') }}" alt="Education Design" class="logo-image">
        </div>
        <h1>Результати профорієнтаційного тестування</h1>
        @if(isset($isHistoryView) && $isHistoryView)
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 10px;">
                📅 Результати від {{ $session->completed_at->format('d.m.Y H:i') }}
            </p>
        @endif
    </div>

    <div class="results-content">
        <div style="text-align: center; margin-bottom: 30px;">
            @if(isset($isHistoryView) && $isHistoryView)
                <a href="{{ route('quiz.test-history') }}" style="color: #0c68f5; text-decoration: none; font-weight: 600; font-size: 16px;">← Повернутися до історії</a>
            @else
                <a href="{{ route('home') }}" style="color: #0c68f5; text-decoration: none; font-weight: 600; font-size: 16px;">← Повернутися до кабінету</a>
            @endif
        </div>
        
        <div class="completion-badge">
            <div class="badge-icon">✓</div>
            <h2>Вітаємо! Ви завершили тестування</h2>
            <p>Дата завершення: {{ $session->completed_at->format('d.m.Y H:i') }}</p>
            <div class="results-actions">
                <a href="{{ route('quiz.report.view', ['sessionId' => $session->id]) }}" class="btn btn-success" target="_blank">Переглянути та роздрукувати звіт</a>
            </div>
        </div>

        @if($result)
        <div class="results-section">
            <h3>📋 Ваш професійний профіль</h3>
            
            @php
                $scores = $result->module_scores;
                
                // 1. Інтелектуальний потенціал (Модуль 3 - Типи мислення)
                $dominantThinking = '';
                $thinkingLabel = '';
                if(isset($scores['module3'])) {
                    $thinkingTypes = [
                        'artistic' => 'Художнє (наочно-образне)',
                        'theoretical' => 'Теоретичне',
                        'practical' => 'Практичне',
                        'creative' => 'Творче (продуктивне)',
                        'convergent' => 'Конвергентне',
                        'intuitive' => 'Інтуїтивне',
                        'analytical' => 'Аналітичне'
                    ];
                    $thinkingScores = $scores['module3'];
                    arsort($thinkingScores);
                    $dominantThinking = array_key_first($thinkingScores);
                    $thinkingLabel = $thinkingTypes[$dominantThinking] ?? 'Не визначено';
                }
                
                // 2. Домінантні риси характеру
                $characterTraits = [];
                
                $thinkingTraits = [
                    'artistic' => ['Уява', 'Креативність', 'Емоційність'],
                    'theoretical' => ['Аналітичність', 'Логічність', 'Систематичність'],
                    'practical' => ['Виваженість', 'Прагматичність', 'Реалістичність'],
                    'creative' => ['Оригінальність', 'Інноваційність', 'Гнучкість'],
                    'convergent' => ['Точність', 'Концентрація', 'Цілеспрямованість'],
                    'intuitive' => ['Проникливість', 'Передбачення', 'Чутливість'],
                    'analytical' => ['Критичність', 'Детальність', 'Обґрунтованість']
                ];
                
                if($dominantThinking && isset($thinkingTraits[$dominantThinking])) {
                    $characterTraits = array_merge($characterTraits, $thinkingTraits[$dominantThinking]);
                }
                
                if(isset($scores['module7'])) {
                    $hollandScores = $scores['module7'];
                    arsort($hollandScores);
                    $topHolland = array_key_first($hollandScores);
                    $hollandTraits = [
                        'realistic' => 'Практичність',
                        'investigative' => 'Допитливість',
                        'artistic' => 'Творчість',
                        'social' => 'Емпатія',
                        'enterprising' => 'Лідерство',
                        'conventional' => 'Організованість'
                    ];
                    if(isset($hollandTraits[$topHolland])) {
                        $characterTraits[] = $hollandTraits[$topHolland];
                    }
                }
                
                $characterTraits = array_unique($characterTraits);
                $characterTraits = array_slice($characterTraits, 0, 3);
                
                // 3. Самоконтроль
                $selfControl = 'Середній';
                if(isset($scores['module6'])) {
                    $motivationScores = $scores['module6'];
                    $avgMotivation = array_sum($motivationScores) / count($motivationScores);
                    if($avgMotivation >= 5) {
                        $selfControl = 'Високий';
                    } elseif($avgMotivation >= 3) {
                        $selfControl = 'Середній';
                    } else {
                        $selfControl = 'Потребує розвитку';
                    }
                }
                
                // 4. Схильності та уподобання
                $inclinations = [];
                
                if(isset($scores['module3'])) {
                    $thinkingScores = $scores['module3'];
                    arsort($thinkingScores);
                    $topThinking = array_slice($thinkingScores, 0, 3, true);
                    $inclinationMap = [
                        'artistic' => 'Творчість',
                        'theoretical' => 'Наукова діяльність',
                        'practical' => 'Практична діяльність',
                        'creative' => 'Креативні проєкти',
                        'convergent' => 'Стратегічне планування',
                        'intuitive' => 'Інтуїтивне прийняття рішень',
                        'analytical' => 'Аналітика'
                    ];
                    foreach(array_keys($topThinking) as $type) {
                        if(isset($inclinationMap[$type])) {
                            $inclinations[] = $inclinationMap[$type];
                        }
                    }
                }
                
                if(isset($scores['module7'])) {
                    $hollandScores = $scores['module7'];
                    arsort($hollandScores);
                    $topHollandTypes = array_slice($hollandScores, 0, 2, true);
                    $hollandInclinations = [
                        'realistic' => 'Технічна робота',
                        'investigative' => 'Дослідження',
                        'artistic' => 'Мистецтво',
                        'social' => 'Робота з людьми',
                        'enterprising' => 'Підприємництво',
                        'conventional' => 'Організаційна діяльність'
                    ];
                    foreach(array_keys($topHollandTypes) as $type) {
                        if(isset($hollandInclinations[$type])) {
                            $inclinations[] = $hollandInclinations[$type];
                        }
                    }
                }
                
                $inclinations = array_unique($inclinations);
                $inclinations = array_slice($inclinations, 0, 4);
                
                // 5. Ціннісні орієнтири (Модуль 4)
                $valueOrientations = [];
                if(isset($scores['module4'])) {
                    $valueScores = $scores['module4'];
                    asort($valueScores);
                    $topValues = array_slice($valueScores, 0, 3, true);
                    $valueNames = [
                        'Активне, діяльне життя' => 'Активність',
                        'Життєва мудрість' => 'Мудрість',
                        'Здоров\'я' => 'Здоров\'я',
                        'Цікава робота' => 'Цікава робота',
                        'Краса природи та мистецтва' => 'Естетика',
                        'Любов' => 'Любов',
                        'Матеріальна забезпеченість' => 'Матеріальна стабільність',
                        'Наявність добрих друзів' => 'Дружба',
                        'Впевненість у собі' => 'Впевненість',
                        'Пізнання' => 'Пізнання',
                        'Свобода' => 'Свобода',
                        'Щасливе сімейне життя' => 'Сім\'я',
                        'Творчість' => 'Творчість',
                        'Суспільне визнання' => 'Визнання',
                        'Розваги' => 'Розваги',
                        'Продуктивне життя' => 'Продуктивність',
                        'Розвиток' => 'Розвиток',
                        'Задоволеність собою' => 'Задоволеність'
                    ];
                    foreach(array_keys($topValues) as $value) {
                        $valueOrientations[] = $valueNames[$value] ?? $value;
                    }
                }
                
                // 6. RIASEC код (Модуль 7)
                $riasecCode = '';
                $riasecTypes = [];
                if(isset($scores['module7'])) {
                    $hollandScores = $scores['module7'];
                    arsort($hollandScores);
                    $topThreeRiasec = array_slice($hollandScores, 0, 3, true);
                    $letterMap = [
                        'realistic' => 'R',
                        'investigative' => 'I',
                        'artistic' => 'A',
                        'social' => 'S',
                        'enterprising' => 'E',
                        'conventional' => 'C'
                    ];
                    $typeNames = [
                        'realistic' => 'Realistic (Практик)',
                        'investigative' => 'Investigative (Мислитель. Дослідник)',
                        'artistic' => 'Artistic (Творець)',
                        'social' => 'Social (Помічник)',
                        'enterprising' => 'Enterprising (Лідер)',
                        'conventional' => 'Conventional (Організатор)'
                    ];
                    foreach(array_keys($topThreeRiasec) as $type) {
                        $riasecCode .= $letterMap[$type] ?? '';
                        $riasecTypes[$type] = $typeNames[$type] ?? '';
                    }
                }
            @endphp

            <div style="overflow-x: auto; margin-top: 20px;">
                <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                    <tbody>
                        <tr>
                            <td style="width: 35%; padding: 12px; border: 1px solid #E5E7EB; background: #F9FAFB; font-weight: 600;">Інтелектуальний потенціал</td>
                            <td style="padding: 12px; border: 1px solid #E5E7EB;">домінує {{ strtolower($thinkingLabel) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #E5E7EB; background: #F9FAFB; font-weight: 600;">Домінантні риси характеру</td>
                            <td style="padding: 12px; border: 1px solid #E5E7EB;">{{ implode(', ', $characterTraits) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #E5E7EB; background: #F9FAFB; font-weight: 600;">Самоконтроль</td>
                            <td style="padding: 12px; border: 1px solid #E5E7EB;">Показник {{ strtolower($selfControl) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #E5E7EB; background: #F9FAFB; font-weight: 600;">Схильності, уподобання</td>
                            <td style="padding: 12px; border: 1px solid #E5E7EB;">{{ implode(', ', $inclinations) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px; border: 1px solid #E5E7EB; background: #F9FAFB; font-weight: 600;">Ціннісні орієнтири</td>
                            <td style="padding: 12px; border: 1px solid #E5E7EB;">{{ implode(', ', $valueOrientations) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($riasecCode)
            <div style="margin-top: 30px;">
                <h4 style="margin-bottom: 15px;">Типи професійних схильностей за Голландом (RIASEC):</h4>
                
                <div style="margin: 20px 0; padding: 15px 20px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); border-radius: 12px; color: white; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);">
                    <div style="flex: 1;">
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">Ваш індивідуальний код</div>
                        <div style="font-size: 32px; font-weight: bold; letter-spacing: 6px;">{{ $riasecCode }}</div>
                    </div>
                    <div style="flex: 1; font-size: 12px; opacity: 0.85; line-height: 1.5; padding-left: 20px; border-left: 1px solid rgba(255,255,255,0.3);">
                        <strong>RIASEC</strong> — шість літер, які описують твій тип особистості та професійні інтереси за теорією Джона Голланда.
                    </div>
                </div>

                <table style="width: 100%; border-collapse: collapse; margin: 15px 0; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                    <thead>
                        <tr style="background: #F3F4F6;">
                            <th style="padding: 10px; border: 1px solid #E5E7EB; text-align: left; width: 35%;">Тип</th>
                            <th style="padding: 10px; border: 1px solid #E5E7EB; text-align: left;">Опис</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riasecTypes as $type => $typeName)
                        @php
                            $descriptions = [
                                'realistic' => 'Орієнтація на роботу з інструментами, машинами, природою',
                                'investigative' => 'Аналіз, наукове мислення, вирішення складних завдань',
                                'artistic' => 'Самовираження, творчість, нестандартні рішення',
                                'social' => 'Робота з людьми, допомога, навчання, турбота',
                                'enterprising' => 'Управління, організація, досягнення цілей',
                                'conventional' => 'Порядок, структура, робота з даними'
                            ];
                        @endphp
                        <tr style="{{ $loop->even ? 'background: #f9fafb;' : '' }}">
                            <td style="padding: 10px; border: 1px solid #E5E7EB;"><strong>{{ $typeName }}</strong></td>
                            <td style="padding: 10px; border: 1px solid #E5E7EB;">{{ $descriptions[$type] ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px;">
                <p style="color: #92400e; margin: 0; font-size: 14px;">
                    <strong>Зверніть увагу!</strong> Кожен з вищевказаних показників є актуальним на момент тестування і найближчий період після. Кожна людина – гнучка особистість і протягом професійного життя здатна розвивати будь-які навички, інтереси і схильності.
                </p>
            </div>
        </div>

        @if(isset($result->recommendations['career_paths']) && count($result->recommendations['career_paths']) > 0)
        <div class="results-section recommendations">
            <h3>🎯 Рекомендовані професійні напрямки</h3>
            <p style="margin-bottom: 20px; color: #6b7280;">На основі комплексного аналізу ваших результатів тестування рекомендуємо наступні професійні напрямки та фахи для навчання:</p>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #0c68f5, #0a4fb8);">
                            <th style="padding: 12px; text-align: left; color: white; font-weight: 600;">Професійний напрямок</th>
                            <th style="padding: 12px; text-align: left; color: white; font-weight: 600;">Основна спеціальність (Major)</th>
                            <th style="padding: 12px; text-align: left; color: white; font-weight: 600;">Додаткова спеціальність (Minor)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result->recommendations['career_paths'] as $index => $path)
                        <tr style="border-bottom: 1px solid #e5e7eb; {{ $index % 2 == 0 ? 'background: #f9fafb;' : '' }}">
                            <td style="padding: 12px; vertical-align: top;">
                                <strong style="color: #1f2937;">{{ $path['direction'] ?? $path['career_field'] ?? '' }}</strong>
                            </td>
                            <td style="padding: 12px; vertical-align: top;">
                                @if(isset($path['majors']) && is_array($path['majors']))
                                    @foreach($path['majors'] as $major)
                                        <div style="color: #374151; margin-bottom: 5px;">• {{ $major }}</div>
                                    @endforeach
                                @elseif(isset($path['major']))
                                    <div style="color: #374151;">{{ $path['major'] }}</div>
                                @endif
                            </td>
                            <td style="padding: 12px; vertical-align: top;">
                                @if(isset($path['minors']) && is_array($path['minors']))
                                    @foreach($path['minors'] as $minor)
                                        <div style="color: #374151; margin-bottom: 5px;">• {{ $minor }}</div>
                                    @endforeach
                                @elseif(isset($path['minor']))
                                    <div style="color: #374151;">{{ $path['minor'] }}</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #ecfdf5; border-left: 4px solid #10b981; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #065f46;">Важлива інформація</h4>
                <p style="color: #047857; margin-bottom: 10px;">
                    <strong>Major</strong> – основна спеціальність, яка забезпечує глибокі знання в певній галузі.<br>
                    <strong>Minor</strong> – додаткова спеціальність, що розширює ваш профіль і робить вас більш конкурентоспроможним.
                </p>
                <p style="color: #047857; margin-bottom: 0;">
                    Ці рекомендації розроблені на основі комплексного аналізу всіх модулів тестування і враховують ваші сильні сторони, інтереси та особистісні характеристики.
                </p>
            </div>
        </div>
        @endif

        <div class="results-actions">
            <a href="{{ route('home') }}" class="btn btn-primary">Повернутися до кабінету</a>
            <a href="{{ route('quiz.report.view', ['sessionId' => $session->id]) }}" class="btn btn-success" target="_blank">📄 Переглянути та роздрукувати звіт</a>
        </div>
        @endif
    </div>
</div>
@endsection
