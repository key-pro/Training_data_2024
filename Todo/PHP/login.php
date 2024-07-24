<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <title>ログイン</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-6">
                <div class="login-container">
                    <h1 class="my-4">ログイン</h1>
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="user_name" class="form-label">ユーザー名</label>
                            <input type="text" id="user_name" name="user_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">パスワード</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="submit" class="btn btnshine btn-primary mt-3">ログイン</button>
                        <!-- 新規登録の同線 -->
                        <a href="register_html.php" class="btn btnshine btn-outline-primary register_btn">新規登録はこちら</a>
                    </form>

                    <?php
                        // セッションを開始
                        session_start();

                        // データベース接続ファイルを読み込む
                        include ('db_config.php');

                        // エラー表示を有効にする
                        ini_set('display_errors', "On");

                        // POSTリクエストがある場合の処理
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            // ユーザー名とパスワードを取得
                            $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
                            $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

                            // SQL文を作成
                            $sql = "SELECT * FROM users WHERE user_name = :user_name";

                            //　SQLの準備
                            $stmt = $pdo->prepare($sql);

                            // ユーザー名バインド
                            $stmt->bindParam(':user_name', $user_name);

                            // SQLを実行
                            $stmt->execute();

                            // 結果を取得
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);

                            // ユーザーが存在し、パスワードが一致する場合の処理
                            if ($user && password_verify($password, $user['password'])) {

                                // セッションにユーザー情報を保存
                                $_SESSION['user_id'] = $user['user_id'];
                                $_SESSION['user_name'] = $user['user_name'];
                                $_SESSION['user_organization'] = $user['user_organization'];
                                $_SESSION['role'] = $user['user_role'];

                                // MyPage.phpリダイレクト
                                header('Location: MyPage.php');

                            } else {

                                // ユーザー名またはパスワードが正しくない場合の処理
                                echo "<br><div class='alert alert-danger'>ユーザー名またはパスワードが正しくありません。</div>";

                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        rossorigin="anonymous"></script>
</body>
</html>
