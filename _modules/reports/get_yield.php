<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
extract($_POST);

switch ($reportType) {
	
	case "modelno" :
		$query = " SELECT ModelID as ModelID
					, SUM(TestFailCount) as Fail
					, SUM(TestCompleted) as Pass
					, DATE(SourceDT) as SourceDt
				FROM `products` 
				WHERE ModelID = '".$model."'
				GROUP BY ModelID ORDER BY SourceDT ASC";
	break;
	
	case "datereport":		
		$fromDate = $fromDate . " 00:00:00";
		$toDate = $toDate . " 23:59:59";
		
		$query = " SELECT ModelID as ModelID
					, SUM(TestFailCount) as Fail
					, SUM(TestCompleted) as Pass
					, DATE(SourceDT) as SourceDt
				FROM `products` 
				WHERE SourceDT > '".$fromDate."' AND SourceDT <= '".$toDate."'
				GROUP BY ModelID ORDER BY SourceDT ASC";
	break;
	
	case "oneday":
		$query = " SELECT ModelID as ModelID
					, SUM(TestFailCount) as Fail
					, SUM(TestCompleted) as Pass
					, DATE(SourceDT) as SourceDt
				FROM `products` 
				WHERE SourceDT > timestampadd(hour, -24, now()) 
				GROUP BY ModelID ORDER BY SourceDT ASC";
	break;
	
	case "oneweek":
		$query = " SELECT ModelID as ModelID
					, SUM(TestFailCount) as Fail
					, SUM(TestCompleted) as Pass
					, DATE(SourceDT) as SourceDt
				FROM `products` 
				WHERE SourceDT > timestampadd(day, -7, now()) 
				GROUP BY ModelID ORDER BY SourceDT ASC";
	break;
	
	case "halfmonth":			
		$query = " SELECT ModelID as ModelID
					, SUM(TestFailCount) as Fail
					, SUM(TestCompleted) as Pass
					, DATE(SourceDT) as SourceDt
				FROM `products` 
				WHERE SourceDT > timestampadd(day, -15, now()) 
				GROUP BY ModelID ORDER BY SourceDT ASC";
	break;
	
	case "month":
		$query = " SELECT ModelID as ModelID
					, SUM(TestFailCount) as Fail
					, SUM(TestCompleted) as Pass
					, DATE(SourceDT) as SourceDt
				FROM `products` 
				WHERE SourceDT > timestampadd(day, -30, now()) 
				GROUP BY ModelID ORDER BY SourceDT ASC";
	break;
	
	default:
	break;
}


$result = mysqli_query($db, $query);
$resultset = [];
while($row = mysqli_fetch_assoc($result)){
	$resultset[]=$row;
}
echo json_encode($resultset);
mysqli_free_result($result);

/* close connection */
mysqli_close($db);


