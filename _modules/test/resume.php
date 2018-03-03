<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

if(isset($_POST['bayid']) && isset($_POST['bayname']) && isset($_POST['username']) && isset($_POST['serialnum']) && isset($_POST['modelid']) && isset($_POST['faildesc'])) {

	$bay_id = $_POST['bayid'];
	$bay_name = $_POST['bayname'];
	$username = $_POST['username'];
	$serialnum = $_POST['serialnum'];
	$modelid = $_POST['modelid'];
	$faildesc = $_POST['faildesc'];

	// get rating
	$bay_q = "SELECT * FROM bays WHERE BayID = '$bay_id'";
	$run_bay_q = mysqli_query($db, $bay_q) or die(mysqli_error($db));
	$bay_data = mysqli_fetch_assoc($run_bay_q) or die('Cannot connect to bays at Resume test!');
	$rating = $bay_data['BayRating'];
	$bayactual = $bay_data['BayActualName'];
	$bayname = $bay_data['BayName'];
	echo json_encode($bay_data);

	// PPI and BAY UTIL-----------------------------------------------
	// update ppi
	$GetPPI = "SELECT * FROM ppi WHERE BayName = '$bayname'";
	$RunQueryPPI = mysqli_query($db,$GetPPI) or die('Cannot connect to PPI at complete.php'); 
	$PPI=mysqli_fetch_assoc($RunQueryPPI);
	$PPIResumeTime = $PPI['ResumeTime'];
	$PPIStartTime = $PPI['StartTest'];
	$PPIFailTime = $PPI['FailTime'];
	// if failed first time
	if ($PPIResumeTime == NULL) {
		$UpdatePPI = "UPDATE ppi SET Status='inprogress', ResumeTime=NOW(), DownTime=CEIL(TIMESTAMPDIFF(MINUTE,FailTime,NOW())), StaticDownTime=CEIL(TIMESTAMPDIFF(MINUTE,FailTime,NOW())) WHERE BayName='$bayname'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Start test!");
	} else {
		$UpdatePPI = "UPDATE ppi SET Status='inprogress', ResumeTime=NOW(), DownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,FailTime,NOW())), StaticDownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,FailTime,NOW())) WHERE BayName='$bayname'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Start test!");
	}

	// Update bayutil----------------------
	// check if curtime is midnight 
	$timestamp = strtotime('today midnight');
	$midnight = date("Y/m/d H:i:s",$timestamp);
	$curdate = date("Y/m/d H:i:s");

	// for checking after midnight
	$midToNow = round(abs(strtotime($midnight) - strtotime($curdate)) / 60);
	$testToNow = round(abs(strtotime($PPIStartTime) - strtotime($curdate)) / 60);

	if($testToNow > $midToNow) { //overlap
		// also check if bay row already created after midnight
		$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
		$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Start test!');

		if(mysqli_num_rows($run_check_bayutil) == 0) { // overlap and row does not exist
			// if the test is running the down start should be now at midnight
			$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate,DownStart,DownEnd,UsageStart,Status) 
										VALUES ('$bay_id','$bayactual',CURDATE(),'$midnight',NOW(),NOW(),'up')";  
			$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Start test!");

			$UpdateCurBayUTbl = "UPDATE bayutil SET DownTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
									StaticDownTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
									IdleTime=1440-StaticDownTime, StaticIdleTime=1440-StaticDownTime  
				 					WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
			$RunQueryCurBayUTbl = mysqli_query($db,$UpdateCurBayUTbl) or die("Connection was not created for UpdateCurBayUTbl Current Day!");

			// Update previous bayUtil
			$UpdateBayUTbl = "UPDATE bayutil SET DownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIFailTime','$midnight')), 
								StaticDownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIFailTime','$midnight')), 
								IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
			 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND BayActualName='$bayactual'";
			$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for UpdateBayUTbl Current Day!");
			

		} else { // overlap but row for bayUtil exists
			// update bay util
			$UpdateBayUTbl = "UPDATE bayutil SET DownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
									StaticDownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
									IdleTime=1440-StaticUsageTime-StaticDownTime,
									StaticIdleTime=1440-StaticUsageTime-StaticDownTime,
									UsageStart=NOW(), DownEnd=NOW(), DownStart=NULL, UsageEnd=NULL,
									Status='up' 
									WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";						
			$RunUpdateBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die('Cannot connect to Bay Utilization at Resume test!');
		}


	} else { // no overlap
		// update bay util
		$UpdateBayUTbl = "UPDATE bayutil SET DownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
								StaticDownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
								IdleTime=1440-StaticUsageTime-StaticDownTime,
								StaticIdleTime=1440-StaticUsageTime-StaticDownTime,
								UsageStart=NOW(), DownEnd=NOW(), DownStart=NULL, UsageEnd=NULL,
								Status='up' 
								WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";						
		$RunUpdateBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die('Cannot connect to Bay Utilization at Resume test!');
	}

	// update testeractivity-----------------------
	$testeractivity_q = "UPDATE testeractivity SET TestStage='1' WHERE TesterUsername = '$username'";
	$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Resume test!');

} else {
	echo "one of the required fields are empty!";
}
