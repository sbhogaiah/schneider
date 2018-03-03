<?php
	
	require_once '../_includes/bootstrap.php'; 
	require_once '../_includes/dbconnect.php';

	$product_cycle_id = mysqli_real_escape_string($db,$_POST['product_cycle_id']);
	$edit_model = mysqli_real_escape_string($db,$_POST['model_id']);
	$edit_family = mysqli_real_escape_string($db,$_POST['product_family']);
	$edit_cycletime = mysqli_real_escape_string($db,$_POST['cycle_time']);
	$edit_rating = mysqli_real_escape_string($db,$_POST['rating']);
	$edit_cycletol = mysqli_real_escape_string($db,$_POST['cycle_tol']);

	$Sel_Model = "select * from productcycles where ProductCycleID='$product_cycle_id' ";
	$Run_Model = mysqli_query($db,$Sel_Model); 
	$Check_Model = mysqli_num_rows($Run_Model);

	// convert to minutes
	$edit_cycletime = hoursToMinutes($edit_cycletime);

	if($Check_Model == 0) {
		echo "The model ID does not exist in database!";
	} else {

		$data = ["ModelID" => $edit_model, "FamilyName" => $edit_family, "Rating" => $edit_rating, "CycleTime" => $edit_cycletime, "CycTol" => $edit_cycletol];

		foreach ($data as $col => $value) {
			if(!trim($value)) {
				unset($data[$col]);
			} 
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

	    $sql = ("UPDATE productcycles SET $implodeArray WHERE ProductCycleID='$product_cycle_id'");
	    $run = mysqli_query($db, $sql) or die(mysqli_error($db));
	    
	    if($run) {
	    	echo "Cycle Configuration Updated!";
	    }

	}
	