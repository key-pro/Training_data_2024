<?php
    // セッションの開始
    session_start();

    // セッションにユーザー情報がない場合はログイン画面にリダイレクト
    if (!isset($_SESSION['user_id'])) {

        // ログイン画面にリダイレクト
        header('Location: login.php');
        exit;
    }

    // データベース接続ファイルを読み込む
    include('db_config.php');

    // 削除するTodoのIDを取得
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {

        // TodoのIDを取得
        $todo_id = (int)$_GET['id'];

        try {

            // 削除するTodoの存在を確認

            // 指定されたIDのTodoデータを取得するクエリを準備
            $sql = "SELECT * FROM todo WHERE id = :id AND user_id = :user_id";

            // SQL文の実行準備
            $stmt = $pdo->prepare($sql);

            // TodoのIDをバインド
            $stmt->bindValue(':id', $todo_id, PDO::PARAM_INT);

            // ユーザーIDをバインド
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

            // クエリーの実行
            $stmt->execute();

            // Todoデータを取得
            $todo = $stmt->fetch(PDO::FETCH_ASSOC);

            // Todoが見つからない場合はエラーをスロー
            if (!$todo) {
                throw new Exception('指定されたTodoが見つかりませんでした。');
            }


            // Todoを削除するクエリを準備
            $sql = "DELETE FROM todo WHERE id = :id AND user_id = :user_id";

            // SQL文の実行準備
            $stmt = $pdo->prepare($sql);

            // TodoのIDをバインド
            $stmt->bindValue(':id', $todo_id, PDO::PARAM_INT);

            // ユーザーIDをバインド
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

            // クエリーの実行
            $stmt->execute();

            // リダイレクトしてメッセージを表示
            header('Location: view.php?message=Todoを削除しました。');
            exit;

        // データベースエラーが発生した場合
        } catch (PDOException $e) {
            die('データベースエラー: ' . $e->getMessage());

        //　その他のエラーが発生した場合
        } catch (Exception $e) {
            die($e->getMessage());
        }

    // TodoのIDが指定されていない場合
    } else {

        // View.phpにリダイレクト
        header('Location: view.php');
        exit;
    }
?>
