<?PHP
#$input = '{"user":"T01","assistant":"","user_dataSource": "Instructor Contacts"}';
$input = $_POST['thread'];

$thread = base64_encode($input);

#$test = "python3 /var/www/html/chatbot/test.py T01"+$input;
$output = shell_exec("python3 /var/www/html/chatbot/rolePlay/rolePlay.py ".$thread);
echo $output
?>
