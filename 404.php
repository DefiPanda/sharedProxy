<?php
$ch = curl_init();
$fields = http_build_query($_POST);
$URI = $_SERVER['REQUEST_URI'];
$pos1 = strpos($URI, '/');
$pos2 = strpos($URI, '/', $pos1 + strlen('/'));
$tokens = explode('file=', $_SERVER['HTTP_REFERER']);

if(count($tokens) == 2){
	$filename = trim($tokens[1]);
	$filepath = 'api/shared/'.$filename.'/host.txt';
	$prefix = file_get_contents($filepath);
	$postfix = substr($URI, $pos2);
	$url = $prefix.$postfix;
	curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	$response = curl_exec($ch);
	header('Content-type: application/json');
	print(json_encode($response));
	curl_close ($ch);
}
?>