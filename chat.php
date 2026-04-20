<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Discussion - NYMATE</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Container khusus ruang chat agar bisa scroll di tengah saja */
        .chat-room-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #F9F3D9;
        }

        /* Header Chat Room */
        .chat-room-header {
            background-color: #A8D8EA;
            padding: 45px 20px 15px;
            display: flex;
            align-items: center;
            border-radius: 0 0 30px 30px;
            color: white;
            z-index: 10;
        }

        .back-link {
            text-decoration: none;
            color: white;
            font-size: 24px;
            margin-right: 15px;
        }

        .header-info h2 {
            font-size: 18px;
            margin: 0;
        }

        .header-info p {
            font-size: 11px;
            margin: 0;
            opacity: 0.8;
        }

        /* Area Pesan */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Bubble Chat Umum */
        .message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 20px;
            font-size: 14px;
            line-height: 1.4;
            position: relative;
        }

        .message .sender-name {
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }

        /* Pesan Orang Lain */
        .message.received {
            align-self: flex-start;
            background-color: white;
            color: #333;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .message.received .sender-name { color: #81D4FA; }

        /* Pesan Kamu (Me) */
        .message.sent {
            align-self: flex-end;
            background-color: #FFD54F; /* Kuning NYMATE */
            color: #333;
            border-bottom-right-radius: 5px;
            box-shadow: 0 2px 5px rgba(255, 213, 79, 0.3);
        }
        .message.sent .sender-name { display: none; }

        .message-time {
            font-size: 9px;
            display: block;
            text-align: right;
            margin-top: 5px;
            opacity: 0.6;
        }

        /* Input Area di Bawah */
        .chat-input-bar {
            background-color: white;
            padding: 15px 20px 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 30px 30px 0 0;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.03);
        }

        .input-box {
            flex: 1;
            background-color: #F0F0F0;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
        }

        .send-btn {
            background-color: #81D4FA;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <div class="mobile-container">
        <div class="chat-room-container">
            
            <!-- Header -->
            <header class="chat-room-header">
                <a href="chat.php" class="back-link">←</a>
                <div class="header-info">
                    <h2>General Discussion 📢</h2>
                    <p>128 Nymates Online</p>
                </div>
    </header>
<div class="messages-area" id="chatBox"></div>
    </div>

           <!-- Input Bar -->
<div class="chat-input-bar">
    <button onclick="openFile()" style="background:none; border:none; font-size:20px; color:#bbb;">📎</button>
    
    <input type="file" id="fileInput" accept="image/*" style="display:none;">
    
 <input type="text" class="input-box" id="chatInput" placeholder="Type a message...">
<button class="send-btn" onclick="sendMessage()">➤</button>
</div>

    
    


<script>

let myId = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ?>;
let lastId = 0;

// =======================
// LOAD CHAT (AJAX)
// =======================
function loadMessages(){

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "ambil_chat.php?last_id=" + lastId, true);

    xhr.onload = function(){

        if(this.status === 200){

            try{
                let data = JSON.parse(this.responseText);
                let container = document.getElementById("chatBox");

                data.forEach(msg => {
                   let isMe = String(msg.sender_id) === String(myId);

let div = document.createElement("div");
div.className = "message " + (isMe ? "sent" : "received");

div.innerHTML = `
    ${!isMe ? `<span class="sender-name">${msg.nama}</span>` : ""}
    ${msg.message}
    <span class="message-time">${msg.created_at.slice(11,16)}</span>
`;
                    container.appendChild(div);
                    lastId = msg.id;
                });

                container.scrollTop = container.scrollHeight;

            }catch(e){
                console.error("JSON ERROR:", this.responseText);
            }
        }
    };

    xhr.send();
}


// =======================
// SEND MESSAGE
// =======================
function sendMessage(){

    let input = document.getElementById("chatInput");
    let text = input.value.trim();

    if(text === "") return;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "api_chat.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.send("message=" + encodeURIComponent(text));

    input.value = "";
}

// =======================
// INIT
// =======================
document.addEventListener("DOMContentLoaded", () => {

    document.querySelector(".send-btn").onclick = sendMessage;

    document.getElementById("chatInput").addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        sendMessage();
    }
});

    setInterval(loadMessages, 1500);
    loadMessages();
});



// =======================
// EVENT BUTTON
// =======================
document.querySelector(".send-btn").onclick = sendMessage;

// ENTER = SEND
document.querySelector(".input-box").addEventListener("keypress", e=>{
    if(e.key === "Enter"){
        sendMessage();
    }
});

// =======================
// AUTO REFRESH CHAT
// =======================
setInterval(loadMessages, 2000);

// INIT
loadMessages();

</script>
</body>
</html>