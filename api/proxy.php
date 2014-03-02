<?php
ini_set('display_errors', 'On');

// INITIALIZE IT
// phpQuery::newDocumentHTML($markup);
// phpQuery::newDocumentXML();
// phpQuery::newDocumentFileXHTML('test.html');
// phpQuery::newDocumentFilePHP('test.php');
// phpQuery::newDocument('test.xml', 'application/rss+xml');
// this one defaults to text/html in utf8


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


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['url']) && isset($_GET['filepath'])) {

$url = $_GET['url'];
$filepath = $_GET['filepath'];

$returned_content = get_data($url);
// echo $doc['a']->attr('src', "999");
// $doc['a']->attr('href', "qwert88888qwert");

  $dom = new DomDocument();
  $dom->loadHTML($returned_content);
  $A = array();
  $refs = $dom->getElementsByTagName('script');
  foreach ($refs as $book) {
  	array_push($A, $book->getAttribute('src'));
  }

   $refs = $dom->getElementsByTagName('link');
  foreach ($refs as $book) {
  	array_push($A, $book->getAttribute('href'));
  }

  $relative_address = '../shared/' . $filepath;
  if (!is_dir($relative_address))
  {
    mkdir($relative_address, 0755, true);
  }
  

// $regex = '/(script|link)*(href|src)[^ ]*/';

// preg_match_all($regex, $returned_content, $result, PREG_PATTERN_ORDER);
// $A = $result[0];

// foreach($A as $B)
// {
//    print($B);
//    print('<br>');
// }


for($i = 0; $i < count($A); $i++)
{ 
     $data = get_data($A[$i]);
     
     $dir_name = $relative_address;
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
     print($filename);
     print('<br>');
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