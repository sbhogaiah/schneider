<?php 
	
	$current_user = "";

	// SESSION CHECK ~~~~~~~~~~~
	if(isset($_SESSION['id'])) {
		$current_user = $_SESSION['fullname'];
		$user_id = $_SESSION['id'];
	}
	// SESSION CHECK ~~~~~~~~~~~
	
?>

<!-- main-nav -->
<nav class="page-nav">
	<div class="wrap">
		<div class="pull-left">
			<a href="<?php echo BASE_URL; ?>index.php" class="brand"><img src="<?php echo BASE_URL; ?>img/se-logo.png" alt="Schneider Electric"></a>
		</div>
		<div class="page-nav--mid">
			<h3>STCC - Testing Infra Realtime Monitoring</h3>
		</div>
		<div class="pull-right">
			<ul class="links">
				<li><a href="#" class="flow-btn" title="View the process flow diagram" data-modal-open="#process-flow"><span class="ico i-flow"></span></a></li>
				<li><a href="<?php echo BASE_URL; ?>index.php" class="home-btn"><span class="ico i-home"></span></a></li> 
				<?php if($current_user): ?>
					<li class="dropdown">
						<span class="logout-btn dropdown-toggle">
							<span class="ico i-person"></span> 
							<?php echo $current_user; ?> 
							<span class="i-arrow-down-b"></span>
						</span>
						<ul class="menu right">
							<?php if($session_role == "production" || $session_role == "logistic"): ?>
								<li><a href="<?php echo BASE_URL; ?>account.php">Account</a></li>
							<?php endif; ?>
							<li><a href="<?php echo BASE_URL; ?>_modules/logout.php">Logout</a></li>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>


<div class="modal" id="process-flow">
	<div class="modal-contents">
		<button class="modal-close" data-modal-close="#process-flow">&times;</button>
		<div class="modal-header">
			<h4>Process Flow</h4>
		</div>
		<div class="modal-body">
			<img src="<?php echo BASE_URL; ?>img/process-flow.jpg" alt="Process flow Image">
		</div>
	</div>
</div>