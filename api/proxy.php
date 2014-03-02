<?php
//ini_set('display_errors', 'On');

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

function findexts ($filename) 
 { 
 $filename = strtolower($filename) ; 
 $exts = split("[/\\.]", $filename) ; 
 $n = count($exts)-1; 
 $exts = $exts[$n]; 
 return $exts; 
 } 


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['url']) && isset($_GET['filepath'])) {

$url = $_GET['url'];
$filepath = $_GET['filepath'];


$returned_content = get_data($url);
// echo $doc['a']->attr('src', "999");
// $doc['a']->attr('href', "qwert88888qwert");

  $dom = new DomDocument();
  $dom->loadHTML($returned_content);
  $relative_address = 'shared/' . $filepath;
  if (!is_dir($relative_address))
  {
    mkdir($relative_address, 0755, true);
    $fp = fopen($relative_address.'/host.txt', 'w');
    $data = $url;
    fwrite($fp, $data);
    fclose($fp);
  }
  $refs = $dom->getElementsByTagName('script');
  foreach ($refs as $book) {
  	$file = $book->getAttribute('src');
   $dir_name = $relative_address.'/'.sha1(sha1(time() . rand()) . $file) . '.' . findexts($file);
       $fp = fopen($dir_name, 'w');
       
       $data = get_data($url . $file);
       fwrite($fp, $data);
       fclose($fp);
     $book->setAttribute('src', 'api/'.$dir_name);
  }

  $refs = $dom->getElementsByTagName('link');
  foreach ($refs as $book) {
    $file = $book->getAttribute('href');

    $dir_name = $relative_address.'/'.sha1(sha1(time() . rand()) . $file) . '.' . findexts($file);
       $fp = fopen($dir_name, 'w');
       
       $data = get_data($url . $file);
       fwrite($fp, $data);
       fclose($fp);
       $book->setAttribute('href', 'api/'.$dir_name);
  }

  $refs = $dom->getElementsByTagName('img');
  foreach ($refs as $book) {
    $file = $book->getAttribute('src');

    $dir_name = $relative_address.'/'.sha1(sha1(time() . rand()) . $file) . '.' . findexts($file);
    $fp = fopen($dir_name, 'w');
       
    $data = get_data($url . $file);
    fwrite($fp, $data);
    fclose($fp);
    $book->setAttribute('src', 'api/'.$dir_name);
 }

$newHTML = $dom->saveHTML();
  
print($newHTML);
exit();
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");
?>