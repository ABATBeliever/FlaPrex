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
        'description' => 'recommend-rnd_apiは検索が有効な記事を指定件数ランダムに探し、jsonで返すapiです。',
        'params' => [
            'cnt' => '返す最大件数（正の整数、省略時は10件 ただし記事数を超える指定の場合、記事すべてを返す）'
        ],
        'example' => $_SERVER['PHP_SELF'] . '?cnt=5',
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

$max = isset($_GET['cnt']) ? max(0, intval($_GET['cnt'])) : 10;

$visible = array_values(array_filter($data, fn($item) => ($item['robots'] ?? 'y') === 'y'));

shuffle($visible);
$result = array_slice($visible, 0, $max);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
