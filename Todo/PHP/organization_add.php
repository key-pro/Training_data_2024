<?php
    // コメント: セッションの開始
    session_start();

    // コメント: ログインチェック
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
    <title>部署名登録メニュー</title>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-outline-primary text-black">
                        <h4 class="mb-2">部署名新規登録メニュー</h4>
                    </div>
                    <?php
                        // コメント: データベース接続ファイルを読み込む
                        include ('db_config.php');

                        if (isset($_POST['department_name'])) {

                            // コメント: 部署を取得
                            $sql = "SELECT * FROM organization";

                            // コメント: SQL文の実行準備
                            $stmt = $pdo->prepare($sql);

                            // コメント: SQL文の実行
                            $stmt->execute();

                            // コメント: 結果を取得
                            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);


                            // コメント: 既存の部署が登録されないか確認する
                            $existing = false;
                            if(isset($departments)){
                                foreach ($departments as $department) {
                                    if ($department['department_name'] == $_POST['department_name']) {
                                        echo '<div class="alert alert-danger">部署名が既に存在します。</div>';
                                        // コメント: 既存の部署が登録された場合は、フラグを立てる
                                        $existing = true;
                                    }
                                }
                            }

                            // コメント: 既存の部署が登録されなかった場合、新しい部署を追加する
                            if ($existing == false) {
                                $department_id = count($departments) + 1;

                                // コメント: POSTリクエストから送られてきたデータを取得
                                $department_name = $_POST['department_name'];

                                // コメント: データベースに新しい部署を追加
                                $sql = "INSERT INTO organization (id, department_name) VALUES (:id, :department_name)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':id', $department_id);
                                $stmt->bindParam(':department_name', $department_name);
                                $stmt->execute();

                                // コメント: 追加が成功した場合の処理
                                if ($stmt) {
                                    echo '<div class="alert alert-success">部署が追加されました。</div>';
                                } else {
                                    echo '<div class="alert alert-danger">部署の追加に失敗しました。</div>';
                                }
                            }
                        }
                    ?>
                    <div class="card-body">
                        <div class="alert alert-primary" role="alert">
                            ユーザー名:<?= $_SESSION["user_name"] ?><br>
                            所属部署名:<?= $_SESSION["department_name"] ?>
                        </div>
                        <a href="MyPage.php" class="btn btn-primary btnshine mb-4">マイページへ戻る</a>
                        <form action="organization_add.php" method="POST">
                            <div class="form-group mb-4">
                                <label for="department_name font-weight-bold">新規登録する部署名を入力して下さい。</label>
                                <input type="text" class="form-control" id="department_name" name="department_name" placeholder="部署名">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btnshine">新規登録</button>
                        </form>
                        <a href="organization.php" class="btn btn btn-secondary btnshine mb-4">キャンセル</a>
                        <a href="organization_edit.php" class="btn btn btn-warning btnshine mb-4">部署の変更はこちら</a>
                        <a href="organization_delete.php" class="btn btn btn-danger btnshine mb-4">部署の削除はこちら</a>
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