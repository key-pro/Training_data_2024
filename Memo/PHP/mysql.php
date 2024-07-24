<?php
include('db_config.php');

$action = $_POST['action'];

if ($action === 'add') {
    $memo = $_POST['memo'];
    $sql = "INSERT INTO notes (memo) VALUES (:memo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['memo' => $memo]);

    $message = "メモが追加されました";
    header("Location: index.php?message=" . urlencode($message));
    exit();
} elseif ($action === 'delete') {
    $sql = "DELETE FROM notes";
    $pdo->exec($sql);
    
    $message = "すべてのメモが削除されました";
    header("Location: index.php?message=" . urlencode($message));
    exit();
}
?>