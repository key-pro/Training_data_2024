<?php
    // データベース接続ファイルを読み込む
    include('db_config.php');

    // エラー表示を有効にする
    ini_set('display_errors', "On");

    // POSTリクエストがある場合の処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // ユーザー名、パスワード、所属部署を取得
        $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
        $user_password = htmlspecialchars($_POST['user_password'], ENT_QUOTES, 'UTF-8');
        //配列を使用している為、+1調整する
        $user_organization = htmlspecialchars($_POST['user_organization'], ENT_QUOTES, 'UTF-8');

        // パスワードをハッシュ化
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // ユーザーをデータベースsql
        $sql = "INSERT INTO users (user_name, password, department_id) VALUES (:user_name, :password, :department_id)";

        // SQL文の実行準備
        $stmt = $pdo->prepare($sql);

        // ユーザー名のバインド
        $stmt->bindParam(':user_name', $user_name);

        // passwordのバインド
        $stmt->bindParam(':password', $hashed_password);

        // ユーザの組織をバインド
        $stmt->bindParam(':department_id', $user_organization);

        // SQLを実行し、結果を確認
        if ($stmt->execute()) {

            // 登録に成功した場合はログインページにリダイレクト
            header("Location: login.php");
            exit;

        } else {

            // 登録に失敗した場合は登録ページにリダイレクト
            header("Location: register_html.php.php");
            exit;
        }
    }
?>
