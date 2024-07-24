<?php
    // セッションの開始
    session_start();

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
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>部署名編集メニュー</title>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-outline-primary text-black">
                        <h4 class="mb-2">部署名編集メニュー</h4>
                    </div>
                    <?php
                        // データベース接続ファイルを読み込む
                        include ('db_config.php');

                        if (isset($_POST['department_name'])) {

                            // POSTリクエストから送られてきたデータを取得
                            $department_id = $_POST['department_id'];
                            $department_name = $_POST['department_name'];

                            // 部署を取得
                            $sql = "SELECT * FROM organization";

                            // SQL文の実行準備
                            $stmt = $pdo->prepare($sql);

                            // SQL文の実行
                            $stmt->execute();

                            // 結果を取得
                            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // 既存の部署が登録されないか確認する
                            $existing = false;
                            if (isset($departments)) {
                                foreach ($departments as $department) {
                                    if ($department['department_name'] == $_POST['department_name']) {
                                        echo '<div class="alert alert-danger">部署名が既に存在します。</div>';
                                        // 既存の部署が登録された場合は、フラグを立てる
                                        $existing = true;
                                    }
                                }
                            }

                            // 既存の部署が登録されなかった場合、新しい部署を追加する
                            if ($existing == false && $_POST['department_name'] != "未所属") {

                                // データベースの部署名を更新
                                $sql = "UPDATE organization SET department_name = :department_name WHERE id = :id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $department_id);
                                $stmt->bindParam(':department_name', $department_name);
                                $stmt->execute();
                                $sql = "UPDATE todo SET department_id = :department_id WHERE department_id = :department_id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':department_id', $department_id);
                                $stmt->execute();
                                // 更新が成功した場合の処理
                                if ($stmt) {
                                    echo '<div class="alert alert-success">部署が更新されました。</div>';
                                } else {
                                    echo '<div class="alert alert-danger">部署の更新に失敗しました。</div>';
                                }
                            }else{
                                // 未所属は変更不可メッセージ表示
                                echo '<div class="alert alert-danger">未所属は変更できません。</div>';
                            }
                        }
                    ?>
                    <div class="card-body">
                        <div class="alert alert-primary" role="alert">
                            ユーザー名:<?= $_SESSION["user_name"] ?><br>
                            所属部署名:<?= $_SESSION["department_name"] ?>
                        </div>
                        <a href="MyPage.php" class="btn btn-primary btnshine mb-4">マイページへ戻る</a>
                        <form action="organization_edit.php" method="POST">
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
                                <label for="department_id">変更したい部署を選択して下さい</label>
                                <select class="form-select mb-4" id="department_id" name="department_id">
                                    <?php foreach ($departments as $department) { ?>
                                        <option value="<?= $department['id'] ?>">
                                            <?= $department['department_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="department_name">変更する部署名を入力して下さい。</label>
                                <input type="text" class="form-control mb-4" id="department_name" name="department_name" placeholder="部署名">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btnshine mb-4">変更</button>
                        </form>
                        <a href="organization.php" class="btn btn btn-secondary btnshine mb-4">キャンセル</a>
                        <a href="organization_add.php" class="btn btn btn-info btnshine mb-4">新規登録</a>
                        <a href="organization_delete.php" class="btn btn btn-danger btnshine mb-4">削除</a>
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