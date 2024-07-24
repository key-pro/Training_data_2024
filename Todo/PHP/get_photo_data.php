<?php
    // セッションの開始
    session_start();

    // データベースの接続ファイルを読み込む
    include ('db_config.php');

    // 画像のIDを取得
    $photo_id = $_GET['photo_id'];

    // SQL文を準備
    $sql = "SELECT photo_data FROM photos WHERE id = :photo_id";

    // SQL実行準備
    $stmt = $pdo->prepare($sql);

    // 写真IDをバインド
    $stmt->bindValue(':photo_id', $photo_id, PDO::PARAM_INT);

    // SQL実行
    $stmt->execute();

    // SQL実行した結果を取得
    $photo_data = $stmt->fetchColumn();

    // バイナリーデータをセッションに保存
    $_SESSION['image_data'] = $photo_data;

    // レスポンスを返す
    echo 'Image data saved in session.';
?>