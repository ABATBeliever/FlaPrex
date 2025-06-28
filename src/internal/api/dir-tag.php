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
        'description' => 'dir-tag_apiは記事をタグ検索し、結果をjsonで返すapiです。',
        'params' => [
            'cnt' => '返す最大件数（正の整数、省略時は全件 ただし記事数を超える指定の場合、記事すべてを返す）',
            'tag' => 'カンマ区切りのタグ名、複数選択可能',
            'mode' => 'タグ一致方法: "and" または "or"（既定: or）'
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

$tagParam = $_GET['tag'] ?? '';
$mode = strtolower($_GET['mode'] ?? 'or');
$max = isset($_GET['cnt']) ? max(0, intval($_GET['cnt'])) : null;

$tagList = array_filter(array_map('trim', explode(',', $tagParam)));
if (empty($tagList)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No tags specified']);
    exit;
}

$filtered = array_filter($data, fn($item) => ($item['robots'] ?? 'y') === 'y');

$result = [];
foreach ($filtered as $article) {
    $keywords = $article['keyword'] ?? [];
    if (!is_array($keywords)) continue;

    $matched = false;

    if ($mode === 'and') {

        $matched = !array_diff($tagList, $keywords);
    } else {

        $matched = count(array_intersect($tagList, $keywords)) > 0;
    }

    if ($matched) {
        $result[] = $article;
        if ($max !== null && count($result) >= $max) break;
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
