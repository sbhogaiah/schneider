<?php 
	// for sidebar active toggle
	$page = "account";
	
	// session
	require_once '_modules/session.php';
	
	// head 
	require_once '_includes/head.php'; 

	if(isset($_SESSION['id'])) {

		$user_id = $_SESSION['id'];

		$user_q = "SELECT * FROM users WHERE UserID='$user_id'";
		$user_data = mysqli_query($db, $user_q) or die('Cannot make connection to database!');
		$user_data = mysqli_fetch_assoc($user_data);

	}

?>

<body class="production">
	
	<div id="page"><!-- #page -->

		<?php // navigation
			require_once '_includes/nav.php'; 
		?>
		<!-- page-header -->
		<header class="small-page-header">
			<div class="wrap">
				<h3>Update Account Details</h3>
			</div>
		</header>
		<!-- contents -->
		<div class="wrap">
			<!-- login-box -->
			<div class="account-box cf">
				<div class="form-header">
					<h4>Account Information</h4>
				</div>
				<div class="grid">
					<div class="col m-1-3">	
						<ul class="account-links">
							<li><a class="active" href="#updateUserInfo">User details</a></li>
							<li><a href="#updatePassword">Change Password</a></li>
						</ul>
					</div>
					<div class="col m-2-3 account-update-box">
						<div id="updateUserInfo">
							<form action="_modules/account_update.php" method="POST">
								<div class="form-group">
									<label for="fullname">Update Full Name</label>
									<input type="text" id="fullname" name="fullname" value="<?php echo $user_data['Fullname']; ?>">
								</div>
								<div class="form-group">
									<label for="fullname">Update Location</label>
									<select name="user_loc" id="user_loc" data-validation="required">
										<option value="1" <?php if($user_data['idfID'] == "1") { echo "selected"; } ?>>IDF 1</option>
										<option value="2" <?php if($user_data['idfID'] == "2") { echo "selected"; } ?>>IDF 2</option>
										<option value="3" <?php if($user_data['idfID'] == "3") { echo "selected"; } ?>>IDF 3</option>
										<option value="4" <?php if($user_data['idfID'] == "4") { echo "selected"; } ?>>IDF 4</option>
										<option value="5" <?php if($user_data['idfID'] == "5") { echo "selected"; } ?>>IDF 5</option>
										<option value="6" <?php if($user_data['idfID'] == "6") { echo "selected"; } ?>>IDF 6</option>
										<option value="7" <?php if($user_data['idfID'] == "7") { echo "selected"; } ?>>IDF 7</option>
										<option value="8" <?php if($user_data['idfID'] == "8") { echo "selected"; } ?>>IDF 8</option>
										<option value="9" <?php if($user_data['idfID'] == "9") { echo "selected"; } ?>>IDF 9</option>
										<option value="10" <?php if($user_data['idfID'] == "10") { echo "selected"; } ?>>SAF</option>
									</select>
								</div>
								<button class="button secondary" type="submit">Update User Info</button>
							</form>
						</div>
						<div id="updatePassword" class="hidden">
							<form action="_modules/account_update.php" method="POST">
								<div class="form-group required">
									<label for="user_pass_confirmation">Password</label>
									<input type="password" id="user_pass_confirmation" name="user_pass_confirmation" data-validation="required length" data-validation-length="min8" placeholder="new password">
								</div>
								<div class="form-group required">
									<label for="user_pass">Re-type Password</label>
									<input type="password" id="user_pass" name="user_pass" data-validation="confirmation" data-validation-error-msg="Password does not match" placeholder="repeat password">
								</div>
								<button class="button secondary" type="submit">Update User Info</button>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>


		<?php // Footer
			require_once '_includes/footer.php'; 
		?>

	</div><!-- #page -->
	
	<script>
		
		$(function() {

			var accLinks = $('.account-links li a');

			accLinks.on('click', function (e) {
				e.preventDefault();

				var id = $(this).attr('href');

				accLinks.not(this).removeClass('active');
				$(this).addClass('active');

				$(accLinks.not(this).attr('href')).addClass('hidden');
				$(id).removeClass('hidden');

			});

			// submit
			$('form').on('submit', function (e) {
				e.preventDefault();

				var link = $(this).attr('action');
				var d = $(this).serialize();
				d = d + '&user_id='+<?php echo $user_id; ?>;

				$.ajax({
					url: link,
					data: d,
					method: 'POST',
					success: function ( response ) {
						if(response == "User details updated successfully!") {
							page.createMsg(response);
						}
					}
				});
			});

		});	

	</script>

</body>
</html>