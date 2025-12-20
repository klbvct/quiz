<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $user->name }}</title>
    <style>
        :root {
            --primary-color: #0c68f5;
            --dark-blue: #1e3a8a;
            --light-blue: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --gray: #6b7280;
            --light-gray: #f3f4f6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #1f2937;
            line-height: 1.6;
            font-size: 11px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .report-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--dark-blue);
        }
        
        .report-header h1 {
            font-size: 28px;
            color: var(--dark-blue);
            font-weight: 700;
            margin: 0;
        }
        
        section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        h2 {
            color: var(--dark-blue);
            font-size: 16px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        h3 {
            color: var(--dark-blue);
            font-size: 13px;
            margin-top: 18px;
            margin-bottom: 8px;
        }
        
        h4 {
            color: var(--dark-blue);
            font-size: 11px;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        
        p {
            margin-bottom: 8px;
            color: #374151;
            font-size: 10px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: white;
        }
        
        .info-table th {
            background: var(--primary-color);
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            width: 40%;
            font-size: 10px;
        }
        
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        
        .info-table tr:last-child td {
            border-bottom: none;
        }
        
        .section-applicant-info {
            margin-bottom: 20px;
        }
        
        .highlight-box {
            background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid var(--primary-color);
        }
        
        .highlight-box p {
            color: var(--dark-blue);
            font-weight: bold;
            text-align: center;
            margin: 0;
            font-size: 11px;
        }
        
        .section-content {
            margin: 20px 0;
        }
        
        .toc-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }
        
        .toc-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: var(--light-gray);
            border-left: 4px solid var(--primary-color);
            margin-bottom: 8px;
            font-size: 10px;
        }
        
        .toc-item span {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .content-list {
            list-style: none;
            margin: 12px 0;
            padding: 0;
        }
        
        .content-list li {
            padding: 8px 0;
            padding-left: 20px;
            position: relative;
            line-height: 1.5;
            font-size: 10px;
        }
        
        .content-list li:before {
            content: "▪";
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-weight: bold;
            font-size: 14px;
        }
        
        .content-list li strong {
            color: var(--dark-blue);
        }
        
        .chart-bar {
            margin: 10px 0;
        }
        
        .chart-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .chart-label-text {
            font-weight: 600;
            color: var(--dark-blue);
        }
        
        .chart-label-value {
            color: var(--gray);
        }
        
        .bar-bg {
            height: 16px;
            background: var(--light-gray);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--light-blue) 100%);
            border-radius: 8px;
        }
        
        .bar-fill.highlight {
            background: linear-gradient(90deg, var(--success-color) 0%, #34d399 100%);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 9px;
        }
        
        table thead th {
            background: var(--dark-blue);
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: 600;
        }
        
        table tbody td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tbody tr.highlight {
            background: #fef3c7;
        }
        
        table tbody td:first-child {
            font-weight: 600;
            color: var(--dark-blue);
        }
        
        .recommendation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .recommendation-table thead th {
            background: var(--dark-blue);
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
        }
        
        .recommendation-table tbody td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
            vertical-align: top;
        }
        
        .note-block {
            background: #fef3c7;
            border-left: 4px solid var(--warning-color);
            padding: 12px 15px;
            margin: 15px 0;
            font-size: 10px;
            line-height: 1.5;
        }
        
        .note-block strong {
            color: var(--warning-color);
        }
        
        .contact-block {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            text-align: center;
            font-size: 10px;
        }
        
        .contact-block strong {
            color: var(--dark-blue);
            font-size: 11px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: var(--gray);
            font-size: 9px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .code-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 12px 15px;
            border-radius: 8px;
            margin: 12px 0;
            text-align: center;
            color: white;
        }
        
        .code-box-label {
            font-size: 9px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        
        .code-box-value {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 4px;
        }
        
        .code-box-desc {
            font-size: 9px;
            margin-top: 6px;
            opacity: 0.85;
        }
        
        @page {
            margin: 20mm;
            @top-left { content: none; }
            @top-center { content: none; }
            @top-right { content: none; }
            @bottom-left { content: none; }
            @bottom-center { content: counter(page); }
            @bottom-right { content: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <header class="report-header">
        <h1>ДИЗАЙН ОСВІТИ</h1>
    </header>

    {{-- Інформація про Абітурієнта --}}
    <section class="section-applicant-info">
        <table class="info-table">
            <tr><th>Ім'я та прізвище</th><td>{{ $user->name }}</td></tr>
            <tr><th>Дата народження</th><td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d.m.Y') : 'Не вказано' }}</td></tr>
            <tr><th>Дата тестування</th><td>{{ $session->completed_at ? $session->completed_at->format('d.m.Y') : $session->updated_at->format('d.m.Y') }}</td></tr>
        </table>
    </section>

    <div class="highlight-box">
        <p>Дизайн Освіти – не просто тест, це процес формування індивідуальної освітньої траєкторії для кожного.</p>
    </div>

    {{-- Зміст --}}
    <section class="section-content">
        <h2>📜 Зміст</h2>
        <div class="toc-grid">
            <div>
                <div class="toc-item">Професійна спрямованість <span>01</span></div>
                <div class="toc-item">Індивідуальні інтереси і здібності в навчанні <span>02</span></div>
                <div class="toc-item">Домінуючі типи мислення <span>03</span></div>
                <div class="toc-item">Мотивація та цінності <span>04</span></div>
                <div class="toc-item">Співвідношення типів інтелекту <span>05</span></div>
            </div>
            <div>
                <div class="toc-item">Типологія професійних інтересів за Голландом (RIASEC) <span>06</span></div>
                <div class="toc-item">Типологія сприйняття <span>07</span></div>
                <div class="toc-item">Рекомендації до вибору професійних напрямків <span>08</span></div>
                <div class="toc-item">Індивідуальна психологічна карта <span>09</span></div>
                <div class="toc-item">Підготовка до вступу <span>10</span></div>
            </div>
        </div>
    </section>


    {{-- Професійна спрямованість --}}
    @if(isset($scores['module1']) && is_array($scores['module1']) && count($scores['module1']) > 0)
    <section id="thinking-types">
        <h2>🎯 Професійна спрямованість</h2>
        <p>Виявляє схильність та інтерес школяра до таких галузей знань, як Природа, Техніка, Людина, Знакова система, Художній образ.</p>
        
        @php
            $thinkingTypes = [
                'nature' => 'Людина-Природа (П)',
                'technic' => 'Людина-Техніка (Т)',
                'human' => 'Людина-Людина (Ч)',
                'sign' => 'Людина-Знакова система (З)',
                'art' => 'Людина-Художній образ (Х)'
            ];
            $totalThinking = array_sum($scores['module1']);
            
            $thinkingColors = [
                'nature' => '#10B981',
                'technic' => '#3B82F6',
                'human' => '#EF4444',
                'sign' => '#8B5CF6',
                'art' => '#F59E0B'
            ];
            
            $klimovDescriptions = [
                'nature' => 'Сфери діяльності, пов\'язані з дослідженнями природи, біології, тварин та мікроорганізмів, планети, атмосфери, Землі. Характерні професії: біолог, еколог, ветеринар, агроном, зоотехнік, ландшафтний дизайнер.',
                'technic' => 'Винахід та створення, виробництво та переробка. Підтримка існуючого технічного прогресу або винахід нового. Характерні професії: інженер, механік, програміст, електрик, будівельник, конструктор.',
                'human' => 'Діяльність, пов\'язана з вивченням та взаємодією з людьми, їх життям, лікуванням, навчанням, соціальною сферою, обслуговуванням, захистом та безпекою. Характерні професії: вчитель, лікар, психолог, соціальний працівник, менеджер.',
                'sign' => 'Робота з цифрами, знаками, літерами, схемами, таблицями. Вивчення знакових систем: мови, цифри, формули та символи. Характерні професії: бухгалтер, перекладач, програміст, математик, редактор, архіваріус.',
                'art' => 'Освоєння, опис, художнє зображення дійсності. Почуття прекрасного, створення гармонії, естетики, краси, збереження культурної спадщини. Характерні професії: художник, дизайнер, музикант, актор, архітектор, скульптор.'
            ];
            
            $maxScore = max($scores['module1']);
        @endphp
        
        <h3>Розподіл професійних схильностей:</h3>
        
        @if($totalThinking > 0)
        {{-- Легенда з кольорами --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 15px 0; padding: 12px; background: var(--light-gray); border-radius: 6px;">
            @foreach($thinkingTypes as $key => $name)
            @if(isset($scores['module1'][$key]))
            @php
                $score = $scores['module1'][$key];
                $percent = ($score / 8) * 100;
                $color = $thinkingColors[$key];
            @endphp
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 16px; height: 16px; background-color: {{ $color }}; border-radius: 3px; flex-shrink: 0;"></div>
                <div style="flex: 1; font-size: 9px;">
                    <div style="font-weight: 600; color: #2D3748;">{{ $name }}</div>
                    <div style="color: #718096;">{{ $score }} балів ({{ round($percent) }}%)</div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        
        {{-- Стовпчики з кольорами --}}
        <div style="margin: 15px 0;">
            @foreach($thinkingTypes as $key => $name)
            @if(isset($scores['module1'][$key]))
            @php
                $score = $scores['module1'][$key];
                $percent = ($score / 8) * 100;
                $isMax = ($score == $maxScore);
                $color = $thinkingColors[$key];
            @endphp
            <div class="chart-bar">
                <div class="chart-label">
                    <span class="chart-label-text" style="{{ $isMax ? 'font-weight: bold;' : '' }}">{{ $name }}</span>
                    <span class="chart-label-value">{{ $score }}/8 ({{ round($percent) }}%)</span>
                </div>
                <div class="bar-bg">
                    <div class="bar-fill" style="width: {{ $percent }}%; background: {{ $color }};"></div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        <ul class="content-list">
            @foreach($thinkingTypes as $key => $name)
            @if(isset($scores['module1'][$key]))
            @php
                $score = $scores['module1'][$key];
                $level = $score >= 7 ? 'високий' : ($score >= 4 ? 'середній' : 'низький');
            @endphp
            <li><strong>{{ $name }}</strong> – показник <u>{{ $level }}</u>. {{ $klimovDescriptions[$key] }}</li>
            @endif
            @endforeach
        </ul>
    </section>
    @endif

    <div class="page-break"></div>


    {{-- Інтереси і здібності в навчанні --}}
    @if(isset($scores['module2']))
    <section id="abilities">
        <h2>📚 Індивідуальні інтереси і здібності в навчанні</h2>
        <p>Модуль спрямований на розуміння поточних інтересів та визначення шкали здібностей абітурієнта.</p>
        
        @php
            $industryQuestionMap = [
                'natural_science' => range(1, 11),
                'engineering' => range(12, 22),
                'it' => range(23, 33),
                'business' => range(34, 44),
                'humanities' => range(45, 55),
                'journalism' => range(56, 66),
                'social' => range(67, 77),
                'communications' => range(78, 88),
                'creative' => range(89, 99),
                'education' => range(100, 110),
                'law' => range(111, 121),
                'medicine' => range(122, 133),
                'art' => range(134, 144),
                'hospitality' => range(145, 155),
                'agriculture' => range(156, 165),
                'construction' => range(166, 173)
            ];
            
            $industryInfo = [
                'natural_science' => ['Природничі науки', '#10B981'],
                'engineering' => ['Інженерія та технології', '#3B82F6'],
                'it' => ['Інформаційні технології', '#8B5CF6'],
                'business' => ['Бізнес, менеджмент та економіка', '#F59E0B'],
                'humanities' => ['Гуманітарні науки', '#EF4444'],
                'journalism' => ['Журналістика, медіа, кіно та піар', '#EC4899'],
                'social' => ['Соціальні науки', '#14B8A6'],
                'communications' => ['Комунікації та медіа', '#6366F1'],
                'creative' => ['Креативні індустрії та дизайн', '#F97316'],
                'education' => ['Освіта та педагогіка', '#84CC16'],
                'law' => ['Право та державне управління', '#06B6D4'],
                'medicine' => ['Медицина та здоров\'я', '#10B981'],
                'art' => ['Мистецтво', '#EC4899'],
                'hospitality' => ['Готельно-ресторанна справа та туризм', '#F59E0B'],
                'agriculture' => ['Аграрні науки та FoodTech', '#22C55E'],
                'construction' => ['Будівництво та архітектура', '#6366F1']
            ];
            
            $module2Answers = \App\Models\QuizAnswer::where('session_id', $session->id)
                ->where('module_number', 2)
                ->pluck('answer', 'question_number');
            
            $industryScores = [];
            $scaleValues = ['++' => 3, '+' => 2, '0' => 1, '-' => 0, '--' => -1];
            
            foreach($industryQuestionMap as $key => $questions) {
                $score = 0;
                $maxPossible = count($questions) * 3;
                
                foreach($questions as $qNum) {
                    if(isset($module2Answers[$qNum])) {
                        $answer = $module2Answers[$qNum];
                        $score += $scaleValues[$answer] ?? 0;
                    }
                }
                
                $minPossible = count($questions) * (-1);
                $normalizedScore = $score - $minPossible;
                $normalizedMax = $maxPossible - $minPossible;
                
                $percent = $normalizedMax > 0 ? ($normalizedScore / $normalizedMax) * 100 : 0;
                
                if($percent >= 75) {
                    $level = '++';
                    $levelText = 'дуже сильна';
                } elseif($percent >= 50) {
                    $level = '+';
                    $levelText = 'добра';
                } elseif($percent >= 30) {
                    $level = '0';
                    $levelText = 'сіра зона';
                } elseif($percent >= 15) {
                    $level = '-';
                    $levelText = 'низька';
                } else {
                    $level = '--';
                    $levelText = 'мінімальна';
                }
                
                $industryScores[] = [
                    'key' => $key,
                    'name' => $industryInfo[$key][0],
                    'color' => $industryInfo[$key][1],
                    'score' => $normalizedScore,
                    'percent' => $percent,
                    'level' => $level,
                    'levelText' => $levelText
                ];
            }
            
            usort($industryScores, function($a, $b) {
                return $b['percent'] <=> $a['percent'];
            });
        @endphp
        
        <h3 style="margin-bottom: 10px;">Топ-10 сфер за рівнем інтересу:</h3>
        
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Сфера діяльності</th>
                    <th>Рівень</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @php $rank = 1; @endphp
                @foreach(array_slice($industryScores, 0, 10) as $industry)
                    <tr class="{{ $rank <= 3 ? 'highlight' : '' }}">
                        <td>{{ $rank++ }}</td>
                        <td>{{ $industry['name'] }}</td>
                        <td>{{ $industry['levelText'] }}</td>
                        <td>{{ round($industry['percent']) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <p style="margin-top: 15px; font-size: 10px;">Не всі навички, здобуті в школі, легко перетворюються на професійні компетенції бакалаврату. Тут важливо розуміти різницю між <strong>трансферними вміннями</strong> (наприклад, комунікація, розуміння предмету) і <strong>спеціалізованими знаннями</strong>, які здебільшого здобуваються лише у ВИШі.</p>
    </section>
    @endif

    <div class="page-break"></div>


    {{-- Домінуючі типи мислення (Модуль 3) --}}
    @if(isset($scores['module3']))
    <section id="thinking-types-module3">
        <h2>🧠 Домінуючі типи мислення</h2>
        <p>У людини задіяна велика кількість типів мислення. У даному випадку ми говоримо про ті, які <strong>переважають</strong>.</p>
        
        @php
            $thinkingTypesM3 = [
                'artistic' => 'Художнє',
                'theoretical' => 'Теоретичне',
                'practical' => 'Практичне',
                'creative' => 'Творче',
                'convergent' => 'Конвергентне',
                'intuitive' => 'Інтуїтивне',
                'analytical' => 'Аналітичне'
            ];
            
            $thinkingDescriptions = [
                'artistic' => 'Наочно-образне мислення, сприйняття світу через образи та відчуття.',
                'theoretical' => 'Схильність до узагальнення, аналізу та класифікації знань.',
                'practical' => 'Орієнтація на практичне застосування знань та конкретні результати.',
                'creative' => 'Генерація нових ідей, нестандартний підхід до вирішення завдань.',
                'convergent' => 'Бізнес-мислення, здатність швидко адаптуватися до змін.',
                'intuitive' => 'Прийняття рішень на основі внутрішнього відчуття та досвіду.',
                'analytical' => 'Структурування інформації, робота з абстрактними поняттями.'
            ];
            
            $totalThinkingM3 = array_sum($scores['module3']);
            $percentagesM3 = [];
            
            if($totalThinkingM3 > 0) {
                foreach($thinkingTypesM3 as $key => $name) {
                    if(isset($scores['module3'][$key])) {
                        $percentagesM3[$key] = [
                            'name' => $name,
                            'score' => $scores['module3'][$key],
                            'percent' => ($scores['module3'][$key] / $totalThinkingM3) * 100
                        ];
                    }
                }
                
                uasort($percentagesM3, function($a, $b) {
                    return $b['score'] <=> $a['score'];
                });
            }
        @endphp
        
        @if($totalThinkingM3 > 0 && count($percentagesM3) > 0)
        <h3>Профіль мислення:</h3>
        
        {{-- Стовпці з процентами --}}
        <div style="margin: 15px 0;">
            @foreach($percentagesM3 as $key => $data)
            <div class="chart-bar">
                <div class="chart-label">
                    <span class="chart-label-text">{{ $data['name'] }}</span>
                    <span class="chart-label-value">{{ round($data['percent']) }}%</span>
                </div>
                <div class="bar-bg">
                    <div class="bar-fill" style="width: {{ $data['percent'] }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Описи типів --}}
        <ul class="content-list">
            @foreach($percentagesM3 as $key => $data)
            <li><strong>{{ $data['name'] }} мислення ({{ round($data['percent']) }}%)</strong> — {{ $thinkingDescriptions[$key] }}</li>
            @endforeach
        </ul>
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для побудови діаграми.</p>
        @endif
    </section>
    @endif

    <div class="page-break"></div>


    {{-- Мотивація та цінності абітурієнта --}}
    @if(isset($scores['module4']) && isset($scores['module6']))
    <section id="motivation-values">
        <h2>🎯 Мотивація та цінності абітурієнта</h2>
        
        @php
        $module4 = $scores['module4'] ?? [];
        $veryImportant = [];
        
        if (is_array($module4) && count($module4) > 0) {
            asort($module4);
            $veryImportant = array_filter($module4, function($rank) { 
                return is_numeric($rank) && $rank >= 1 && $rank <= 6; 
            });
        }
        
        $module6 = $scores['module6'] ?? [];
        $strongMotivators = [];
        
        if (is_array($module6) && count($module6) > 0) {
            arsort($module6);
            $strongMotivators = array_filter($module6, function($score) { 
                return is_numeric($score) && $score >= 6; 
            });
        }
        @endphp
        
        <h3>📊 Топ-6 найважливіших цінностей:</h3>
        
        @if(count($veryImportant) > 0)
        <table>
            <thead>
                <tr>
                    <th>Ранг</th>
                    <th>Цінність</th>
                </tr>
            </thead>
            <tbody>
                @foreach($veryImportant as $value => $rank)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $rank }}</td>
                        <td>{{ ucfirst($value) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <h3 style="margin-top: 15px;">⚡ Сильні мотиватори (6-7 балів):</h3>
        
        @if(count($strongMotivators) > 0)
        <table>
            <thead>
                <tr>
                    <th>Фактор</th>
                    <th>Бал</th>
                </tr>
            </thead>
            <tbody>
                @foreach($strongMotivators as $factor => $score)
                    <tr>
                        <td>{{ ucfirst($factor) }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $score }} / 7</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 10px; color: #666;">Немає сильних мотиваторів (6-7 балів).</p>
        @endif
        
        <div class="note-block" style="margin-top: 15px;">
            <strong>Рекомендація:</strong> При виборі освітньої програми орієнтуйтеся на топ-6 цінностей та враховуйте сильні мотиватори.
        </div>
    </section>
    @endif

    <div class="page-break"></div>


    {{-- Співвідношення типів інтелекту (Модуль 5) --}}
    @if(isset($scores['module5']))
    <section id="intelligence-types">
        <h2>🧠 Співвідношення типів інтелекту</h2>
        <p>Визначення типу інтелекту згідно теорії множинного інтелекту Говарда Гарднера.</p>
        
        @php
            $intelligenceTypes = [
                'linguistic' => 'Лінгвістичний',
                'logical_mathematical' => 'Логіко-математичний',
                'spatial' => 'Просторовий',
                'bodily_kinesthetic' => 'Тілесно-кінестетичний',
                'musical' => 'Музичний',
                'interpersonal' => 'Міжособистісний',
                'intrapersonal' => 'Внутрішньоособистісний',
                'naturalistic' => 'Натуралістичний'
            ];
            
            $intelligenceDescriptions = [
                'linguistic' => 'Здатність ефективно використовувати мову, схильність до читання та письма.',
                'logical_mathematical' => 'Логічне мислення, математичні здібності, аналіз причинно-наслідкових зв\'язків.',
                'spatial' => 'Просторова уява, здатність до візуалізації та роботи з образами.',
                'bodily_kinesthetic' => 'Контроль тіла, координація рухів, практичні навички.',
                'musical' => 'Музичний слух, ритм, здатність розрізняти та створювати музику.',
                'interpersonal' => 'Розуміння інших людей, емпатія, комунікативні навички.',
                'intrapersonal' => 'Самоаналіз, розуміння власних емоцій та мотивацій.',
                'naturalistic' => 'Розуміння природи, здатність класифікувати та працювати з живими об\'єктами.'
            ];
            
            $intelligenceScores = $scores['module5'];
            arsort($intelligenceScores);
            
            $maxScore = max($intelligenceScores);
            $topIntelligence = array_slice($intelligenceScores, 0, 3, true);
        @endphp
        
        @if(count($intelligenceScores) > 0)
        
        <h3>Профіль інтелекту:</h3>
        
        <div style="margin: 15px 0;">
            @foreach($intelligenceScores as $type => $score)
            @php
                $percent = ($maxScore > 0) ? ($score / $maxScore) * 100 : 0;
                $isTop3 = array_key_exists($type, $topIntelligence);
            @endphp
            <div class="chart-bar">
                <div class="chart-label">
                    <span class="chart-label-text" style="{{ $isTop3 ? 'font-weight: bold;' : '' }}">{{ $intelligenceTypes[$type] ?? $type }}</span>
                    <span class="chart-label-value">{{ $score }} балів</span>
                </div>
                <div class="bar-bg">
                    <div class="bar-fill {{ $isTop3 ? 'highlight' : '' }}" style="width: {{ $percent }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <h3>Топ-3 домінуючі типи інтелекту:</h3>
        <ul class="content-list">
            @foreach($topIntelligence as $type => $score)
            <li>
                <strong>{{ $intelligenceTypes[$type] ?? $type }}</strong> ({{ $score }} балів) — 
                {{ $intelligenceDescriptions[$type] ?? '' }}
            </li>
            @endforeach
        </ul>
        
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для аналізу типів інтелекту.</p>
        @endif
    </section>
    @endif
    
    <div class="page-break"></div>


    {{-- Система Голланда RIASEC (Модуль 7) --}}
    @if(isset($scores['module7']))
    <section id="holland-riasec">
        <h2>🎯 Типологія професійних інтересів за Голландом (RIASEC)</h2>
        <p>Визначення домінуючого типу особистості за системою професійних інтересів Джона Голланда.</p>
        
        @php
            $hollandTypes = [
                'realistic' => ['R', 'Реалістичний', '#3B82F6'],
                'investigative' => ['I', 'Дослідницький', '#10B981'],
                'artistic' => ['A', 'Артистичний', '#EC4899'],
                'social' => ['S', 'Соціальний', '#F59E0B'],
                'enterprising' => ['E', 'Підприємницький', '#EF4444'],
                'conventional' => ['C', 'Конвенційний', '#8B5CF6']
            ];
            
            $hollandDescriptions = [
                'realistic' => 'Практична робота з інструментами, технікою, механізмами.',
                'investigative' => 'Наукове мислення, дослідження, аналіз даних.',
                'artistic' => 'Творчість, самовираження, робота в мистецтві.',
                'social' => 'Робота з людьми, навчання, допомога іншим.',
                'enterprising' => 'Лідерство, управління, підприємництво.',
                'conventional' => 'Структурована робота, облік, порядок, системи.'
            ];
            
            $hollandScores = $scores['module7'];
            arsort($hollandScores);
            
            $topThree = array_slice($hollandScores, 0, 3, true);
            $hollandCode = '';
            foreach($topThree as $type => $score) {
                if(isset($hollandTypes[$type])) {
                    $hollandCode .= $hollandTypes[$type][0];
                }
            }
            
            $maxScore = max($hollandScores);
        @endphp
        
        @if(count($hollandScores) > 0)
        
        {{-- Код Голланда в стильному блоці --}}
        <div class="code-box">
            <div class="code-box-label">Ваш домінуючий код Голланда:</div>
            <div class="code-box-value">{{ $hollandCode }}</div>
            <div class="code-box-desc">
                @foreach($topThree as $type => $score)
                    @if(isset($hollandTypes[$type]))
                        {{ $hollandTypes[$type][1] }}@if(!$loop->last) • @endif
                    @endif
                @endforeach
            </div>
        </div>
        
        <h3>Розподіл типів професійних інтересів:</h3>
        
        <div style="margin: 15px 0;">
            @foreach($hollandScores as $type => $score)
            @php
                $typeData = $hollandTypes[$type] ?? ['?', $type, '#6b7280'];
                $percent = ($maxScore > 0) ? ($score / $maxScore) * 100 : 0;
                $isTop3 = array_key_exists($type, $topThree);
            @endphp
            <div class="chart-bar">
                <div class="chart-label">
                    <span class="chart-label-text" style="{{ $isTop3 ? 'font-weight: bold;' : '' }}">
                        {{ $typeData[0] }} - {{ $typeData[1] }}
                    </span>
                    <span class="chart-label-value">{{ $score }} балів</span>
                </div>
                <div class="bar-bg">
                    <div class="bar-fill {{ $isTop3 ? 'highlight' : '' }}" style="width: {{ $percent }}%; background: {{ $typeData[2] }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <h3>Детальна характеристика домінуючих типів:</h3>
        <ul class="content-list">
            @foreach($topThree as $type => $score)
            @php
                $typeData = $hollandTypes[$type] ?? ['?', $type, '#6b7280'];
                $name = $typeData[1];
            @endphp
            <li>
                <strong>{{ $typeData[0] }} - {{ $name }}</strong> ({{ $score }} балів) — {{ $hollandDescriptions[$type] ?? '' }}
            </li>
            @endforeach
        </ul>
        
        <div class="note-block">
            <strong>Інтерпретація коду {{ $hollandCode }}:</strong> Ваш професійний код показує комбінацію домінуючих типів особистості. При виборі професії шукайте напрямки, які відповідають цим типам.
        </div>
        
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для визначення типу за системою Голланда.</p>
        @endif
    </section>
    @endif
    
    <div class="page-break"></div>


    {{-- Типологія сприйняття (Модуль 8) --}}
    @if(isset($scores['module8']))
    <section id="perception-types">
        <h2>👁️ Типологія сприйняття</h2>
        <p>Визначення домінуючих каналів отримання та обробки інформації.</p>
        
        @php
            $perceptionTypes = [
                'visual' => 'Візуальний',
                'auditory' => 'Аудіальний',
                'kinesthetic' => 'Кінестетичний',
                'digital' => 'Дискретний'
            ];
            
            $perceptionDescriptions = [
                'visual' => 'Сприйняття через зір, легко запам\'ятовуються образи та схеми.',
                'auditory' => 'Сприйняття через слух, краще засвоюється інформація при прослуховуванні.',
                'kinesthetic' => 'Сприйняття через рух та відчуття, потреба в практиці.',
                'digital' => 'Логічне, аналітичне сприйняття через структуру та систему.'
            ];
            
            $totalScore = array_sum($scores['module8']);
            $perceptionPercentages = [];
            
            if($totalScore > 0) {
                foreach($perceptionTypes as $key => $name) {
                    if(isset($scores['module8'][$key])) {
                        $score = $scores['module8'][$key];
                        $percent = ($score / $totalScore) * 100;
                        
                        $perceptionPercentages[$key] = [
                            'name' => $name,
                            'score' => $score,
                            'percent' => $percent
                        ];
                    }
                }
                
                uasort($perceptionPercentages, function($a, $b) {
                    return $b['score'] <=> $a['score'];
                });
            }
            
            $dominantType = count($perceptionPercentages) > 0 ? array_key_first($perceptionPercentages) : null;
        @endphp
        
        @if(count($perceptionPercentages) > 0)
        
        <h3>Розподіл каналів сприйняття:</h3>
        
        <div style="margin: 15px 0;">
            @foreach($perceptionPercentages as $key => $data)
            <div class="chart-bar">
                <div class="chart-label">
                    <span class="chart-label-text">{{ $data['name'] }}</span>
                    <span class="chart-label-value">{{ round($data['percent']) }}%</span>
                </div>
                <div class="bar-bg">
                    <div class="bar-fill" style="width: {{ $data['percent'] }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <h3>Характеристика типів сприйняття:</h3>
        <ul class="content-list">
            @foreach($perceptionPercentages as $key => $data)
            <li>
                <strong>{{ $data['name'] }}</strong> ({{ round($data['percent']) }}%) — {{ $perceptionDescriptions[$key] ?? '' }}
            </li>
            @endforeach
        </ul>
        
        @if($dominantType)
        <div class="note-block">
            <strong>Рекомендації для навчання:</strong> Ваш домінуючий тип сприйняття - <strong>{{ $perceptionTypes[$dominantType] }}</strong>. 
            Використовуйте цей канал для ефективного засвоєння інформації.
        </div>
        @endif
        
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для визначення типу сприйняття.</p>
        @endif
    </section>
    @endif

    <div class="page-break"></div>

    {{-- Рекомендації до вибору професійних напрямків --}}
    <section id="recommendations">
        <h2>🎯 Рекомендації до вибору професійних напрямків</h2>
        <p>Рекомендовані напрямки (major) та додаткове навчання (minor):</p>
        
        @if($result && isset($result->recommendations['professional_types']) && count($result->recommendations['professional_types']) > 0)
        <table class="recommendation-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Тип професії</th>
                    <th>Опис та рекомендовані професії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result->recommendations['professional_types'] as $recommendation)
                <tr>
                    <td style="font-weight: bold; vertical-align: top;">
                        {{ $recommendation['type'] ?? 'Тип професії' }}
                    </td>
                    <td>
                        @if(isset($recommendation['description']))
                        <p style="margin: 0 0 8px 0; font-size: 10px;">{{ $recommendation['description'] }}</p>
                        @endif
                        
                        @if(isset($recommendation['professions']) && count($recommendation['professions']) > 0)
                        <div style="font-size: 9px;">
                            <strong>Рекомендовані професії:</strong>
                            {{ implode(', ', $recommendation['professions']) }}
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 10px;">Рекомендації формуються на основі результатів тестування...</p>
        @endif
    </section>

    <div class="page-break"></div>
    
    {{-- Психологічна Карта --}}
    <section id="psychological-map">
        <h2>📋 Індивідуальна психологічна карта</h2>

        @php
            // Збираємо дані для психологічної карти
            $dominantThinking = '';
            $thinkingLabel = '';
            if(isset($scores['module3'])) {
                $maxThinkingScore = max($scores['module3']);
                foreach($scores['module3'] as $type => $score) {
                    if($score == $maxThinkingScore) {
                        $dominantThinking = $type;
                        $thinkingLabels = [
                            'artistic' => 'Художнє',
                            'theoretical' => 'Теоретичне',
                            'practical' => 'Практичне',
                            'creative' => 'Творче',
                            'convergent' => 'Конвергентне',
                            'intuitive' => 'Інтуїтивне',
                            'analytical' => 'Аналітичне'
                        ];
                        $thinkingLabel = $thinkingLabels[$type] ?? $type;
                        break;
                    }
                }
            }
            
            // Ціннісні орієнтири
            $valueOrientations = [];
            if(isset($scores['module4'])) {
                asort($scores['module4']);
                $topValues = array_slice($scores['module4'], 0, 3, true);
                foreach($topValues as $value => $rank) {
                    $valueOrientations[] = ucfirst($value);
                }
            }
            
            // RIASEC код
            $riasecCode = '';
            $riasecTypes = [];
            if(isset($scores['module7'])) {
                $hollandScores = $scores['module7'];
                arsort($hollandScores);
                $topThreeHolland = array_slice($hollandScores, 0, 3, true);
                
                $hollandTypesMap = [
                    'realistic' => ['R', 'Реалістичний'],
                    'investigative' => ['I', 'Дослідницький'],
                    'artistic' => ['A', 'Артистичний'],
                    'social' => ['S', 'Соціальний'],
                    'enterprising' => ['E', 'Підприємницький'],
                    'conventional' => ['C', 'Конвенційний']
                ];
                
                foreach($topThreeHolland as $type => $score) {
                    if(isset($hollandTypesMap[$type])) {
                        $riasecCode .= $hollandTypesMap[$type][0];
                        $riasecTypes[] = [
                            'code' => $hollandTypesMap[$type][0],
                            'name' => $hollandTypesMap[$type][1],
                            'score' => $score
                        ];
                    }
                }
            }
        @endphp

        <table style="width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 10px;">
            <tr>
                <th style="background: #f3f4f6; padding: 8px; border: 1px solid #e5e7eb; width: 35%;">Інтелектуальний потенціал</th>
                <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ $thinkingLabel ?: 'Не визначено' }}</td>
            </tr>
            <tr>
                <th style="background: #f3f4f6; padding: 8px; border: 1px solid #e5e7eb;">Ціннісні орієнтири</th>
                <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ count($valueOrientations) > 0 ? implode(', ', $valueOrientations) : 'Не визначено' }}</td>
            </tr>
            <tr>
                <th style="background: #f3f4f6; padding: 8px; border: 1px solid #e5e7eb;">Код Голланда (RIASEC)</th>
                <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold; color: #667eea;">{{ $riasecCode ?: 'Не визначено' }}</td>
            </tr>
        </table>

        @if($riasecCode)
        <h3 style="margin-top: 20px;">Типи професійних схильностей за Голландом:</h3>
        
        <table style="width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 10px;">
            <thead>
                <tr>
                    <th style="background: #667eea; color: white; padding: 6px; text-align: center; width: 15%;">Код</th>
                    <th style="background: #667eea; color: white; padding: 6px;">Тип</th>
                    <th style="background: #667eea; color: white; padding: 6px; text-align: center; width: 20%;">Бал</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riasecTypes as $rtype)
                <tr>
                    <td style="border: 1px solid #e5e7eb; padding: 6px; text-align: center; font-weight: bold; color: #667eea;">
                        {{ $rtype['code'] }}
                    </td>
                    <td style="border: 1px solid #e5e7eb; padding: 6px;">
                        {{ $rtype['name'] }}
                    </td>
                    <td style="border: 1px solid #e5e7eb; padding: 6px; text-align: center;">
                        {{ $rtype['score'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="note-block" style="margin-top: 15px;">
            <strong>Примітка:</strong> Психологічна карта формується на основі комплексного аналізу всіх модулів тестування та показує ключові характеристики абітурієнта.
        </div>
    </section>

    {{-- Примітки --}}
    <section id="notes">
        <h2>📑 Підготовка до вступу</h2>
        <ol style="margin-left: 20px; font-size: 10px; line-height: 1.6;">
            <li>Проаналізуйте результати тестування та визначте топ-3 напрямки, які найбільше відповідають вашим здібностям та інтересам.</li>
            <li>Дослідіть освітні програми університетів за обраними напрямками.</li>
            <li>Відвідайте дні відкритих дверей та зустрічі з представниками факультетів.</li>
            <li>Складіть список необхідних предметів для вступу та почніть підготовку.</li>
            <li>Звертайтеся до профорієнтаційного консультанта для отримання додаткових рекомендацій.</li>
            <li>Розгляньте можливість проходження стажувань або курсів у обраній сфері.</li>
            <li>Регулярно переглядайте свої цілі та коригуйте план підготовки.</li>
            <li>Пам'ятайте: правильний вибір професії - це баланс між здібностями, інтересами та можливостями.</li>
        </ol>

        <div class="contact-block">
            <strong>Потрібна додаткова консультація?</strong><br>
            Зверніться до нашої служби підтримки для отримання персональних рекомендацій<br>
            щодо вибору освітньої траєкторії.
        </div>
    </section>

    <div class="footer">
        <p>Звіт згенеровано {{ now()->format('d.m.Y о H:i') }}</p>
        <p>Система профорієнтаційного тестування "Дизайн Освіти"</p>
    </div>

</div>

</body>
</html>
