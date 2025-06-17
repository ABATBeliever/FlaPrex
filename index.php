<?php
// ===================================================================
//
//  ████████  ████                ██████▒                                
//  ████████  ████                ███████▒                               
//  ██          ██                ██   ▒██                               
//  ██          ██       ▒████▓   ██    ██   ██░████   ░████▒   ███  ███ 
//  ██          ██       ██████▓  ██   ▒██   ███████  ░██████▒   ██▒▒██  
//  ███████     ██       █▒  ▒██  ███████▒   ███░     ██▒  ▒██   ▒████▒  
//  ███████     ██        ▒█████  ██████▒    ██       ████████    ████   
//  ██          ██      ░███████  ██         ██       ████████    ▒██▒   
//  ██          ██      ██▓░  ██  ██         ██       ██          ████   
//  ██          ██▒     ██▒  ███  ██         ██       ███░  ▒█   ▒████▒  
//  ██          █████   ████████  ██         ██       ░███████   ██▒▒██  
//  ██          ░████    ▓███░██  ██         ██        ░█████▒  ███  ███ 
//
//  Insider Preview - Release 1
//  Made by ABATBeliever
//
//  https://github.com/ABATBeliever/FlaPrex'
//
// ===================================================================
 
$requestUri = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($requestUri, '/'));
$articleId = isset($segments[1]) ? $segments[1] : null;

$articlePath   = __DIR__ . "/article/{$articleId}/article.dat";
$structurePath = __DIR__ . "/article/structure.dat";

if (!$articleId) {
    readfile(__DIR__ . '/src/internal/default.html');
    exit;
}

if (!file_exists($structurePath))       {
    echo './article/structure.dat が見つかりません。サイト管理者にご連絡ください。' ;
    exit;
}

if (strpos($articleId, '..') !== false) {
    $articlePath   = __DIR__ . "/src/internal/error/anti-request-injection.dat";
}    

if (!file_exists($articlePath))         {
    $articlePath   = __DIR__ . "/src/internal/error/not-found.dat";
}

$articleRaw = file_get_contents($articlePath);

preg_match_all('/^([a-z]+)=(.*)$/m', $articleRaw, $matches);
$meta = array_combine($matches[1], $matches[2]);

$title       = $meta['title']       ?? '無題';
$author      = $meta['author']      ?? '匿名';
$description = $meta['description'] ?? '';
$day         = $meta['day']         ?? '1900-01-01';
$update      = $meta['update']      ?? $day;
$robots      = (isset($meta['robots']) && strtolower($meta['robots']) === 'n') ? 
    '<meta name="robots" content="noindex, nofollow">' : 
    '<meta name="robots" content="index, follow">';

if (preg_match('/<content>(.*?)<\/content>/s', $articleRaw, $contentMatch)) {
    $content = trim($contentMatch[1]);
} else {
    $content = '<p>[記事は空です]</p>';
}

$template = file_get_contents($structurePath);

$output = str_replace(
    ['{{title}}', '{{author}}', '{{description}}', '{{robots}}', '{{day}}', '{{update}}', '{{content}}'],
    [$title, $author, $description, $robots, $day, $update, $content],
    $template
);

echo $output;
