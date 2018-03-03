<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';


$name = $_POST['set-tester'];
$serialnum = $_POST['serialnum'];

$UpdateProducts = "UPDATE products SET Tester='$name' WHERE SerialNumber = '$serialnum'";
$RunQueryProducts = mysqli_query($db, $UpdateProducts) or die("Connection was not created for Products at Start test!");

if($RunQueryProducts) {
	echo "Updated!";
}