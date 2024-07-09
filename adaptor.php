<?PHP
#$input = $_POST['input'];
$thread = base64_encode(($_POST['thread']));
$output =  shell_exec("python ./chatbot.py $thread");
echo ($output);
?>
