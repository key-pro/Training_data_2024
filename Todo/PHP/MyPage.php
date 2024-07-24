<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">My Page</h2>
        <?php
            // セッションの開始
            session_start();

            // ユーザーIDがセットされていない場合は、ログインページにリダイレクト
            if (!isset($_SESSION['user_id'])) {

                // ユーザーIDがセットされていない場合
                // ログインページにリダイレクト
                header('Location: login.php');

                // スクリプトを終了
                exit;

            }

            // データベース接続ファイルを読み込む
            include ('db_config.php');

            // 写真データの取得
            // SQL文を設定
            $sql = "SELECT * FROM photos WHERE user_id = :user_id";

            // SQL文を準備
            $stmt = $pdo->prepare($sql);

            // パラメータを設定
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

            // SQL文を実行
            $stmt->execute();

            // 結果を取得
            $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 写真データがある場合は、セッションに画像データを保存
            if (!empty($photos)) {

                // 写真データがある場合
                // セッションに画像データを保存
                $_SESSION['image_data'] = $photos[0]['photo_data'];
            }

            // ユーザーの所属部署情報の取得
            // SQL文を設定
            $department_sql = "SELECT users.user_id, organization.id, organization.department_name
                                FROM users
                                LEFT JOIN organization ON users.department_id = organization.id
                                WHERE users.user_id = :user_id";

            // SQL文を準備
            $department_stmt = $pdo->prepare($department_sql);

            // SQL文を実行
            $department_stmt->execute([':user_id' => $_SESSION['user_id']]);

            // 結果を取得
            $department_results = $department_stmt->fetchAll(PDO::FETCH_ASSOC);

            // セッションに部署IDを保存
            $_SESSION['department_id'] = $department_results[0]['id'];

            // セッションに部署名を保存
            $_SESSION['department_name'] = $department_results[0]['department_name'];

        ?>

        <div class="alert alert-primary" role="alert">
            // ユーザー名を表示
            ユーザー名: <?= $_SESSION["user_name"] ?><br>
            // 部署名を表示
            所属部署名: <?= $_SESSION["department_name"] ?>
        </div>

        <div class="mb-4">
            <a href="view.php" class="btn btn-primary me-2">Todoリストへ</a>
            <a href="password_change.php" class="btn btn-secondary me-2">パスワードリセット</a>
            <a href="organization.php" class="btn btn-info me-2">所属部関連</a>
            <a href="logout.php" class="btn btn-warning me-2">ログアウト</a>
            <a href="user_delete.php" class="btn btn-danger">ユーザー削除</a>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-<?= (strpos($_GET['message'], '成功') !== false) ? 'success' : 'danger'; ?>" role="alert">
                <?= htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <h3 class="mb-4">写真をアップロード</h3>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="photo" class="form-label fs-4">写真を選択してください</label>
                <input type="file" class="form-control form-control-lg" id="photo" name="photo" required>
            </div>
            <button type="submit" class="btn btn-success btn-lg">アップロード</button>
        </form>

        <h3 class="mt-5 mb-3">アップロードした写真</h3>
        <div class="row" id="photo-gallery">
            <?php foreach ($photos as $photo): ?>
                <div class="col-md-4 mb-4">
                    <div class="card photo-thumb" data-photo-id="<?= $photo['id']; ?>">
                        <img src="data:image/jpeg;base64,<?= base64_encode($photo['photo_data']); ?>" class="card-img-top"
                            alt="<?= htmlspecialchars($photo['photo_name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($photo['photo_name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="photoModalLabel">選択した写真</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="selected-photo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOMが読み込まれたら
        document.addEventListener("DOMContentLoaded", function () {
            // 写真カードを取得
            const photoThumbs = document.querySelectorAll('.photo-thumb');

            // 写真カードをループ
            photoThumbs.forEach(thumb => {

                // クリックイベントを設定
                thumb.addEventListener('click', function () {

                    // 写真IDを取得
                    const photoId = this.getAttribute('data-photo-id');

                    // XMLHttpRequestを生成
                    const xhr = new XMLHttpRequest();

                    // リクエストを設定
                    xhr.open('GET', 'get_photo_data.php?photo_id=' + photoId, true);

                     // リクエストが完了したら
                    xhr.onload = function () {

                        // ステータスが200の場合
                        if (xhr.status === 200) {

                             // 選択した写真を表示
                            document.getElementById('selected-photo').innerHTML = xhr.responseText;

                            // モーダルを表示
                            new bootstrap.Modal(document.getElementById('photoModal')).show();

                        // ステータスが200以外の場合
                        } else {

                            // エラーメッセージを出力
                            console.error('Error:', xhr.statusText);

                        }
                    };

                    // リクエストを送信
                    xhr.send();
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</html>