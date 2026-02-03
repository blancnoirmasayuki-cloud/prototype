<?php

// 1. index.phpから送られてきた値を変数へ格納
$uniqueid = $_POST['uniqueid'];
$title    = $_POST['title'];
$content  = $_POST['ai_response'];

try {
    $db_name = 'marketing_prompt';
    $db_id   = 'root';
    $db_pw   = '';
    $db_host = 'localhost';
    $pdo = new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
} catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
}

// 2. データ登録SQL作成（新しく保存するための INSERT 文）
// marketing_prompt_table の各カラムに値を流し込みます
$stmt = $pdo->prepare("INSERT INTO marketing_prompt_table (id, uniqueid, title, content, date) 
                        VALUES (NULL, :uniqueid, :title, :content, now())");

// 3. バインド変数（PHPの変数をSQLのセット場所に紐付け）
$stmt->bindValue(':uniqueid', $uniqueid, PDO::PARAM_STR);
$stmt->bindValue(':title',    $title,    PDO::PARAM_STR);
$stmt->bindValue(':content',  $content,  PDO::PARAM_STR);

// 4. SQL実行
$status = $stmt->execute();

// 5. 実行結果の確認
if ($status === false) {
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} 
// 保存に成功した場合は、下のHTMLが表示されます
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>保存完了</title>
</head>
<body style="text-align:center; padding-top:50px; font-family:sans-serif;">
    <h2 style="color: #34a853;">保存が完了しました</h2>
    <p>データベースへの登録が成功しました！</p>
    <a href="index.php" style="color: #4285f4; text-decoration: none; font-weight: bold;">← アプリに戻る</a>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>保存完了</title>
</head>
<body style="text-align:center; padding-top:50px; font-family:sans-serif;">
    <h2 style="color: #34a853;">保存が完了しました</h2>
    <a href="../index.html" style="color: #4285f4; text-decoration: none; font-weight: bold;">← アプリに戻る</a>
</body>
</html>