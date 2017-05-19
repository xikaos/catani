<?php
//	STRUCTURE DATABASE MODEL TO FIT THE NEEDS OF THE
//	APPLICATION 
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

function tb_exists($conn, $table){
	$exists = mysqli_query($conn, "
		SELECT table_name
		FROM information_schema.tables 
		WHERE table_name = '${table}'");
	return $exists->num_rows > 0;
}

//TODO: PUT THESE ON NON VERSIONED FILE. TEST DATABASE MODE ON
$host = 'db';
$user = 'root';
$pass = 'noclip';
$database = 'ntasd';

$conn = db_setup($host, $user, $pass, $database);

//	CHECK IF TABLES EXIST
if(tb_exists($conn,'Pages')){
	echo('Pages exists');
} 
else {
	$pages_create = 
		"CREATE TABLE Pages (
			page_id INT NOT NULL,
			PRIMARY KEY (page_id));";
	mysqli_query($conn, $pages_create);
	echo(mysqli_error($conn));
}

if(tb_exists($conn,'Episodes')){
	echo('Episodes exist!');
} 
else {
	$create = 
		"CREATE TABLE Episodes (
		id INT NOT NULL,
		page_id INT NOT NULL,
		date VARCHAR(45) NULL,
		download VARCHAR(45) NULL,
		setlist VARCHAR(45) NULL,
		title VARCHAR(45) NULL,
		mp3 BLOB NULL,
		PRIMARY KEY (id),
		FOREIGN KEY (page_id) REFERENCES Pages(page_id));";
  mysqli_query($conn, $create);
  echo(mysqli_error($conn));
}
//var_dump($_POST);





