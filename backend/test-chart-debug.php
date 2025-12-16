<?php
// Тестовий файл для діагностики відображення діаграми

$scores = [
    'module1' => [
        'artistic' => 5,
        'theoretical' => 8,
        'practical' => 6,
        'creative' => 7,
        'convergent' => 4,
        'intuitive' => 3,
        'analytical' => 9
    ]
];

$thinkingTypes = [
    'artistic' => 'Художнє (наочно-образне)',
    'theoretical' => 'Теоретичне',
    'practical' => 'Практичне',
    'creative' => 'Творче (продуктивне)',
    'convergent' => 'Конвергентне',
    'intuitive' => 'Інтуїтивне',
    'analytical' => 'Аналітичне'
];

$totalThinking = array_sum($scores['module1']);

echo "Total thinking: $totalThinking\n";
echo "Module1 data:\n";
print_r($scores['module1']);

$thinkingColors = [
    'artistic' => '#FF6B6B',
    'theoretical' => '#4ECDC4',
    'practical' => '#95E1D3',
    'creative' => '#FFD93D',
    'convergent' => '#6BCF7F',
    'intuitive' => '#A78BFA',
    'analytical' => '#60A5FA'
];

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

echo "\nPercentages array:\n";
print_r($percentages);

echo "\nSVG Circle calculations:\n";
$radius = 80;
$circumference = 2 * pi() * $radius;
echo "Radius: $radius\n";
echo "Circumference: $circumference\n";

$currentOffset = 0;
foreach($percentages as $key => $data) {
    $strokeLength = ($data['percent'] / 100) * $circumference;
    $gap = 1;
    echo "\n$key:\n";
    echo "  Percent: {$data['percent']}%\n";
    echo "  Stroke length: $strokeLength\n";
    echo "  Current offset: $currentOffset\n";
    $currentOffset += $strokeLength;
}
