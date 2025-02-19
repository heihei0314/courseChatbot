<?php 
	if(isset($_GET['uid'])){
		$uid=$_GET['uid'];
	}
	else{
		$uid="unknown_user";
	}
	//echo $uid;
	if(isset($_GET['chatname'])){
		$chatname = $_GET['chatname'];
	}
	//echo $chatname;

	$filename = "history/".$uid.".json";
	$HTMLname = "history/".$uid.".html";	
	if (file_exists($filename)){
		$file_text = file_get_contents($filename);
		if(isset($_GET['chatname'])){
			$file_json = json_decode($file_text, true);	
			$file_text = '{"'.$chatname.'":'.json_encode($file_json[$chatname], true)."}";	
			
		}
			
	}
	$htmlContent ="<html>";
	$htmlContent =$htmlContent."<head><style>
			:root {
				--primary-color: #3498db;
				--secondary-color: #2c3e50;
				--background-color: #f5f5f5;
				--text-color: #333;
				--message-bg-user: #e8f5fe;
				--message-bg-bot: #f0f0f0;
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
			.topic-btn {
				display: block;
				width: 100%;
				padding: 12px;
				margin-bottom: 10px;
				background-color: rgba(255, 255, 255, 0.1);
				color: white;
				border: none;
				cursor: pointer;
				text-align: center;
				transition: all 0.3s ease;
				border-radius: 25px;
			}
			.topic-btn:hover {
				transform: translateY(-2px);
				box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
		</style></head>";
		$htmlContent =$htmlContent.'<body>
			<div id="sidebar">
			<h2 style="text-align: center;">The Challenger History</h2>
				<div id="chatHistory">
					
				</div>
			</div>
		';
		$htmlContent =$htmlContent.'<div id="chat-container">
			<div id="chat-messages">
				
			</div>
		';
		$htmlContent = $htmlContent."<script>";
		
		$htmlContent =$htmlContent.'const history_json = '.$file_text;
		
		$htmlContent =$htmlContent.="
			const chatMessages = document.getElementById('chat-messages');
			var datetime= Object.keys(history_json)[0];
			setHistory();
			setChat();
			function setHistory(){
				var chatHistoryText = '';			
				for (let chat in history_json){
					chatHistoryText += '<button class=topic-btn data-topic='+chat+' id='+chat+' >'+chat+'</button>';
				}
				document.getElementById('chatHistory').innerHTML = chatHistoryText;
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

				}
			}
			function addMessage(sender, message) {
				const messageElement = document.createElement('div');
				messageElement.classList.add('message', sender+'-message');
				messageElement.innerHTML = message;
				chatMessages.appendChild(messageElement);
				chatMessages.scrollTop = chatMessages.scrollHeight;
			}
			function topicBtnsEvent(){
				const topicBtns = document.querySelectorAll('.topic-btn');
				topicBtns.forEach(btn => {
					btn.addEventListener('click', () => {
						topicBtns.forEach(b => b.classList.remove('active'));
						btn.classList.add('active');
						chatMessages.innerHTML = '';
						datetime = btn.innerHTML;
						addMessage('bot', `Switched to chat:`+btn.innerHTML);
						setChat()
					});
				});
			}
		</script>";

	$htmlContent =$htmlContent."</body></html>";
	//echo $htmlContent;
	file_put_contents($HTMLname, $htmlContent);
	echo $HTMLname;
?>