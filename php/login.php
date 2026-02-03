<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="login-body">

    <div id="auth-container">
        <form action="login_act.php" method="POST">
            <fieldset>
                <legend>ユニークIDでログイン</legend>
                <p style="font-size: 14px; color: #666; margin-bottom: 10px;">
                    以前の履歴にある uniqueid を入力してください
                </p>
                <label>
                    ID入力: <input type="text" name="uid" placeholder="例: 42rk2G..." required />
                </label>
                <br><br>
                <button type="submit">ログイン</button>
            </fieldset>
        </form>
    </div>

</body>
</html>