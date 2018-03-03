<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['serialnum'])) {

	$sn = $_POST['serialnum'];
	$mid = $_POST['modelid'];
	$idf = $_POST['idfId'];
	$operator = $_POST['username'];

	if ($idf == '4') {
		
		$insertProduct4 = "INSERT INTO products (SerialNumber,ModelID,idfID,SourceDT ) VALUES ('$sn', '$mid','$idf', NOW())";
		$run1 = mysqli_query($db, $insertProduct4) or die(mysqli_error($db));

		$insertProduct4TS = "INSERT INTO producttimestamp (SerialNumber,Idf4In_SendProductionDT,Idf4In_SendProductionOpID,ProductStage) VALUES ('$sn',(NOW()),'$operator',7)";
		$run2 = mysqli_query($db, $insertProduct4TS) or die(mysqli_error($db));

		echo "Product record created successfully!";

	} else {

		$insertProduct = "INSERT INTO products (SerialNumber,ModelID,idfID,SourceDT ) VALUES ('$sn', '$mid','$idf', NOW())";
		$run3 = mysqli_query($db, $insertProduct) or die(mysqli_error($db));

		$insertProductTS = "INSERT INTO producttimestamp (SerialNumber,Src_SendProductionDT,Src_SendProductionOpID,ProductStage) VALUES ('$sn',(NOW()),'$operator',1)";
		$run4 = mysqli_query($db, $insertProductTS) or die(mysqli_error($db));

		echo "Product record created successfully!";
	}

}
// $InsertProduct = "INSERT INTO Products (SerialNumber, ModelID,idfID, SourceDT )
// 													  VALUES   ('$prod_SN', '$prod_Model',$prod_idfID,NOW())";
										  
// $RunQuery = mysqli_query($db,$InsertProduct);
// echo "RunQuery"."$RunQuery\n<br/>";
// $InsertProductTS = "INSERT INTO ProductTimeStamp (SerialNumber, Src_SendProductionDT,Src_SendProductionOpID,ProductStage)
// 								VALUES 			 ('$prod_SN',(NOW()),'$CurrentProductionOpID',1)";


// $RunQuery = mysqli_query($db,$InsertProductTS);