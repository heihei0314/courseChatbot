<?php 
	if(isset($_POST['uid'])){
		$uid=$_POST['uid'];
		//echo $uid;
	}
	else{
		$uid="unknown_user";
	}
	if(isset($_POST['history'])){
		$history = $_POST['history'];
	}
	else{
		$history = json_decode('{"user":"test"}');
	}
	if(isset($_POST['datetime'])){
		$datetime = $_POST['datetime'];
	}
	else{
		$datetime = "20250206";
	}

	$filename = "history/".$uid.".json";
	
	if (file_exists($filename)){
		$file_text = file_get_contents($filename);
		
		$file_json = json_decode($file_text, true);		
		if($file_json[$datetime]){
			array_push($file_json[$datetime],$history);
		}
		else{
			$file_json[$datetime]=[$history];
		}
		$history = json_encode($file_json);
		echo $history;
	}
	else{
		$file_json[$datetime]=[$history];
		$history = json_encode($file_json);
		echo $history;
	}
	file_put_contents($filename, $history);
?>