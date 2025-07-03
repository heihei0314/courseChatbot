<?PHP
#$input = '{"user":"T01","assistant":"","user_dataSource": "Instructor Contacts"}';

$outputDir = __DIR__ . '/voice/';
$outputFile = $outputDir . 'output_' . time() . '.mp3';
$outputUrl = 'voice/' . basename($outputFile); // Relative URL for the audio file


$input = $_POST['thread'];

$thread = base64_encode($input);
#$test = "python3 /var/www/html/chatbot/test.py T01"+$input;
$output = shell_exec("python3 /var/www/html/chatbot/rolePlay/rolePlay.py ".$thread." ".$outputFile);
#echo $output;

$response = [
    "output" => $output ?: "",
    "audio" => $outputUrl
];

echo json_encode($response);
?>
