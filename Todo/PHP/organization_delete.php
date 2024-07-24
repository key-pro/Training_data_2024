<?php
    // セッションの開始
    session_start();

    // ユーザーIDがセットされていない場合は、ログインページにリダイレクト
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>所属部署</title>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-outline-primary text-black">
                        <h4 class="mb-2">現在所属部署確認および削除メニュー</h4>
                    </div>
                    <div class="card-body">
                        <?php
                            // データベース接続ファイルを読み込む
                            include ('db_config.php');

                            // 部署IDがPOSTリクエストで送信された場合の処理
                            if (isset($_POST['department_id'])) {

                                // POSTリクエストから送られてきたデータを取得
                                $department_id = $_POST['department_id'];

                                // 選択された部署が未所属でない場合の処理
                                if($_POST['department_id'] != "1"){
                                    // 削除が成功した場合の処理
                                    echo '<div class="alert alert-success">部署が削除されました。</div>';
                                    // 他のユーザーも該当している部署が削除された場合、未所属に変更する
                                    if ($_SESSION['department_id'] == $department_id) {

                                        // ユーザーの部署IDを未所属に変更
                                        // SQLセット
                                        $user_sql = "UPDATE users SET department_id = 1 WHERE department_id = :department_id";

                                        // ユーザーの部署IDを未所属に変更するSQL文の準備
                                        $user_stmt = $pdo->prepare($user_sql);

                                        // ユーザーの部署IDをバインド
                                        $user_stmt->bindParam(':department_id', $department_id);

                                        // ユーザーの部署IDを未所属に変更するSQL文を実行
                                        $user_stmt->execute();

                                        // エラーハンドリングを追加
                                        if (!$user_stmt) {
                                            // ユーザーの部署IDの変更に失敗した場合の処理
                                            echo '<div class="alert alert-danger">ユーザーの部署IDの変更に失敗しました。</div>';
                                        }

                                        // TODOの部署IDを未所属に変更
                                        $todo_sql = "UPDATE todo SET department_id = 1 WHERE department_id = :department_id";

                                        // TODOの部署IDを未所属に変更するSQL文の準備
                                        $todo_stmt = $pdo->prepare($todo_sql);

                                        // エラーハンドリングを追加
                                        if (!$todo_stmt) {
                                            // TODOの部署IDの変更に失敗した場合の処理
                                            echo '<div class="alert alert-danger">TODOの部署IDの変更に失敗しました。</div>';
                                        }

                                        // 部署IDをバインド
                                        $todo_stmt->bindParam(':department_id', $department_id);

                                        // TODOの部署IDを未所属に変更するSQL文を実行
                                        $todo_stmt->execute();

                                        // 部署削除のSQL文
                                        // SQL文を修正して、複数のクエリを実行できるようにする
                                        $stmt = $pdo->prepare("DELETE FROM organization WHERE id = ?");

                                        // SQL実行し結果を受け取る
                                        $stmt->execute([$department_id]);

                                        // SQL実行結果の確認
                                        if ($stmt) {

                                            //実行が正常に
                                            echo '<div class="alert alert-success">未所属に変更されました。</div>';

                                            //セッションの部署IDと部署名を更新
                                            $_SESSION['department_id'] = 1;
                                            $_SESSION['department_name'] = "未所属";

                                        // 削除処理が失敗した場合の処理
                                        } else {

                                            // 削除処理が失敗した場合の処理
                                            echo '<div class="alert alert-danger">部署の変更に失敗しました。</div>';
                                        }
                                    }

                                //全ての処理が失敗した際にエラー処理
                                } else {
                                    // 削除処理が失敗した場合の処理
                                    echo '<div class="alert alert-danger">部署の削除に失敗しました。</div>';
                                }

                            // 未所属を削除しようとした場合の処理
                            } else {
                                // 未所属は変更不可メッセージ表示
                                echo '<div class="alert alert-danger">未所属は変更できません。</div>';
                            }
                        ?>
                        <div class="alert alert-primary" role="alert">
                            ユーザー名:<?= $_SESSION["user_name"] ?><br>
                            所属部署名:<?= $_SESSION["department_name"] ?>
                        </div>
                        <a href="MyPage.php" class="btn btn-primary btnshine mb-4">マイページへ戻る</a>
                        <form action="organization_delete.php" method="POST">
                            <?php
                                // データベース接続ファイルを読み込む
                                include ('db_config.php');

                                // 部署を取得
                                $sql = "SELECT * FROM organization";

                                // SQL文の実行準備
                                $stmt = $pdo->prepare($sql);

                                // SQL文の実行
                                $stmt->execute();

                                // 結果を取得
                                $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <div class="form-group mb-4">
                                <label
                                    for="department_id font-weight-bold">部署の削除メニュー</label>
                                <select class="form-select mb-4" id="department_id" name="department_id">
                                    <?php foreach ($departments as $department) { ?>
                                        <option value="<?= $department['id'] ?>">
                                            <?= $department['department_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger btnshine mb-4">部署削除</button><br>
                        </form>
                        <a href="organization.php" class="btn btn btn-secondary btnshine mb-4">キャンセル</a>
                        <a href="organization_add.php" class="btn btn btn-info btnshine mb-4">部署登録</a>
                        <a href="organization_edit.php" class="btn btn btn-warning btnshine mb-4">部署変更</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</body>
</html>