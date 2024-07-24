<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">
    <link rel="stylesheet" href="css/register.css">
    <title>会員登録</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-8">
                <div class="registration-container">
                    <h1>会員登録</h1>
                    <form action="register.php" method="POST">
                        <div class="form-group">
                            <label for="user_name">ユーザー名</label>
                            <input type="text" id="user_name" name="user_name" class="form-control" maxlength=50" required><br>
                        </div>
                        <div class="form-group">
                            <label for="user_password">パスワード</label>
                            <input type="password" id="user_password" name="user_password" class="form-control" maxlength="255" required><br>
                        </div>
                        <div class="form-group">
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
                            <label for="department_id font-weight-bold">所属部署</label>
                            <select class="form-select mb-4" id="user_organization" name="user_organization">
                                <?php foreach ($departments as $department) { ?>
                                    <option value="<?= $department['id'] ?>">
                                        <?= $department['department_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" name="submit" class="btn btnshine btn-primary">登録</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>
</html>