<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

if(isset($_POST['bayid']) && isset($_POST['bayname']) && isset($_POST['username']) && isset($_POST['serialnum']) && isset($_POST['modelid']) && isset($_POST['faildesc'])) {

	// echo $_POST['bayid'].' '.$_POST['bayname'].' '.$_POST['username'].' '.$_POST['serialnum'].' '.$_POST['modelid'];
	// do not echo, echo data will corrupt json, only echo while testing

	$bay_id = $_POST['bayid'];
	$bay_name = $_POST['bayname'];
	$username = $_POST['username'];
	$serialnum = $_POST['serialnum'];
	$modelid = $_POST['modelid'];
	$faildesc = $_POST['faildesc'];
	
	// get info from testeractivity table
	$activity_q = "SELECT * FROM testeractivity WHERE SerialNumber='$serialnum'";
	$run_activity_q = mysqli_query($db, $activity_q) or die(mysqli_error($db));
	$activity_data = mysqli_fetch_assoc($run_activity_q);

	if($activity_data['TestType'] == 'final') {
		$ifFinal = 'yes';
	} else {
		$ifFinal = 'no';
	}
	echo json_encode($activity_data);

	// get rating
	$bay_q = "SELECT * FROM bays WHERE BayID = '$bay_id'";
	$run_bay_q = mysqli_query($db, $bay_q) or die(mysqli_error($db));
	$bay_data = mysqli_fetch_assoc($run_bay_q) or die('Cannot connect to bays at complete test!');
	$rating = $bay_data['BayRating'];
	$bayactual = $bay_data['BayActualName'];
	$bayname = $bay_data['BayName'];

	// Update ppi----------------------
	$GetPPI = "SELECT ResumeTime FROM ppi WHERE BayName = '$bayname'";
	$RunQueryPPI = mysqli_query($db,$GetPPI) or die('Cannot connect to PPI at complete.php'); 
	$PPI=mysqli_fetch_assoc($RunQueryPPI);
	$PPIResumeTime = $PPI['ResumeTime']; 
	
	if($ifFinal == 'no') {
		if ($PPIResumeTime == NULL) {
			$UpdatePPI = "UPDATE ppi SET Status='complete', EndTest=NOW(), UsageTime=CEIL(TIMESTAMPDIFF(MINUTE,StartTest,NOW())), TotalTime=CEIL(TIMESTAMPDIFF(MINUTE,StartTest,NOW())) WHERE BayName='$bayname'";
			$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Complete test!");
		} else {
			$UpdatePPI = "UPDATE ppi SET Status='complete', EndTest=NOW(), UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,ResumeTime,NOW())), StaticUsageTime=StaticUsageTime + CEIL(TIMESTAMPDIFF(MINUTE,ResumeTime,NOW())) WHERE BayName='$bayname'";
			$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die(mysqli_error($db));
		}
	} else if($ifFinal == 'yes') {
		$UpdatePPI = "UPDATE ppi SET Status='idle', SerialNumber=NULL, ModelID=NULL, StartTest=NULL, EndTest=NULL, FAILTIME=NULL, ResumeTime=NULL, UsageTime=0, DownTime=0, FailCount=0, CycleTime=0, TotalTime=0, Tolerance=0, StaticUsageTime=0, StaticDownTime=0 WHERE SerialNumber='$serialnum'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at complete test!");
	}


	// get all details
	$GetPPI2 = "SELECT * FROM ppi WHERE BayName = '$bayname'";
	$RunQueryPPI2 = mysqli_query($db,$GetPPI2) or die('Cannot connect to PPI at complete.php'); 
	$PPI2=mysqli_fetch_assoc($RunQueryPPI2); 
	$PPI2DownTime = $PPI2['DownTime'];
	$PPI2ResumeTime = $PPI2['ResumeTime'];
	$PPI2StartTime = $PPI2['StartTest'];
	$PPI2UsageTime = $PPI2['UsageTime'];

	// Update bayutil----------------------
	// check if curtime is midnight 
	$timestamp = strtotime('today midnight');
	$midnight = date("Y/m/d H:i:s",$timestamp);
	$curdate = date("Y/m/d H:i:s");

	// for checking after midnight
	$midToNow = round(abs(strtotime($midnight) - strtotime($curdate)) / 60);
	$testToNow = round(abs(strtotime($PPI2StartTime) - strtotime($curdate)) / 60);

	if($testToNow > $midToNow) { //overlap
		// also check if bay row already created after midnight
		$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
		$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Complete test!');

		if(mysqli_num_rows($run_check_bayutil) == 0) { // overlap and row does not exist
			// if the test is running the usage start should be now at midnight
			$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate,UsageStart,UsageEnd,DownStart,Status) 
										VALUES ('$bay_id','$bayactual',CURDATE(),'$midnight',NOW(),NOW(),'down')";  
			$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Complete test!");

			$UpdateCurBayUTbl = "UPDATE bayutil SET UsageTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
									StaticUsageTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
									IdleTime=1440-StaticUsageTime, StaticIdleTime=1440-StaticUsageTime  
				 					WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
			$RunQueryCurBayUTbl = mysqli_query($db,$UpdateCurBayUTbl) or die("Connection was not created for UpdateCurBayUTbl Current Day!");
			
			// for previous day bayUtil
			if ($PPI2ResumeTime == NULL) { // check if failing first time or not
				$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPI2StartTime','$midnight')), 
									StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPI2StartTime','$midnight')), 
									IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
				 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND BayActualName='$bayactual'";
				$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for UpdateBayUTbl Current Day!");
			} else {
				$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPI2ResumeTime','$midnight')), 
									StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPI2ResumeTime','$midnight')), 
									IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
				 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND BayActualName='$bayactual'";
				$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for UpdateBayUTbl Current Day!");
			}

		} else { // overlap but row for bayUtil exists
			$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())),
										StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
										IdleTime=1440-StaticUsageTime-StaticDownTime, 
										StaticIdleTime=1440-StaticUsageTime-StaticDownTime, 
										UsageEnd=NOW(), 
										Status='idle' 
										WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";						
			$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die('Cannot connect to bay util at complete test!');
		}


	} else { // no overlap
		// your were adding total test time which includes downtime and usagetime to the bayutil usage time which is wrong
		$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())),
									StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
									IdleTime=1440-StaticUsageTime-StaticDownTime, 
									StaticIdleTime=1440-StaticUsageTime-StaticDownTime, 
									UsageEnd=NOW(), 
									Status='idle' 
									WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";						
		$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die('Cannot connect to bay util at complete test!');
	}

	if($ifFinal == 'yes') {
		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET TestStage='0', BayID=NULL, BayName=NULL, SerialNumber=NULL, ModelID=NULL, TestStartDT=NULL, TestEndDT=NULL, FailCount=0, TestType='burnin' WHERE TesterUsername = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at complete test!');

		// Update products----------------------
		$UpdateTestComplete = "UPDATE products SET TestEndFinal=NOW(), CurStatus=NULL, TestCompletedFinal=1 WHERE SerialNumber='$serialnum'";
		$RunQueryUpdateTestComplete = mysqli_query($db,$UpdateTestComplete) or die('Cannot connect to products at complete test!');
	} else {
		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET TestStage='3', TestEndDT=NOW() WHERE TesterUsername = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at complete test!');

		// Update products----------------------
		$UpdateTestComplete = "UPDATE products SET TestEnd=NOW(), TestCompleted=1, CurStatus='Completed', UsageTime='$PPI2UsageTime', Downtime='$PPI2DownTime' WHERE SerialNumber='$serialnum'";
		$RunQueryUpdateTestComplete = mysqli_query($db,$UpdateTestComplete) or die('Cannot connect to products at complete test!');
	}


} else {
	echo "one of the required fields are empty!";
}
