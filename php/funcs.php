<?php
// 1. DB接続関数
function db_conn() {
    try {
        $db_name = "marketing_prompt"; // あなたの環境に合わせてください
        $db_id   = "root";
        $db_pw   = "";
        $db_host = "localhost";
        return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:'.$e->getMessage());
    }
}

// 2. SQLエラー関数
function sql_error($stmt) {
    $error = $stmt->errorInfo();
    exit("SQLError:" . print_r($error, true));
}

// 3. リダイレクト関数
function redirect($file_name) {
    header("Location: ".$file_name);
    exit();
}

// 4. XSS対策関数（select.phpなどで使用）
function ssch($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}