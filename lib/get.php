<?php
$page_num = $_GET['page_num'];
$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, "http://podcast.hernancattaneo.com/page/{$page_num}");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl,CURLOPT_HEADER, false); 

//header("Content-Type: plain/text");
$page = curl_exec($curl);

if(!$page){
	echo($curl_errors);
} else {
	curl_close($curl);
	return $page;
}