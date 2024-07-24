<?php
    //セッションの開始
    session_start();

    // セッションにユーザー情報がない場合
    if (!isset($_SESSION['user_id'])) {

        //ログイン画面にリダイレクト
        header('Location: login.php');
        exit;

    }

    // データベース接続ファイルを読み込む
    include('db_config.php');

    // IDがGETパラメータとして送信されていることを確認する
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

        // idを指定していない場合はエラーメッセージを表示して一覧画面にリダイレクト
        header('Location: view.php?message=不正なアクセスです');
        exit;

    }

    // idを取得
    $todo_id = $_GET['id'];

    try {
        // 指定されたIDのTodoデータを取得するクエリを準備
        $sql = "SELECT * FROM todo WHERE id = :id AND user_id = :user_id";

        // SQL文の実行準備
        $stmt = $pdo->prepare($sql);

        // TodoのIDをバインド
        $stmt->bindValue(':id', $todo_id, PDO::PARAM_INT);

        // ユーザーIDをバインド
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        // SQL実行
        $stmt->execute();

        // Todoデータを取得
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Todoが存在しない場合はエラーメッセージを表示して一覧画面にリダイレクト
        if (!$todo) {

            // Todoが見つからない場合はエラーメッセージを表示して一覧画面にリダイレクト
            header('Location: view.php?message=指定されたTodoが見つかりません');
            exit;

        }

        // SQL文作成
        $sql_update = "UPDATE todo SET status = '未完了' WHERE id = :id";

        // SQL文の実行準備
        $stmt_update = $pdo->prepare($sql_update);

        // IDをバインド
        $stmt_update->bindValue(':id', $todo_id, PDO::PARAM_INT);

        // SQL実行
        $stmt_update->execute();

        // 完了から未完了に戻す処理が成功したら一覧画面にリダイレクト
        header('Location: view.php?message=Todoを未完了に戻しました');

    // データベースエラーが発生した場合
    } catch (PDOException $e) {

        // エラーが発生した場合はエラーメッセージを表示して一覧画面にリダイレクト
        header('Location: view.php?message=' . $e->getMessage());
        exit;

    }
?>
