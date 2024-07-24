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
                        <h4 class="mb-2">現在所属部署確認および変更メニュー</h4>
                    </div>
                    <div class="card-body">
                        <?php
                            // データベース接続ファイルを読み込む
                            include ('db_config.php');

                            if (isset($_POST['department_id']) && isset($_SESSION['user_id'])) {
                                $department_id = $_POST['department_id'];
                                $user_id = $_SESSION['user_id'];
                                // ユーザーの部署情報を更新するクエリを実行
                                $sql = "UPDATE users SET department_id = :department_id WHERE user_id = :user_id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':department_id', $department_id);
                                $stmt->bindParam(':user_id', $user_id);
                                $stmt->execute();
                                $sql = "UPDATE todo SET department_id = :department_id";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':department_id', $department_id);
                                $stmt->execute();
                                // 追加が成功した場合の処理
                                if ($stmt) {

                                    //所属部署の変更完了したら、MyPage.phpにリダイレクト
                                    header("Location: MyPage.php");

                                } else {

                                    // 失敗した場合の処理
                                    echo '<div class="alert alert-danger">所属部署が失敗しました。</div>';

                                }
                            }
                        ?>
                        <div class="alert alert-primary" role="alert">
                            ユーザー名:<?= $_SESSION["user_name"] ?><br>
                            所属部署名:<?= $_SESSION["department_name"] ?>
                        </div>
                        <a href="MyPage.php" class="btn btn-primary btnshine mb-4">マイページへ戻る</a>
                        <form action="organization.php" method="POST">
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
                                <label for="department_id font-weight-bold">所属部署変更する場合は下記のプルダウンから選択後変更ボタンをクリックして下さい。</label>
                                <select class="form-select mb-4" id="department_id" name="department_id">
                                    <?php foreach ($departments as $department ) { ?>
                                        <option value="<?= $department['id'] ?>">
                                            <?= $department['department_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button class="btn btn-outline-primary btnshine mb-4">所属部署変更</button>
                        </form>
                        <a href="organization_add.php" class="btn btn btn-info btnshine mb-4">所属登録</a>
                        <a href="organization_edit.php" class="btn btn btn-warning btnshine mb-4">部署変更</a>
                        <a href="organization_delete.php" class="btn btn btn-danger btnshine mb-4">部署削除</a>
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