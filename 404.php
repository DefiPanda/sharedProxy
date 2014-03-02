<?php
$ch = curl_init();
$fields = http_build_query($_POST);

curl_setopt($ch,CURLOPT_URL,'https://wwwx.cs.unc.edu/~duozhao/projects/candyGame/connector.php');

  curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
  curl_setopt($ch,CURLOPT_TIMEOUT, 20);

$response = curl_exec($ch);
header('Content-type: application/json');
print(json_encode($response));
curl_close ($ch);
?>