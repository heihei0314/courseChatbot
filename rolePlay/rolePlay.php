<!DOCTYPE html>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script>
		let uid='';
		let datetime='';
		let web_log_time = '';
		
		function login(user){
			uid = user;
			//console.log("here is iframe");
			datetime=new Date(Date.now()).toLocaleString();
			web_log_time=datetime;
		}
</script>
<?php 
	if(isset($_GET['uid'])){
		$uid=$_GET['uid'];
		echo "<script> login('".$uid."'); </script>";
		//echo $uid;
	}
	else{
		echo "<script> login('test'); </script>";
	}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>
	
    <title>PDEV6800Z - The Challenger</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --background-color: #f5f5f5;
            --text-color: #333;
            --message-bg-user: #e8f5fe;
            --message-bg-bot: #f0f0f0;
            --message-bg-system: #fff3cd;
            --clear-chat-color: #7f8c8d;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 550px;
            height: 100vh;
            background-color: var(--background-color);
            color: var(--text-color);
			width: 100%;
        }
        #sidebar {
            width:20%;
			max-width: 300px;
            flex-shrink: 1;
            background-color: var(--secondary-color);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            color: white;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        #chatHistory {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        #sidebar-toggle{
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #sidebar-toggle:hover {
            background-color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            #sidebar {
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                z-index: 1000;
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #sidebar-toggle {
                display: block;
            } 
            #sidebar h2 {
                padding-left: 2em;
            }

            #chat-container {
                width: 100%;
                padding-top: 70px; /* Make room for the toggle button */
            }
        }
        #chat-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            background-color: white;
			width:80%;
        }
        #chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding-left: 15px;
            padding-right: 15px;
            margin-bottom: 20px;
			width:calc(100% - 30px);
        }
        #user-input {
            display: flex;
        }
        #user-input textarea {
            flex-grow: 1;
            padding: 12px;
            font-size: 16px;
            border: 2px solid var(--primary-color);
            border-radius: 25px 0 0 25px;
            transition: all 0.3s ease;
        }
        #user-input textarea:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.5);
        }
        #user-input button, .function-chat {
            padding: 12px 20px;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        #user-input button {
            background-color: var(--primary-color);
            border-radius: 0 25px 25px 0;
        }

        #sidebar-controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: auto;
            padding-top: 20px;
        }
        .function-chat {
            background-color: var(--primary-color);
            padding: 12px 24px;
            font-weight: 500;
            border-radius: 25px;
            width: 100%;
            color: white;
        }
        .export-btn{
            background-color: var(--clear-chat-color);
        }
        .topic-btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s ease;
            border-radius: 25px;
        }
        .topic-btn:hover, .function-chat:hover, #user-input button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .topic-btn:hover, #user-input button:hover {
            background-color: var(--primary-color);
        }
        .function-chat:hover {
            background-color: #95a5a6;
        }
        .export-btn:hover {
            background-color: var(--primary-color);
        }
        .topic-btn.active {
            background-color: var(--primary-color);
            font-weight: bold;
        }
        .message {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 18px;
            max-width: 80%;
			width: fit-content;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease forwards;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .user-message {
            background-color: var(--message-bg-user);
            margin-left: auto;
            text-align: right;
            border-bottom-right-radius: 0;
        }
        .bot-message {
            background-color: var(--message-bg-bot);
            border-bottom-left-radius: 0;
        }
        .system-message {
            background-color: var(--message-bg-system);
            text-align: left;
            font-style: italic;
            min-width: calc(100% - 24px);
        }
        .feedback {
            display: flex;
            justify-content: flex-end;
            margin-top: 5px;
        }
        .feedback button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
            margin-left: 10px;
            color: #7f8c8d;
            transition: all 0.3s ease;
        }
        .feedback button:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }
        .btn-disable
        {
			cursor: not-allowed;
			pointer-events: none;
        }

        #typing-indicator {
            padding: 10px;
            display: none;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            50% { opacity: 0.5; }
        }
        #disclaimer {
            font-size: 0.8em;
            color: #888;
            text-align: center;
            margin-top: 10px;
        }
        #footer {
            font-size: 0.8em;
            color: #888;
            text-align: center;
            margin-top: auto;
            padding-top: 20px;
        }
        #footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
        #footer a:hover {
            text-decoration: underline;
        }
		.card-image{
            width: 48.5%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            margin-left: auto;
            margin-right: auto;
		}
		.icon-name{
			display:flex;
			gap:3%;
			align-items: center;
		}
    </style>
</head>
<body>
    <button id="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div id="sidebar">
        <div class="icon-name"><img src="../challenger.jpg" alt="The Challenger" class="card-image">
		    <h2 style="text-align: left;">The Challenger</h2>
        </div>
		<div style="margin-bottom:1em;">Your Student Role-play Partner</div>
		<div id="chatHistory">
			
		</div>
		
        <div id="sidebar-controls">
            <button id="clear-chat" class="function-chat"><i class="fas fa-plus"></i> New Challenge</button>
            <button id="export-chat" class="function-chat export-btn"><i class="fas fa-download"></i> Export Current Chat</button>
            <button id="export-all-chat" class="function-chat export-btn"><i class="fas fa-file-export"></i> Export All Chats</button>
        </div>
		
        <div id="footer">
            Created by CEI<br>
            <a href="https://forms.gle/7ba14yMCo5eSdD8F8" target="_blank" id="feedback-link">Feedback Survey</a> | 
            <a href="mailto:ericyeung@ust.hk?subject=Error Report on AI Tool The Challenger" id="error-report-link">Report an Error</a>
        </div>
    </div>
    <div id="chat-container">
        <div id="chat-messages"></div>
        <div id="typing-indicator">The Challenger is typing...</div>
        <div id="user-input">
            <textarea id="user-message" placeholder="Type your message here..."></textarea>
            <button id="send-btn">Send</button>
        </div>
        <div id="disclaimer">
            The Challenger is an AI assistant in training and may make mistakes. 
            Please consult your instructor if you're unsure about any information provided.
        </div>
    </div>
    <script>
		const apiURL = '';
		let action = 'setChallengerLog';
		let messageCount = 0;
		let token=0;
		sendLog(messageCount,token, action);
		
		var open_new_chat = 1;
		var history_json;
		
		const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-message');
        const sendBtn = document.getElementById('send-btn');
        //const topicBtns = document.querySelectorAll('.topic-btn');
        const feedbackBtns = document.querySelectorAll('.feedbackbutton');
        const clearChatBtn = document.getElementById('clear-chat');
        const exportChatBtn = document.getElementById('export-chat');        
        const exportAllChatBtn = document.getElementById('export-all-chat');
        const typingIndicator = document.getElementById('typing-indicator');
        var thread = {
            "user":[],
            "assistant":[]
        } 
        // Add welcome message
        addMessage('system', "<strong>Welcome to 'The Challenger' !</strong> 🎭<br>In this roleplay, you'll practice handling challenging questions from students in your tutorials. I'll take on the role of a student with specific characteristics, and you'll respond as their teaching assistant.<br>We'll continue until we reach a natural stopping point, or you explicitly type '<strong>stop</strong>'. Once the session ends, I'll provide you with constructive feedback on your responses, including insights from both a student’s and an educator’s perspective.<p><strong>Before we begin, please provide:</strong><br>📚 Your Teaching Topic: [The specific concept you'll explain]<br>👥 Student Level: [e.g., Year 1 Undergraduate]<br>🎭 Choose Your Challenger:<br>A. The Skeptic – Questions everything, needs evidence and real-world relevance.<br>B. The Confused-but-Eager – Trying hard but missing key concepts, wants to understand but struggles.<br>C. The Debater – Challenges assumptions, pushes for deeper analysis.</p><p><strong>Please respond with:</strong><br>Topic: [your topic]<br>Student Level: [your student level]<br>Challenger: The Skeptic / The Confused-but-Eager / The Debater (Choose one)");

		//console.log(uid, datetime);
		getHistory();
		
		function sendLog(msgCount, token, action){
			var query={
				'action': action,
				'uid': uid,
				'activity':'The Challenger',
				'msgCount':msgCount,
				'token':token,
				'datetime':web_log_time
				//'datetime':new Date(Date.now()).toLocaleString()
			};
			console.log(query);
			$.ajax({
				type: "GET",
				url: apiURL,
				data: query,
				dataType: "JSON",			
				success: function(response){
					console.log(response);
				},
				error: function(response){
					console.log(response);
				}
			});
		}
		
		function createLog(role,msg){
			msg = msg.replace(/"/g, '&quot;');
			const history = '{"'+role+'":"'+msg+'"}';
			json_history = JSON.parse(history);
			var data = {
				uid: uid,
				history: json_history,
				datetime: datetime
			};
			$.post("https://pdev6800z-ai.ust.hk/chatbot/rolePlay/writeHistory.php", data);
			getHistory();
		}
		function getHistory(){
			const xmlhttp = new XMLHttpRequest();        
			xmlhttp.open("GET", "history/"+uid+".json", false);
			xmlhttp.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
			//xmlhttp.setRequestHeader('Pragma', 'no-cache');		
			//xmlhttp.setRequestHeader('Expires', '0');		            
			xmlhttp.onload = function() {
				if (this.readyState == 4 && this.status == 200) {
					history_json = JSON.parse(this.responseText); 
					//console.log(history_json); 
					if(open_new_chat==1||open_new_chat==3){
						setHistory();
					}					
					if(open_new_chat==2){
						setHistory();
						setChat();
					}
					open_new_chat=0;
				}
			};
			xmlhttp.send();
		}
		function setHistory(){
			var chatHistoryText = "";
			
			for (let chat in history_json){
				//console.log(chat);
				chatHistoryText += '<button class="topic-btn" data-topic="'+chat+'" id="'+chat+'" >'+chat+'</button>';
			}
			document.getElementById("chatHistory").innerHTML = chatHistoryText;
			topicBtnsEvent();
		}		
		function setChat(){
			if(document.getElementById(datetime)){
				document.getElementById(datetime).classList.add('active');
			}
			for(var chat_object in history_json[datetime]){
				var msg_object = history_json[datetime][chat_object];
				//console.log(history_json[datetime][chat_object]);
				var role = Object.keys(msg_object)[0];
				var new_msg = msg_object[role];
				//console.log(role,new_msg);
				addMessage(role, new_msg);
				if(role=='user'){
					thread.user.push(new_msg);
				}
				else{
					thread.assistant.push(new_msg);
				}
			}
		}

		function clear(){
			chatMessages.innerHTML = '';
			thread = {
				"user":[],
				"assistant":[]
			} 			
		}

        function sendMessage() {
            userInput.disabled=true;
			const topicBtns = document.querySelectorAll('.topic-btn');
			topicBtns.forEach(b => b.classList.add('btn-disable'));	
			clearChatBtn.classList.add('btn-disable');
            const message = userInput.value.trim();
            if (message) {
				msg = reformMessage(message);
				addMessage('user', msg);
                userInput.value = '';
                generateResponse(message);
                //simulateBotResponse(message,true)
				createLog("user",msg);
				messageCount++;
				action = 'updateLog';
            }
        }

        function reformMessage(message) {
            // Basic Markdown-style formatting
            message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); // Bold
            message = message.replace(/\*(.*?)\*/g, '<em>$1</em>'); // Italic
            message = message.replace(/\n/g, '<br>'); // Line breaks
			
            // Handle lists
            message = message.replace(/^\s*-\s(.+)$/gm, '<ul><li>$1</li></ul>');
            message = message.replace(/^\s*\d+\.\s(.+)$/gm, '<ol><li>$1</li></ol>');
            message = message.replace(/<\/([uo])l>\s*<\1l>/g, '');
			return message;
		}
		
		function addMessage(sender, message) {
			const messageElement = document.createElement('div');
            messageElement.classList.add('message', `${sender}-message`);
            messageElement.innerHTML = message;
            chatMessages.appendChild(messageElement);
			messageElement.scrollIntoView(false);	
        }

        function addFeedback(messageElement) {
            const feedbackElement = document.createElement('div');
            feedbackElement.classList.add('feedback');
            feedbackElement.innerHTML = `
                <button class="thumbs-up"><i class="fas fa-thumbs-up"></i></button>
                <button class="thumbs-down"><i class="fas fa-thumbs-down"></i></button>
            `;
            messageElement.appendChild(feedbackElement);
            feedbackElement.addEventListener('click', (e) => {
                feedbackElement.querySelector('.thumbs-up').disabled=true
                feedbackElement.querySelector('.thumbs-down').disabled=true
                // Handle positive feedback
                e.target.style.color = 'var(--primary-color)';
                //console.log(e.target);
            });
        }

        function simulateBotResponse(message, addFeedbackFlag) {
            setTimeout(() => {
                typingIndicator.style.display = 'none';
                
                addMessage('bot', message);
                if (addFeedbackFlag) {
                    addFeedback(chatMessages.lastElementChild);
                }
                userInput.disabled=false;
				clearChatBtn.classList.remove('btn-disable');
				
				const topicBtns = document.querySelectorAll('.topic-btn');
				topicBtns.forEach(b => b.classList.remove('btn-disable'));
				
            }, 1000); // Simulating a delay in response
        }

        function generateResponse (message) {
            typingIndicator.style.display = 'block';
            thread.user.push(message);
            const xhttp = new XMLHttpRequest();
            xhttp.open("POST", "https://pdev6800z-ai.ust.hk/chatbot/rolePlay/rolePlay_adaptor.php");
			
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onload = function() {
                response = this.responseText.split(/({token})/)
				//console.log(response);
				bot_response = response[0];
				thread.assistant.push(bot_response);
                simulateBotResponse(reformMessage(bot_response),true);
				createLog("bot",reformMessage(bot_response));
                //console.log(thread)
				response[2] = response[2].replace(/\n/g, ''); // Line breaks
				token += parseInt(response[2]);
				sendLog(messageCount,token, action);
            }
			console.log(thread);
            xhttp.send("thread="+JSON.stringify(thread));
			//return true
        };
		
		sendBtn.addEventListener('click', sendMessage);
        
		userInput.addEventListener('keydown', function(e) {
            if (e.key == 'Enter' && !e.shiftKey) {
                sendMessage();
            }
        });
		
        clearChatBtn.addEventListener('click', () => {
			datetime=new Date(Date.now()).toLocaleString();
			clear();
			open_new_chat = 3;
			addMessage('system', 'Chat cleared. To start over, please respond with:<br>Topic: [your topic]<br>Student Level: [your student level]<br>Challenger: The Skeptic / The Confused-but-Eager / The Debater (Choose one)');
			
			const topicBtns = document.querySelectorAll('.topic-btn');
			topicBtns.forEach(b => b.classList.remove('active'));
			
        });

        exportChatBtn.addEventListener('click', () => {
            generateHTML(datetime);
			
        });

        exportAllChatBtn.addEventListener('click', () => {
            generateHTML();
        });

        function downloadFile(fileName,chatName){
            //console.log(chatName);
			let element = document.createElement('a');
            element.setAttribute('href',fileName);
			if (typeof chatName !== 'undefined'){
				chatName = chatName.replace(/[,:]/g, '');
				element.setAttribute('download',uid+'_'+chatName+'.html');
				//console.log(chatName);
            }
			else{
				element.setAttribute('download',uid+'.html');
			}
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        }

		function generateHTML(chatName){
			const xmlhttp = new XMLHttpRequest();  
			var url = 'https://pdev6800z-ai.ust.hk/chatbot/rolePlay/writeHTML.php?uid='+uid;
			if (typeof chatName !== 'undefined'){
				url += "&chatname="+chatName;
			}
			xmlhttp.open("GET", url, false);  
			xmlhttp.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
			xmlhttp.setRequestHeader('Pragma', 'no-cache');		
			xmlhttp.setRequestHeader('Expires', '0');					
			xmlhttp.onload = function() {
				if (this.readyState == 4 && this.status == 200) {
					downloadFile(this.responseText, chatName);
				}
			};
			xmlhttp.send();
		}

		function topicBtnsEvent(){
			const topicBtns = document.querySelectorAll('.topic-btn');
			if (open_new_chat==3){
				userInput.disabled=true;
				clearChatBtn.classList.add('btn-disable');
				topicBtns.forEach(b => b.classList.add('btn-disable'));
			}
			topicBtns.forEach(btn => {
				btn.addEventListener('click', () => {
					open_new_chat = 2;
					topicBtns.forEach(b => b.classList.remove('active'));
					btn.classList.add('active');
					clear();
					addMessage('system', `Switched to chat: ${btn.innerHTML}`);
					datetime = btn.innerHTML;
					getHistory();
					//console.log(history_json[new_chat]);
				});
			});
		}

        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const icon = sidebarToggle.querySelector('i');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            if(icon.classList.contains('fa-bars')){
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-close');
            }else{
                icon.classList.remove('fa-close');
                icon.classList.add('fa-bars');
            }
        });

        // Close sidebar when a topic is selected (on mobile)
        document.querySelectorAll('.topic-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('open');
                    icon.classList.remove('fa-close');
                    icon.classList.add('fa-bars');
                }
            });
        });

        // Close sidebar when clicking outside of it (on mobile)
        document.addEventListener('click', (event) => {
            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && event.target !== sidebarToggle) {
                sidebar.classList.remove('open');
                icon.classList.remove('fa-close');
                icon.classList.add('fa-bars');
            }
        });
    </script>
</body>
</html>
