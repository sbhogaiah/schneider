<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

if(isset($_GET['bay'])) {
	$bayname = $_GET['bay'];

	$q = "SELECT SerialNumber FROM ppi WHERE BayName = '$bayname'";
	$run_q = mysqli_query($db, $q) or die(mysqli_error($db));
	$serial = mysqli_fetch_assoc($run_q)['SerialNumber'];

	$prod_q = "SELECT * FROM products WHERE SerialNumber = '$serial'";
	$run_prod_q = mysqli_query($db, $prod_q) or die(mysqli_error($db));
	
	$data = mysqli_fetch_assoc($run_prod_q);
	echo json_encode($data);

}
