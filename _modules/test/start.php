<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

if(isset($_POST['bayid']) && isset($_POST['bayname']) && isset($_POST['username']) && isset($_POST['serialnum']) && isset($_POST['modelid'])) {

	$bay_id = $_POST['bayid'];
	$bay_name = $_POST['bayname'];
	$username = $_POST['username'];
	$serialnum = $_POST['serialnum'];
	$modelid = $_POST['modelid'];
	$ifFinal = $_POST['isFinal'];

	// get standard cycle time
	$cycle_q = "SELECT * FROM productcycles WHERE ModelID = '$modelid'";
	$run_cycle_q = mysqli_query($db, $cycle_q) or die('Cannot connect to productcycles. Start test!');
	$cycle = mysqli_fetch_assoc($run_cycle_q);
	$cycletime = $cycle['CycleTime'];
	$cycletol = $cycle['CycTol'];

	if($ifFinal == 'yes') {
		$cycletime = 60;
	}

	// get rating
	$bay_q = "SELECT * FROM bays WHERE BayID = '$bay_id'";
	$run_bay_q = mysqli_query($db, $bay_q) or die('Cannot connect to bays at Start test!');
	$bay_data = mysqli_fetch_assoc($run_bay_q);
	$rating = $bay_data['BayRating'];
	$bayactual = $bay_data['BayActualName'];
	$bayname = $bay_data['BayName'];
	echo json_encode($bay_data);

	// Bay util
	// check if row for bay already
	$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
	$run_check_bayutil = mysqli_query($db, $check_bayutil) or die('Cannot connect to bay util table at Start test!');
	
	if(mysqli_num_rows($run_check_bayutil) == 0) {
		$InsertBayUTR = "INSERT INTO bayutil (BayID,BayActualName,BayDate) VALUES ('$bay_id','$bayactual',CURDATE())";  
		$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Start test!");
	}

	// add time
	$UpdateBayUTbl = "UPDATE bayutil SET IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime, UsageStart=NOW(), UsageEnd=NULL, DownStart=NULL, DownEnd=NOW(), Status='up' WHERE BayDate=CURDATE() AND BayActualName='$bayactual'";
	$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl) or die("Connection was not created for BayUtil at Start test!");

	// PPI
	$UpdatePPI = "UPDATE ppi SET SerialNumber = '$serialnum', ModelID = '$modelid', Status = 'inprogress', StartTest = NOW(), CycleTime = '$cycletime', Tolerance = '$cycletol' WHERE BayName = '$bayname'";
	$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at Start test!");

	// Products
	if($ifFinal == 'yes') {
		$UpdateProducts = "UPDATE products SET TestStartFinal = NOW(), BayName='$bay_name', CurStatus='Testing', TestType='final' WHERE SerialNumber = '$serialnum'";
		$RunQueryProducts = mysqli_query($db, $UpdateProducts) or die("Connection was not created for Products at Start test!");

		// insert into testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET BayID='$bay_id',BayName='$bay_name',SerialNumber='$serialnum',ModelID='$modelid',TestStartDT=NOW(),TestStage='1',FailCount=0,TotalTestCount=TotalTestCount+1,TestType='final' WHERE TesterUsername = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Start test!');
	} else {
		$UpdateProducts = "UPDATE products SET TestStart = NOW(), BayName='$bay_name', CurStatus='Testing', TestType='burnin' WHERE SerialNumber = '$serialnum'";
		$RunQueryProducts = mysqli_query($db, $UpdateProducts) or die("Connection was not created for Products at Start test!");

		// insert into testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET BayID='$bay_id',BayName='$bay_name',SerialNumber='$serialnum',ModelID='$modelid',TestStartDT=NOW(),TestStage='1',FailCount=0,TotalTestCount=TotalTestCount+1,TestType='burnin' WHERE TesterUsername = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Start test!');
	}

	
	
} else {
	echo "one of the required fields are empty!";
}

