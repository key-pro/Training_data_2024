<?php
    // セッションの開始
    session_start();
?>

<?php
    // 削除ボタンが押された場合の処理
    if (isset($_POST['delete'])) {
        // ユーザーIDを取得
        $user_id = $_SESSION['user_id'];

        include('db_config.php');

        // ユーザーを削除するクエリを実行
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $sql = "DELETE FROM todo WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // 削除が成功した場合の処理
        if ($stmt) {
            echo '<div class="alert alert-success">ユーザーが削除されました。</div>';
            // セッションを破棄
            session_destroy();
            // ログイン画面にリダイレクト
            header('Location: login.php');
            exit;
        } else {
            echo '<div class="alert alert-danger">ユーザーの削除に失敗しました。</div>';
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>ユーザー削除</title>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-outline-primary text-black">
                        <h4 class="mb-2">ユーザー削除</h4>
                    </div>
                    <div class="card-body">
                        <p>本当に削除しますか?</p>
                        <form action="" method="post">
                            <input type="hidden" name="delete" value="true">
                            <button type="submit" class="btn btn-danger">削除</button>
                            <a href="MyPage.php" class="btn btn-secondary">キャンセル</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
