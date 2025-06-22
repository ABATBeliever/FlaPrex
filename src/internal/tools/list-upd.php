<?php
if (!empty($_SERVER['REMOTE_ADDR']) || php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Aborted due to unauthorized access');
}
echo "████████  ████                ██████▒                                \n";
echo "████████  ████                ███████▒                               \n";
echo "██          ██                ██   ▒██                               \n";
echo "██          ██       ▒████▓   ██    ██   ██░████   ░████▒   ███  ███ \n";
echo "██          ██       ██████▓  ██   ▒██   ███████  ░██████▒   ██▒▒██  \n";
echo "███████     ██       █▒  ▒██  ███████▒   ███░     ██▒  ▒██   ▒████▒  \n";
echo "███████     ██        ▒█████  ██████▒    ██       ████████    ████   \n";
echo "██          ██      ░███████  ██         ██       ████████    ▒██▒   \n";
echo "██          ██      ██▓░  ██  ██         ██       ██          ████   \n";
echo "██          ██▒     ██▒  ███  ██         ██       ███░  ▒█   ▒████▒  \n";
echo "██          █████   ████████  ██         ██       ░███████   ██▒▒██  \n";
echo "██          ░████    ▓███░██  ██         ██        ░█████▒  ███  ███ \n";
echo "FlaPrex list updater\n";
echo "v0.1\n";

$articleDir = __DIR__ . '/../../../article';
$outputFile = $articleDir . '/search-list.json';

$searchList = [];
$articleCount = 0;
$skippedCount = 0;

echo "Processing...\n\n";
foreach (glob($articleDir . '/*/article.dat') as $filePath) {
    $articleId = basename(dirname($filePath));
    echo "[INFO] Loading ID '{$articleId}' >";

    $iniData = [];
    $handle = fopen($filePath, 'r');
    if (!$handle) {
        echo "'[ERROR] File {$filePath}' unreachable\n";
        $skippedCount++;
        continue;
    }

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '<content>') break;

        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $iniData[trim($key)] = trim($val);
        }
    }
    fclose($handle);

    if (empty($iniData['title']) || empty($iniData['description'])) {
        echo "[ERROR] 'File {$articleId}' is break\n";
        $skippedCount++;
        continue;
    }

    $searchList[] = [
        'id' => $articleId,
        'title' => $iniData['title'] ?? '',
        'author' => $iniData['author'] ?? '',
        'description' => $iniData['description'] ?? '',
        'robots' => $iniData['robots'] ?? 'y',
        'day' => $iniData['day'] ?? '',
        'update' => $iniData['update'] ?? '',
        'keyword' => isset($iniData['keyword']) ? array_map('trim', explode(',', $iniData['keyword'])) : [],
    ];

    echo "[SUCCESS] '{$articleId}'\n";
    $articleCount++;
}

file_put_contents($outputFile, json_encode($searchList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\n\nDone.\n{$articleCount} case(s) were included.\n";
if ($skippedCount > 0) {
    echo "{$skippedCount} case(s) were skipped becaude it contains error\n";
}
echo "\n\nlocate: {$outputFile}\n";

