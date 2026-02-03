<?php
session_start();
$uid = $_POST['uid']; // 入力されたユニークID

// DB接続
try {
    $db_name = 'marketing_prompt'; 
    $db_id   = 'root';
    $db_pw   = '';
    $db_host = 'localhost';
    $pdo = new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
} catch (PDOException $e) {
    exit('DB_Error:'.$e->getMessage());
}

// 履歴テーブル（marketing_prompt_table）に入力されたuniqueidがあるか確認
$sql = "SELECT * FROM marketing_prompt_table WHERE uniqueid = :uid LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status === false) { exit('SQLError'); }

$val = $stmt->fetch();

if ($val !== false) {
    // IDが存在すればセッションに保存してメイン画面へ
    $_SESSION['chk_ssid'] = session_id();
    $_SESSION['uid']      = $uid;
    header('Location: index.php');
} else {
    // IDがなければ戻す
    exit('Error: 該当するユニークIDが見つかりませんでした。');
}
exit();