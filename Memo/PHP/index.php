<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Memo</title>
</head>
<body>
    <div class="container">
        <h1>Memo</h1>
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <form action="mysql.php" method="POST">
            <textarea class="form-control" id="add-text" name="memo" placeholder="Memo" required></textarea>
            <br>
            <button type="submit" class="btn btn-primary" name="action" value="add">登録</button>
        </form>
        <br>
        <form action="mysql.php" method="POST">
            <button type="submit" class="btn btn-danger" name="action" value="delete">削除</button>
        </form>
    </div>
    <div class="container mt-5">
        <h2>メモ一覧</h2>
        <?php
        include('db_config.php');
        // データ取得クエリ
        $sql = "SELECT * FROM notes";
        $stmt = $pdo->query($sql);

        // データ取得
        $notes = $stmt->fetchAll();
        if (!empty($notes)): ?>
            <ul class="list-group">
                <?php foreach ($notes as $item): ?>
                    <li class="list-group-item">
                        ID: <?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>　Memo: <?= htmlspecialchars($item['memo'], ENT_QUOTES, 'UTF-8') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>メモ内容はありません</p>
        <?php endif; ?>
    </div>
</body>
</html>
