<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';


	// update ppi table based on conditions
	$query = "SELECT * FROM ppi";
	$run_query = mysqli_query($db, $query);

	if(mysqli_num_rows($run_query)>0) {

		while($row = mysqli_fetch_assoc($run_query)) {
			
			// check if status is inprogress
			if($row['Status'] == "inprogress") {

				if ($row['ResumeTime'] == NULL) {
					$upd_query = "UPDATE ppi SET UsageTime=StaticUsageTime+TIMESTAMPDIFF(MINUTE,StartTest,NOW()) WHERE BayName = '$row[BayName]'";
					$run_upd_query = mysqli_query($db, $upd_query);
				} else {
					$upd_query = "UPDATE ppi SET UsageTime=StaticUsageTime+(TIMESTAMPDIFF(MINUTE,ResumeTime,NOW())) WHERE BayName = '$row[BayName]'";
					$run_upd_query = mysqli_query($db, $upd_query);
				}

				// check if curtime is midnight 
				$timestamp = strtotime('today midnight');
				$midnight = date("Y/m/d H:i:s",$timestamp);
				$TestStart = $row['StartTest'];
				$curdate = date("Y/m/d H:i:s");

				$todayTotal = round(abs(strtotime($midnight) - strtotime($curdate)) / 60,2);
				$testTotal = round(abs(strtotime($TestStart) - strtotime($curdate)) / 60,2);

				// echo $testTotal.' '.$todayTotal;

				if($testTotal > $todayTotal) {
					// echo 'After midnight';
					$bay_q = "SELECT BayActualName, BayID FROM bays WHERE BayName = '$row[BayName]'";
					$run_bay_q = mysqli_query($db, $bay_q) or die(mysqli_error($db));
					$bayinfo = mysqli_fetch_assoc($run_bay_q);
					$bayactual = $bayinfo['BayActualName'];
					$bay_id = $bayinfo['BayID'];

					$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
					$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Start test!');

					if(mysqli_num_rows($run_check_bayutil) == 0) {
						// if the test is running the usage start should be now at midnight
						$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate,UsageStart,DownEnd,Status) VALUES ('$bay_id','$bayactual',CURDATE(), '$midnight', NULL,'up')";  
						$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Start test!");
					}
				}

			}

			// check if status is inprogress
			if($row['Status'] == "fail") {
				if ($row['ResumeTime'] == NULL) {
					$upd_query2 = "UPDATE ppi SET DownTime=StaticDownTime + TIMESTAMPDIFF(MINUTE,FailTime,NOW()) WHERE BayName = '$row[BayName]'";
					$run_upd_query2 = mysqli_query($db, $upd_query2);
				} else {
					$upd_query3 = "UPDATE ppi SET DownTime=StaticDownTime + TIMESTAMPDIFF(MINUTE,FailTime,NOW()) WHERE BayName = '$row[BayName]'";
					$run_upd_query3 = mysqli_query($db, $upd_query3);
				}

				// check if curtime is midnight 
				$timestamp = strtotime('today midnight');
				$midnight = date("Y/m/d H:i:s",$timestamp);
				$TestStart = $row['StartTest'];
				$curdate = date("Y/m/d H:i:s");

				$todayTotal = round(abs(strtotime($midnight) - strtotime($curdate)) / 60,2);
				$testTotal = round(abs(strtotime($TestStart) - strtotime($curdate)) / 60,2);

				if($testTotal > $todayTotal) {
					// echo 'After midnight';
					$bay_q = "SELECT BayActualName, BayID FROM bays WHERE BayName = '$row[BayName]'";
					$run_bay_q = mysqli_query($db, $bay_q) or die(mysqli_error($db));
					$bayinfo = mysqli_fetch_assoc($run_bay_q);
					$bayactual = $bayinfo['BayActualName'];
					$bay_id = $bayinfo['BayID'];

					$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
					$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Start test!');

					if(mysqli_num_rows($run_check_bayutil) == 0) {
						// if the test is running the usage start should be now at midnight
						$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate,DownStart,UsageEnd,Status) VALUES ('$bay_id','$bayactual',CURDATE(), '$midnight', NULL,'down')";  
						$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Start test!");
					}
				}  

			}

		}

	} else {
		echo "No data found in database: get_ppi.php";		
		exit();
	}


	// data variable for full data
	$data = array();
	// get all info from ppi table
	$get_query = "SELECT * FROM ppi";
	$run_get_query = mysqli_query($db, $get_query);

	if(mysqli_num_rows($run_get_query)>0) {

		while($r = mysqli_fetch_assoc($run_get_query)) {
		    array_push($data, $r);
		}
		echo json_encode($data);

	} 