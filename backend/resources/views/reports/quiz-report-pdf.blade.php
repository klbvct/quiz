<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Звіт профорієнтаційного тестування</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            color: #1f2937;
            line-height: 1.6;
            font-size: 12px;
        }
        
        h1 {
            color: #0c68f5;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #0c68f5;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #0c68f5;
            padding-left: 10px;
        }
        
        h3 {
            color: #4b5563;
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        
        h4 {
            color: #4b5563;
            font-size: 12px;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        
        .user-info {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .user-info p {
            margin: 5px 0;
        }
        
        .module-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }
        
        table th {
            background: #0c68f5;
            color: white;
            padding: 8px;
            text-align: left;
        }
        
        table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }
        
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .highlight {
            background-color: #fef3c7 !important;
            font-weight: bold;
        }
        
        .recommendations {
            background: #d1fae5;
            padding: 15px;
            border-left: 4px solid #10b981;
            margin: 15px 0;
        }
        
        .professions-list {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .professions-list li {
            margin: 5px 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>Звіт профорієнтаційного тестування</h1>
    
    <div class="user-info">
        <p><strong>ПІБ:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Дата проходження:</strong> {{ $session->updated_at->format('d.m.Y H:i') }}</p>
        <p><strong>Кількість відповідей:</strong> {{ $totalAnswers }}</p>
    </div>

    @if($result && $result->summary)
    <div class="recommendations">
        <h3>Загальний висновок</h3>
        <p>{{ $result->summary }}</p>
    </div>
    @endif

    <!-- Модуль 1: ДДО -->
    @if(isset($scores['module1']))
    <div class="module-block">
        <h2>1. ДДО (Диференційно-діагностичний опитувальник Климова)</h2>
        <p><em>Методика для виявлення схильності до одного з п'яти типів професій</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Тип професії</th>
                    <th>Бали</th>
                    <th>Інтерпретація</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $types = [
                        'nature' => 'Людина-Природа',
                        'technic' => 'Людина-Техніка',
                        'human' => 'Людина-Людина',
                        'sign' => 'Людина-Знакова система',
                        'art' => 'Людина-Художній образ'
                    ];
                    $maxScore = max($scores['module1']);
                @endphp
                @foreach($types as $key => $name)
                    <tr class="{{ $scores['module1'][$key] == $maxScore ? 'highlight' : '' }}">
                        <td>{{ $name }}</td>
                        <td>{{ $scores['module1'][$key] }}/8</td>
                        <td>
                            @if($scores['module1'][$key] >= 7)
                                Ярко виражена схильність
                            @elseif($scores['module1'][$key] >= 3)
                                Помірна схильність
                            @else
                                Низька схильність
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 2: Карта інтересів -->
    @if(isset($scores['module2']))
    <div class="module-block page-break">
        <h2>2. Карта інтересів</h2>
        <p><em>Визначення професійних інтересів у 29 сферах діяльності</em></p>
        
        @php
            $spheres = [
                'biology' => 'Біологія',
                'geography' => 'Географія',
                'geology' => 'Геологія',
                'medicine' => 'Медицина',
                'light_industry' => 'Легка промисловість',
                'physics' => 'Фізика',
                'chemistry' => 'Хімія',
                'engineering' => 'Інженерія',
                'electronics' => 'Електроніка',
                'metal' => 'Металообробка',
                'woodworking' => 'Деревообробка',
                'construction' => 'Будівництво',
                'transport' => 'Транспорт',
                'aviation' => 'Авіація',
                'military' => 'Військова справа',
                'history' => 'Історія',
                'literature' => 'Література',
                'journalism' => 'Журналістика',
                'arts' => 'Мистецтво',
                'pedagogy' => 'Педагогіка',
                'law' => 'Право',
                'service' => 'Сфера обслуговування',
                'math' => 'Математика',
                'economics' => 'Економіка',
                'foreign_languages' => 'Іноземні мови',
                'chemistry_applied' => 'Хімія прикладна',
                'agriculture' => 'Сільське господарство',
                'philology' => 'Філологія',
                'sports' => 'Спорт'
            ];
            arsort($scores['module2']);
            $topSpheres = array_slice($scores['module2'], 0, 10, true);
        @endphp
        
        <h3>Топ-10 сфер за рівнем інтересу:</h3>
        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Сфера діяльності</th>
                    <th>Бали</th>
                    <th>Рівень інтересу</th>
                </tr>
            </thead>
            <tbody>
                @php $rank = 1; @endphp
                @foreach($topSpheres as $key => $score)
                    <tr>
                        <td>{{ $rank++ }}</td>
                        <td>{{ $spheres[$key] ?? $key }}</td>
                        <td>{{ $score }}</td>
                        <td>
                            @if($score >= 7)
                                Високий
                            @elseif($score >= 4)
                                Середній
                            @elseif($score >= 1)
                                Помірний
                            @else
                                Низький
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 3: Холланд -->
    @if(isset($scores['module3']))
    <div class="module-block">
        <h2>3. Опитувальник Холланда</h2>
        <p><em>Визначення типу особистості та професійних переваг</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Тип особистості</th>
                    <th>Бали</th>
                    <th>Характеристика</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $hollandTypes = [
                        'realistic' => ['Реалістичний', 'Орієнтація на практичну роботу з матеріалами, інструментами'],
                        'intellectual' => ['Інтелектуальний', 'Схильність до наукової, дослідницької діяльності'],
                        'social' => ['Соціальний', 'Орієнтація на роботу з людьми, навчання, допомогу'],
                        'conventional' => ['Конвенційний', 'Схильність до структурованої роботи, обліку, порядку'],
                        'enterprising' => ['Підприємницький', 'Лідерські якості, управління, бізнес'],
                        'artistic' => ['Артистичний', 'Творчість, самовираження, мистецтво']
                    ];
                    $maxHolland = max($scores['module3']);
                @endphp
                @foreach($hollandTypes as $key => $info)
                    <tr class="{{ $scores['module3'][$key] == $maxHolland ? 'highlight' : '' }}">
                        <td>{{ $info[0] }}</td>
                        <td>{{ $scores['module3'][$key] }}/7</td>
                        <td>{{ $info[1] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 4: Якоря кар'єри -->
    @if(isset($scores['module4']))
    <div class="module-block page-break">
        <h2>4. Якоря кар'єри (Шейн)</h2>
        <p><em>Визначення провідних кар'єрних орієнтацій</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Кар'єрний якір</th>
                    <th>Бали</th>
                </tr>
            </thead>
            <tbody>
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
                    $maxAnchor = max($scores['module4']);
                @endphp
                @foreach($anchors as $key => $name)
                    <tr class="{{ $scores['module4'][$key] == $maxAnchor ? 'highlight' : '' }}">
                        <td>{{ $name }}</td>
                        <td>{{ $scores['module4'][$key] }}/50</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 5: Мотиви -->
    @if(isset($scores['module5']))
    <div class="module-block">
        <h2>5. Мотиви вибору професії</h2>
        <p><em>Виявлення внутрішніх і зовнішніх мотивів професійного вибору</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Тип мотивації</th>
                    <th>Бали</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $motives = [
                        'internal_individual' => 'Внутрішні індивідуальні',
                        'internal_social' => 'Внутрішні соціальні',
                        'external_positive' => 'Зовнішні позитивні',
                        'external_negative' => 'Зовнішні негативні'
                    ];
                @endphp
                @foreach($motives as $key => $name)
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $scores['module5'][$key] }}/25</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 6: Басс -->
    @if(isset($scores['module6']))
    <div class="module-block">
        <h2>6. Орієнтаційна анкета (Басс)</h2>
        <p><em>Визначення спрямованості особистості</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Спрямованість</th>
                    <th>Бали</th>
                    <th>Характеристика</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $directions = [
                        'self' => ['На себе', 'Орієнтація на пряму винагороду, престиж'],
                        'interaction' => ['На взаємодію', 'Орієнтація на спілкування, колектив'],
                        'task' => ['На завдання', 'Орієнтація на справу, результат']
                    ];
                    $maxDirection = max($scores['module6']);
                @endphp
                @foreach($directions as $key => $info)
                    <tr class="{{ $scores['module6'][$key] == $maxDirection ? 'highlight' : '' }}">
                        <td>{{ $info[0] }}</td>
                        <td>{{ $scores['module6'][$key] }}</td>
                        <td>{{ $info[1] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 7: Емоційна спрямованість -->
    @if(isset($scores['module7']))
    <div class="module-block page-break">
        <h2>7. Емоційна спрямованість</h2>
        <p><em>Визначення провідних емоційних установок особистості</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Тип емоційної спрямованості</th>
                    <th>Бали</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $emotions = [
                        'altruistic' => 'Альтруїстична',
                        'communicative' => 'Комунікативна',
                        'gloric' => 'Глорична (слава)',
                        'praxic' => 'Праксична (діяльність)',
                        'pugnistic' => 'Пугнічна (боротьба)',
                        'romantic' => 'Романтична',
                        'gnostic' => 'Гностична (пізнання)',
                        'aesthetic' => 'Естетична',
                        'hedonistic' => 'Гедоністична (задоволення)',
                        'acquisitive' => 'Акизитивна (накопичення)'
                    ];
                    arsort($scores['module7']);
                @endphp
                @foreach($scores['module7'] as $key => $score)
                    <tr>
                        <td>{{ $emotions[$key] ?? $key }}</td>
                        <td>{{ $score }}/5</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Модуль 8: Темперамент -->
    @if(isset($scores['module8']))
    <div class="module-block">
        <h2>8. Темперамент</h2>
        <p><em>Визначення типу темпераменту</em></p>
        
        <table>
            <thead>
                <tr>
                    <th>Тип темпераменту</th>
                    <th>Бали</th>
                    <th>Характеристика</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $temperaments = [
                        'choleric' => ['Холерик', 'Енергійний, імпульсивний, емоційний'],
                        'sanguine' => ['Сангвінік', 'Товариський, веселий, активний'],
                        'phlegmatic' => ['Флегматик', 'Спокійний, стриманий, стабільний'],
                        'melancholic' => ['Меланхолік', 'Чутливий, вдумливий, емпатійний']
                    ];
                    $maxTemp = max($scores['module8']);
                @endphp
                @foreach($temperaments as $key => $info)
                    <tr class="{{ $scores['module8'][$key] == $maxTemp ? 'highlight' : '' }}">
                        <td>{{ $info[0] }}</td>
                        <td>{{ $scores['module8'][$key] }}</td>
                        <td>{{ $info[1] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Рекомендації -->
    @if($result && isset($result->recommendations['professional_types']) && count($result->recommendations['professional_types']) > 0)
    <div class="recommendations page-break">
        <h2>Професійні рекомендації</h2>
        
        @foreach($result->recommendations['professional_types'] as $recommendation)
            <h3>{{ $recommendation['type'] ?? 'Тип професії' }}</h3>
            @if(isset($recommendation['description']))
                <p>{{ $recommendation['description'] }}</p>
            @endif
            
            @if(isset($recommendation['professions']) && count($recommendation['professions']) > 0)
                <h4>Рекомендовані професії:</h4>
                <ul class="professions-list">
                    @foreach($recommendation['professions'] as $profession)
                        <li>{{ $profession }}</li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    </div>
    @endif

    <div class="footer">
        <p>Звіт згенеровано {{ now()->format('d.m.Y о H:i') }}</p>
        <p>Система профорієнтаційного тестування</p>
    </div>
</body>
</html>
