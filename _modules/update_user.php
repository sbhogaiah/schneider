<?php
require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

$user_id = mysqli_real_escape_string($db,$_POST['user_id']);
$user_name = mysqli_real_escape_string($db,$_POST['user_name']);
$full_name = mysqli_real_escape_string($db,$_POST['full_name']);
// $user_email = mysqli_real_escape_string($db,$_POST['user_email']);
$user_pass = mysqli_real_escape_string($db,$_POST['user_pass']);
$user_role = mysqli_real_escape_string($db,$_POST['user_role']);
$user_loc = mysqli_real_escape_string($db,$_POST['user_loc']);

$sel_userid = "select * from users where UserID='$user_id' ";
$run_userid = mysqli_query($db,$sel_userid); 
$check_userid = mysqli_num_rows($run_userid);

if($check_userid == 0) {
	echo "User does not exist!";
} else {
	$data = ["Username" => $user_name, "Fullname" => $full_name, "Password" => $user_pass, "Role" => $user_role, "idfID" => $user_loc, "SecuKy" => $user_pass];

	foreach ($data as $col => $value) {
		if(!trim($value)) {
			unset($data[$col]);
		} 
	}

	// var_dump($data);
    
    if(array_key_exists('Password', $data)){
    	$data['Password'] = md5($data['Password']);
    }	
    /*Assuming data keys are = to database fileds*/
    if (count($data) > 0) {
        foreach ($data as $key => $value) {

            $value = mysqli_real_escape_string($db,$value); // this is dedicated to @Jon
            $value = "'$value'";
            $updates[] = "$key = $value";
        }
    }
    $implodeArray = implode(', ', $updates);

    $sql = ("UPDATE users SET $implodeArray WHERE userID='$user_id'");
    $run = mysqli_query($db, $sql) or die(mysqli_error($db));
    
    if($run) {
    	echo "User updated successfully!";
    }
}

