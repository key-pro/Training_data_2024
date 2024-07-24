<?php
    // セッションの開始
    session_start();

    // セッションにユーザー情報がない場合はログイン画面にリダイレクト
    if (!isset($_SESSION['user_id'], $_SESSION['role'])) {

        // login.phpにリダイレクト
        header('Location: login.php');
        exit;
    }

    // データベース接続ファイルを読み込む
    include('db_config.php');

    // 編集するTodoのIDを取得
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {

        // TodoのIDを取得
        $todo_id = (int)$_GET['id'];

        try {

            // 編集するTodoのデータを取得
            $sql = "SELECT * FROM todo WHERE id = :id AND user_id = :user_id";

            // SQL文の実行準備
            $stmt = $pdo->prepare($sql);

            // TodoのIDをバインド
            $stmt->bindValue(':id', $todo_id, PDO::PARAM_INT);

            // ユーザーIDをバインド
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

            // SQLの実行
            $stmt->execute();

            // Todoデータを取得
            $todo = $stmt->fetch(PDO::FETCH_ASSOC);

            // Todoが見つからない場合はエラーをスロー
            if (!$todo) {
                throw new Exception('指定されたTodoが見つかりませんでした。');
            }

        // データベースエラーが発生した場合の処理
        } catch (PDOException $e) {

            // データベースエラーメッセージを表示
            die('データベースエラー: ' . $e->getMessage());

        // その他のエラーが発生した場合の処理
        } catch (Exception $e) {

            // エラーメッセージを表示
            die($e->getMessage());

        }

    // TodoのIDが指定されていない場合は、view.phpにリダイレクト
    } else {

        // view.phpにリダイレクト
        header('Location: view.php');
        exit;
    }

    // 編集フォームが送信された場合の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // urgencyとtitleとvalueとdue_dateがセットされているかを確認
        if (isset($_POST['urgency'], $_POST['title'], $_POST['value'], $_POST['due_date'], $_POST['progress'])) {

            try {

                // Todoを更新するSQLを準備
                $sql = "UPDATE todo SET urgency = :urgency, title = :title, value = :value, due_date = :due_date, progress = :progress WHERE id = :id AND user_id = :user_id";

                // SQL文の実行準備
                $stmt = $pdo->prepare($sql);

                // 緊急度をバインド
                $stmt->bindValue(':urgency', $_POST['urgency'], PDO::PARAM_STR);

                // タイトルをバインド
                $stmt->bindValue(':title', $_POST['title'], PDO::PARAM_STR);

                // 内容をバインド
                $stmt->bindValue(':value', $_POST['value'], PDO::PARAM_STR);

                // 期限をバインド
                $stmt->bindValue(':due_date', $_POST['due_date'], PDO::PARAM_STR);

                // IDをバインド
                $stmt->bindValue(':id', $todo_id, PDO::PARAM_INT);

                // ユーザーIDをバインド
                $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

                // 進捗度をバインド
                $stmt->bindValue(':progress', $_POST['progress'], PDO::PARAM_INT);

                // SQL文の実行
                $stmt->execute();

                // リダイレクトしてメッセージを表示
                header('Location: view.php?message=Todoを更新しました。');
                exit;

            // データベースエラーが発生した場合の処理
            } catch (PDOException $e) {

                // データベースエラーメッセージを表示
                die('データベースエラー: ' . $e->getMessage());

            }

        // 緊急度とタイトルと内容と期限がセットされていない場合の処理
        } else {

            // エラーメッセージをセット
            $error = '全てのフィールドを入力してください。';

        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo編集</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Todo編集</h2>

        <!-- エラーがある確認 -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">

                <!-- エラーメッセージを表示 -->
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="urgency" class="form-label">優先度</label>
                <select name="urgency" id="urgency" class="form-select" required>

                    <!-- 既存の値を表示する -->
                    <option value="高" <?= $todo['urgency'] === '高' ? 'selected' : '' ?>>高</option>
                    <option value="中" <?= $todo['urgency'] === '中' ? 'selected' : '' ?>>中</option>
                    <option value="小" <?= $todo['urgency'] === '小' ? 'selected' : '' ?>>小</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">TODOのタイトル</label>

                <!-- 既存の値を表示する -->
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') ?>"  maxlength="255" required>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">TODOの内容</label>

                <!-- 既存の値を表示する -->
                <input type="text" class="form-control" id="value" name="value" value="<?= htmlspecialchars($todo['value'], ENT_QUOTES, 'UTF-8') ?>" maxlength="255" required>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">期限</label>

                <!-- 既存の値を表示する -->
                <input type="date" class="form-control" id="due_date" name="due_date" value="<?= htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="mb-3">
                <label for="range" class="form-label">進捗度</label>

                <!-- 既存の値を表示する -->
                <input type="number" class="form-control" id="progress" name="progress" min="0" max="100" value="<?= htmlspecialchars($todo['progress'], ENT_QUOTES, 'UTF-8') ?>" required>
                <input type="range" class="form-range" id="progress_number" name="progress_number" value="<?= htmlspecialchars($todo['progress'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="100" data-unit="%" disabled>
            </div>
            <button type="submit" class="btn btn-primary">更新</button>
            <button href="view.php" class="btn btn-secondary">キャンセル</button>
        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="javascript/slider.js"></script>
</body>
</html>

