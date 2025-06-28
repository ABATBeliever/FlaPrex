<?php
$sourcePath = __DIR__ . '/../../../article/search-list.json';
if (!file_exists($sourcePath)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'search-list.json not found']);
    exit;
}

if (isset($_GET['help'])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'name' => basename(__FILE__),
        'description' => 'dir-date_apiは記事を指定した日付範囲でdayまたはupdateを基準に検索し、結果をjsonで返すapiです。',
        'params' => [
            'from' => '起点日付（YYYY/MM/DD）以降の記事を対象',
            'to' => '終点日付（YYYY/MM/DD）以前の記事を対象',
            'target' => '"day" または "update"（省略時は update）',
            'cnt' => '最大件数（省略時は制限なし）'
        ],
        'example' => $_SERVER['PHP_SELF'] . '?from=2024/01/01&to=2025/12/31&target=day&cnt=10'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

$data = json_decode(file_get_contents($sourcePath), true);
if (!is_array($data)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid JSON format']);
    exit;
}

$from = isset($_GET['from']) ? strtotime($_GET['from']) : null;
$to = isset($_GET['to']) ? strtotime($_GET['to']) : null;
$target = ($_GET['target'] ?? 'update') === 'day' ? 'day' : 'update';
$max = isset($_GET['cnt']) ? max(0, intval($_GET['cnt'])) : null;

$filtered = array_filter($data, fn($item) => ($item['robots'] ?? 'y') === 'y');

$result = [];
foreach ($filtered as $article) {
    $dateStr = $article[$target] ?? '';
    $articleDate = strtotime($dateStr);

    if (!$articleDate) continue;

    if ($from !== null && $articleDate < $from) continue;
    if ($to !== null && $articleDate > $to) continue;

    $result[] = $article;
    if ($max !== null && count($result) >= $max) break;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
