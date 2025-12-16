<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Звіт профорієнтаційного тестування - {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/report-styled.css') }}">
</head>
<body>

<div class="container">
    <div class="report-banner"> 
        <img src="{{ asset('images/1.jpg') }}" alt="Banner" class="banner-image">
    </div>
    <header class="report-header">
        <h1>ДИЗАЙН ОСВІТИ</h1>
    </header>

    {{-- Інформація про Абітурієнта --}}
    <section class="section-applicant-info">
        <table class="info-table">
            <tr><th>Ім'я абітурієнта</th><td>{{ $user->name }}</td></tr>
            <tr><th>Дата народження</th><td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('d.m.Y') : 'Не вказано' }}</td></tr>
            <tr><th>Дата тестування</th><td>{{ $session->completed_at ? $session->completed_at->format('d.m.Y') : $session->updated_at->format('d.m.Y') }}</td></tr>
        </table>
    </section>

    <div class="highlight-box">
        <p style="font-size: 12px; color: var(--dark-blue); font-weight: bold; text-align: center;">
            Дизайн Освіти – не просто тест, це процес формування індивідуальної освітньої траєкторії для кожного.
        </p>
    </div>

    {{-- Зміст --}}
    <section class="section-content">
        <h2>📜 Зміст</h2>
        <div class="toc-grid">
            <div>
                <div class="toc-item">Домінуючі типи мислення <span>03</span></div>
                <div class="toc-item">Інтереси і здібності в навчанні <span>04</span></div>
                <div class="toc-item">Типи професійної взаємодії <span>05</span></div>
                <div class="toc-item">Типологія сприйняття <span>06</span></div>
            </div>
            <div>
                <div class="toc-item">Типи Інтелекту <span>07</span></div>
                <div class="toc-item">Освітньо-кваліфікаційні напрямки <span>08</span></div>
                <div class="toc-item">Психологічна карта <span>09</span></div>
                <div class="toc-item">Примітки <span>11</span></div>
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
            
            // Цвета для каждого типа
            $thinkingColors = [
                'nature' => '#10B981',
                'technic' => '#3B82F6',
                'human' => '#EF4444',
                'sign' => '#8B5CF6',
                'art' => '#F59E0B'
            ];
            
            // Описания для каждого типа
            $klimovDescriptions = [
                'nature' => 'Сфери діяльності, пов\'язані з дослідженнями природи, біології, тварин та мікроорганізмів, планети, атмосфери, Землі. Характерні професії: біолог, еколог, ветеринар, агроном, зоотехнік, ландшафтний дизайнер.',
                'technic' => 'Винахід та створення, виробництво та переробка. Підтримка існуючого технічного прогресу або винахід нового. Характерні професії: інженер, механік, програміст, електрик, будівельник, конструктор.',
                'human' => 'Діяльність, пов\'язана з вивченням та взаємодією з людьми, їх життям, лікуванням, навчанням, соціальною сферою, обслуговуванням, захистом та безпекою. Характерні професії: вчитель, лікар, психолог, соціальний працівник, менеджер.',
                'sign' => 'Робота з цифрами, знаками, літерами, схемами, таблицями. Вивчення знакових систем: мови, цифри, формули та символи. Характерні професії: бухгалтер, перекладач, програміст, математик, редактор, архіваріус.',
                'art' => 'Освоєння, опис, художнє зображення дійсності. Почуття прекрасного, створення гармонії, естетики, краси, збереження культурної спадщини. Характерні професії: художник, дизайнер, музикант, актор, архітектор, скульптор.'
            ];
            
            // Вычисляем проценты для круговой диаграммы
            $percentages = [];
            $cumulativePercent = 0;
            if($totalThinking > 0) {
                foreach($thinkingTypes as $key => $name) {
                    if(isset($scores['module1'][$key]) && $scores['module1'][$key] > 0) {
                        $percent = ($scores['module1'][$key] / $totalThinking) * 100;
                        $percentages[$key] = [
                            'name' => $name,
                            'value' => $scores['module1'][$key],
                            'percent' => $percent,
                            'cumulative' => $cumulativePercent,
                            'color' => $thinkingColors[$key]
                        ];
                        $cumulativePercent += $percent;
                    }
                }
            }
        @endphp
        
        @if($totalThinking > 0 && count($percentages) > 0)
        {{-- Круговая диаграмма --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin: 30px 0; gap: 40px;">
            {{-- Круговая диаграмма --}}
            <div style="flex: 0 0 300px;">
                <svg viewBox="0 0 200 200" style="width: 300px; height: 300px; transform: rotate(-90deg);">
                    @php
                        $radius = 80;
                        $circumference = 2 * pi() * $radius;
                        $currentOffset = 0;
                    @endphp
                    
                    @foreach($percentages as $key => $data)
                        @php
                            $strokeLength = ($data['percent'] / 100) * $circumference;
                            $gap = 1; // Небольшой зазор между сегментами
                        @endphp
                        <circle
                            cx="100"
                            cy="100"
                            r="{{ $radius }}"
                            fill="none"
                            stroke="{{ $data['color'] }}"
                            stroke-width="40"
                            stroke-dasharray="{{ $strokeLength - $gap }} {{ $circumference - $strokeLength + $gap }}"
                            stroke-dashoffset="{{ -$currentOffset }}"
                            opacity="0.9"
                        />
                        @php
                            $currentOffset += $strokeLength;
                        @endphp
                    @endforeach
                    
                    {{-- Центральный круг для создания эффекта пончика --}}
                    <circle cx="100" cy="100" r="50" fill="white"/>
                    
                    {{-- Проценты на сегментах --}}
                    @php
                        $currentAngle = 0;
                    @endphp
                    @foreach($percentages as $key => $data)
                        @php
                            // Вычисляем угол сегмента в градусах
                            $segmentAngle = ($data['percent'] / 100) * 360;
                            // Угол середины сегмента
                            $midAngle = $currentAngle + ($segmentAngle / 2);
                            // Конвертируем в радианы
                            $midAngleRad = deg2rad($midAngle);
                            // Радиус для размещения текста (на середине кольца)
                            $textRadius = $radius;
                            // Вычисляем координаты
                            $textX = 100 + $textRadius * cos($midAngleRad);
                            $textY = 100 + $textRadius * sin($midAngleRad);
                            
                            $currentAngle += $segmentAngle;
                            
                            // Показываем только если процент >= 5%
                            $showPercent = $data['percent'] >= 5;
                        @endphp
                        @if($showPercent)
                        <text 
                            x="{{ $textX }}" 
                            y="{{ $textY }}" 
                            text-anchor="middle" 
                            dominant-baseline="middle"
                            style="font-size: 10px; font-weight: bold; fill: white; transform: rotate(90deg); transform-origin: {{ $textX }}px {{ $textY }}px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                            {{ round($data['percent']) }}%
                        </text>
                        @endif
                    @endforeach
                    
                    {{-- Текст в центре --}}
                    <text x="100" y="95" text-anchor="middle" style="font-size: 16px; font-weight: bold; fill: #2D3748; transform: rotate(90deg); transform-origin: 100px 100px;">
                        Типи
                    </text>
                    <text x="100" y="110" text-anchor="middle" style="font-size: 14px; fill: #4A5568; transform: rotate(90deg); transform-origin: 100px 100px;">
                        мислення
                    </text>
                </svg>
            </div>
            
            {{-- Легенда --}}
            <div style="flex: 1;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    @foreach($percentages as $key => $data)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 20px; height: 20px; background-color: {{ $data['color'] }}; border-radius: 3px; flex-shrink: 0;"></div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 11px; font-weight: 600; color: #2D3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $data['name'] }}
                            </div>
                            <div style="font-size: 10px; color: #718096;">
                                {{ $data['value'] }} балів ({{ round($data['percent']) }}%)
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для побудови діаграми. Переконайтеся, що тестування пройдено повністю.</p>
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
            // Маппинг вопросов модуля 2 на 16 отраслей (по 10-11 вопросов на отрасль)
            // Всего 173 вопроса
            $industryQuestionMap = [
                'natural_science' => range(1, 11),        // Естественные науки (1-11)
                'engineering' => range(12, 22),           // Инженерия и технологии (12-22)
                'it' => range(23, 33),                    // Информационные технологии (23-33)
                'business' => range(34, 44),              // Бизнес, менеджмент и экономика (34-44)
                'humanities' => range(45, 55),            // Гуманитарные науки (45-55)
                'journalism' => range(56, 66),            // Журналистика, медиа, кино и пиар (56-66)
                'social' => range(67, 77),                // Социальные науки (67-77)
                'communications' => range(78, 88),        // Коммуникации и медиа (78-88)
                'creative' => range(89, 99),              // Креативные индустрии и дизайн (89-99)
                'education' => range(100, 110),           // Образование и педагогика (100-110)
                'law' => range(111, 121),                 // Право и государственное управление (111-121)
                'medicine' => range(122, 133),            // Медицина и здоровье (122-133)
                'art' => range(134, 144),                 // Искусство (134-144)
                'hospitality' => range(145, 155),         // Гостинично-ресторанное дело, туризм (145-155)
                'agriculture' => range(156, 165),         // Аграрные науки и FoodTech (156-165)
                'construction' => range(166, 173)         // Строительство и архитектура (166-173)
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
            
            // Получаем все ответы модуля 2 из базы
            $module2Answers = \App\Models\QuizAnswer::where('session_id', $session->id)
                ->where('module_number', 2)
                ->pluck('answer', 'question_number');
            
            // Вычисляем баллы для каждой отрасли
            $industryScores = [];
            $scaleValues = ['++' => 3, '+' => 2, '0' => 1, '-' => 0, '--' => -1];
            
            foreach($industryQuestionMap as $key => $questions) {
                $score = 0;
                $maxPossible = count($questions) * 3; // максимум ++  для всех вопросов
                
                foreach($questions as $qNum) {
                    if(isset($module2Answers[$qNum])) {
                        $answer = $module2Answers[$qNum];
                        $score += $scaleValues[$answer] ?? 0;
                    }
                }
                
                // Нормализуем к положительной шкале (добавляем минимум, чтобы избежать отрицательных значений)
                $minPossible = count($questions) * (-1); // минимум -- для всех вопросов
                $normalizedScore = $score - $minPossible; // сдвигаем в положительную область
                $normalizedMax = $maxPossible - $minPossible;
                
                $percent = $normalizedMax > 0 ? ($normalizedScore / $normalizedMax) * 100 : 0;
                
                // Определяем уровень
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
                    'maxScore' => $normalizedMax,
                    'percent' => $percent,
                    'level' => $level,
                    'levelText' => $levelText
                ];
            }
            
            // Сортируем по процентам (от большего к меньшему)
            usort($industryScores, function($a, $b) {
                return $b['percent'] <=> $a['percent'];
            });
            
            // Пересчитываем для treemap
            $totalScore = 0;
            foreach($industryScores as $industry) {
                $totalScore += max(1, $industry['score']); // минимум 1 для визуализации
            }
            
            foreach($industryScores as $key => $industry) {
                $industryScores[$key]['areaPercent'] = $totalScore > 0 ? (max(1, $industry['score']) / $totalScore) * 100 : 0;
            }
        @endphp
        
        <h3 style="margin-bottom: 10px;">Деревоподібна карта інтересів та здібностей:</h3>
        
        {{-- Treemap visualization --}}
        <div style="width: 100%; height: 100%; border: 2px solid #E5E7EB; border-radius: 10px; overflow: hidden; position: relative; background: #F9FAFB; display: flex; flex-wrap: wrap; align-content: flex-start;">
            @foreach($industryScores as $industry)
            @php
                // Вычисляем размер блока пропорционально score
                $width = sqrt($industry['areaPercent']) * 10;
                $minWidth = 120;
                $calculatedWidth = max($minWidth, $width);
                
                // Определяем насыщенность цвета в зависимости от уровня
                $opacity = $industry['percent'] / 100;
            @endphp
            <div style="
                width: {{ $calculatedWidth }}px;
                flex-grow: {{ max(1, $industry['score']) }};
                min-height: 100px;
                background: {{ $industry['color'] }};
                opacity: {{ max(0.5, $opacity) }};
                border: 2px solid white;
                padding: 12px;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                transition: all 0.3s;
                position: relative;
                overflow: hidden;
            ">
                <div>
                    <div style="font-weight: bold; color: white; font-size: 12px; line-height: 1.2; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                        {{ $industry['name'] }}
                    </div>
                    <div style="background: rgba(255,255,255,0.9); color: {{ $industry['color'] }}; display: inline-block; padding: 2px 6px; border-radius: 8px; font-weight: bold; font-size: 16px; margin-top: 6px;">
                        {{ round($industry['percent']) }}%
                    </div>
                </div>
                <div style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                    <div style="font-size: 13px; font-weight: 600; opacity: 0.95;">{{ $industry['levelText'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div style="background: #F3F4F6; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <h4 style="margin-top: 0; color: #1F2937; font-size: 14px;">Легенда рівнів прояву:</h4>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; font-size: 11px;">
                <div><strong style="font-size: 16px;">++</strong> дуже сильна (≥75%)</div>
                <div><strong style="font-size: 16px;">+</strong> добра (50-74%)</div>
                <div><strong style="font-size: 16px;">0</strong> сіра зона (30-49%)</div>
                <div><strong style="font-size: 16px;">-</strong> низька (15-29%)</div>
                <div><strong style="font-size: 16px;">--</strong> мінімальна (&lt;15%)</div>
            </div>
        </div>
        
        <p style="margin-top: 20px; font-size: 12px; color: #666;">
            <em>Розмір блоку відповідає інтенсивності прояву інтересу. Більший блок = вищий рівень інтересу та здібностей.</em>
        </p>
        
        <p style="margin-top: 20px;">Не всі навички, здобуті в школі, легко перетворюються на професійні компетенції бакалаврату. Тут важливо розуміти різницю між <strong>трансферними вміннями</strong> (наприклад, комунікація, розуміння предмету, тощо) і <strong>спеціалізованими знаннями</strong>, які здебільшого здобуваються лише у ВИШі.</p>
    </section>
    @endif

    <div class="page-break"></div>

    {{-- Типи взаємодії з навколишнім світом --}}
    @if(isset($scores['module3']))
    <section id="interaction-types">
        <h2>🌎 Типи взаємодії з навколишнім світом (професійна спрямованість)</h2>
        
        @php
            $interactionTypes = [
                'nature' => 'Людина-Природа',
                'human' => 'Людина-Людина',
                'sign' => 'Людина-Знакова система',
                'technic' => 'Людина-Техніка',
                'art' => 'Людина-Художній образ'
            ];
            $klimovDescriptions = [
                'nature' => 'Сфери діяльності, пов\'язані з дослідженнями природи, біології, тварин та мікроорганізмів, планети, атмосфери, Землі. Крім цього: високий рівень спостережливості, терпіння та наполегливості.',
                'human' => 'Пов\'язаний з вивченням та діяльністю людини, її життя, лікування, навчання, соціальною сферою, обслуговуванням, захистом та безпекою, покращенням сфер життя. Крім цього: налагодження комунікацій різних груп людей, представників різних культур, вивчення їх особливостей та взаємодії.',
                'sign' => 'Пов\'язаний із сферами діяльності: цифри, знаки, літери, схеми, таблиці. Основне завдання: вивчення знакових систем: мови, цифри, знаки, формули та символи, інженерні і технологічні розробки.',
                'technic' => 'Напрями: винахід та створення, виробництво та переробка. Основне завдання: підтримка існуючого технічного прогресу або винахід нового.',
                'art' => 'Освоєння, опис, художнє зображення дійсності. Почуття прекрасного, відчуття пропорцій та кольору, форми. Створення гармонії, естетики, краси, збереження культурної спадщини людства. Тяжіння до громадського визнання.'
            ];
        @endphp
        
        <ul class="content-list">
            @foreach($interactionTypes as $key => $name)
                @if(isset($scores['module3'][$key]))
                @php
                    $score = $scores['module3'][$key];
                    $level = $score >= 6 ? 'високий' : ($score >= 3 ? 'середній' : 'низький');
                @endphp
                <li><strong>{{ $name }} – показник {{ $level }}.</strong> {{ $klimovDescriptions[$key] }}</li>
                @endif
            @endforeach
        </ul>
        
        <div class="chart-container">
            @foreach($interactionTypes as $key => $name)
                @if(isset($scores['module3'][$key]))
                <div class="chart-bar">
                    <div class="chart-label">
                        <span class="chart-label-text">{{ $name }}</span>
                        <span class="chart-label-value">{{ $scores['module3'][$key] }} балів ({{ round(($scores['module3'][$key] / 8) * 100) }}%)</span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: {{ ($scores['module3'][$key] / 8) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    <div class="page-break"></div>

    {{-- Типологія сприйняття --}}
    @if(isset($scores['module4']))
    <section id="perception-types">
        <h2>🎭 Аналіз особистості за типом сприйняття</h2>
        <p>Тип сприйняття - це особливість виявлення та розкриття інформації, що надходить ззовні, домінантний канал надходження даних. Він може бути:<br>візуальним (зір), аудіальним (слух), кінестетичним (тактильні відчуття + нюх + рух), дискретним/дигітальним (логічний аналіз, оперування цифрами, символами, знаками).</p>
        <p>Цій типології відповідають 4 базові типи того, як людина сприймає інформацію, що надходить ззовні: візуал, аудіал, кінестетик, дискрет/дигітал.</p>
        
        <div class="chart-container">
            @php
                $perceptionTypes = [
                    'kinesthetic' => 'Кінестетик',
                    'discrete' => 'Дискрет',
                    'audial' => 'Аудіал',
                    'visual' => 'Візуал'
                ];
            @endphp
            @foreach($perceptionTypes as $key => $name)
                @if(isset($scores['module4'][$key]))
                <div class="chart-bar">
                    <div class="chart-label">
                        <span class="chart-label-text">{{ $name }}</span>
                        <span class="chart-label-value">{{ $scores['module4'][$key] }} балів</span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: {{ ($scores['module4'][$key] / 20) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <p><strong>Кінестетики</strong> – це люди, які найкраще сприймають інформацію через свої тіла. Вони живуть у світі відчуттів, дотиків, рухів і емоцій. Для них важливо не просто бачити чи чути, а відчувати тілом, рухатись. Основні характеристики кінестетиків: активні та рухливі, практичні, навчання через досвід.</p>
        <p><strong>Дискрети</strong> – це люди, які сприймають світ через логіку, аналіз та факти. Вони мислять абстрактно, оперують поняттями та ідеями. Для дискретів важлива послідовність, чіткість і точність. Основні характеристики дискретів: логічні мислителі, абстрактне мислення, точність і деталізація, схильність до систематизації.</p>
        <p><strong>Аудіали</strong> – це люди, які найкраще сприймають інформацію на слух. Вони живуть у світі звуків, інтонацій і ритмів. Для них важливо не тільки те, що говориться, але й як. Основні характеристики аудіалів: чуйні, розмовники, музичні, легко запам'ятовують інформацію, яку чули.</p>
        <p><strong>Візуали</strong> – це люди, які найкраще сприймають інформацію через зір. Вони живуть у світі образів, кольорів та форм. Для них важливо бачити, щоб зрозуміти. Основні характеристики візуалів: образне мислення, увага до деталей, візуальна пам'ять, художні таланти.</p>
    </section>
    @endif

    <div class="page-break"></div>

    {{-- Типи інтелекту за теорією Говарда Гарднера --}}
    @if(isset($scores['module5']))
    <section id="intelligence-types">
        <h2>🧩 Типи інтелекту за теорією Говарда Гарднера</h2>
        
        <div class="chart-container">
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
            @endphp
            @foreach($intelligenceTypes as $key => $name)
                @if(isset($scores['module5'][$key]))
                <div class="chart-bar">
                    <div class="chart-label">
                        <span class="chart-label-text">{{ $name }}</span>
                        <span class="chart-label-value">{{ $scores['module5'][$key] }} балів</span>
                    </div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: {{ ($scores['module5'][$key] / 25) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <p>Говард Гарднер — психолог і професор Гарвардського університету, автор <strong>теорії множинного інтелекту</strong>. Він поставив під сумнів традиційне уявлення про інтелект як про один показник (IQ) і довів, що у людини може бути кілька різних типів інтелекту, які розвиваються по-різному.</p>
        <p>Кожна людина має свій унікальний профіль сильних сторін, а успішність залежить не від одного показника, а від поєднання цих інтелектів.</p>
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
                    <th>Тип професії</th>
                    <th>Major (Основний напрям)</th>
                    <th>Minor (Додаткове навчання)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result->recommendations['professional_types'] as $recommendation)
                <tr>
                    <td><strong>{{ $recommendation['type'] ?? 'Не вказано' }}</strong><br>
                        <small>{{ $recommendation['description'] ?? '' }}</small>
                    </td>
                    <td>
                        @if(isset($recommendation['majors']) && count($recommendation['majors']) > 0)
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach($recommendation['majors'] as $major)
                            <li>{{ $major }}</li>
                            @endforeach
                        </ul>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        @if(isset($recommendation['minors']) && count($recommendation['minors']) > 0)
                        <ul style="margin: 0; padding-left: 15px;">
                            @foreach($recommendation['minors'] as $minor)
                            <li>{{ $minor }}</li>
                            @endforeach
                        </ul>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Рекомендації формуються на основі результатів тестування...</p>
        @endif
    </section>

    <div class="page-break"></div>
    
    {{-- Психологічна Карта --}}
    <section id="psychological-map">
        <h2>📋 Індивідуальна психологічна карта</h2>
        
        <table class="info-table">
            @if(isset($scores['module8']))
            @php
                $temperaments = [
                    'choleric' => 'Холерик',
                    'sanguine' => 'Сангвінік',
                    'phlegmatic' => 'Флегматик',
                    'melancholic' => 'Меланхолік'
                ];
                $maxTemp = max($scores['module8']);
                $dominantTemp = array_search($maxTemp, $scores['module8']);
            @endphp
            <tr>
                <th>Темперамент</th>
                <td>{{ $temperaments[$dominantTemp] ?? 'Не визначено' }}</td>
            </tr>
            @endif
            
            @if(isset($scores['module6']))
            @php
                $directions = [
                    'self' => 'Спрямованість на себе',
                    'interaction' => 'Спрямованість на взаємодію',
                    'task' => 'Спрямованість на завдання'
                ];
                $maxDirection = max($scores['module6']);
                $dominantDirection = array_search($maxDirection, $scores['module6']);
            @endphp
            <tr>
                <th>Спрямованість особистості</th>
                <td>{{ $directions[$dominantDirection] ?? 'Не визначено' }}</td>
            </tr>
            @endif
            
            @if(isset($scores['module3']))
            @php
                arsort($scores['module3']);
                $topKlimov = array_slice(array_keys($scores['module3']), 0, 3, true);
                $klimovCode = '';
                foreach($topKlimov as $type) {
                    $klimovCode .= strtoupper(substr($type, 0, 1));
                }
            @endphp
            <tr>
                <th>Код професійних переваг (за Климовим)</th>
                <td>{{ $klimovCode }}</td>
            </tr>
            @endif
            
            @if(isset($scores['module7']))
            @php
                $anchors = [
                    'professional_competence' => 'Професійна компетентність',
                    'management' => 'Менеджмент',
                    'autonomy' => 'Автономія',
                    'stability_place' => 'Стабільність місця роботи',
                    'stability_residence' => 'Стабільність місця проживання',
                    'service' => 'Служіння',
                    'challenge' => 'Виклик',
                    'entrepreneurship' => 'Підприємництво'
                ];
                arsort($scores['module7']);
                $topAnchors = array_slice($scores['module7'], 0, 3, true);
                $anchorsList = implode(', ', array_map(function($key) use ($anchors) {
                    return $anchors[$key] ?? $key;
                }, array_keys($topAnchors)));
            @endphp
            <tr>
                <th>Кар'єрні якоря (ТОП-3)</th>
                <td>{{ $anchorsList }}</td>
            </tr>
            @endif
        </table>

        <div class="note-block">
            <strong>Зверніть увагу!</strong> Кожна людина – гнучка особистість і протягом професійного життя здатна розвивати будь-які навички.
        </div>
    </section>

    {{-- Примітки --}}
    <section id="notes">
        <h2>📑 Підготовка до вступу</h2>
        <ul class="content-list">
            <li><strong>Комбінуйте навички:</strong> Монопрофесії вже у минулому. Комбінуйте навички з різних галузей знань. Це цікаво і дає величезну перевагу у майбутньому.</li>
            <li><strong>Базова освіта:</strong> Чим базовіший, фундаментальний напрямок вивчається на бакалавріаті, тим більше її складові стануть у нагоді надалі.</li>
            <li><strong>Цифрові навички:</strong> Не забувайте про цифрові навички, які межують з основною спеціальністю. Сьогодні фахівець повинен працювати в умовах цифровізації.</li>
            <li><strong>Іноземні мови:</strong> Крім англійської вчимо БУДЬ-ЯКУ ІНШУ поширену іноземну мову! Мови – обов'язкова навичка для більшості професій.</li>
        </ul>

        <div class="contact-block">
            <h3 style="margin-top: 0;">Контакти</h3>
            <p><strong>Автор проекту:</strong> Калабухова Мар'яна</p>
            <p><strong>Telegram:</strong> @edu_carrier_design – Гранти, Стипендії, Новини освіти</p>
            <p><strong>Сайт Дизайн Освіти:</strong> <a href="https://education-design.com.ua/">https://education-design.com.ua/</a></p>
        </div>
    </section>

    <div class="footer">
        <p>Звіт згенеровано {{ now()->format('d.m.Y о H:i') }}</p>
        <p>Система профорієнтаційного тестування "Дизайн Освіти"</p>
    </div>

</div>

</body>
</html>
