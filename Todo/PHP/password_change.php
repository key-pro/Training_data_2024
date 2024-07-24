<?php
    // セッションの開始
    session_start();

    //セッションにユーザー情報がない場合はログイン画面にリダイレクト
    if (!isset($_SESSION['user_id'])) {

        // ログイン画面にリダイレクト
        header('Location: login.php');
        exit;

    }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードリセット</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

    <div class="container rounded shadow py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="display-4 text-center mb-4">パスワードリセット</h1>
                <?php
                    // ユーザー名を表示
                    echo '<h2 class="display-4 text-center mb-4">' . $_SESSION['user_name'] . ' さん</h2>';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $password = $_POST['password'];
                        $password_confirm = $_POST['password_confirm'];

                        // パスワードが一致しているかチェック
                        if ($password !== $password_confirm) {
                            echo '<div class="alert alert-danger" role="alert">パスワードが一致しません</div>';
                            exit;
                        } else {
                            // データベース接続ファイルを読み込む
                            include ('db_config.php');

                            try {
                                // ユーザー名、パスワード、公開範囲を取得
                                $user_name = htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8');
                                $user_password = htmlspecialchars($_POST['password_confirm'], ENT_QUOTES, 'UTF-8');

                                // パスワードをハッシュ化
                                $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

                                // ユーザーをデータベースsql
                                $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";

                                // SQL文の実行準備
                                $stmt = $pdo->prepare($sql);

                                // ユーザー名のバインド
                                $stmt->bindParam('user_id', $user_name);

                                // passwordのバインド
                                $stmt->bindParam(':password', $hashed_password);

                                // SQLを実行し、結果を確認
                                if ($stmt->execute()) {
                                    echo '<div class="alert alert-success" role="alert">パスワードリセットが完了しました。</div>';
                                }
                            } catch (PDOException $e) {
                                echo '<div class="alert alert-danger" role="alert">エラーが発生しました。管理者にお問い合わせください。</div>';
                                exit;
                            }
                        }
                    }
                ?>
                <p class="text-center mb-4">パスワードリセットしてよろしいでしょうか？</p>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="password" class="form-label">新しいパスワード</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">新しいパスワード（確認）</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control"
                            required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btnshine btn-warning rounded-pill px-4">パスワードをリセット</button>
                    </div>
                </form>
                <a href="MyPage.php" class="btn btnshine btn-outline-primary rounded-pill px-4">マイページに戻る</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>