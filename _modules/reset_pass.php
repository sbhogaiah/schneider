<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['user_name'])) {

	if($_POST['role'] != 'admin') {
		$user_name = $_POST['user_name'];
		// $user_email = $_POST['user_email'];
		$role = $_POST['role'];
		$user_pass = $_POST['user_pass'];

		$check_query = "SELECT * FROM users WHERE Username='$user_name' AND Role = '$role'";
		$run_check_query = mysqli_query($db, $check_query) or die(mysqli_error($db));
		$result_check_query = mysqli_fetch_assoc($run_check_query);

		if(mysqli_num_rows($run_check_query) == 0) {
			echo "err:1";
			exit();
		} 
		// else if($result_check_query['Email'] != $user_email) {
		// 	echo "err:2";
		// 	exit();
		// } 
		else {
			$update_query = "UPDATE users SET Password=md5('$user_pass'), SecuKy='$user_pass' WHERE Username='$user_name' AND Role='$role'";
			$run_update_query = mysqli_query($db,$update_query) or die(mysqli_error($db));

			echo "Your password has been changed!";
		}
	} else {
		$user_name = $_POST['user_name'];
		// $user_email = $_POST['user_email'];
		$role = $_POST['role'];
		$user_pass = $_POST['user_pass'];

		$check_query = "SELECT * FROM admin WHERE Username='$user_name'";
		$run_check_query = mysqli_query($db, $check_query) or die(mysqli_error($db));
		$result_check_query = mysqli_fetch_assoc($run_check_query);

		if(mysqli_num_rows($run_check_query) == 0) {
			echo "err:1";
			exit();
		} 
		// else if($result_check_query['Email'] != $user_email) {
		// 	echo "err:2";
		// 	exit();
		// } 
		else {
			$update_query = "UPDATE admin SET Password=md5('$user_pass'), SecuKy='$user_pass' WHERE Username='$user_name'";
			$run_update_query = mysqli_query($db,$update_query) or die(mysqli_error($db));

			echo "Your password has been changed!";
		}
	}

}