<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['user_id'])){
	
	$user_id = $_POST['user_id']; 
	$delete = $_POST['delete'];

	if($delete == "single") {
		$delete = "delete from users where userID='$user_id'"; 
		$run_delete = mysqli_query($db,$delete); 
		
		if($run_delete) {
			echo "User deleted successfully!";
		}
	} else if($delete == "multiple") {
		$ids = explode(",",$user_id);
		
		foreach ($ids as $id) {
			$delete = "delete from users where userID='$id'"; 
			$run_delete = mysqli_query($db,$delete) or die(mysqli_error($db));
		}

		echo "All selected users deleted successfully!";
	}

}