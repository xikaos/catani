<?php
$page_num = $_GET['page_num'];
//Initialize curl object
$curl = curl_init();
//Set curl url
curl_setopt($curl, CURLOPT_URL, "http://podcast.hernancattaneo.com/page/{$page_num}");
//
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl,CURLOPT_HEADER, false); 

$page = curl_exec($curl);

echo $page;