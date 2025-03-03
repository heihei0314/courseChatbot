<!DOCTYPE html>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
		apiURL = '';
		let uid='';
		let action='setLog';
		let datetime='';
		function login(user){
			uid = user;
			console.log("here is iframe");
		}
		
		function createLog(topic,msgCount,token, action){
			var query={
				'action': action,
				'uid': uid,
				'activity':topic,
				'msgCount':msgCount,
				'token':token,
				'datetime':datetime
				//'datetime':new Date(Date.now()).toLocaleString()
			};
			console.log(msgCount); 
			setLog(query);
		}
		function setLog(query){
			console.log(query);
			$.ajax({
				type: "GET",
				url: apiURL,
				data: query,
				dataType: "JSON",			
				success: function(response){
					console.log(response);
					datetime = response.datetime;
				}
			});
		}
</script>
<?php 
	if(isset($_GET['uid'])){
		$uid=$_GET['uid'];
		echo "<script> login('".$uid."'); </script>";
		//echo $uid;
	}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDEV6800Z Administrative Assistant - The Secretary</title>
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
        #user-input input {
            flex-grow: 1;
            padding: 12px;
            font-size: 16px;
            border: 2px solid var(--primary-color);
            border-radius: 25px 0 0 25px;
            transition: all 0.3s ease;
        }
        #user-input input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.5);
        }
        #user-input button, #clear-chat {
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
        #clear-chat {
            background-color: var(--clear-chat-color);
            margin-top: 20px;
            border-radius: 25px;
            width: 100%;
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
        .topic-btn:hover, #clear-chat:hover, #user-input button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .topic-btn:hover, #user-input button:hover {
            background-color: var(--primary-color);
        }
        #clear-chat:hover {
            background-color: #95a5a6;
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
            text-align: center;
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
        <div class="icon-name"><img src="The Secretary.jpg" alt="The Secretary" class="card-image">
		<h2 style="text-align: left;">The Secretary</h2></div>
		<div style="margin-bottom:1em;">Your Personalized Administrative Assistant for PDEV6800Z</div>
        <button class="topic-btn" data-topic="Instructor Contacts">Instructor Contacts</button>
        <button class="topic-btn" data-topic="Administrative Issues and Policies">Administrative Issues</button>
        <button class="topic-btn" data-topic="Class Schedule">Class Schedule</button>
        <button class="topic-btn" data-topic="Course Content and Structure">Course Structure</button>
        <button class="topic-btn" data-topic="Assessment and Grading">Assessments</button>
        <button class="topic-btn" data-topic="Gamification">Gamification</button>
        <button id="clear-chat">Clear Chat</button>
        <div id="footer">
            Created by CEI<br>
            <a href="https://forms.gle/7ba14yMCo5eSdD8F8" target="_blank" id="feedback-link">Feedback Survey</a> | 
            <a href="mailto:ericyeung@ust.hk?subject=Error Report on AI Tool The Secretary" id="error-report-link">Report an Error</a>
        </div>
    </div>
    <div id="chat-container">
        <div id="chat-messages"></div>
        <div id="typing-indicator">The Secretary is typing...</div>
        <div id="user-input">
            <input type="text" disabled id="user-message" placeholder="Type your message here...">
            <button id="send-btn">Send</button>
        </div>
        <div id="disclaimer">
            The Secretary is an AI assistant in training and may make mistakes. 
            Please consult your instructor if you're unsure about any information provided.
        </div>
    </div>

    <script>
        let currentTopic = '';
        let messageCount = 0;
		let token=0;
		
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-message');
        const sendBtn = document.getElementById('send-btn');
        const topicBtns = document.querySelectorAll('.topic-btn');
        const feedbackBtns = document.querySelectorAll('.feedbackbutton');
        const clearChatBtn = document.getElementById('clear-chat');
        const typingIndicator = document.getElementById('typing-indicator');
        var thread = {
            "user":[],
            "assistant":[],
            "user_dataSource":""
        } 
        // Add welcome message
        addMessage('system', "Hello, Sherlock! I'm the Secretary, here to help with PDEV6800Z administrative matters, assessment and grading policies, course structure and tips on gamification. For detailed course content and micro-teaching assistance, please consult <a target='_blank' href='https://poe.com/24F_Mr_Watson' > Mr. Watson </a>. How may I assist you today?");

        const presetMessages = {
            'Instructor Contacts': "Certainly, I can help you with instructor contacts. First, could you tell me which section you're in (T01-T02)?",
            'Class Schedule': "I'd be happy to help with your class schedule. To provide accurate information, could you please tell me which section you're in (T01-T02)?",
            'Administrative Issues and Policies': "I can assist you with administrative issues. What specific aspect would you like to know about?",
            'Course Content and Structure': "I can provide general information about the course structure, including the pre-class tasks, in-class sessions, and post-class challenges for each module. I can tell you what you need to do before each module as well. What specific aspect of the course structure would you like to know about?",
            'Assessment and Grading': "I can help you understand the assessment structure for PDEV6800Z. What specific aspect of assessments are you interested in?",
            'Gamification': "PDEV6800Z uses gamification to enhance learning. What would you like to know about our gamification system?"
        };

        topicBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                userInput.disabled = false;
				
				
                const newTopic = btn.dataset.topic;
                if (newTopic !== currentTopic) {
                    currentTopic = newTopic;
                    topicBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    addMessage('system', `Switched to topic: ${btn.innerHTML}`);
                    if (presetMessages[currentTopic]) {
                        addMessage('bot',presetMessages[currentTopic]);
                        thread.user_dataSource = currentTopic
                    }
                    
					action = 'setLog';
					messageCount = 0; // Reset message count when switching topics
					token = 0;
					createLog(newTopic,messageCount,token, action);
                }
            });
        });

        userInput.addEventListener('keydown', function(e) {
            if (e.key == 'Enter' && !e.shiftKey) {
                sendMessage();
            }
        });

        clearChatBtn.addEventListener('click', () => {
            chatMessages.innerHTML = '';
            addMessage('system', 'Chat cleared. How may I assist you today?');
			
			currentTopic = '';
            messageCount = 0;
			action = 'setLog';
			token = 0;
			
            thread = {
                "user":[],
                "assistant":[],
                "user_dataSource":""
            } 
            userInput.disabled=true;
            topicBtns.forEach(b => b.classList.remove('active'));
        });



        function sendMessage() {
            userInput.disabled=true;
            const message = userInput.value.trim();
            if (message) {
                if (!currentTopic) {
                    addMessage('system', 'Please select a topic before asking a question.');
                    userInput.disabled=false;
                    return;
                }
                addMessage('user', message);
                userInput.value = '';
                generateResponse(message);
                //simulateBotResponse(message,true)
                messageCount++;
				action = 'updateLog';
            }
        }

        function addMessage(sender, message) {
            // Basic Markdown-style formatting
            message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); // Bold
            message = message.replace(/\*(.*?)\*/g, '<em>$1</em>'); // Italic
            message = message.replace(/\n/g, '<br>'); // Line breaks

            // Handle lists
            message = message.replace(/^\s*-\s(.+)$/gm, '<ul><li>$1</li></ul>');
            message = message.replace(/^\s*\d+\.\s(.+)$/gm, '<ol><li>$1</li></ol>');
            message = message.replace(/<\/([uo])l>\s*<\1l>/g, '');

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
                console.log(e.target);
            });
        }

        function simulateBotResponse(message, addFeedbackFlag) {
            setTimeout(() => {
                typingIndicator.style.display = 'none';
                
                addMessage('bot', message);
                if (addFeedbackFlag && messageCount > 0) {
                    addFeedback(chatMessages.lastElementChild);
                }
                userInput.disabled=false;
            }, 1000); // Simulating a delay in response
        }

        function generateResponse (message) {
            typingIndicator.style.display = 'block';
            thread.user.push(message);
            const xhttp = new XMLHttpRequest();
            xhttp.open("POST", "https://pdev6800z-ai.ust.hk/chatbot/adaptor.php");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onload = function() {
				console.log(this.responseText)
				response = this.responseText.split(/({token})/)
				
				//bot_response = this.responseText;
				bot_response = response[0];
                thread.assistant.push(bot_response);
                simulateBotResponse(bot_response,true);
				
				token += parseInt(response[2]);
				console.log(token);
				
				createLog(currentTopic,messageCount,token, action);
            }
            xhttp.send("thread="+JSON.stringify(thread));
        return true
        };

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
