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
	$faildesc = '['.date('m/d/Y h:i:s a').'] '.$faildesc.',';

	// get rating
	$bay_q = "SELECT * FROM bays WHERE BayID = '$bay_id'";
	$run_bay_q = mysqli_query($db, $bay_q) or die(mysqli_error($db));
	$bay_data = mysqli_fetch_assoc($run_bay_q) or die('Cannot connect to bays at Stop test!');
	$rating = $bay_data['BayRating'];
	$bayactual = $bay_data['BayActualName'];
	$bayname = $bay_data['BayName'];
	echo json_encode($bay_data);

	// Update ppi----------------------
	$GetPPI = "SELECT * FROM ppi WHERE BayName = '$bayname'";
	$RunQueryPPI = mysqli_query($db,$GetPPI) or die('Cannot connect to PPI at Stop test!'); 
	$PPI=mysqli_fetch_assoc($RunQueryPPI);
	$PPIResumeTime = $PPI['ResumeTime'];
	$PPIStartTime = $PPI['StartTest'];

	// if failed first time
	if ($PPIResumeTime == NULL) {
		$UpdatePPI = "UPDATE ppi SET Status = 'fail', FailTime = NOW(), UsageTime = StaticUsageTime + CEIL(TIMESTAMPDIFF(MINUTE,StartTest,NOW())), StaticUsageTime = StaticUsageTime + CEIL(TIMESTAMPDIFF(MINUTE,StartTest,NOW())), FailCount = FailCount+1 WHERE BayName = '$bayname'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Stop test!");
	} else {// if failed second time
		$UpdatePPI = "UPDATE ppi SET Status = 'fail', FailTime = NOW(), UsageTime = StaticUsageTime + CEIL(TIMESTAMPDIFF(MINUTE,ResumeTime,NOW())), StaticUsageTime = StaticUsageTime + CEIL(TIMESTAMPDIFF(MINUTE,ResumeTime,NOW())), FailCount = FailCount+1 WHERE BayName = '$bayname'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Stop test!");
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
		$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Stop test!');

		if(mysqli_num_rows($run_check_bayutil) == 0) { // overlap and row does not exist
			// if the test is running the usage start should be now at midnight
			$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate,UsageStart,UsageEnd,DownStart,Status) 
										VALUES ('$bay_id','$bayactual',CURDATE(),'$midnight',NOW(),NOW(),'down')";  
			$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Stop test!");

			$UpdateCurBayUTbl = "UPDATE bayutil SET UsageTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
									StaticUsageTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
									IdleTime=1440-StaticUsageTime, StaticIdleTime=1440-StaticUsageTime  
				 					WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
			$RunQueryCurBayUTbl = mysqli_query($db,$UpdateCurBayUTbl) or die("Connection was not created for UpdateCurBayUTbl Current Day!");
			
			// for previous day bayUtil
			if ($PPIResumeTime == NULL) { // check if failing first time or not
				$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIStartTime','$midnight')), 
									StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIStartTime','$midnight')), 
									IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
				 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND BayActualName='$bayactual'";
				$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for UpdateBayUTbl Current Day!");
			} else {
				$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIResumeTime','$midnight')), 
									StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIResumeTime','$midnight')), 
									IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
				 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND BayActualName='$bayactual'";
				$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for UpdateBayUTbl Current Day!");
			}

		} else { // overlap but row for bayUtil exists
			$UpdateBayUTbl = "UPDATE bayutil SET UsageTime = StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
							StaticUsageTime = StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
							IdleTime=1440-StaticUsageTime-StaticDownTime,
							StaticIdleTime=1440-StaticUsageTime-StaticDownTime, 
							UsageStart=NULL,DownEnd=NULL, UsageEnd=NOW(),DownStart=NOW(),
							Status='down' 
							WHERE BayDate=CURDATE() AND BayActualName = '$bayactual'";
			$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for bayutil at Stop Test!");
		}


	} else { // no overlap
		$UpdateBayUTbl = "UPDATE bayutil SET UsageTime = StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
							StaticUsageTime = StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
							IdleTime=1440-StaticUsageTime-StaticDownTime,
							StaticIdleTime=1440-StaticUsageTime-StaticDownTime, 
							UsageStart=NULL,DownEnd=NULL, UsageEnd=NOW(),DownStart=NOW(),
							Status='down' 
							WHERE BayDate=CURDATE() AND BayActualName = '$bayactual'";
		$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for bayutil at Stop Test!");
	}
	// update testeractivity-----------------------
	$testeractivity_q = "UPDATE testeractivity SET TestStage='2', FailCount=FailCount+1 ,TotalFailCount=TotalFailCount+1 WHERE TesterUsername = '$username'";
	$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Stop test!');

	// Update products----------------------
	// Need to store multiple failure reasons in product table
	$UpdateProducts = "UPDATE products SET TestFailCount=TestFailCount+1,FailReasons=CONCAT('$faildesc', FailReasons), TestCompleted=0 WHERE SerialNumber='$serialnum'";
	$RunUpdateProducts = mysqli_query($db,$UpdateProducts) or die("Connection was not created for Products table at Stop Test!");

} else {
	echo "one of the required fields are empty!";
}