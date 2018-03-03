
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
	
	case "serialno" :
		$query = " SELECT th.*, pr.*
			FROM producttimestamp as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE th.SerialNumber = '".$sn."'";
	break;
	case "datereport":
		$fromDate = $fromDate . " 00:00:00";
		$toDate = $toDate . " 23:59:59";
		$query = " SELECT th.*, pr.*
			FROM producttimestamp as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.SourceDT >= '".$fromDate."' AND pr.SourceDT <= '".$toDate."'";				
	break;
	case "oneday":
		$query = " SELECT th.*, pr.*
			FROM producttimestamp as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.SourceDT > timestampadd(hour, -24, now())";	
	break;
	case "oneweek":
		$query = " SELECT th.*, pr.*
			FROM producttimestamp as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.SourceDT > timestampadd(day, -7, now())";			
	break;
	case "halfmonth":
		$query = " SELECT th.*, pr.*
			FROM producttimestamp as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.SourceDT > timestampadd(day, -15, now())";			
	break;
	case "month":
		$query = " SELECT th.*, pr.*
			FROM producttimestamp as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.SourceDT > timestampadd(day, -30, now())";	
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
?>

