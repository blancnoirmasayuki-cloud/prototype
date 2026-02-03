// Firebaseの初期化設定
const firebaseConfig = {

};

// Firebaseを初期化
firebase.initializeApp(firebaseConfig);
const auth = firebase.auth(); 
const db = firebase.firestore(); 


// 認証と画面表示の切り替え
auth.onAuthStateChanged(function(user) {
    if (user) {
        const uid = user.uid; 
        console.log("現在ログイン中のUID:", uid);

        $("#auth-container").hide();
        $("#app-content").show();
        
    } else {
        console.log("ユーザーはログアウトしています");
        $("#auth-container").show();
        $("#app-content").hide();
    }
});

// ユーザー登録
$("#register-btn").on("click", function() {
    const email = $("#email").val();
    const password = $("#password").val();
    $("#auth-error-message").text(""); 

    auth.createUserWithEmailAndPassword(email, password)
        .then((userCredential) => {
            console.log("新規登録成功:", userCredential.user);
        })
        .catch((error) => {
            $("#auth-error-message").text("登録失敗: " + error.message);
        });
});


// ログイン処理
$("#login-btn").on("click", function() {
    const email = $("#email").val();
    const password = $("#password").val();
    $("#auth-error-message").text(""); 

    auth.signInWithEmailAndPassword(email, password)
        .then((userCredential) => {
            console.log("ログイン成功:", userCredential.user);
        })
        .catch((error) => {
            $("#auth-error-message").text("ログイン失敗: " + error.message);
        });
});

// プロンプト生成をクリックする
$("#makeprompt").on("click", async function () {
  // --- 1. 入力値の取得 ---
  const name = $(".servicename").val();
  const what = $(".servicewhat").val();
  const point1 = $(".servicepoint1").val();
  const point2 = $(".servicepoint2").val();
  const point3 = $(".servicepoint3").val();
  const mission = $(".brandmission").val();
  const tagline = $(".brandtagline").val();
  const coremessage = $(".brandcoremessage").val();
  const tone = $(".brandtone").val();
  const standard = $(".brandstandard").val();
  const ng = $(".brandng").val();
  const industry = $(".targetindustry").val();
  const scall = $(".targetscall").val();
  const branch = $(".targetbranch").val();
  const job = $(".targetjob").val();
  const position = $(".targetposition").val();
  const pain = $(".targetpain").val();

  // ---  Geminiに渡す時の変数 ---
  const promptInstruction = `
    あなたは、BtoBマーケティングに精通したマーケターです。
    下記の情報を厳守しつつ、マーケティングの戦略を統一感を維持しつつ提案してください。
    文章が長くならないように最小限の文章にしてください。

    #サービスについて
    --サービス名：${name}
    --サービス概要：${what}
    --強み：${point1}, ${point2}, ${point3}

    #ブランドについて
    --ブランドミッション：${mission}
    --ブランドタグライン：${tagline}
    --コアメッセージ：${coremessage}
    --トーン：${tone}
    --ブランド統一基準：${standard}
    --NGワード：${ng}

    #ターゲットについて
    --業界：${industry}
    --社員数：${scall}
    --部署：${branch}
    --職種：${job}
    --役職：${position}
    --ペインポイント：${pain}
  `;

  // ---  GeminiAPIの接続 ---
  let aiResponse = ""; 

  try {
    const url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=";
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        contents: [{ parts: [{ text: promptInstruction }] }]
      })
    });

    if (!response.ok) {
        throw new Error(`APIリクエスト失敗: ${response.status}`);
    }

    const data = await response.json();
    aiResponse = data.candidates[0].content.parts[0].text;
    console.log("Geminiの回答取得成功");

  } catch (error) {
    console.error("Gemini APIエラー:", error);
    alert("失敗したのでコンソールを確認してください");
    return; 
  }

  // ---  Firestoreへの保存 ---
  const dataToSave = {
    uid: auth.currentUser.uid,
    servicename: name,
    servicewhat: what,
    servicepoint1: point1,
    servicepoint2: point2,
    servicepoint3: point3,
    brandmission: mission,
    brandtagline: tagline,
    brandcoremessage: coremessage,
    brandtone: tone,
    brandstandard: standard,
    brandng: ng,
    targetindustry: industry,
    targetscall: scall,
    targetbranch: branch,
    targetjob: job,
    targetposition: position,
    targetpain: pain,
    ai_response: aiResponse,
    timestamp: firebase.firestore.FieldValue.serverTimestamp()
  };

  db.collection("prompts").add(dataToSave)
    .then(function (docRef) {
      console.log("Firestoreに保存完了 ID: ", docRef.id);
    })
    .catch(function(error) {
      console.error("Firestore保存エラー: ", error);
    });

  //Geminiの回答をSQLに保存
    $("#hidden-ai-data").val(aiResponse); 
    $("#hidden-title").val(name);
    $("#hidden-uniqueid").val(auth.currentUser.uid);

  // ---  HTMLへの表示 ---
  const html = `
    <li class="response-item">
      <h3>🤖 AIマーケターからの提案</h3>
      <div style="white-space: pre-wrap; background: #f4f7f6; padding: 15px; border-radius: 8px; border-left: 5px solid #4285f4;">${aiResponse}</div>
      <hr>
      <details>
        <summary>入力したプロンプト内容を確認</summary>
        <p><small>サービス名: ${name} / ターゲット: ${industry} ${position}</small></p>
      </details>
    </li>
  `;
  $("#list").append(html);

  // 非表示にしていた保存ボタン付きのフォームを表示
  $("#save-form").fadeIn();
});

// プロンプトのみリセット
$("#clearprompt").on("click", function () {
  $("#list").empty();
});

// プロンプトをコピー
$("#copyprompt").on("click", function() {
  const prompttext = $("#list").text(); 
  navigator.clipboard.writeText(prompttext).then(() => {
      alert("コピーしました！");
  });
});

// 全てリセット
$("#clearall").on("click", function () {
  $("#list").empty();
  $("input, textarea").val('');
});

// 自分の履歴を見る
$("#view-history").on("click", function() {
    const user = firebase.auth().currentUser;
    if (user) {
        window.location.href = "select.php?uid=" + user.uid;
    } else {
        alert("ログインが必要です");
    }
});