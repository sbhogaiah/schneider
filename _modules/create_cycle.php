<?php
	
	require_once '../_includes/bootstrap.php'; 
	require_once '../_includes/dbconnect.php';

	if(isset($_POST['model_id'])) {
	
		$cycle_ModelID = mysqli_real_escape_string($db,$_POST['model_id']);
		$cycle_Family = mysqli_real_escape_string($db,$_POST['product_family']);		
		$cycle_Time = mysqli_real_escape_string($db,$_POST['cycle_time']);
		$cycle_Rating = mysqli_real_escape_string($db,$_POST['rating']);
		$cycle_Tol = mysqli_real_escape_string($db,$_POST['cycle_tol']);
		// $cycle_Mins = mysqli_real_escape_string($db,$_POST['cycle_time_mins']);

		$query = "SELECT * FROM productcycles where ModelID = '$cycle_ModelID'";
		$run_query = mysqli_query($db, $query);
		$check_modelId = mysqli_num_rows($run_query);


		// convert cycletime to minutes
		$cycle_Time = hoursToMinutes($cycle_Time);

		if($check_modelId == 1) {
			echo "err:1";
		} else {
			$insert = "insert into productcycles (ModelID,FamilyName,Rating,CycleTime,CycTol) values ('$cycle_ModelID', '$cycle_Family', '$cycle_Rating', '$cycle_Time', '$cycle_Tol')";
			$run_insert = mysqli_query($db, $insert);

			if($run_insert){
				echo "Product Cycle created!";
			} else {
				echo "Product Cycle cannot be created! There has been an error!";
			}
		}

	}