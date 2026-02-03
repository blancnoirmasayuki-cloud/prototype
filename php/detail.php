<?php
// 1. GETで送られてきた id を取得
$id = $_GET['id'];

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

// 3. SQL作成（指定されたIDのデータ1件だけを取得）
$stmt = $pdo->prepare("SELECT * FROM marketing_prompt_table WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// 4. データ表示準備
if ($status === false) {
    exit('SQLError');
} else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // 1件だけ取得
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>プロンプト詳細</title>
</head>
<body style="font-family: sans-serif; background: #fdfdfd; padding: 20px; line-height: 1.6;">
    <header style="margin-bottom: 20px;">
        <a href="javascript:history.back()" style="color:#4285f4; text-decoration:none;">← 履歴一覧に戻る</a>
        <h1>📄 プロンプト詳細</h1>
    </header>

    <main style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 30px; max-width: 800px;">
        <h2 style="border-bottom: 2px solid #34a853; padding-bottom: 10px; color: #333;">
            <?= htmlspecialchars($result['title'], ENT_QUOTES) ?>
        </h2>
        
        <p style="color: #666; font-size: 0.9em;">作成日: <?= $result['date'] ?></p>

        <div style="margin-top: 20px;">
            <strong>📝 生成された内容:</strong>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd; white-space: pre-wrap; margin-top: 10px;">
                <?= htmlspecialchars($result['content'], ENT_QUOTES) ?>
            </div>
        </div>
    </main>
</body>
</html>