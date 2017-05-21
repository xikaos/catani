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
$data = $_POST;
//	CHECK IF TABLES EXIST
if(tb_exists($conn,'Pages')){
	// echo('Pages exists');
	// echo("\n");
} 
else {
	$pages_create = 
		"CREATE TABLE Pages (
			page_id SMALLINT NOT NULL,
			PRIMARY KEY (page_id));";
	mysqli_query($conn, $pages_create);
	echo(mysqli_error($conn));
}

if(tb_exists($conn,'Episodes')){
	// echo('Episodes exist!');
	// echo("\n");
} 
else {
	$create = 
		"CREATE TABLE Episodes (
		id VARCHAR(45) NOT NULL,
		page_id SMALLINT NOT NULL,
		date VARCHAR(45) NULL,
		download VARCHAR(120) NULL,
		setlist VARCHAR(750) NULL,
		title VARCHAR(120) NULL,
		mp3 BLOB NULL,
		PRIMARY KEY (id),
		FOREIGN KEY (page_id) REFERENCES Pages(page_id));";
  mysqli_query($conn, $create);
  echo(mysqli_error($conn));
}


// THIS IS ~ FUCKING ~ UNSANITIZED INPUT 
$page_num = $_POST["page_num"];
$episodes =$_POST["data"];
// DON'T FUCKING PASS IT DIRECTLY TO MYSQLI_QUERY
// WITHOUT FUCKING SANITIZATION: mysqli_real_escape_string


$create_page = "INSERT INTO Pages (page_id)
								VALUES ('{$page_num}');";

mysqli_query($conn, $create_page);
echo(mysqli_error($conn));

foreach($episodes as $ep){
	$ep_id = hash('sha1', $ep["title"]);
	$date = utf8_encode($ep["date"]);
	$download = $ep["download"];
	$setlist = mysqli_real_escape_string($conn,$ep["setlist"]);
	$title = $ep["title"];
	$create_episode = 
		"INSERT INTO Episodes (id, page_id, date, download, setlist, title) VALUES ('{$ep_id}', '{$page_num}', '{$date}', '{$download}', '{$setlist}' , '{$title}');
		";
	mysqli_query($conn, $create_episode);
	echo(mysqli_error($conn));
}

//echo($create_page);
// foreach($episodes as $ep){

// }





