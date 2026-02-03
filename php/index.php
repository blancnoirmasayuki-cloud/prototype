<?php
session_start();
// セッションIDがない人は強制的にログイン画面へ
if (!isset($_SESSION['chk_ssid']) || $_SESSION['chk_ssid'] != session_id()) {
    header('Location: login.php');
    exit();
}
// ログイン中のIDを変数に入れておく
$login_uid = $_SESSION['uid'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>マーケティングプロンプトメーカー</title> <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- <div id="auth-container">
        <div>ユーザー登録 / ログイン</div>
        <input type="email" id="email" placeholder="メールアドレス" required><br>
        <input type="password" id="password" placeholder="パスワード" required><br><br>
        
        <button id="register-btn">新規登録</button>
        <button id="login-btn">ログイン</button>
        <p id="auth-error-message" style="color: red; margin-top: 10px;"></p>
    </div>

    <div id="app-content" style="display: none;"> -->
        
        <h1 id="app-title">マーケティングプロンプトメーカー📢</h1> <img src="../img/Gemini_Generated_Image_xrbtegxrbtegxrbt.png" class="marketingimg">
        
        <div id="input-area">
          <div class="service">
            <p class="about-service">📱 サービスについて</p>
            <input class="servicename" type="text" placeholder="サービス名を入力">
            <input class="servicewhat" type="text" placeholder="何をするサービスか入力"> <input class="servicewhat_value" type="text" placeholder="提供できる価値を入力"> <input class="servicepoint1" type="text" placeholder="サービスの強み1を入力">
            <input class="servicepoint2" type="text" placeholder="サービスの強み2を入力">
            <input class="servicepoint3" type="text" placeholder="サービスの強み3を入力">
          </div>

          <div class="brand">
            <p class="about-brand">🧭 ブランドについて</p>
            <input class="brandmission" type="text" placeholder="ブランドのミッションを入力">
            <input class="brandtagline" type="text" placeholder="ブランドのタグラインを入力">
            <input class="brandcoremessage" type="text" placeholder="ブランドのコアメッセージを入力">
            <input class="brandtone" type="text" placeholder="ブランドトーンを入力">
            <input class="brandstandard" type="text" placeholder="ブランドの統一基準を入力">
            <input class="brandng" type="text" placeholder="NGワードを入力">
          </div>

          <div class="target">
            <p class="about-target">👤 ターゲットについて</p>
            <input class="targetindustry" type="text" placeholder="業種を入力">
            <input class="targetscall" type="text" placeholder="社員数を入力">
            <input class="targetbranch" type="text" placeholder="部署を入力">
            <input class="targetjob" type="text" placeholder="職種を入力">
            <input class="targetposition" type="text" placeholder="役職を入力">
            <input class="targetpain" type="text" placeholder="ペインポイントを入力">
          </div>
        </div>

        <div class="button-area">
          <button id="makeprompt">プロンプト生成</button>
          <button id="clearprompt">プロンプトをリセット</button>
          <button id="copyprompt">プロンプトをコピー</button>
          <button id="clearall">全てリセット</button>
          <button id="view-history" onclick="location.href='select.php'">自分の履歴を見る</button></div>
        </div>

        <form action="write.php" method="post" id="save-form" style="display:none; text-align: center; margin-top: 20px;">
            <input type="hidden" name="ai_response" id="hidden-ai-data">
            <input type="hidden" name="title" id="hidden-title">
            <input type="hidden" name="uniqueid" id="hidden-uniqueid">
            <button type="submit" style="background-color: #34a853; color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                📥 このプロンプトをSQLに保存する
            </button>
        </form>

        <div class="prompt-area">
          <p>ここにプロンプトが出力されます📝</p>
          <ul id="list"></ul>
        </div>


    <script src="../js/jquery-2.1.3.min.js"></script>

    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>

    <script src="../js/script.js"></script>
</body>
</html>