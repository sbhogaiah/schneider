<?php 
	require_once 'bootstrap.php'; 
	require_once 'dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
	<meta charset="utf-8">
	<title>Schneider Electric Test Monitor App</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- STYLES -->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/icons.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/jquery.datetimepicker.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">

	<!-- SCRIPTS -->
	<script src="<?php echo BASE_URL; ?>js/vendor/jquery-3.1.0.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/js.cookie.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/jquery.form-validator.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/Chart.bundle.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/vendor/jquery.datetimepicker.min.js"></script>
	<script src="<?php echo BASE_URL; ?>js/components.js"></script>
	<script src="<?php echo BASE_URL; ?>js/main.js"></script>

	<script>
		$(function() {
			modal.openEvent('[data-modal-open]');
		});
	</script>

</head>