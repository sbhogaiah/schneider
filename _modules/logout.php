<?php
	
	require_once '../_includes/bootstrap.php';
	
	session_start();
	$a = session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Schneider Electric Test Monitor App</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/icons.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
</head>
<body>
	
	<!-- IMPORTANT: DO NOT REMOVE -->
	<div id="page">

		<div class="fixed-center">
			<h2>You have been logged out.</h2>
			<p>Redirecting you to homepage.</p>
		</div>

	</div>

	<script type="text/javascript">
		
		setTimeout(function () {
			window.location.href = "<?php echo BASE_URL; ?>";
		}, 1000);

	</script>

</body>
</html>