<?php
require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['serialnum'])) {
	
	$sn = $_POST['serialnum'];
	$mid = $_POST['modelid'];
	$idf = $_POST['idfId'];
	$operator = $_POST['username'];
	$stage = $_POST['stage'];


	//sent from production before idf4 to partial test if applicable
	if ($stage == '1.5') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Src_RxdPartTestDT=NOW() ,Src_RxdPartTestOpID = '$operator', ProductStage='1.5' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Partial testing started successfully!";
		}
	}

	//sent from production before idf4 to partial test if applicable
	if ($stage == '1.8') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Src_SendPartTestDT=NOW() ,Src_SendPartTestOpID = '$operator', ProductStage='1.8' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Partial testing completed successfully!";
		}
	}

	//sent from production or partial testing before idf4
	if ($stage == '2') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Src_RxdLogisticDT=NOW() ,Src_RxdLogisticOpID = '$operator', ProductStage='2' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product received successfully!";
		}
	}

	//receive at idf logistic before idf4
	if ($stage == '3') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Src_SendLogisticDT=NOW() ,Src_SendLogisticOpID = '$operator', ProductStage='3' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product sent successfully!";
		}
	}

	//receive at idf4 logistic
	if ($stage == '4') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4In_RxdLogisticDT=NOW() ,Idf4In_RxdLogisticOpID = '$operator', ProductStage='4' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product received successfully!";
		}
	}

	//send from idf4 logistic
	if ($stage == '5') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4In_SendLogisticDT=NOW() ,Idf4In_SendLogisticOpID = '$operator', ProductStage='5' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product sent successfully!";
		}
	}

	//receive at idf4
	if ($stage == '6') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4In_RxdProductionDT=NOW() ,Idf4In_RxdProductionOpID = '$operator', ProductStage='6' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product received successfully!";
		}
	}

	//send from idf4
	if ($stage == '7') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4In_SendProductionDT=NOW() ,Idf4In_SendProductionOpID = '$operator', ProductStage='7' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product sent successfully!";
		}
	}

	//receive at test infra
	if ($stage == '8') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET BayIn_TestInfraDT=NOW() ,BayIn_TestInfraOpID = '$operator', ProductStage='8' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product received successfully!";
		}
	}

	//send from test infra
	if ($stage == '9') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET BayOut_TestInfraDT=NOW() ,BayOut_TestInfraOpID = '$operator', ProductStage='9' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET TestStage='0', BayID=NULL, BayName=NULL, SerialNumber=NULL, ModelID=NULL,
									TestStartDT=NULL, TestEndDT=NULL,FailCount=0 WHERE SerialNumber='$sn'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at update product!');

		// update ppi
		$UpdatePPI = "UPDATE ppi SET Status='idle', SerialNumber=NULL, ModelID=NULL, StartTest=NULL, EndTest=NULL, FAILTIME=NULL, 
								ResumeTime=NULL, UsageTime=0, DownTime=0, FailCount=0, CycleTime=0, TotalTime=0, Tolerance=0,
								StaticUsageTime=0,StaticDownTime=0 WHERE SerialNumber = '$sn'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die("Could not update PPI at update prod data test!");

		if($run_upd_prod_ts_query) {
			echo "Product sent successfully!";
		}
	}

	//receive at idf4 after testing
	if ($stage == '10') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4Out_RxdProductionDT=NOW() ,Idf4Out_RxdProductionOpID = '$operator', ProductStage='10' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product received successfully!";
		}
	}

	//send from idf4 after testing
	if ($stage == '11') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4Out_SendProductionDT=NOW() ,Idf4Out_SendProductionOpID = '$operator', ProductStage='11' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product sent successfully!";
		}
	}

	//receive at idf4 logistic after testing
	if ($stage == '12') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4out_RxdLogisticDT=NOW() ,Idf4out_RxdLogisticOpID = '$operator', ProductStage='12' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query) {
			echo "Product received successfully!";
		}
	}

	//send from idf4 logistic after testing
	if ($stage == '13') {
		$upd_prod_ts_query = "UPDATE producttimestamp SET Idf4Out_SendLogisticDT=NOW() ,Idf4Out_SendLogisticOpID = '$operator', ProductStage='13' WHERE SerialNumber='$sn'";
		$run_upd_prod_ts_query = mysqli_query($db, $upd_prod_ts_query) or die(mysqli_error($db));

		$upd_prod_query = "UPDATE products SET EndDT=NOW() WHERE SerialNumber='$sn'";
		$run_upd_prod_query = mysqli_query($db, $upd_prod_query) or die(mysqli_error($db));

		if($run_upd_prod_ts_query && $run_upd_prod_query) {
			echo "Product sent successfully!";
		}
	}
	
}