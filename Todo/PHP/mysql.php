<?php
    // データベース接続ファイルを読み込む
    include ('db_config.php');

    // セッションを開始
    session_start();

    // POSTリクエストがある場合の処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // アクションがセットされているかを確認
        if (isset($_POST['action'])) {

            // アクションとIDを取得
            $action = $_POST['action'];

            // idをチェック
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;

            // タイトルをチェック
            $title = isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : null;

            // 値をチェック
            $value = isset($_POST['value']) ? htmlspecialchars($_POST['value'], ENT_QUOTES, 'UTF-8') : null;

            // 優先度をチック
            $urgency = isset($_POST['urgency']) ? htmlspecialchars($_POST['urgency'], ENT_QUOTES, 'UTF-8') : null;

            // 期限をチック
            $due_date = isset($_POST['due_date']) ? htmlspecialchars($_POST['due_date'], ENT_QUOTES, 'UTF-8') : null;

            // ユーザーIDをチェック
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

            // 部署をチェック
            $department_id = isset($_SESSION['department_id']) ? htmlspecialchars($_SESSION['department_id'], ENT_QUOTES, 'UTF-8') : null;

            // 進捗度をチェック
            $progress = isset($_POST['progress_number']) ? htmlspecialchars($_POST['progress_number'], ENT_QUOTES, 'UTF-8') : null;

            // ユーザー役割をチェック
            $user_role = isset($_POST['range']) ? htmlspecialchars($_POST['range'], ENT_QUOTES, 'UTF-8') : null;

                // TODOを追加するSQLを準備
                $sql = "INSERT INTO todo (title, value, urgency, due_date, status, user_id, department_id, progress, role) VALUES (:title, :value, :urgency, :due_date, '未完了', :user_id, :department_id, :progress, :role)";

                // sqlの準備
                $stmt = $pdo->prepare($sql);

                // タイトルをバインド
                $stmt->bindParam(':title', $title);

                // 値をバインド
                $stmt->bindParam(':value', $value);

                // 緊急度をバインド
                $stmt->bindParam(':urgency', $urgency);

                // 期限日をバインド
                $stmt->bindParam(':due_date', $due_date);

                // ユーザーIDをバインド
                $stmt->bindParam(':user_id', $user_id);

                // 部署IDをバインド
                $stmt->bindParam(':department_id', $department_id);

                // 進捗率をバインド
                $stmt->bindParam(':progress', $progress);

                //　ユーザー役割をバインド
                $stmt->bindParam(':role', $user_role);

                // SQLを実行し、結果を確認
                if ($stmt->execute()) {

                    // 追加のメッセージをセット
                    $message = 'TODOを追加しました。';

                } else {

                    // 追加失敗のメッセージをセット
                    $message = 'TODOの追加に失敗しました。';

                }

                // メッセージをセットして、ビューページにリダイレクト
                header("Location: view.php?message=" . urlencode($message));
                exit();
        }
    }else{
        // 追加失敗のメッセージをセット
        $message = 'TODOの追加に失敗しました。';
        // メッセージをセットして、ビューページにリダイレクト
        header("Location: view.php?message=" . urlencode($message));
        exit();
    }
?>