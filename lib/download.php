<?php 
//This is nasty. I don't know how to do it on docker.
ini_set('memory_limit', '521M');

//Code reuse, except I pasted it here direclty instead of including.
function db_setup($host, $user, $pass, $database){
	$conn = mysqli_connect($host, $user, $pass);
	if ($conn){
		if(mysqli_select_db($conn, $database)){
			return $conn;
		}
		else {
			mysqli_query($conn, "CREATE DATABASE ${database}");	
			mysqli_select_db($conn, $database);
			return $conn;
		}
	} 
	else {
		echo('Could not connect to database: '. mysql_error());
	}
}

//Unsanitized input
$page_num = $_POST['page_num'];

$host = 'db';
$user = 'root';
$pass = 'noclip';
$database = 'ntasd';

$conn = db_setup($host, $user, $pass, $database);
echo(mysqli_error($conn));

//Get all download links from refered page
$sqs = mysqli_query($conn, "SELECT download FROM Episodes WHERE page_id = '${page_num}';");
echo(mysqli_error($conn));

$episodes = $sqs->fetch_all();

//TMP: Testing how it goes on the first episode
$ep = $episodes[0][0];

$ch = curl_init("${ep}");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_NOBODY, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);

file_put_contents("/var/www/html/0.mp3", curl_exec($ch));
echo(curl_error($ch));
