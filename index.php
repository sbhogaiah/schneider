<?php
	session_start();

	// SESSION CHECK ~~~~~~~~~~~
	$session_username = "";
	$session_role = "";
	$session_fullname = "";
	$session_location = "";

	if(isset($_SESSION['id'])) {
		$session_username = $_SESSION['username'];
		$session_role = $_SESSION['role'];
		$session_location = $_SESSION['user_location'];
		$session_fullname = $_SESSION['fullname'];
	}
?>

<!-- HEAD ~~~~~~~~~~~ -->
<?php require_once '_includes/head.php'; ?>
<!-- HEAD ~~~~~~~~~~~ -->

<body class="home loading-animation">
	<script type="text/javascript">
	if (screen.width <= 899) {
		$.ajax({
			method: 'POST',
			url: '<?php echo BASE_URL; ?>_modules/login.php',
			data: 'role=guest',
			complete: function(data) {
				window.location.href = data.responseText;
			}
		});
	} else {
		setTimeout(function (argument) {
			page.loadAnim.stop('.home');
		}, 500);
	}
	</script>

	<!-- IMPORTANT: DO NOT REMOVE -->
	<div id="page" class="home-alternate">

		<!-- NAVIGATION ~~~~~~~~~~~ -->
		<?php require_once '_includes/nav.php'; ?>
		<!-- NAVIGATION ~~~~~~~~~~~ -->

		<!-- PAGE HEADER ~~~~~~~~~~~ -->
		<header class="page-header">
			<div class="wrap">
				<h1>Welcome to Schneider Electric ITBU</h1>
				<p>World Class Testing Infrastructure</p>
			</div>
		</header>
		<!-- PAGE HEADER ~~~~~~~~~~~ -->
		
		<!-- CONTENT ~~~~~~~~~~~ -->
		<div class="home-contents">
			<div class="wrap">
				<!-- login-box -->
				<div class="login-box">
					
					<?php
						// check if user already logged in
						if(!$session_username):
					?>
						<div class="login-btns">
							<h4>Welcome to STCC | Please login...</h4>
							<ul class="list-unstyled">
								<li><a class="login-btns--production" data-login-btn="production" href="#"><span class="ico"><img src="img/production.png"></span><span class="label">Production</span></a></li>
								<li><a class="login-btns--logistics" data-login-btn="logistic" href="#"><span class="ico"><img src="img/logistic.png"></span><span class="label">Logistics</span></a></li>
								<li><a class="login-btns--tester" data-login-btn="baytester" href="#"><span class="ico"><img src="img/testing.png"></span><span class="label">Testing</span></a></li>
								<li><a class="login-btns--admin" data-login-btn="admin" href="#"><span class="ico"><img src="img/admin.png"></span><span class="label">Admin</span></a></li>
								<li><a class="login-btns--guest" href="#"><span class="ico i-eye"></span><span class="label">Guest</span></a></li>
								<li><a class="login-btns--flow" href="#" data-modal-open="#process-flow"><span class="ico i-flow"></span><span class="label">Process Flow</span></a></li>
							</ul>
						</div>

						<div class="login-form">
							<div class="login-form--header">
								<h4><span class="login-area">Production</span> Login</h4>
							</div>
							<form id="login_form" action="<?php echo BASE_URL; ?>_modules/login.php" method="post" enctype="multipart/form-data">
								<div class="form-group required">
									<label for="username">Username</label>
									<input type="text" id="username" name="username" placeholder="user id" data-validation="required alphanumeric">
								</div>
								<div class="form-group required">
									<label for="password">Password</label>
									<input type="password" id="password" name="password" placeholder="password" data-validation="required">
								</div>
								<div class="form-group">
									<a id="reset_pass_link" href="reset_password.php">Forgot Password?</a>
								</div>

								<!-- hidden role input, changed by js -->
								<input type="hidden" name="role" id="role" value="null">

								<button type="submit" name="login" class="button secondary loading"><span>Login</span></button>

								<div class="form-messages">
									Please enter username and password
								</div>
							</form>
							<div class="login-form--back">
								back
							</div>
						</div>
					<?php else: //if logged in then show log out and a message ?>
						<div class="logout-box">
							<h4><b>Welcome <?php echo $session_fullname; ?></b></h4>
							<p>You are already logged in...</p>

							<?php if($session_role == "admin" || $session_role == "guest"): ?>
								<p>Go back to <a class="button secondary" href="<?php echo BASE_URL; ?>admin_charts.php"><?php echo $session_role; ?></a> page</p>
							<?php else: ?>
								<?php if($session_role == "baytester"): 
									if($session_location == "1" || $session_location == "8"): ?>
										<p>Go back to <a class="button secondary" href="<?php echo BASE_URL?>partial_test.php"> partial testing</a> page</p>
									<?php elseif($session_location == "4"): ?>
										<p>Go back to <a class="button secondary" href="<?php echo BASE_URL . $session_role; ?>.php"><?php echo $session_role; ?></a> page</p>
									<?php endif; ?>
								<?php else: ?>
									<p>Go back to <a class="button secondary" href="<?php echo BASE_URL . $session_role; ?>.php"><?php echo $session_role; ?></a> page</p>
								<?php endif; ?>
							<?php endif; ?>
							<hr>
							<p>Or, <a class="button secondary" href="<?php echo BASE_URL ?>_modules/logout.php">Logout</a> and login as another user.</p>
						</div>
					<?php endif; ?>
				</div>

			</div>
		</div>	
		<!-- CONTENT ~~~~~~~~~~~ -->

		<!-- FOOTER ~~~~~~~~~~~ -->
		<?php require_once '_includes/footer.php'; ?>
		<!-- FOOTER ~~~~~~~~~~~ -->

	</div>
	

	<!-- LOCAL SCRIPTS ~~~~~~~~~~~~~~ -->
	<script>
		$(function () {

			// for changing to login form
			// once one of the login area buttons is pressed

			var body, loginButtons, loginFormDiv, loginForm_Heading, loginForm_Back, loginFormId;

			body = $('body');

			loginButtons = $('.login-btns [data-login-btn]');

			loginFormDiv = $('.login-form');

			loginForm_Heading = loginFormDiv.find('.login-form--header .login-area');

			loginForm_Back = loginFormDiv.find('.login-form--back');

			loginFormId = $('#login_form');

			// to change to form
			loginButtons.on('click', function(e) {
				e.preventDefault();

				var areaLabel = $(this).data('login-btn');

				// assign the areaLabel to the header of the form
				loginForm_Heading.text(areaLabel);
				body.addClass(areaLabel);

				// change action of the role input to choose which role of user
				$('#role').val(areaLabel);
				$('#reset_pass_link').attr('href', 'reset_password.php?role='+areaLabel);

				// add Class to the body to open the form
				// adding class to body because we can use the same body's class later for doing extra stuff to page if needed
				body.addClass('login-form--open');

			});

			// back to buttons
			loginForm_Back.on('click', function() {
				body.removeClass('login-form--open production logistic baytester');
				loginFormId.get()[0].reset();
				$('.form-messages').removeClass('error');
				$('.form-messages').text('Please enter username and password.');
			});

			// ajax login 
			function redirectToPage( pageUrl ) {

				window.location.href = pageUrl;

			}

			loginFormId.on('submit', function(e) {

				var $this = $(this),
					formMsg = $this.find('.form-messages');

				// prevent the form from submitting itself
				e.preventDefault();

				// activate loading
				$this.find('[type=submit]').addClass('active');

				var formData = $this.serialize();
				var url = $this.attr('action');

				$.ajax({
					method: 'POST',
					url: url,
					data: formData,
					complete: function(data) {
						
						switch (data.responseText) {
							case "err:1":
								formMsg.addClass('error');
								formMsg.text('Wrong username or password!');

								// deactivate loading
								setTimeout(function() {
									$this.find('[type=submit]').removeClass('active');
								}, 400);

								break;
							case "err:2":
								formMsg.addClass('error');
								formMsg.text('Choose the correct role!');

								// deactivate loading
								setTimeout(function() {
									$this.find('[type=submit]').removeClass('active');
								}, 400);
								
								break;
							default:
								formMsg.removeClass('error');
								formMsg.text('Please wait! Redirecting...');
								redirectToPage(data.responseText);
								break;
						}

						
					}
				});

			});

			// guest login
			$('.login-btns--guest').on('click', function () {
				
				$.ajax({
					method: 'POST',
					url: '<?php echo BASE_URL; ?>_modules/login.php',
					data: 'role=guest',
					complete: function(data) {
						redirectToPage(data.responseText);
					}
				});

			});

		});	

	</script>
	<!-- LOCAL SCRIPTS ~~~~~~~~~~~~~ -->
</body>
</html>