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
        'description' => '全ての robots=y な記事のキーワード（タグ）と出現件数を返すAPI',
        'params' => '引数はありません',
        'example' => $_SERVER['PHP_SELF']
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

$tagCount = [];

foreach ($data as $article) {
    if (($article['robots'] ?? 'y') !== 'y') continue;

    $keywords = $article['keyword'] ?? [];
    if (!is_array($keywords)) continue;

    foreach ($keywords as $tag) {
        $tag = trim($tag);
        if ($tag === '') continue;
        if (!isset($tagCount[$tag])) {
            $tagCount[$tag] = 1;
        } else {
            $tagCount[$tag]++;
        }
    }
}

ksort($tagCount, SORT_LOCALE_STRING);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($tagCount, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
