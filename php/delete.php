<?php
// 1. GETデータ（削除対象のIDと本人確認用のUID）取得
$id  = $_GET['id'];
$uid = $_GET['uid']; 

// 2. DB接続
try {
    $db_name = 'marketing_prompt';
    $db_id   = 'root';
    $db_pw   = '';
    $db_host = 'localhost';
    $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
} catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
}

// 3. 削除SQL作成（本人かつ対象IDのみを削除）
$stmt = $pdo->prepare('DELETE FROM marketing_prompt_table WHERE id = :id AND uniqueid = :uid;');
$stmt->bindValue(':id',  $id,  PDO::PARAM_INT);
$stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
$status = $stmt->execute();

// 4. 実行後の処理
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    // 削除成功後、自分のUIDをURLに付けて一覧画面に戻る
    // これにより、戻った時に「ログイン情報がありません」エラーになるのを防ぎます
    header('Location: select.php?uid=' . $uid);
    exit();
}