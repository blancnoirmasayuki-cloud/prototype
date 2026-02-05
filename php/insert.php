<?php
// 1. セッション開始と共通関数の読み込み
session_start();
require_once("funcs.php");

// 2. データの受け取り
$content = $_POST["content"];
// ログインID（無い場合は仮のIDを入れます）
$login_uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : "GUEST"; 

// 3. DB接続
$pdo = db_conn();

// 4. データ登録SQL作成（流用するテーブル名とカラム名に合わせる）
// スクリーンショットのテーブル構造に合わせて、uniqueid, title, content, date に入れます
$stmt = $pdo->prepare("INSERT INTO marketing_prompt_table (uniqueid, title, content, date) 
                        VALUES (:uid, :title, :content, sysdate())");

// 5. バインド変数（型を合わせる）
$stmt->bindValue(':uid',     $login_uid,           PDO::PARAM_STR);
$stmt->bindValue(':title',   'チャットボット依頼',  PDO::PARAM_STR); // タイトルは固定で入力
$stmt->bindValue(':content', $content,             PDO::PARAM_STR);

// 6. 実行
$status = $stmt->execute();

// 7. データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    // 成功したら一覧へ
    redirect("select.php");
}
?>