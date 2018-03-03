<?php
require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

session_start();

if(isset($_POST['role']) && $_POST['role'] == "guest") {
	$_SESSION['username'] = 'guest';
	$_SESSION['fullname'] = 'Guest';
	$_SESSION['id'] = '0';
	$_SESSION['role'] = 'guest';
	$_SESSION['user_location'] = 'none';
	echo "admin_charts.php";
	exit();
}

if(isset($_POST['password'])) {
	$username = strip_tags($_POST['username']);
	$password = strip_tags($_POST['password']);
	$role = strip_tags($_POST['role']);

	$username = stripslashes($username);
	$password = stripslashes($password);
	$role = stripslashes($role);

	$username = mysqli_real_escape_string($db, $username);
	$password = mysqli_real_escape_string($db, $password);
	$role = mysqli_real_escape_string($db, $role);

	$password = md5($password);
	
	// check if admin role
	if($role != "admin") {

		// not admin
		$sql = "SELECT * FROM users WHERE username ='$username' LIMIT 1";
		$query = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($query);

		if (count($row)>=1) {

			$id = $row['UserID'];
			$full_name = $row['Fullname'];
			$table_role = $row['Role'];
			$db_password = $row['Password'];
			$location = $row['idfID'];

			if($role == $table_role) {

				if ($password == $db_password) {
					
					$_SESSION['username'] = $username;
					$_SESSION['fullname'] = $full_name;
					$_SESSION['id'] = $id;
					$_SESSION['role'] = $table_role;
					$_SESSION['user_location'] = $location;
					
					// add last login date
					$current_datatime = date("Y-m-d H:i:s");
					$lastlogin_query = "UPDATE users SET Lastlogin = '$current_datatime' WHERE UserID = '$id'";
					$run_lastlogin_query = mysqli_query($db, $lastlogin_query) or die(mysqli_error($db));

					// check role and redirect
					switch ($role) {
						case 'logistic':
							echo BASE_URL.'logistic.php';
							break;
						case 'production':
							echo BASE_URL.'production.php';
							break;
						case 'baytester':

							if($location == "4") {
								echo BASE_URL.'baytester.php';
							} else if($location == "1" || $location == "8") {
								echo BASE_URL.'partial_test.php';
							}
							break;
					}
				} else {
					echo "err:1";
				}

			} else {
				echo 'err:2';
			}

		} else {
			echo "err:1";
		}
		// not admin
	} else {
		// admin
		$sql = "SELECT * FROM admin WHERE username ='$username' LIMIT 1";
		$query = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($query);

		$id = $row['AdminID'];
		$full_name = $row['Fullname'];
		$db_password = $row['Password'];

		if (count($row)>=1 && $password == $db_password) {
					
			$_SESSION['username'] = $username;
			$_SESSION['fullname'] = $full_name;
			$_SESSION['id'] = $id;
			$_SESSION['role'] = "admin";
			$_SESSION['user_location'] = "admin";

			echo "admin_charts.php";
			
		} else {
			echo "err:1";
		}
		//admin
	}
}