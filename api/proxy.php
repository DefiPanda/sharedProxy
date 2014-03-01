<?php
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['url'])) {

$url = $_GET['url'];

$returned_content = get_data($url);

// $regex = '/(script|link)*(href|src)[^ ]*/';

// preg_match_all($regex, $returned_content, $result, PREG_PATTERN_ORDER);
// $A = $result[0];

// foreach($A as $B)
// {
//    print($B);
//    print('<br>');
// }

$A = array("/cs.unc.edu/cms/unctheme/static/xdvprint.css", '/cs.unc.edu/cms/portal_css/Oasis%20Onetheme/people-cachekey4934.css');

for($i = 0; $i < count($A); $i++)
{ 
     $data = get_data($A[$i]);
     $dir_name = '.';
     $dirs = explode('/', $A[$i]);
     for($j = 0; $j < count($dirs) - 1; $j++){
       if($dirs[$j] != ""){
       	 $dir_name = $dir_name . '/' . $dirs[$j];
       	 if (!is_dir($dir_name))
	     {
	       mkdir($dir_name, 0755, true);
	     }
       }
     }
     $filename = $dir_name . '/' . $dirs[count($dirs) - 1];
     $fp = fopen($filename, 'w');
	 fwrite($fp, $data);
	 fclose($fp);
}

print($returned_content);
exit();
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");
?>