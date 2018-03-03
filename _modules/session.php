<?php
require_once '_includes/bootstrap.php'; 

	session_start();

	// SESSION CHECK ~~~~~~~~~~~
	$session_username = "";
	$session_role = "";

	if(isset($_SESSION['id'])) {
		$session_username = $_SESSION['username'];
		$session_fullname = $_SESSION['fullname'];
		$session_userid = $_SESSION['id'];
		$session_role = $_SESSION['role'];
		$session_location = $_SESSION['user_location'];
	} else {
		header("Location:".BASE_URL);
	}
	// SESSION CHECK ~~~~~~~~~~~