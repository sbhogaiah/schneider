<?php
	if(isset($_GET['role'])) {
		$role = $_GET['role'];
	}
?>

<!-- HEAD ~~~~~~~~~~~ -->
<?php require_once '_includes/head.php'; ?>
<!-- HEAD ~~~~~~~~~~~ -->

<body class="home">

	<!-- IMPORTANT: DO NOT REMOVE -->
	<div id="page" class="home-alternate">

		<!-- NAVIGATION ~~~~~~~~~~~ -->
		<?php require_once '_includes/nav.php'; ?>
		<!-- NAVIGATION ~~~~~~~~~~~ -->

		<!-- PAGE HEADER ~~~~~~~~~~~ -->
		<header class="page-header">
			<div class="wrap">
				<h1>Welcome to Schneider Electric</h1>
				<p>World Class Testing Infrastructure</p>
			</div>
		</header>
		<!-- PAGE HEADER ~~~~~~~~~~~ -->

		<div class="home-contents">
			<div class="wrap">
				<!-- login-box -->
				<div class="login-box">
					<div class="form-container reset-form">
						<div class="login-form--header">
							<h4><span class="login-area">Forgot Password</span></h4>
						</div>
						<form id="reset_form" action="<?php echo BASE_URL; ?>_modules/reset_pass.php" method="post"> 
						   	<div class="form-group required">
								<label for="user_name">Username</label>
								<input type="text" id="user_name" name="user_name" data-validation="required" placeholder="username / operator id">
							</div>
							<!-- <div class="form-group required">
								<label for="user_email">Email</label>
								<input type="text" id="user_email" name="user_email" data-validation="required email" placeholder="email address">
							</div> -->
							<div class="form-group required">
								<label for="user_pass_confirmation">Password</label>
								<input type="password" id="user_pass_confirmation" name="user_pass_confirmation" data-validation="required length" data-validation-length="min8" placeholder="new password">
							</div>
							<div class="form-group required">
								<label for="user_pass">Re-type Password</label>
								<input type="password" id="user_pass" name="user_pass" data-validation="confirmation" data-validation-error-msg="Password does not match" placeholder="confirm password">
							</div>

							<button type="submit" id="reset_pass_btn" class="button secondary loading"><span>Reset Password</span></button>
							<div class="form-messages">
								Enter username and email to reset password.
							</div>
						</form>
						<a href="<?php echo BASE_URL; ?>" class="login-form--back">
							Cancel
						</a>
					</div>
					<div class="done-msg hidden">
						<h3>Password changed!</h3>
						<p>Your password has been successfully changed. Please <a href="<?php echo BASE_URL; ?>">click here to login.</a></p>
					</div>
				</div>
			</div>
		</div>


		<!-- FOOTER ~~~~~~~~~~~ -->
		<?php require_once '_includes/footer.php'; ?>
		<!-- FOOTER ~~~~~~~~~~~ -->

	</div>
	
	<!-- LOCAL SCRIPTS ~~~~~~~~~~~~~ -->
	<script>
		$(function () {


			var body, loginFormDiv, loginFormId, role;

			body = $('body');

			resetFormDiv = $('#reset_form');

			resetBtn = $('#reset_pass_btn');

			role = '<?php echo $role; ?>';
			
			resetFormDiv.on('submit', function(e) {

				var $this = $(this),
					formMsg = $this.find('.form-messages');

				// prevent the form from submitting itself
				e.preventDefault();

				// activate loading
				$this.find('[type=submit]').addClass('active');

				var formData = $this.serialize() + '&role=' + role;
				var url = $this.attr('action');

				$.ajax({
					method: 'POST',
					url: url,
					data: formData,
					success: function(data) {
						console.log(data);
						switch (data) {
							case "err:1":
								formMsg.addClass('error');
								formMsg.text('No user found with such records! Check username, or role.');
								page.createMsg('No user found with such records! Check username, or role.', 'error');
								// deactivate loading
								setTimeout(function() {
									$this.find('[type=submit]').removeClass('active');
								}, 400);

								break;
							// case "err:2":
							// 	formMsg.addClass('error');
							// 	formMsg.text('Username and email address does not match!');
							// 	// deactivate loading
							// 	setTimeout(function() {
							// 		$this.find('[type=submit]').removeClass('active');
							// 	}, 400);
								
							// 	break;
							default:
								formMsg.removeClass('error');
								$('.form-container').addClass('hidden');
								$('.done-msg').removeClass('hidden');
								// deactivate loading
								setTimeout(function() {
									$this.find('[type=submit]').removeClass('active');
								}, 400);
								break;
						}
						
					}
				});

			});

		});	

	</script>
	<!-- LOCAL SCRIPTS ~~~~~~~~~~~~~ -->

</body>
</html>