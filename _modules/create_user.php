<?php
	
require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['user_pass'])) {

	$user_name = mysqli_real_escape_string($db,$_POST['user_name']);
	$full_name = mysqli_real_escape_string($db,$_POST['full_name']);
	// $user_email = mysqli_real_escape_string($db,$_POST['user_email']);
	$user_pass = mysqli_real_escape_string($db,$_POST['user_pass']);
	$user_role = mysqli_real_escape_string($db,$_POST['user_role']);
	$user_loc = mysqli_real_escape_string($db,$_POST['user_loc']);

	// error checks
	// $sel_email = "select * from users where Email='$user_email'";
	// $run_email = mysqli_query($db,$sel_email); 
	// $check_email = mysqli_num_rows($run_email);

	$sel_username = "select * from users where Username='$user_name'";
	$run_username = mysqli_query($db,$sel_username); 
	$check_username = mysqli_num_rows($run_username);

	if($check_username == 1){
		echo "err:2";
	} 
	// else if($check_email == 1) {
	// 	echo "err:1";
	// } 
	else {
		$insert = "INSERT INTO users (Username,OperatorID,Fullname,Role,idfID,Password,SecuKy) VALUES ('$user_name','$user_name','$full_name','$user_role','$user_loc',md5('$user_pass'),'$user_pass') ";
		$run_insert = mysqli_query($db, $insert);

		if($user_role == "baytester") {
			$insert_testact = "INSERT INTO testeractivity (TesterUsername,TesterFullname) VALUES ('$user_name','$full_name') ";
			$run_insert_testact = mysqli_query($db, $insert_testact);
		}

		if($run_insert){
			echo "User account created!";
		} else {
			echo "User cannot be created! There has been an error!";
		}
	}

}