<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Звіт профорієнтаційного тестування - {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/report-styled.css') }}">
    <style>
        .print-button-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }
        
        .print-button {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-blue));
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .print-hint {
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 11px;
            color: #666;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: right;
            max-width: 250px;
        }
        
        @media print {
            .print-button-container {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Кнопка друку (видима тільки на екрані) -->
<div class="print-button-container">
    <div class="print-hint">
        💡 Натисніть кнопку, щоб роздрукувати або зберегти звіт як PDF файл
    </div>
    <button onclick="window.print()" class="print-button">
        <span>🖨️</span>
        <span>Друк / Зберегти PDF</span>
    </button>
</div>

<div class="container">
    <header class="report-header">
        <h1>ДИЗАЙН ОСВІТИ</h1>
        <p>Звіт профорієнтаційного тестування</p>
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
        <p style="font-size: 12px; color: var(--dark-blue); font-weight: bold; text-align: center;">
            Дизайн Освіти – не просто тест, це процес формування індивідуальної освітньої траєкторії для кожного.
        </p>
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
                <div class="toc-item">Типологія професійних інтересів за Голландом <span>06</span></div>
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

    {{-- Домінуючі типи мислення (Модуль 3) --}}
    @if(isset($scores['module3']))
    <section id="thinking-types-module3">
        <h2>🧠 Домінуючі типи мислення</h2>
        <p>У людини задіяна велика кількість типів мислення. У даному випадку ми говоримо про ті, які <strong>переважають</strong>.</p>
        
        @php
            $thinkingTypesM3 = [
                'artistic' => 'Художнє (наочно-образне)',
                'theoretical' => 'Теоретичне',
                'practical' => 'Практичне',
                'creative' => 'Творче (продуктивне)',
                'convergent' => 'Конвергентне',
                'intuitive' => 'Інтуїтивне',
                'analytical' => 'Аналітичне'
            ];
            
            $thinkingColorsM3 = [
                'artistic' => '#F59E0B',
                'theoretical' => '#3B82F6',
                'practical' => '#10B981',
                'creative' => '#EC4899',
                'convergent' => '#8B5CF6',
                'intuitive' => '#14B8A6',
                'analytical' => '#EF4444'
            ];
            
            $thinkingDescriptions = [
                'artistic' => 'Інструмент, який дозволяє нам конструювати в уяві неіснуючу реальність або перетворювати існуючу на щось нове. Створювати образи та оперування ними у процесі вирішення поставлених завдань — основна стратегія цього типу мислення. Розвинуто візуальне сприйняття та уяву.',
                'theoretical' => 'Дозволяє узагальнювати, порівнювати, аналізувати та класифікувати накопичені знання та уявлення, виражаючи у формі норм, правил, законів, концепцій, книг. Абстрактне, узагальнене відображення. Дозволяє знаходити причинно-наслідкові зв\'язки у явищах та предметах, шукати відповідь на запитання «чому?».',
                'practical' => 'Теорії перевіряються практично — схеми, креслення, проекти, плани перетворюють теоретичні поняття на реальну дійсність. Думка набуває фізичної форми. Характерно для людей, орієнтованих на результат, а не на процес.',
                'creative' => 'Оригінальність ідей, нетривіальність, гнучкість. Пошук нових рішень для існуючих завдань чи проблемних ситуацій. Така людина завжди прагне знайти своє власне оригінальне рішення. Здатність продукувати нові ідеї в різних ситуаціях невизначеності на основі раніше відомої інформації.',
                'convergent' => '«Бізнес-мислення» — система поглядів на світ і особливостей розумового процесу, базою до якого є твердження: «ми самі відтворюємо своє життя». Визначається вірою в можливість будувати власну реальність, адаптуватися до певних умов. Ключовими умовами виступають: самостійність, відповідальність, широта поглядів, дальнобачність, готовність до ризику, віра в себе.',
                'intuitive' => 'Це спосіб прийняття рішень, в якому результат виникає швидко й без явного логічного аналізу. Людина отримує відповідь на основі підсвідомого досвіду, емоцій або власних відчуттів, часто не усвідомлюючи, як саме вона до нього дійшла.',
                'analytical' => 'Особливий тип абстрактного мислення, яке допомагає структурувати, конструювати і оперувати абстрактними поняттями. Стимулює здатність розкладувати великі завдання на дрібні складові, логічно переосмислюючи їх.'
            ];
            
            $totalThinkingM3 = array_sum($scores['module3']);
            
            // Вычисляем проценты
            $percentagesM3 = [];
            if($totalThinkingM3 > 0) {
                foreach($thinkingTypesM3 as $key => $name) {
                    if(isset($scores['module3'][$key]) && $scores['module3'][$key] > 0) {
                        $percent = ($scores['module3'][$key] / $totalThinkingM3) * 100;
                        $percentagesM3[$key] = [
                            'name' => $name,
                            'value' => $scores['module3'][$key],
                            'percent' => $percent,
                            'color' => $thinkingColorsM3[$key]
                        ];
                    }
                }
                // Сортируем по проценту
                uasort($percentagesM3, function($a, $b) {
                    return $b['percent'] <=> $a['percent'];
                });
            }
        @endphp
        
        @if($totalThinkingM3 > 0 && count($percentagesM3) > 0)
        {{-- Горизонтальные столбцы с процентами --}}
        <div style="margin: 20px 0;">
            @foreach($percentagesM3 as $key => $data)
            <div style="margin-bottom: 10px; page-break-inside: avoid;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                    <span style="font-size: 13px; font-weight: 600; color: #2D3748;">{{ $data['name'] }}</span>
                    <span style="font-size: 14px; font-weight: bold; color: {{ $data['color'] }};">{{ round($data['percent']) }}%</span>
                </div>
                <div style="width: 100%; height: 24px; background: #E5E7EB; border-radius: 12px; overflow: hidden; position: relative;">
                    <div style="width: {{ $data['percent'] }}%; height: 100%; background: {{ $data['color'] }}; border-radius: 12px;"></div>
                </div>
                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">{{ $data['value'] }} балів</div>
            </div>
            @endforeach
        </div>
        
        {{-- Описания типов --}}
        <ul class="content-list">
            @foreach($percentagesM3 as $key => $data)
            <li><strong>{{ $data['name'] }} мислення ({{ round($data['percent']) }}%)</strong> — {{ $thinkingDescriptions[$key] }}</li>
            @endforeach
        </ul>
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для побудови діаграми. Переконайтеся, що тестування пройдено повністю.</p>
        @endif
    </section>
    @endif

    <div class="page-break"></div>

    {{-- Мотивація та цінності абітурієнта --}}
    @if(isset($scores['module4']) && isset($scores['module6']))
    <section id="motivation-values">
        <h2>🎯 Мотивація та цінності абітурієнта</h2>
        
        @php
        // Модуль 4: Ціннісні категорії (ранжування від 1 до 18)
        $module4 = $scores['module4'] ?? [];
        $veryImportant = [];
        $moderatelyImportant = [];
        $notImportant = [];
        
        if (is_array($module4) && count($module4) > 0) {
            // Сортування цінностей за рангом (1 - найважливіше)
            asort($module4);
            
            // Топ важливі цінності (ранг 1-6)
            $veryImportant = array_filter($module4, function($rank) { 
                return is_numeric($rank) && $rank >= 1 && $rank <= 6; 
            });
            
            // Помірно важливі (ранг 7-12)
            $moderatelyImportant = array_filter($module4, function($rank) { 
                return is_numeric($rank) && $rank >= 7 && $rank <= 12; 
            });
            
            // Не важливі (ранг 13-18)
            $notImportant = array_filter($module4, function($rank) { 
                return is_numeric($rank) && $rank >= 13 && $rank <= 18; 
            });
        }
        
        // Модуль 6: Мотиваційні фактори (шкала від -1 до 7)
        $module6 = $scores['module6'] ?? [];
        $strongMotivators = [];
        $moderateMotivators = [];
        $weakMotivators = [];
        $demotivators = [];
        
        if (is_array($module6) && count($module6) > 0) {
            // Сортування мотивацій за балами (від високих до низьких)
            arsort($module6);
            
            // Сильні мотиватори (бали 6-7)
            $strongMotivators = array_filter($module6, function($score) { 
                return is_numeric($score) && $score >= 6; 
            });
            
            // Помірні мотиватори (бали 4-5)
            $moderateMotivators = array_filter($module6, function($score) { 
                return is_numeric($score) && $score >= 4 && $score < 6; 
            });
            
            // Слабкі мотиватори (бали 1-3)
            $weakMotivators = array_filter($module6, function($score) { 
                return is_numeric($score) && $score >= 1 && $score < 4; 
            });
            
            // Демотиватори (бали -1 до 0)
            $demotivators = array_filter($module6, function($score) { 
                return is_numeric($score) && $score < 1; 
            });
        }
        @endphp
        
        {{-- Ціннісні категорії --}}
        <div style="margin-bottom: 30px;">
            <h3 style="color: #2c5282; margin-bottom: 15px;">📊 Ціннісні категорії</h3>
            <p style="margin-bottom: 20px; color: #4a5568;">
                Результати показують, які аспекти життя є найбільш важливими для абітурієнта. 
                Цінності впорядковані від найважливішої (ранг 1) до найменш важливої (ранг 18).
            </p>
            
            @if(count($veryImportant) === 0 && count($moderatelyImportant) === 0 && count($notImportant) === 0)
            <div style="padding: 20px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <p style="color: #92400e; margin: 0;">
                    ⚠️ Дані для модуля "Ціннісні категорії" відсутні. Переконайтеся, що користувач пройшов усі питання модуля 4.
                </p>
            </div>
            @else
            {{-- Крайне важливі цінності --}}
            @if(count($veryImportant) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #e6fffa; border-left: 4px solid #0d9488; border-radius: 4px;">
                <h4 style="color: #0f766e; margin-bottom: 10px;">✅ Крайне важливі цінності</h4>
                <p style="color: #134e4a; margin-bottom: 10px; font-size: 0.9em;">
                    Ці цінності визначають життєві пріоритети та керують основними рішеннями:
                </p>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($veryImportant as $value => $rank)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="min-width: 40px; text-align: center; background: #14b8a6; color: white; border-radius: 4px; padding: 4px 8px; font-weight: bold; font-size: 0.85em;">
                            {{ $rank }}
                        </div>
                        <div style="flex: 1;">
                            <strong style="color: #0f766e;">{{ ucfirst($value) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Помірно важливі цінності --}}
            @if(count($moderatelyImportant) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #fef9c3; border-left: 4px solid #ca8a04; border-radius: 4px;">
                <h4 style="color: #a16207; margin-bottom: 10px;">⚖️ Помірно важливі цінності</h4>
                <p style="color: #713f12; margin-bottom: 10px; font-size: 0.9em;">
                    Значущі, але не першочергові аспекти:
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach($moderatelyImportant as $value => $rank)
                    <div style="background: white; border: 1px solid #fbbf24; border-radius: 4px; padding: 6px 12px; font-size: 0.9em;">
                        <span style="color: #92400e; font-weight: bold;">{{ $rank }}.</span>
                        <span style="color: #78350f;">{{ ucfirst($value) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Не важливі цінності --}}
            @if(count($notImportant) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #fee2e2; border-left: 4px solid #dc2626; border-radius: 4px;">
                <h4 style="color: #991b1b; margin-bottom: 10px;">❌ Не важливі цінності</h4>
                <p style="color: #7f1d1d; margin-bottom: 10px; font-size: 0.9em;">
                    Ці аспекти не мають суттєвого значення для прийняття рішень:
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach($notImportant as $value => $rank)
                    <div style="background: white; border: 1px solid #f87171; border-radius: 4px; padding: 4px 10px; font-size: 0.85em; color: #991b1b;">
                        {{ ucfirst($value) }} <span style="font-weight: bold;">({{ $rank }})</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>
        
        {{-- Мотиваційні фактори --}}
        <div style="margin-bottom: 30px;">
            <h3 style="color: #2c5282; margin-bottom: 15px;">⚡ Мотиваційні фактори</h3>
            <p style="margin-bottom: 20px; color: #4a5568;">
                Оцінка факторів від -1 (абсолютне протиріччя) до 7 (повна відповідність цінностям). 
                Результати показують, що саме мотивує абітурієнта, а що є демотивуючим.
            </p>
            
            @if(count($strongMotivators) === 0 && count($moderateMotivators) === 0 && count($weakMotivators) === 0 && count($demotivators) === 0)
            <div style="padding: 20px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <p style="color: #92400e; margin: 0;">
                    ⚠️ Дані для модуля "Мотиваційні фактори" відсутні. Переконайтеся, що користувач пройшов усі питання модуля 6.
                </p>
            </div>
            @else
            {{-- Сильні мотиватори --}}
            @if(count($strongMotivators) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #dbeafe; border-left: 4px solid #2563eb; border-radius: 4px;">
                <h4 style="color: #1e40af; margin-bottom: 10px;">🚀 Сильні мотиватори (6-7 балів)</h4>
                <p style="color: #1e3a8a; margin-bottom: 10px; font-size: 0.9em;">
                    Абсолютно відповідає ціннісним категоріям, максимально мотивує:
                </p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($strongMotivators as $factor => $score)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="min-width: 60px;">
                            <div style="background: #2563eb; color: white; border-radius: 4px; padding: 6px 10px; text-align: center; font-weight: bold;">
                                {{ $score }} / 7
                            </div>
                        </div>
                        <div style="flex: 1; position: relative;">
                            <div style="background: #e0e7ff; border-radius: 4px; height: 24px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%); height: 100%; width: {{ ($score / 7) * 100 }}%; transition: width 0.3s;"></div>
                            </div>
                            <div style="position: absolute; top: 0; left: 10px; line-height: 24px; font-weight: bold; color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                {{ ucfirst($factor) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Помірні мотиватори --}}
            @if(count($moderateMotivators) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 4px;">
                <h4 style="color: #15803d; margin-bottom: 10px;">✔️ Помірні мотиватори (4-5 балів)</h4>
                <p style="color: #166534; margin-bottom: 10px; font-size: 0.9em;">
                    Має певне мотиваційне значення:
                </p>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($moderateMotivators as $factor => $score)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="min-width: 60px;">
                            <div style="background: #16a34a; color: white; border-radius: 4px; padding: 4px 8px; text-align: center; font-weight: bold; font-size: 0.9em;">
                                {{ $score }} / 7
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <strong style="color: #15803d;">{{ ucfirst($factor) }}</strong>
                        </div>
                        <div style="width: 100px; background: #dcfce7; border-radius: 4px; height: 8px; overflow: hidden;">
                            <div style="background: #16a34a; height: 100%; width: {{ ($score / 7) * 100 }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Слабкі мотиватори --}}
            @if(count($weakMotivators) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-left: 4px solid #9ca3af; border-radius: 4px;">
                <h4 style="color: #6b7280; margin-bottom: 10px;">➖ Слабкі мотиватори (1-3 бали)</h4>
                <p style="color: #374151; margin-bottom: 10px; font-size: 0.9em;">
                    Майже не впливає на мотивацію:
                </p>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach($weakMotivators as $factor => $score)
                    <div style="background: white; border: 1px solid #d1d5db; border-radius: 4px; padding: 4px 10px; font-size: 0.85em; color: #6b7280;">
                        {{ ucfirst($factor) }} <span style="font-weight: bold;">({{ $score }})</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- Демотиватори --}}
            @if(count($demotivators) > 0)
            <div style="margin-bottom: 20px; padding: 15px; background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 4px;">
                <h4 style="color: #b91c1c; margin-bottom: 10px;">⛔ Демотиватори (-1 до 0 балів)</h4>
                <p style="color: #991b1b; margin-bottom: 10px; font-size: 0.9em;">
                    Протирічить ціннісним категоріям, може викликати відторгнення:
                </p>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    @foreach($demotivators as $factor => $score)
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="min-width: 60px;">
                            <div style="background: #ef4444; color: white; border-radius: 4px; padding: 4px 8px; text-align: center; font-weight: bold; font-size: 0.9em;">
                                {{ $score }}
                            </div>
                        </div>
                        <div style="flex: 1;">
                            <strong style="color: #b91c1c;">{{ ucfirst($factor) }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>
        
        {{-- Підсумок та рекомендації --}}
        @if((count($veryImportant) > 0 || count($moderatelyImportant) > 0 || count($notImportant) > 0) && 
            (count($strongMotivators) > 0 || count($moderateMotivators) > 0 || count($weakMotivators) > 0 || count($demotivators) > 0))
        <div style="padding: 20px; background: #f0f9ff; border: 2px solid #0284c7; border-radius: 8px;">
            <h4 style="color: #0369a1; margin-bottom: 15px;">💡 Інтерпретація та рекомендації</h4>
            <div style="color: #075985; line-height: 1.6;">
                <p style="margin-bottom: 10px;">
                    <strong>Ціннісний профіль:</strong> 
                    @if(count($veryImportant) > 0)
                        Для абітурієнта найбільш важливими є: 
                        <strong style="color: #0369a1;">{{ implode(', ', array_map('ucfirst', array_keys(array_slice($veryImportant, 0, 3, true)))) }}</strong>.
                    @else
                        Недостатньо даних для аналізу.
                    @endif
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>Мотиваційний профіль:</strong>
                    @if(count($strongMotivators) > 0)
                        Максимально мотивують: 
                        <strong style="color: #0369a1;">{{ implode(', ', array_map('ucfirst', array_keys($strongMotivators))) }}</strong>.
                    @endif
                    @if(count($demotivators) > 0)
                        Викликають відторгнення: 
                        <strong style="color: #dc2626;">{{ implode(', ', array_map('ucfirst', array_keys($demotivators))) }}</strong>.
                    @endif
                    @if(count($strongMotivators) === 0 && count($demotivators) === 0)
                        Недостатньо даних для аналізу.
                    @endif
                </p>
                @if(count($veryImportant) > 0 || count($strongMotivators) > 0)
                <p style="margin-top: 15px; padding: 10px; background: white; border-left: 3px solid #0284c7; border-radius: 4px;">
                    <strong>Рекомендація:</strong> При виборі освітньої програми та майбутньої кар'єри варто орієнтуватися на цінності топ-6 та враховувати сильні мотиватори. Уникати напрямків, які суперечать ключовим цінностям або містять демотиваційні фактори.
                </p>
                @endif
            </div>
        </div>
        @endif
    </section>
    @endif

    <div class="page-break"></div>

    {{-- Співвідношення типів інтелекту (Модуль 5) --}}
    @if(isset($scores['module5']))
    <section id="intelligence-types">
        <h2>🧠 Співвідношення типів інтелекту</h2>
        <p>Визначення типу інтелекту згідно теорії множинного інтелекту Говарда Гарднера.</p>
        
        @php
            // Типы интеллекта по Говарду Гарднеру
            $intelligenceTypes = [
                'linguistic' => 'Лінгвістичний',
                'logical_mathematical' => 'Логіко-математичний',
                'spatial' => 'Просторово-візуальний',
                'musical' => 'Музичний',
                'bodily_kinesthetic' => 'Кінестетичний (руховий)',
                'interpersonal' => 'Міжособистісний',
                'intrapersonal' => 'Внутрішньоособистісний',
                'naturalistic' => 'Натуралістичний'
            ];
            
            $intelligenceColors = [
                'linguistic' => '#3B82F6',
                'logical_mathematical' => '#8B5CF6',
                'spatial' => '#EC4899',
                'musical' => '#F59E0B',
                'bodily_kinesthetic' => '#10B981',
                'interpersonal' => '#EF4444',
                'intrapersonal' => '#14B8A6',
                'naturalistic' => '#84CC16'
            ];
            
            $intelligenceDescriptions = [
                'linguistic' => 'Здатність ефективно використовувати мову, виражати думки усно та письмово. Характерно для письменників, поетів, журналістів, ораторів.',
                'logical_mathematical' => 'Здатність до логічного мислення, аналізу, роботи з числами та абстрактними поняттями. Характерно для математиків, програмістів, учених.',
                'spatial' => 'Здатність сприймати візуальний світ, створювати мисленні образи, орієнтуватися у просторі. Характерно для художників, архітекторів, дизайнерів.',
                'musical' => 'Здатність сприймати, створювати та відтворювати музику, розуміти ритм, тон, тембр. Характерно для музикантів, композиторів, звукорежисерів.',
                'bodily_kinesthetic' => 'Здатність контролювати рухи тіла, координація, спритність. Характерно для спортсменів, танцюристів, хірургів, ремісників.',
                'interpersonal' => 'Здатність розуміти інших людей, їхні наміри, мотивацію, емоції. Характерно для педагогів, психологів, менеджерів, лідерів.',
                'intrapersonal' => 'Здатність розуміти себе, свої емоції, мотиви, цілі. Саморефлексія та самоаналіз. Характерно для філософів, психологів, письменників.',
                'naturalistic' => 'Здатність розпізнавати та класифікувати об\'єкти природи, розуміти природні явища. Характерно для біологів, екологів, ветеринарів.'
            ];
            
            // Сортируем по баллам для отображения
            $intelligenceScores = $scores['module5'];
            arsort($intelligenceScores);
            
            $maxScore = max($intelligenceScores);
            $minScore = min($intelligenceScores);
            
            // Вычисляем соотношение прогресс/не наблюдается для каждого типа
            // Шкала от 1 до 9, где середина = 5
            $intelligenceRatios = [];
            foreach($intelligenceScores as $type => $score) {
                // Нормализуем балл к шкале 1-9
                if ($maxScore > $minScore) {
                    $normalized = 1 + (($score - $minScore) / ($maxScore - $minScore)) * 8;
                } else {
                    $normalized = 5; // если все одинаковые
                }
                
                // Рассчитываем пропорцию прогресс : не наблюдается
                $progress = round($normalized);
                $notObserved = 10 - $progress;
                
                $intelligenceRatios[$type] = [
                    'score' => $score,
                    'normalized' => $normalized,
                    'progress' => $progress,
                    'notObserved' => $notObserved,
                    'percent' => ($progress / 10) * 100
                ];
            }
        @endphp
        
        @if(count($intelligenceScores) > 0)
        
        {{-- Круговая диаграмма основных типов интеллекта --}}
        <h3 style="margin-bottom: 15px;">Профіль інтелекту:</h3>
        <div style="display: flex; align-items: center; justify-content: space-between; margin: 30px 0; gap: 40px;">
            {{-- Радарная диаграмма (упрощенная через горизонтальные столбцы) --}}
            <div style="flex: 1;">
                @foreach($intelligenceScores as $type => $score)
                @php
                    $ratio = $intelligenceRatios[$type];
                    $name = $intelligenceTypes[$type] ?? $type;
                    $color = $intelligenceColors[$type] ?? '#6B7280';
                @endphp
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <span style="font-size: 13px; font-weight: 600; color: #2D3748;">{{ $name }}</span>
                        <span style="font-size: 12px; font-weight: bold; color: {{ $color }};">
                            Прогрес: {{ $ratio['progress'] }}/10
                        </span>
                    </div>
                    
                    {{-- Двухцветная шкала: зеленый (прогресс) + серый (не наблюдается) --}}
                    <div style="display: flex; width: 100%; height: 28px; border-radius: 14px; overflow: hidden; border: 2px solid #E5E7EB;">
                        {{-- Прогресс --}}
                        <div style="
                            width: {{ $ratio['percent'] }}%; 
                            background: linear-gradient(90deg, {{ $color }} 0%, {{ $color }}dd 100%);
                        ">
                        </div>
                        {{-- Не наблюдается --}}
                        <div style="
                            width: {{ 100 - $ratio['percent'] }}%; 
                            background: #E5E7EB;
                        ">
                        </div>
                    </div>
                    
                    <div style="font-size: 10px; color: #6B7280; margin-top: 3px;">
                        Співвідношення: {{ $ratio['progress'] }} до {{ $ratio['notObserved'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Описания типов интеллекта --}}
        <h3 style="margin-top: 30px; margin-bottom: 15px;">Детальна характеристика типів інтелекту:</h3>
        <ul class="content-list">
            @foreach($intelligenceScores as $type => $score)
            @php
                $ratio = $intelligenceRatios[$type];
                $name = $intelligenceTypes[$type] ?? $type;
                
                // Определяем уровень проявленности
                if ($ratio['progress'] >= 8) {
                    $level = 'дуже високий';
                    $levelEmoji = '🔥';
                } elseif ($ratio['progress'] >= 6) {
                    $level = 'високий';
                    $levelEmoji = '✅';
                } elseif ($ratio['progress'] >= 4) {
                    $level = 'середній';
                    $levelEmoji = '➡️';
                } else {
                    $level = 'низький';
                    $levelEmoji = '⬇️';
                }
            @endphp
            <li>
                <strong>{{ $levelEmoji }} {{ $name }} інтелект</strong> — 
                рівень прояву <u>{{ $level }}</u> ({{ $ratio['progress'] }}/10). 
                {{ $intelligenceDescriptions[$type] ?? '' }}
            </li>
            @endforeach
        </ul>
        
        {{-- Рекомендации --}}
        @php
            // Топ-3 типа интеллекта
            $topIntelligence = array_slice($intelligenceScores, 0, 3, true);
        @endphp
        
        <div style="margin-top: 30px; padding: 20px; background: #EFF6FF; border-left: 4px solid #3B82F6; border-radius: 8px;">
            <h4 style="color: #1E40AF; margin-top: 0;">💡 Рекомендації для розвитку:</h4>
            <p style="color: #1E3A8A; margin-bottom: 10px;">
                <strong>Домінуючі типи інтелекту:</strong>
                @foreach($topIntelligence as $type => $score)
                    {{ $intelligenceTypes[$type] ?? $type }}@if(!$loop->last), @endif
                @endforeach
            </p>
            <p style="color: #1E3A8A; margin-bottom: 0;">
                При виборі професії та освітнього напрямку рекомендується орієнтуватися на домінуючі типи інтелекту. 
                Водночас варто розвивати всі типи, оскільки сучасні професії вимагають комплексного підходу та міждисциплінарних навичок.
            </p>
        </div>
        
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для аналізу типів інтелекту. Переконайтеся, що тестування пройдено повністю.</p>
        @endif
    </section>
    @endif
    
    <div class="page-break"></div>

    {{-- Система Голланда RIASEC (Модуль 7) --}}
    @if(isset($scores['module7']))
    <section id="holland-riasec">
        <h2>🎯 Типологія професійних інтересів за Голландом</h2>
        <p>Визначення домінуючого типу особистості за системою професійних інтересів Джона Голланда.</p>
        
        @php
            // Типы по системе Голланда RIASEC
            $hollandTypes = [
                'realistic' => ['R', 'Практик (Realistic)', '#10B981'],
                'investigative' => ['I', 'Мислитель. Дослідник (Investigative)', '#3B82F6'],
                'artistic' => ['A', 'Творець (Artistic)', '#EC4899'],
                'social' => ['S', 'Помічник (Social)', '#F59E0B'],
                'enterprising' => ['E', 'Лідер (Enterprising)', '#EF4444'],
                'conventional' => ['C', 'Організатор (Conventional)', '#8B5CF6']
            ];
            
            $hollandDescriptions = [
                'realistic' => 'Люди цього типу віддають перевагу роботі з конкретними об\'єктами та їх практичному використанню. Характерні професії: інженер, механік, електрик, будівельник, фермер, водій.',
                'investigative' => 'Схильність до дослідницької діяльності, аналітичного мислення, вирішення інтелектуальних завдань. Характерні професії: науковець, дослідник, аналітик, лікар, програміст.',
                'artistic' => 'Творчі особистості, які прагнуть самовираження через мистецтво та креативність. Характерні професії: художник, дизайнер, музикант, письменник, актор, архітектор.',
                'social' => 'Орієнтовані на роботу з людьми, допомогу іншим, навчання та підтримку. Характерні професії: вчитель, психолог, соціальний працівник, медсестра, консультант.',
                'enterprising' => 'Лідерські якості, прагнення до організації та управління, підприємливість. Характерні професії: менеджер, підприємець, юрист, політик, маркетолог.',
                'conventional' => 'Схильність до структурованої роботи, порядку, обробки даних та документації. Характерні професії: бухгалтер, секретар, адміністратор, банкір, аналітик даних.'
            ];
            
            // Сортируем по баллам
            $hollandScores = $scores['module7'];
            arsort($hollandScores);
            
            // Формируем доминирующий код из 3 букв
            $topThree = array_slice($hollandScores, 0, 3, true);
            $hollandCode = '';
            foreach($topThree as $type => $score) {
                if(isset($hollandTypes[$type])) {
                    $hollandCode .= $hollandTypes[$type][0];
                }
            }
            
            // Вычисляем максимальный балл для нормализации
            $maxScore = max($hollandScores);
            $minScore = min($hollandScores);
        @endphp
        
        @if(count($hollandScores) > 0)
        
        {{-- Доминирующий код --}}
        <div style="padding: 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; margin: 25px 0; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="font-size: 14px; color: rgba(255,255,255,0.9); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">
                Ваш професійний код
            </div>
            <div style="font-size: 48px; font-weight: bold; color: white; letter-spacing: 8px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                {{ $hollandCode }}
            </div>
            <div style="font-size: 13px; color: rgba(255,255,255,0.85); margin-top: 12px;">
                @php
                    $names = [];
                    foreach($topThree as $type => $score) {
                        if(isset($hollandTypes[$type])) {
                            $names[] = explode(' ', $hollandTypes[$type][1])[0];
                        }
                    }
                @endphp
                {{ implode(' → ', $names) }}
            </div>
        </div>
        
        {{-- Визуализация типов --}}
        <h3 style="margin-top: 30px; margin-bottom: 15px;">Розподіл типів професійних інтересів:</h3>
        <div style="margin: 20px 0;">
            @foreach($hollandScores as $type => $score)
            @php
                $typeData = $hollandTypes[$type] ?? ['?', 'Невідомий тип', '#6B7280'];
                $letter = $typeData[0];
                $name = $typeData[1];
                $color = $typeData[2];
                
                // Нормализуем балл к процентам
                if ($maxScore > $minScore) {
                    $percent = (($score - $minScore) / ($maxScore - $minScore)) * 100;
                } else {
                    $percent = 100;
                }
                
                // Определяем уровень проявленности
                if ($percent >= 80) {
                    $level = 'дуже високий';
                } elseif ($percent >= 60) {
                    $level = 'високий';
                } elseif ($percent >= 40) {
                    $level = 'середній';
                } elseif ($percent >= 20) {
                    $level = 'низький';
                } else {
                    $level = 'дуже низький';
                }
                
                // Проверяем, входит ли в топ-3
                $isTop3 = array_key_exists($type, $topThree);
            @endphp
            <div style="margin-bottom: 18px; @if($isTop3) padding: 12px; background: #F0F9FF; border-radius: 8px; border-left: 4px solid {{ $color }}; @endif">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: {{ $color }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            {{ $letter }}
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 600; color: #2D3748;">{{ $name }}</div>
                            <div style="font-size: 11px; color: #6B7280;">{{ $score }} балів • {{ $level }} рівень</div>
                        </div>
                    </div>
                    <div style="font-size: 16px; font-weight: bold; color: {{ $color }};">
                        {{ round($percent) }}%
                    </div>
                </div>
                
                {{-- Прогресс бар --}}
                <div style="width: 100%; height: 12px; background: #E5E7EB; border-radius: 6px; overflow: hidden;">
                    <div style="width: {{ $percent }}%; height: 100%; background: linear-gradient(90deg, {{ $color }} 0%, {{ $color }}dd 100%); transition: width 0.3s;"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Описания типов --}}
        <h3 style="margin-top: 35px; margin-bottom: 15px;">Детальна характеристика домінуючих типів:</h3>
        <ul class="content-list">
            @foreach($topThree as $type => $score)
            @php
                $typeData = $hollandTypes[$type] ?? ['?', 'Невідомий тип', '#6B7280'];
                $letter = $typeData[0];
                $name = $typeData[1];
            @endphp
            <li>
                <strong>{{ $letter }} – {{ $name }}</strong><br>
                {{ $hollandDescriptions[$type] ?? '' }}
            </li>
            @endforeach
        </ul>
        
        {{-- Рекомендации --}}
        <div style="margin-top: 30px; padding: 20px; background: #FEF3C7; border-left: 4px solid #F59E0B; border-radius: 8px;">
            <h4 style="color: #92400E; margin-top: 0;">💡 Інтерпретація коду {{ $hollandCode }}:</h4>
            <p style="color: #78350F; margin-bottom: 10px;">
                Перша буква <strong style="color: #92400E;">({{ substr($hollandCode, 0, 1) }})</strong> вказує на найбільш виражений тип особистості — це ваша основна професійна орієнтація.
            </p>
            <p style="color: #78350F; margin-bottom: 10px;">
                Друга буква <strong style="color: #92400E;">({{ substr($hollandCode, 1, 1) }})</strong> показує додатковий тип, який доповнює основний.
            </p>
            <p style="color: #78350F; margin-bottom: 0;">
                Третя буква <strong style="color: #92400E;">({{ substr($hollandCode, 2, 1) }})</strong> вказує на менш виражений, але все ж значущий аспект вашої професійної особистості.
            </p>
        </div>
        
        <div style="margin-top: 20px; padding: 20px; background: #ECFDF5; border-left: 4px solid #10B981; border-radius: 8px;">
            <h4 style="color: #065F46; margin-top: 0;">🎓 Рекомендації щодо вибору професії:</h4>
            <p style="color: #047857; margin-bottom: 0;">
                При виборі професії шукайте ті сфери діяльності, які поєднують характеристики всіх трьох домінуючих типів вашого коду. 
                Це забезпечить найбільшу задоволеність від роботи та професійний успіх. 
                Звертайте увагу на професії, які дозволяють розвивати ваші природні схильності та інтереси.
            </p>
        </div>
        
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для визначення типу за системою Голланда. Переконайтеся, що тестування пройдено повністю.</p>
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
                'digital' => 'Дискретний/Дигітальний'
            ];
            
            $perceptionColors = [
                'visual' => '#3B82F6',
                'auditory' => '#10B981',
                'kinesthetic' => '#F59E0B',
                'digital' => '#8B5CF6'
            ];
            
            $perceptionDescriptions = [
                'visual' => 'Зорове сприйняття. Краще сприймає інформацію через образи, діаграми, відео. Мислить картинками.',
                'auditory' => 'Слухове сприйняття. Краще сприймає інформацію на слух, через лекції, аудіокниги, обговорення.',
                'kinesthetic' => 'Тактильне сприйняття + нюх + рух. Потребує практичного досвіду, фізичної взаємодії з матеріалом.',
                'digital' => 'Логічний аналіз, робота з цифрами, символами, знаками. Сприймає через логічні схеми та структури.'
            ];
            
            // Нормалізуємо до 100%
            $totalScore = array_sum($scores['module8']);
            $perceptionPercentages = [];
            
            if($totalScore > 0) {
                foreach($perceptionTypes as $key => $name) {
                    if(isset($scores['module8'][$key])) {
                        $percent = ($scores['module8'][$key] / $totalScore) * 100;
                        $perceptionPercentages[$key] = [
                            'name' => $name,
                            'score' => $scores['module8'][$key],
                            'percent' => $percent,
                            'color' => $perceptionColors[$key]
                        ];
                    }
                }
                
                // Сортуємо за процентом
                uasort($perceptionPercentages, function($a, $b) {
                    return $b['percent'] <=> $a['percent'];
                });
            }
            
            // Визначаємо домінуючий тип
            $dominantType = array_key_first($perceptionPercentages);
        @endphp
        
        @if(count($perceptionPercentages) > 0)
        
        {{-- Кругова діаграма з легендою --}}
        <div style="display: flex; align-items: center; justify-content: center; margin: 30px auto; gap: 40px; max-width: 700px;">
            {{-- Діаграма --}}
            <div style="flex-shrink: 0;">
                <svg viewBox="0 0 200 200" style="width: 300px; height: 300px; transform: rotate(-90deg);">
                    @php
                        $radius = 80;
                        $circumference = 2 * pi() * $radius;
                        $currentOffset = 0;
                    @endphp
                    
                    @foreach($perceptionPercentages as $key => $data)
                        @php
                            $strokeLength = ($data['percent'] / 100) * $circumference;
                            $gap = 1;
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
                    
                    <circle cx="100" cy="100" r="50" fill="white"/>
                    
                    {{-- Проценти --}}
                    @php
                        $currentAngle = 0;
                    @endphp
                    @foreach($perceptionPercentages as $key => $data)
                        @php
                            $segmentAngle = ($data['percent'] / 100) * 360;
                            $midAngle = $currentAngle + ($segmentAngle / 2);
                            $midAngleRad = deg2rad($midAngle);
                            $textRadius = $radius;
                            $textX = 100 + $textRadius * cos($midAngleRad);
                            $textY = 100 + $textRadius * sin($midAngleRad);
                            $currentAngle += $segmentAngle;
                            $showPercent = $data['percent'] >= 5;
                        @endphp
                        @if($showPercent)
                        <text 
                            x="{{ $textX }}" 
                            y="{{ $textY }}" 
                            text-anchor="middle" 
                            dominant-baseline="middle"
                            style="font-size: 12px; font-weight: bold; fill: white; transform: rotate(90deg); transform-origin: {{ $textX }}px {{ $textY }}px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                            {{ round($data['percent']) }}%
                        </text>
                        @endif
                    @endforeach
                    
                    <text x="100" y="100" text-anchor="middle" style="font-size: 14px; font-weight: bold; fill: #2D3748; transform: rotate(90deg); transform-origin: 100px 100px;">
                        Сприйняття
                    </text>
                </svg>
            </div>
            
            {{-- Легенда --}}
            <div style="flex: 1; margin-left: 20px;">
                @foreach($perceptionPercentages as $key => $data)
                <div style="display: flex; align-items: center; margin-bottom: 12px;">
                    <div style="width: 20px; height: 20px; background: {{ $data['color'] }}; border-radius: 4px; margin-right: 10px; flex-shrink: 0;"></div>
                    <div style="flex: 1;">
                        <div style="font-size: 14px; font-weight: 600; color: #2D3748;">{{ $data['name'] }}</div>
                        <div style="font-size: 12px; color: #6B7280;">{{ round($data['percent']) }}% ({{ $data['score'] }} балів)</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        {{-- Описи --}}
        <h3 style="margin-top: 30px; margin-bottom: 15px;">Характеристика типів сприйняття:</h3>
        <ul class="content-list">
            @foreach($perceptionPercentages as $key => $data)
            <li>
                <strong>{{ $data['name'] }} ({{ round($data['percent']) }}%)</strong> — 
                {{ $perceptionDescriptions[$key] }}
            </li>
            @endforeach
        </ul>
        
        {{-- Рекомендації --}}
        @if($dominantType)
        <div style="margin-top: 30px; padding: 20px; background: #EFF6FF; border-left: 4px solid {{ $perceptionColors[$dominantType] }}; border-radius: 8px;">
            <h4 style="color: #1E40AF; margin-top: 0;">💡 Рекомендації для навчання:</h4>
            <p style="color: #1E3A8A; margin-bottom: 0;">
                <strong>Домінуючий тип сприйняття:</strong> {{ $perceptionTypes[$dominantType] }}<br>
                Рекомендується використовувати навчальні матеріали, які відповідають вашому типу сприйняття. 
                Однак розвивайте всі канали, оскільки комплексне сприйняття інформації підвищує ефективність навчання.
            </p>
        </div>
        @endif
        
        @else
        <p style="color: #666; font-style: italic;">Недостатньо даних для визначення типу сприйняття. Переконайтеся, що тестування пройдено повністю.</p>
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

        @php
            // Збираємо дані для психологічної карти
            
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
                arsort($scores['module3']);
                $dominantThinking = array_key_first($scores['module3']);
                $thinkingLabel = $thinkingTypes[$dominantThinking] ?? 'Не визначено';
            }
            
            // 2. Домінантні риси характеру
            $characterTraits = [];
            
            // На основі типу мислення
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
            
            // Додаємо риси на основі Holland типу
            if(isset($scores['module7'])) {
                arsort($scores['module7']);
                $topHolland = array_key_first($scores['module7']);
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
            
            // 3. Самоконтроль (на основі модуля 6 - мотивації)
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
            
            // На основі топ-3 типів мислення
            if(isset($scores['module3'])) {
                $topThinking = array_slice($scores['module3'], 0, 3, true);
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
            
            // На основі Holland
            if(isset($scores['module7'])) {
                arsort($scores['module7']);
                $topHollandTypes = array_slice($scores['module7'], 0, 2, true);
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
                asort($scores['module4']); // Сортуємо за зростанням рангу (1 = найважливіше)
                $topValues = array_slice($scores['module4'], 0, 3, true);
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
                arsort($scores['module7']);
                $topThreeRiasec = array_slice($scores['module7'], 0, 3, true);
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

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
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
        </table>

        @if($riasecCode)
        <h3 style="margin-top: 30px;">Типи професійних схильностей за Голландом (RIASEC):</h3>
        
        <div style="margin: 20px 0; padding: 15px 20px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); border-radius: 12px; color: white; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);">
            <div style="flex: 1;">
                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 5px;">Ваш індивідуальний код</div>
                <div style="font-size: 32px; font-weight: bold; letter-spacing: 6px;">{{ $riasecCode }}</div>
            </div>
            <div style="flex: 1; font-size: 12px; opacity: 0.85; line-height: 1.5; padding-left: 20px; border-left: 1px solid rgba(255,255,255,0.3);">
                <strong>RIASEC</strong> — шість літер, які описують твій тип особистості та професійні інтереси за теорією Джона Голланда.
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
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
                <tr>
                    <td style="padding: 10px; border: 1px solid #E5E7EB;"><strong>{{ $typeName }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #E5E7EB;">{{ $descriptions[$type] ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="note-block" style="margin-top: 30px;">
            <strong>Зверніть увагу!</strong> Кожен з вищевказаних показників є актуальним на момент тестування і найближчий період після. Кожна людина – гнучка особистість і протягом професійного життя здатна розвивати будь-які навички, інтереси і схильності, в залежності від обставин, які склалися і певної долі мотивації. В кожного з нас особистості є динамічними і схильними до трансформацій.  
        </div>
    </section>

    {{-- Примітки --}}
    <section id="notes">
        <h2>📑 Підготовка до вступу</h2>
        <ol style="margin-left: 20px;">
            <li style="margin-top: 1rem;">Вивчити представлені напрямки навчання та їх значення. Зрозуміти, що вивчатиметься в рамках цих напрямків, ознайомитись з планом навчання.</li>
            <li style="margin-top: 1rem;">Вибрати галузь знань та дізнатися в ВНЗ, які цікавлять, можливість підготовчих програм. Я завжди рекомендую абітурієнтам проходити підготовчу програму перед університетом – це дає додаткові привілеї під час вступу.</li>
            <li style="margin-top: 1rem;">Розглянути можливість додаткових професійних занять з профільним предметам. Спеціалізовані факультети звертають особливу увагу на кількість балів за профілем.</li>
            <li style="margin-top: 1rem;">Цілеспрямована підготовка до вступу починається з 9 класу, в ідеалі.</li>
            <li style="margin-top: 1rem;">Дані тесту дають можливість зрозуміти, у яких напрямках є схильності на даний момент. Як зупинитися на чомусь одному? До ваших нахилів та інтересів ви додаєте вашу мотивацію. Так буде простіше зрозуміти на чому зупинити свій вибір зараз.</li>
            <li style="margin-top: 1rem;">Оцінюємо знання англійської та можливість здачі міжнародних іспитів для отримання сертифіката під час вирішення навчатися за кордоном. Крім англійської вчимо БУДЬ-ЯКУ ІНШУ поширену іноземну мову! Мови в сучасному світі – вже не додаткова, а для більшості професій – обов'язкова навичка.</li>
            <li style="margin-top: 1rem;">Запам'ятайте! Бакалаврат – базова освіта, на якій буде засновано ваше подальше професійне життя. Ці 3-4 роки можна провчитися з величезною користю для подальшої кар'єри, а можна просто витратити і починати все спочатку.</li>
            <li style="margin-top: 1rem;">Чим базовіший, фундаментальний напрямок вивчається на бакалавріаті, тим більше її складові стануть у нагоді надалі.</li>
            <li style="margin-top: 1rem;">Монопрофесії вже у минулому. Сучасні люди здатні здобути не одну професійну навичку в різних сферах. Комбінуйте навички з різних галузей знань. Це цікаво і дає величезну перевагу у майбутньому.</li>
            <li style="margin-top: 1rem;">Не забувайте про цифрові навички, які межують з основною спеціальністю. Сьогодні фахівець в будь-якій галузі повинен працювати в умовах цифровізації і технологізації.</li>
        </ol>

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
