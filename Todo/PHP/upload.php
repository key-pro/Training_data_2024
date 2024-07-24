<?php
    // セッションの開始
    session_start();

    // セッションにユーザー情報がない場合はログイン画面にリダイレクト
    if (!isset($_SESSION['user_id'])) {

        //　ログイン画面にリダイレクト
        header('Location: login.php');
        exit;

    }

    // データベース接続ファイルを読み込む
    include('db_config.php');

    // ファイルが正しくアップロードされたか確認
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

        // アップロードされたファイルの情報を取得
        $photo = $_FILES['photo']['tmp_name'];

        // アップロードされたファイルの名前を取得
        $photo_name = $_FILES['photo']['name'];

        // ユーザーIDを取得
        $user_id = $_SESSION['user_id'];

        // SQL文を準備
        $sql = "INSERT INTO photos (user_id, photo_name, photo_data) VALUES (:user_id, :photo_name, :photo_data)";

        // SQLを実行する準備
        $stmt = $pdo->prepare($sql);

        // ファイルのデータを取得
        $photo_data = file_get_contents($photo);

        //ユーザーIDをバインド
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        //　写真の名前をバインド
        $stmt->bindParam(':photo_name', $photo_name, PDO::PARAM_STR);

        // 写真データをバインド
        $stmt->bindParam(':photo_data', $photo_data, PDO::PARAM_LOB);

        // SQLを実行し、結果を確認
        if ($stmt->execute()) {

            // アップロード成功をセット
            $message = "写真がアップロードされました。";

        //　アップロード失敗時エラー
        } else {

            // アップロード失敗をセット
            $message = "写真のアップロードに失敗しました。";

        }

        // メッセージをセットして、マイページにリダイレクト
        header("Location: MyPage.php?message=" . urlencode($message));
        exit;

    // ファイルがアップロードされていない場合
    } else {

        // アップロードエラー
        $message = "写真のアップロードに失敗しました。";

        // メッセージをセットして、マイページにリダイレクト
        header("Location: MyPage.php?message=" . urlencode($message));
        exit;

    }
?>
