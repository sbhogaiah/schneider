<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['cycle_id'])){
	
	$product_cycle_id = $_POST['cycle_id']; 
	$delete = $_POST['delete'];

	if($delete == "single") {
		$delete = "delete from productcycles where ProductCycleID='$product_cycle_id'"; 
		$run_delete = mysqli_query($db,$delete); 
		
		if($run_delete) {
			echo "Cycle deleted successfully!";
		}
	} else if($delete == "multiple") {
		$ids = explode(",",$product_cycle_id);

		foreach ($ids as $id) {
			$delete = "delete from productcycles where ProductCycleID='$id'"; 
			$run_delete = mysqli_query($db,$delete);
		}

		echo "All selected Cycles deleted successfully!";
	}

}