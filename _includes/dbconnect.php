<?php
	
	define('DB_SERVER', 'localhost');
   	define('DB_USERNAME', 'Aarunya');
   	define('DB_PASSWORD', 'soma1234');
   	define('DB_DATABASE', 'baydb');

   	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   	if(!$db){
	    die("Connection failed: ".mysqli_connect_error());
	}