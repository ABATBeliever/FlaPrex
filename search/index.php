<?php
$searchFile = __DIR__ . '/../article/search-list.json';
if (!file_exists($searchFile)) {
    http_response_code(500);
    exit('検索キャッシュがありません。サイト管理者にご一報ください。');
}

$json = file_get_contents($searchFile);
$articles = json_decode($json, true);

$searchWord = trim($_GET['word'] ?? '');
$searchWords = array_filter(preg_split('/\s+/u', $searchWord));
$modeRaw = $_GET['mode'] ?? ['0', '1', '2'];
$modeArray = is_array($modeRaw) ? $modeRaw : str_split($modeRaw);
$author = trim($_GET['author'] ?? '');
$tag = trim($_GET['tag'] ?? '');
$tags = array_filter(array_map('trim', explode(',', $tag)));

$check = fn($n) => in_array((string)$n, $modeArray, true);

$result = [];
foreach ($articles as $article) {
    if ($article['robots'] === 'n') continue;

    $hit = false;

    if (count($searchWords) > 0) {
        foreach ($searchWords as $word) {
            if (
                ($check(0) && stripos($article['title'], $word) !== false) ||
                ($check(1) && stripos($article['description'], $word) !== false) ||
                ($check(2) && array_filter($article['keyword'], fn($k) => stripos($k, $word) !== false))
            ) {
                $hit = true;
                break;
            }
        }
    } else {
        $hit = true;
    }

    if (!$hit) continue;
    if ($author && stripos($article['author'], $author) === false) continue;

    if ($tags) {
        $tagHit = false;
        foreach ($tags as $t) {
            if (in_array($t, $article['keyword'])) {
                $tagHit = true;
                break;
            }
        }
        if (!$tagHit) continue;
    }

    $result[] = $article;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>記事を検索</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"      content="記事を検索">
  <meta name="robots"           content="noindex, nofollow">

  <link rel="icon" type="image/x-icon" href="../src/media/favicon/favicon.ico">
  <link rel="stylesheet"               href="../src/media/css/kiso.css">
  <link rel="stylesheet"               href="../src/media/css/search.css">
</head>

<body>
  <header class="site-header">
    <h1>記事を検索</h1>
  </header>

  <main class="main-container">
    <h1>記事の検索フォーム</h1>

    <form method="get" action="/search/">
      <input type="text" name="word" placeholder="検索語" value="<?= htmlspecialchars($searchWord) ?>">
      <label><input type="checkbox" name="mode[]" value="0" <?= in_array('0', $modeArray) ? 'checked' : '' ?>> タイトル</label>
      <label><input type="checkbox" name="mode[]" value="1" <?= in_array('1', $modeArray) ? 'checked' : '' ?>> 説明</label>
      <label><input type="checkbox" name="mode[]" value="2" <?= in_array('2', $modeArray) ? 'checked' : '' ?>> キーワード</label><br>
      <input type="text" name="author" placeholder="著者フィルタ" value="<?= htmlspecialchars($author) ?>">
      <input type="text" name="tag" placeholder="タグ (カンマ区切り)" value="<?= htmlspecialchars($tag) ?>">
      <button type="submit">検索</button>
    </form>

    <section>
      <h2>検索結果 (<?= count($result) ?> 件)</h2>
      <ul>
        <?php foreach ($result as $item): ?>
          <li>
            <a href="/article/<?= htmlspecialchars($item['id']) ?>/">
              <?= htmlspecialchars($item['title']) ?>
            </a><br>
            <small><?= htmlspecialchars($item['description']) ?></small>
            <small>著者: <?= htmlspecialchars($item['author']) ?> | 更新: <?= htmlspecialchars($item['update']) ?></small>
            <small>タグ: <?= htmlspecialchars(implode(', ', $item['keyword'])) ?></small>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </main>

  <footer class="site-footer">
    <p>記事が見つからない場合、検索で非公開にする設定がされている可能性があります。</p>
  </footer>
</body>
</html>
