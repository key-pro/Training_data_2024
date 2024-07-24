<?php
    // データベース接続情報
    $host = 'localhost';
    $db = 'kensyu';
    $user = 'root';
    $pass = '2023-0323';
    $charset = 'utf8mb4';

    // PDOオブジェクトの生成
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [

        // エラーモードを例外に設定
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

        // デフォルトのフェッチモードを連想配列に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // プリペアードステートメントのエミュレーションを無効に設定
        PDO::ATTR_EMULATE_PREPARES   => false,

    ];

    try {

        //　データベース接続
        $pdo = new PDO($dsn, $user, $pass, $options);

    } catch (\PDOException $e) {

        // エラーが発生した場合はエラーメッセージを表示
        throw new \PDOException($e->getMessage(), (int)$e->getCode());

    }
?>
