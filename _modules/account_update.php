<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['user_pass'])) {

	$password = $_POST['user_pass'];
	$password = mysqli_real_escape_string($db,$password);
	$user_id = $_POST['user_id'];
	
	$q = "UPDATE users SET Password=md5('$password'), SecuKy='$password' WHERE UserID='$user_id'";
	$run_q = mysqli_query($db, $q) or die('Cannot update user info! Try again..');

	if($run_q) {
		echo "User details updated successfully!";
	}
}

if(isset($_POST['user_loc']) && isset($_POST['fullname'])) {
	
	$name = $_POST['fullname'];		
	$loc = $_POST['user_loc'];
	$user_id = $_POST['user_id'];

	$q = "UPDATE users SET Fullname='$name', idfID='$loc' WHERE UserID='$user_id'";
	$run_q = mysqli_query($db, $q) or die('Cannot update user info! Try again..');

	if($run_q) {
		session_start();
		$_SESSION['fullname'] = $name;
		echo "User details updated successfully!";
	}

}
