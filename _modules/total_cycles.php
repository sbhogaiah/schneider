<?php 
require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

$query = mysqli_query($db, "SELECT ModelID FROM productcycles");
$total = mysqli_num_rows($query);

echo $total;