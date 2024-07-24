<?php
    // セッションの開始
    session_start();

    // データベース設定ファイルの読み込み
    require('db_config.php');

    // GETリクエストでidが送信された場合
    if (isset($_GET['id'])) {

        // idを取得
        $id = $_GET['id'];

        // SQL文の作成
        $sql = "UPDATE todo SET status = '完了' WHERE id = :id";

        // SQL文の実行準備
        $stmt = $pdo->prepare($sql);

        // idのバインド
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // SQL文の実行
        if ($stmt->execute()) {

            // 完了メッセージを表示
            header('Location: view.php?message=TODOを完了しました');

        } else {

            // エラーメッセージを表示
            header('Location: view.php?message=エラーが発生しました');

        }
    } else {

        // idが送信されなかった場合はTODOリストページにリダイレクト
        header('Location: view.php');
    }
?>
