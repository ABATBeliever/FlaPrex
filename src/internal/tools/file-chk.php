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
echo "FlaPrex file checker\n";
echo "このファイルチェッカーは以下のバージョンのFlaPrex向けです: ";
echo "Version 1.1.0";
echo "\n";

$errorlevel = 0;
function notfound($path) {
    return !(file_exists($path));
}

chdir(__DIR__ . "/../../../");
echo "検査を開始します: " . getcwd() . "\n";

if (notfound("article")) {
    echo "[NotFound] /article/ \n";
    $errorlevel++;
}
if (notfound(".htaccess")) {
    echo "[NotFound] /.htaccess \n";
    $errorlevel++;
}
if (notfound("index.php")) {
    echo "[NotFound] /.index.php \n";
    $errorlevel++;
}
if (notfound("search/index.php")) {
    echo "[NotFound] /search/index.php \n";
    $errorlevel++;
}

if (notfound("src/internal/tools/list-upd.php")) {
    echo "[NotFound] /src/internal/tools/list-upd.php \n";
    $errorlevel++;
}

if (notfound("src/internal/skin/")) {
    echo "[NotFound] /src/internal/skin/ \n";
    $errorlevel++;
}
if (notfound("src/internal/default.dat")) {
    echo "[NotFound] /src/internal/default.dat \n";
    $errorlevel++;
}
if (notfound("src/internal/error/anti-request-injection.dat")) {
    echo "[NotFound] /src/internal/error/anti-request-injection.dat \n";
    $errorlevel++;
}
if (notfound("src/internal/error/not-found.dat")) {
    echo "[NotFound] /src/internal/error/not-found.dat \n";
    $errorlevel++;
}

if (notfound("src/internal/api/list-category.php")) {
    echo "[NotFound] /src/internal/api/list-category.php \n";
    $errorlevel++;
}
if (notfound("src/internal/api/dir-date.php")) {
    echo "[NotFound] /src/internal/api/dir-date.php \n";
    $errorlevel++;
}
if (notfound("src/internal/api/dir-author.php")) {
    echo "[NotFound] /src/internal/api/dir-author.php \n";
    $errorlevel++;
}
if (notfound("src/internal/api/dir-tag.php")) {
    echo "[NotFound] /src/internal/api/dir-tag.php \n";
    $errorlevel++;
}
if (notfound("src/internal/api/old.php")) {
    echo "[NotFound] /src/internal/api/old.php \n";
    $errorlevel++;
}
if (notfound("src/internal/api/new.php")) {
    echo "[NotFound] /src/internal/api/new.php \n";
    $errorlevel++;
}
if (notfound("src/internal/api/recommend.php")) {
    echo "[NotFound] /src/internal/api/recommend.php \n";
    $errorlevel++;
}

if (notfound("src/media/ogp/index.png")) {
    echo "[NotFound] /src/media/ogp/index.png \n";
    $errorlevel++;
}
if (notfound("src/media/css/article.css")) {
    echo "[NotFound] /src/media/css/article.css \n";
    $errorlevel++;
}
if (notfound("src/media/css/search.css")) {
    echo "[NotFound] /src/media/css/search.css \n";
    $errorlevel++;
}

if (notfound("src/media/js/")) {
    echo "[NotFound] /src/media/js/ \n";
    $errorlevel++;
}
if (notfound("src/media/favicon/favicon.ico")) {
    echo "[NotFound] /src/media/favicon/favicon.ico \n";
    $errorlevel++;
}
if (notfound("src/media/favicon/favicon-016.png")) {
    echo "[NotFound] /src/media/favicon/favicon-016.png \n";
    $errorlevel++;
}
if (notfound("src/media/favicon/favicon-032.png")) {
    echo "[NotFound] /src/media/favicon/favicon-032.png \n";
    $errorlevel++;
}
if (notfound("src/media/favicon/favicon-180.png")) {
    echo "[NotFound] /src/media/favicon/favicon-180.png\n";
    $errorlevel++;
}
if (notfound("src/media/favicon/favicon-512.png")) {
    echo "[NotFound] /src/media/favicon/favicon-512.png \n";
    $errorlevel++;
}
if (notfound("src/media/favicon/favicon-raw.png")) {
    echo "[NotFound] /src/media/favicon/favicon-raw.png \n";
    $errorlevel++;
}

if (notfound("src/media/img/")) {
    echo "[NotFound] /src/media/img/ \n";
    $errorlevel++;
}

echo "\n\n判定: ";
if ($errorlevel === 0) {
    echo "問題ありません\n";
} else {
    echo "問題が $errorlevel 件あります\n";
}

echo "終了\n";