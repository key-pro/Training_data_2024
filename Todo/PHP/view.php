<?php
    // セッションの開始
    session_start();

    //セッションにユーザー情報がない場合はログイン画面にリダイレクト
    if (!isset($_SESSION['user_id'])) {

        // ログイン画面にリダイレクト
        header('Location: login.php');
        exit;

    }

    // データベース接続ファイルを読み込む
    include('db_config.php');

    // もしセッションにimage_dataがあれば、背景画像として利用する
    $background_image = '';

    // セッションに写真データがあれば、背景画像として利用する
    if (isset($_SESSION['image_data'])) {

        // 画像データをbase64エンコードして変数に格納
        $background_image = 'data:image/jpeg;base64,' . base64_encode($_SESSION['image_data']);

    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo-List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            /* セッションまはデータベースから取得し、背景に設定 */
            <?php if ($background_image): ?>
                background-image: url('<?= $background_image ?>');
                background-size: cover;
                background-repeat: no-repeat;
            <?php else: ?>
                background-color: #f0f0f0;
            <?php endif; ?>
        }
    </style>
</head>
<body>
<div class="container">
    <div class="my-4">
        <!-- 現在のユーザー名表示 -->
        <div class="alert alert-primary" role="alert">
            ユーザー名:<?= $_SESSION["user_name"] ?><br>
            所属部署名:<?= $_SESSION["department_name"] ?>
        </div>

        <!-- マイページ、ログアウトボタン -->
        <a href="MyPage.php" class="btn btn-primary btnshine">マイページ</a>
        <a href="logout.php" class="btn btn-warning btnshine">ログアウト</a>
    </div>
    <h2 class="display-4 text-center my-4">Todo-List</h2>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!--  検索フォーム -->
    <form action="" method="GET" class="">
            <div class="col-md-6">
                <input type="text" class="form-control search_inputs" placeholder="検索するキーワード" name="keyword" maxlength="255" required>
                <button type="submit" class="btn btn-primary btnshine keyword_btn">検索</button>
            </div>
    </form>

    <!--  検索結果 -->
    <?php

        //検索キーワードチェック
        if (isset($_GET['keyword'])) {

            // 検索キーワードを取得
            $keyword = $_GET['keyword'];

            // SQlを準備
            $search_sql = "SELECT * FROM todo WHERE user_id = :user_id AND title LIKE :keyword";

            // SQLを実行する準備
            $search_stmt = $pdo->prepare($search_sql);

            // ユーザー名とキーワードのバインド
            $search_stmt->execute([':user_id' => $_SESSION['user_id'], ':keyword' => '%' . $keyword . '%']);

            // 検索結果を取得
            $search_results = $search_stmt->fetchAll(PDO::FETCH_ASSOC);

            // 検索結果があれば表示
            if ($search_results) {
                echo '<h3 class="mb-3">検索結果</h3>';
                echo '<ul class="list-group">';
                foreach ($search_results as $result) {
                    echo '<li class="list-group-item">';
                    echo '<div class="row">';
                    echo '<div class="col-md-8">';
                    echo '優先度: ' . htmlspecialchars($result['urgency'], ENT_QUOTES, 'UTF-8') . '<br>';
                    echo 'タイトル: ' . htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') . '<br>';
                    echo '内容: ' . htmlspecialchars($result['value'], ENT_QUOTES, 'UTF-8') . '<br>';
                    echo '期限: ' . htmlspecialchars($result['due_date'], ENT_QUOTES, 'UTF-8');
                    echo '</div>';
                    echo '<div class="col-md-4">';
                    echo '<div class="btn-group mt-2">';
                    // 編集ボタン
                    echo '<a href=edit.php?id=' . $result['id'] . ' class="btn btnshine btn-secondary btn-sm">編集</a>';
                    // 削除ボタン
                    echo '<a href=delete.php?id=' . $result['id'] . ' class="btn btnshine btn-danger btn-sm" onclick="return confirm(\'本当に削除しますか？\');">削除</a>';
                    // 完了ボタン
                    echo '<a href=complete.php?id=' . $result['id'] . ' class="btn btnshine btn-success btn-sm">完了</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                }
                echo '</ul>';

            // 検索結果がなければエラーの表示
            } else {

                //検索結果がない場合のメッセージ
                echo '<p class="alert alert-warning">検索結果はありません。</p>';

            }
        }
    ?>

        <div class="input-area mb-4">
            <form action="mysql.php" method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="urgency" class="form-label">優先度</label>
                    <select name="urgency" id="urgency" class="form-select" required>
                        <option value="高">高</option>
                        <option value="中">中</option>
                        <option value="小">小</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="title" class="form-label">TODOのタイトル</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="TODOのタイトル" maxlength="255" required>
                </div>
                <div class="col-md-6">
                    <label for="value" class="form-label">TODOの内容</label>
                    <input type="text" class="form-control" id="value" name="value" placeholder="TODOの内容" maxlength="255" required>
                </div>
                <div class="col-md-6">
                    <label for="due_date" class="form-label">期限</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                </div>
                <div class="col-md-6">
                    <label for="range" class="form-label">進捗度</label>
                    <input type="number" class="form-control" id="progress_number" name="progress_number" min="0" max="100" required>
                    <input type="range" class="form-range" id="progress" name="progress" value="0" min="0" max="100" data-unit="%" disabled>
                </div>
                <div class="col-md-6">
                    <p class="form-label">Todoリストの公開範囲</p>
                    <div class="form-check">
                        <input type="radio" id="public" name="range" value="public" class="form-check-input">
                        <label for="public" class="form-check-label">公開</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="private" name="range" value="private" class="form-check-input" checked>
                        <label for="private" class="form-check-label">非公開</label>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btnshine btn-primary btn-sm" name="action" value="add">追加</button>
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                </div>
            </form>
        </div>


        <h3 class="mb-3">未完了Todoリスト</h3>
        <?php

            try {

                // SQL文の準備
                $sql = "SELECT * FROM todo WHERE user_id = :user_id AND status = '未完了' AND department_id = :department_id ORDER BY due_date ASC";

                // SQLの実行準備
                $stmt = $pdo->prepare($sql);

                // ユーザー名をバインド
                $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

                // 部署IDをバインド
                $stmt->bindValue(':department_id', $_SESSION['department_id'], PDO::PARAM_INT);

                // SQLの実行
                $stmt->execute();

                // 結果を取得
                $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 未完了のTodoリストがあれば表示
                if ($todos) {
                    echo '<ul class="list-group">';

                    // 未完了のToDoリスト表示部分に編集と削除のリンクを追加
                    foreach ($todos as $todo) {
                        echo '<li class="list-group-item">';
                        echo '<div class="row">';
                        echo '<div class="col-md-8">';
                        echo '優先度: ' . htmlspecialchars($todo['urgency'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo 'タイトル: ' . htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '内容: ' . htmlspecialchars($todo['value'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '期限: ' . htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '進捗度: ' . htmlspecialchars($todo['progress'], ENT_QUOTES, 'UTF-8');
                        echo '</div>';
                        echo '<div class="col-md-4">';
                        echo '<div class="btn-group mt-2">';
                        // 編集ボタン
                        echo '<a href=edit.php?id=' . $todo['id'] . ' class="btn btnshine btn-secondary btn-sm">編集</a>';
                        // 削除ボタン
                        echo '<a href=delete.php?id=' . $todo['id'] . ' class="btn btnshine btn-danger btn-sm" onclick="return confirm(\'本当に削除しますか？\');">削除</a>';
                        // 完了ボタン
                        echo '<a href=complete.php?id=' . $todo['id'] . ' class="btn btnshine btn-success btn-sm">完了</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';

                // 未完了のTodoリストがなければメッセージを表示
                } else {

                    // 未完了のTodoリストがない場合のメッセージ
                    echo '<p>未完了のTodoリストはありません。</p>';

                }

            // データベースエラーが発生した場合の処理
            } catch (PDOException $e) {

                // エラーが発生した場合はエラーメッセージを表示
                echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';

            }
        ?>

        <h3 class="mb-3">完了Todoリスト</h3>
        <?php

            try {

                // SQLの準備
                $sql = "SELECT * FROM todo WHERE user_id = :user_id AND status = '完了' AND department_id = :department_id ORDER BY due_date ASC";

                // SQLの実行準備
                $stmt = $pdo->prepare($sql);

                // ユーザー名をバインド
                $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

                // 部署IDをバインド
                $stmt->bindValue(':department_id', $_SESSION['department_id'], PDO::PARAM_INT);

                // SQLの実行
                $stmt->execute();

                // SQL実行結果を取得
                $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 完了のTodoリストがあれば表示
                if ($todos) {

                    echo '<ul class="list-group">';

                    // 完了済みのToDoリスト表示部分に戻すボタンを追加
                    foreach ($todos as $todo) {
                        echo '<li class="list-group-item">';
                        echo '<div class="row">';
                        echo '<div class="col-md-8">';
                        echo '優先度: ' . htmlspecialchars($todo['urgency'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo 'タイトル: ' . htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '内容: ' . htmlspecialchars($todo['value'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '期限: ' . htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8'). '<br>';
                        echo '進捗度: ' . htmlspecialchars($todo['progress'], ENT_QUOTES, 'UTF-8');
                        echo '</div>';
                        echo '<div class="col-md-4">';
                        echo '<div class="btn-group mt-2">';
                        // 戻すボタン
                        echo '<a href=restore.php?id=' . $todo['id'] . ' class="btn btnshine btn-warning btn-sm">戻す</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';

                //　完了のTodoリストがなければメッセージを表示
                } else {

                    // 完了済みのTodoリストがない場合のメッセージ
                    echo '<p>完了済みのTodoリストはありません。</p>';

                }

            // データベースエラーが発生した場合の処理
            } catch (PDOException $e) {

                // データベースのエラーが発生した場合はエラーメッセージを表示
                echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';

            }
        ?>

        <h3 class="mb-3">公開ユーザーの未完了Todoリスト</h3>
        <?php

            try {

                // SQLセット
                $sql = "SELECT todo.*, users.user_name
                        FROM todo
                        LEFT JOIN users ON todo.user_id = users.user_id
                        WHERE todo.status = '未完了' AND todo.role = 'public' AND todo.department_id = :department_id
                        ORDER BY todo.due_date ASC";

                // SQL実行準備
                $stmt = $pdo->prepare($sql);

                // 部署IDをバインド
                $stmt->bindValue(':department_id', $_SESSION['department_id'], PDO::PARAM_INT);

                // SQL実行
                $stmt->execute();

                //実行結果を取得
                $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 未完了のTodoリストがあれば表示
                if ($todos) {

                    echo '<ul class="list-group">';
                    // 公開ユーザーの未完了のToDoリスト表示部分に編集と削除のリンクを追加
                    foreach ($todos as $todo) {
                        echo '<li class="list-group-item">';
                        echo '<div class="row">';
                        echo '<div class="col-md-8">';
                        echo 'ユーザー名: ' . htmlspecialchars($todo['user_name'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '優先度: ' . htmlspecialchars($todo['urgency'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo 'タイトル: ' . htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '内容: ' . htmlspecialchars($todo['value'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '期限: ' . htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '進捗度: ' . htmlspecialchars($todo['progress'], ENT_QUOTES, 'UTF-8');
                        echo '</div>';
                        echo '<div class="col-md-4">';
                        echo '<div class="btn-group mt-2">';
                        // 編集ボタン
                        echo '<a href=edit.php?id=' . $todo['id'] . ' class="btn btnshine btn-secondary disabled-link btn-sm">編集</a>';
                        // 削除ボタン
                        echo '<a href=delete.php?id=' . $todo['id'] . ' class="btn btnshine btn-danger disabled-link btn-sm" onclick="return confirm(\'本当に削除しますか？\');">削除</a>';
                        // 完了ボタン
                        echo '<a href=complete.php?id=' . $todo['id'] . ' class="btn btnshine btn-success disabled-link btn-sm">完了</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';

                // 未完了のTodoリストがなければ
                } else {

                    // 未完了のTodoがなければメッセージを表示
                    echo '<p>未完了のTodoリストはありません。</p>';
                }

            // データベースエラーが発生した場合の処理
            } catch (PDOException $e) {

                //　データベースのエラーが発生した場合はエラーメッセージを表示
                echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';

            }
        ?>

        <h3 class="mb-3">公開ユーザーの完了Todoリスト</h3>
        <?php

            try {
                // 公開ユーザーの完了済みのTodoリストのデータを取得
                $sql = "SELECT todo.*, users.user_name
                        FROM todo
                        LEFT JOIN users ON todo.user_id = users.user_id
                        WHERE todo.status = '完了' AND todo.role = 'public' AND todo.department_id = :department_id
                        ORDER BY todo.due_date ASC";

                // SQLの実行準備
                $stmt = $pdo->prepare($sql);

                // 部署IDをバインド
                $stmt->bindValue(':department_id', $_SESSION['department_id'], PDO::PARAM_INT);

                // SQL実行
                $stmt->execute();

                // 実行結果を取得
                $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                //　完了のTodoリストがあれば表示
                if ($todos) {

                    echo '<ul class="list-group">';

                    // 公開ユーザーの完了済みのToDoリスト表示部分に戻すボタンを追加
                    foreach ($todos as $todo) {
                        echo '<li class="list-group-item">';
                        echo '<div class="row">';
                        echo '<div class="col-md-8">';
                        echo 'ユーザー名: ' . htmlspecialchars($todo['user_name'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '優先度: ' . htmlspecialchars($todo['urgency'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo 'タイトル: ' . htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '内容: ' . htmlspecialchars($todo['value'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '期限: ' . htmlspecialchars($todo['due_date'], ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '進捗度: ' . htmlspecialchars($todo['progress'], ENT_QUOTES, 'UTF-8');
                        echo '</div>';
                        echo '<div class="col-md-4">';
                        echo '<div class="btn-group mt-2">';
                        // 戻すボタン
                        echo '<a href=restore.php?id=' . $todo['id'] . ' class="btn btnshine btn-warning disabled-link btn-sm">戻す</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';

                //完了の済みのTodoリストがなければ
                } else {

                    //完了の済みのTodoリストがない場合のメッセージ
                    echo '<p>完了済みのTodoリストはありません。</p>';

                }

            // データベースのエラーが発生した場合の処理
            } catch (PDOException $e) {

                // データベースのエラーが発生した場合はエラーメッセージを表示
                echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';

            }
        ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="javascript/slider.js"></script>
</body>
</html>

