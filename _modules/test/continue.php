<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

if(isset($_POST['bayid']) && isset($_POST['bayname']) && isset($_POST['username']) && isset($_POST['serialnum']) && isset($_POST['modelid'])) {

	$bay_id = $_POST['bayid'];
	$bay_name = $_POST['bayname'];
	$username = $_POST['username'];
	$serialnum = $_POST['serialnum'];
	$modelid = $_POST['modelid'];

	// get rating
	$bay_q = "SELECT * FROM bays WHERE BayID = '$bay_id'";
	$run_bay_q = mysqli_query($db, $bay_q) or die(mysqli_error($db));
	$bay_data = mysqli_fetch_assoc($run_bay_q) or die('Cannot connect to bays at pause test!');
	$rating = $bay_data['BayRating'];
	$bayactual = $bay_data['BayActualName'];
	$bayname = $bay_data['BayName'];
	// echo json_encode($bay_data);

	// get standard cycle time
	$cycle_q = "SELECT * FROM productcycles WHERE ModelID = '$modelid'";
	$run_cycle_q = mysqli_query($db, $cycle_q) or die('Cannot connect to productcycles. Start test!');
	$cycle = mysqli_fetch_assoc($run_cycle_q);
	$cycletime = $cycle['CycleTime'];
	$cycletol = $cycle['CycTol'];

	// get info from testeractivity table
	$activity_q = "SELECT * FROM testeractivity WHERE SerialNumber='$serialnum'";
	$run_activity_q = mysqli_query($db, $activity_q) or die(mysqli_error($db));
	$activity_data = mysqli_fetch_assoc($run_activity_q);
	$activity_status = $activity_data['PausedStatus'];
	$activity_start = $activity_data['TestStartDT'];
	$activity_usage = $activity_data['PausedUsageTime'];
	$activity_down = $activity_data['PausedDownTime'];

	// Bay util
	// check if row for bay already
	$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
	$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Continue test!');
	
	if(mysqli_num_rows($run_check_bayutil) == 0) {
		$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate) VALUES ('$bay_id','$bayactual',CURDATE())";  
		$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Continue test!");
	}

	// add time
	$UpdateBayUTbl = "UPDATE bayutil SET IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime, UsageStart=NOW(), UsageEnd=NULL, DownStart=NULL, DownEnd=NOW(), Status='up' WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
	$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for BayUtil at Continue test!");

	// update ppi
	if($activity_status == 'fail') {
		$UpdatePPI = "UPDATE ppi SET Status='$activity_status', SerialNumber='$serialnum', ModelID='$modelid', StartTest='$activity_start', FailTime=NOW(), UsageTime='$activity_usage', DownTime='$activity_down', CycleTime='$cycletime', Tolerance='$cycletol', StaticUsageTime='$activity_usage', StaticDownTime='$activity_down' WHERE BayName='$bayname'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Continue test!");
		
		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET TestStage='2', PausedStatus=NULL WHERE TesterUsername = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Continue test!');

		echo json_encode(["stage"=>"fail"]);

	} else {
		$UpdatePPI = "UPDATE ppi SET Status='$activity_status', SerialNumber='$serialnum', ModelID='$modelid', StartTest='$activity_start', ResumeTime=NOW(), UsageTime='$activity_usage', DownTime='$activity_down', CycleTime='$cycletime', Tolerance='$cycletol', StaticUsageTime='$activity_usage', StaticDownTime='$activity_down' WHERE BayName='$bayname'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Continue test!");

		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET TestStage='1', PausedStatus=NULL WHERE TesterUsername = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Continue test!');

		echo json_encode(["stage"=>"inprogress"]);
	}

	

	// Update products----------------------
	// $UpdateTestRemove = "UPDATE products SET CurStatus='Standby', StandbyRemarks=CONCAT('$standby_remarks', StandbyRemarks) WHERE SerialNumber='$serialnum'";
	// $RunQueryUpdateTestRemove = mysqli_query($db,$UpdateTestRemove) or die('Cannot connect to products at remove test!');

} else {
	echo "one of the required fields are empty!";
}
