<?php
session_start();
// ログインチェック
if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()) {
    exit('ログインしてください');
}
$login_uid = $_SESSION['uid'];

// 1. DB接続
try {
    $db_name = 'marketing_prompt'; 
    $db_id   = 'root';
    $db_pw   = '';
    $db_host = 'localhost';
    $pdo = new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
} catch (PDOException $e) {
    exit('DB_Error:'.$e->getMessage());
}

// 2. SQL作成（自分のuniqueidだけを抽出）
$sql = "SELECT * FROM marketing_prompt_table WHERE uniqueid = :uid ORDER BY date DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uid', $login_uid, PDO::PARAM_STR);
$status = $stmt->execute();

// 3. データ表示
$view = "";
if ($status === false) {
    exit('SQLError');
} else {
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $view .= '<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px; background:white; border-radius:8px;">';
        $view .= '<p><strong>タイトル:</strong> ' . htmlspecialchars($result['title']) . '</p>';
        $view .= '<p><strong>内容:</strong> ' . nl2br(htmlspecialchars($result['content'])) . '</p>';
        $view .= '<p style="font-size:12px; color:#999;">日付: ' . $result['date'] . '</p>';
        $view .= '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>自分の履歴</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="display:block; padding:20px;">
    <h1><?php echo $login_uid; ?>の履歴</h1>
    <a href="index.php">← 戻る</a>
    <hr>
    <div id="history-list">
        <?php echo $view; ?>
    </div>

<hr style="margin:50px 0;">
<section style="padding: 20px; background: #f0f4f8; border-radius: 10px; font-family: sans-serif;">
    <h2 style="color: #002d62;">【新機能】チャットからの承認依頼一覧</h2>
    <?php
    // SQLを既存テーブルのカラム名（date）に合わせます。
    // WHERE句を外して、まずは全件（GUESTのものも）表示されるようにします。
    $stmt2 = $pdo->prepare("SELECT * FROM marketing_prompt_table ORDER BY date DESC");
    $status2 = $stmt2->execute();

    if ($status2 === false) {
        // SQLが失敗した場合はエラー内容を表示して原因を特定します
        $error = $stmt2->errorInfo();
        echo "SQLエラー: " . htmlspecialchars($error[2]);
    } else {
        $count = 0;
        while ($res = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $count++;
            // statusカラムがないため、見た目だけのラベルをつけます
            $st_text = "📩 承認待ち"; 
            
            echo '<div style="border-bottom:1px solid #ddd; padding:10px; margin-bottom:10px; background:white; border-radius:5px;">';
            // indate ではなく date を使用
            echo '<p style="margin:0; font-size:14px;"><strong>日時：</strong>' . htmlspecialchars($res["date"]) . ' ｜ ' . $st_text . '</p>';
            echo '<p style="margin:10px 0 0 0; line-height:1.5;">' . nl2br(htmlspecialchars($res["content"])) . '</p>';
            echo '</div>';
        }

        if ($count === 0) {
            echo "<p>まだデータがありません。チャットから送信してみてください。</p>";
        }
    }
    ?>
</section>

</body>
</html>