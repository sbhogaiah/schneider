<?php 
	// for sidebar active toggle
	$page = "partial_test";
	
	// session
	require_once '_modules/session.php';
	
	// head 
	require_once '_includes/head.php'; 

	// check if correct user
	if($session_role == "baytester"): 
		if($session_location == "1" || $session_location == "8"):

?>
<?php //page functionality
	// TODO: check model id is in database before submit
?>

<body class="partial_test">
	
	<div id="page"><!-- #page -->

		<?php // navigation
			require_once '_includes/nav.php'; 
		?>
		<!-- page-header -->
		<header class="small-page-header">
			<div class="wrap">
				<h3>Partial Testing</h3>
			</div>
		</header>
		<!-- contents -->
		<div class="wrap">
			<!-- login-box -->
			<div class="logistics-box cf">
				<form id="product_form" method="post">
					<div class="form-header">
						<h4>Partial Testing (IDF <?php echo $session_location; ?>)</h4>
					</div>
					<div class="grid">
						<div class="col m-1-2">	
							<div class="form-group required">
								<label for="modelid">Model ID</label>
								<input type="text" id="modelid" name="modelid" placeholder="model id" autocomplete="off" data-validation="required">
							</div>
							<div class="form-group required">
								<label for="serialnum">Serial No.</label>
								<input type="text" id="serialnum" name="serialnum" placeholder="product id" autocomplete="off" data-validation="required">
							</div>
							<div class="form-group">
								<button type="button" id="checkproduct" class="button general">Check Product Status</button>
							</div>
						</div>
						<div class="col m-1-2">
							<div class="current-datetime">
								<p>
									<em>Today's Date</em>
									<span class="date"><?php echo date('d/m/y'); ?></span>
								</p>
								<p>
									<em>Current Time</em>
									<span class="time"><?php echo date('H:i:s'); ?></span>
								</p>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-messages">Scan or enter product details.</div>
					</div>
					<div class="form-footer grid">
						<div class="col m-1-2">	
							<input type="button" class="button primary" id="receive_product" value="Start Test" disabled>
						</div>
						<div class="col m-1-2">	
							<input type="button" class="button secondary" id="send_product" value="Stop Test" disabled>
						</div>
					</div>
				</form>
			</div>

		</div>


		<?php // Footer
			require_once '_includes/footer.php'; 
		?>

	</div><!-- #page -->
	
	<script>
		
		// scripts for this page
		// live date
		setInterval(function() {
			$.ajax({
				url: '_modules/server-time.php',
				method: 'GET',
				success: function(d) {
					var dt = d.split(',');
					$('.current-datetime .date').text(dt[0]);
					$('.current-datetime .time').text(dt[1]);
				}
			});
		}, 1000);


		// product time stamp
		var productUpdate = {

			'sel': {
				'receiveBtn' : '#receive_product',
				'sendBtn' : '#send_product',
				'form' : '#product_form',
				'messages': '.form-messages'
			},

			'idf': parseInt('<?php echo $session_location; ?>'),

			'init': function () {
				
				var obj = this;

				// check product scanned already present
				$('#checkproduct').on('click', function () {
					if($('#serialnum').val().trim().length > 0 && $('#modelid').val().trim().length > 0) {
						obj.checkData(this); 
					} else {
						obj.updateMessage('Please enter or scan Serial Number and Model ID', 'error');
					}
				});

				// events for receive and send
				

			},

			'updateData': function (stage) {
				 
				var obj = this;
				$(obj.sel.receiveBtn+ ',' +obj.sel.sendBtn).on('click', function() {
					var loc = '<?php echo $session_location; ?>';
					var name = '<?php echo $session_username; ?>';
					var formData = $(obj.sel.form).serialize()+'&idfId='+loc+'&username='+name+ '&' +stage;
					page.loadAnim.start('.production-box');
					$.ajax({
						url: '_modules/update_prod_data.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							console.log(d);
							if(d == 'Product received successfully!' || d == 'Product sent successfully!' || d == 'Partial testing started successfully!' || d == 'Partial testing completed successfully!') {
								obj.updateMessage(d, 'success');
								page.createMsg(d, 'success');
								obj.resetForm();
								$(obj.sel.sendBtn).val('Send');
								$(obj.sel.receiveBtn).val('Receive');
							} else {
								obj.updateMessage('Error executing your action. Try again!', 'error');
								page.createMsg('Error executing your action. Try again!', 'error');
								obj.resetForm();
							}
						},
						error: function (d) { console.log(d); },
						complete: function (d) {
							obj.deactivateBtn();
							page.loadAnim.stop('.production-box');
						}
					});
				});	

			},

			'checkData': function (btn) {
				var obj = this; 
				var loc = '<?php echo $session_location; ?>';
				var name = '<?php echo $session_username; ?>';
				var formData = $(obj.sel.form).serialize()+'&idfId='+loc+'&username='+name;
				obj.inputStartLoading('#serialnum');
				$.ajax({
					url: '_modules/check_product.php',
					method: 'post',
					data: formData,
					success: function(d) {
						if(d != "Product not found!" && d != "No such model!") {

							if(d == 'Serial and ModelID do not match!') {
								obj.updateMessage('Product Serial No. and Model ID entered doesn\'t match database record', 'warn');
								obj.deactivateBtn();
							} else {
								//product found
								var data = JSON.parse(d);
								console.log(data);
								if(data.idfID == '1' || data.idfID == '8') {
									// product status
									switch(data.ProductStage) {
										case "1":
											obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Ready to start test.');
											$(obj.sel.receiveBtn).val('Start Test');
											obj.activateBtn('receive');
											obj.updateData('stage=1.5');
										break;
										case "1.5":
											obj.updateMessage('Partial testing for product already started at IDF' + data.idfID + ' testing. Ready to stop test.');
											$(obj.sel.sendBtn).val('Stop Test');
											obj.activateBtn('send');
											obj.updateData('stage=1.8');
										break;
										case "1.8":
											obj.updateMessage('Product already tested at IDF' + data.idfID + ' testing. Not yet dispatched from IDF' + data.idfID + ' logistic.', 'warn');
											obj.deactivateBtn();
										break;
										case "2":
											obj.updateMessage('Product received at IDF' + data.idfID + ' logistics. Not yet dispatched from IDF' + data.idfID + ' logistic.', 'warn');
											obj.deactivateBtn();
										break;
										case "3":
											obj.updateMessage('Product sent from IDF' + data.idfID + ' logistics. Not yet dispatched from IDF4 logistic.', 'warn');
											obj.deactivateBtn();
										break;
										case "4":
											obj.updateMessage('Product received at IDF4 logistics.  Not yet dispatched from IDF4 logistic.', 'warn');
											obj.deactivateBtn();
										break;
										case "5":
											obj.updateMessage('Product already dispatched from IDF4 logistics! Waiting to be received at IDF4 production.', 'warn');
											obj.deactivateBtn();
										break;
										case "6":
											obj.updateMessage('Product received at ID4 production! Waiting to be dispatched from IDF4 production.', 'warn');
											obj.deactivateBtn();
										break;
										case "7":
											obj.updateMessage('Product already dispatched from IDF4 production. Product not yet tested.', 'warn');
											obj.deactivateBtn();
										break;
										case "8":
											obj.updateMessage('Product received at Test Infrastructure. Product not yet tested.', 'warn');
											obj.deactivateBtn();
										break;
										case "9":
											obj.updateMessage('Product dispatched from Test Infrastructure after testing. Waiting to be received at IDF4 production.', 'warn');
											obj.deactivateBtn();
										break;
										case "10":
											obj.updateMessage('Product received at IDF4 production after testing. Waiting to be dispatched from ID4 production.', 'warn');
											obj.deactivateBtn();
										break;
										case "11":
											obj.updateMessage('Product dispatched from IDF4 production after testing. Ready to be received.');
											$(obj.sel.receiveBtn).val('Receive from IDF4 production');
											obj.activateBtn('receive');
											obj.updateData('stage=12');
										break;
										case "12":
											obj.updateMessage('Product received at IDF4 logistics after testing. Ready to be dispatched.');
											obj.activateBtn('send');
											$(obj.sel.sendBtn).val('Send to customer');
											obj.updateData('stage=13');
										break;
										case "13":
											obj.updateMessage('Product already dispatched to the customer. No actions remaining.', 'warn');
											obj.deactivateBtn();
										break;
									} 
								} else {
									//product not found
									obj.updateMessage('Product not qualified for partial testing.', 'warn');
									obj.deactivateBtn();
								}
							}
						} else if(d == "Product not found!") {
							//product not found
							obj.updateMessage('Product not present in database. Can\'t proceed.', 'warn');
							obj.deactivateBtn();
						} else if(d == "No such model!") {
							//Model not found
							obj.updateMessage('Unknown model number, please check with system admin, if problem persists.', 'error');
						}

					}, error: function (d) { console.log(d); },
					complete: function () { obj.inputStopLoading('#serialnum'); }
				});
			},

			'updateMessage': function (msg, flag) {

				var obj = this;
				var msgBox = $(obj.sel.messages);

				flag = typeof flag !== 'undefined' ? flag : 'default';

				if(flag == "error") {
					msgBox.removeClass('success warn').addClass('error');
				} else if(flag == "success") {
					msgBox.addClass('success').removeClass('error warn');
				} else if(flag == "warn") {
					msgBox.addClass('warn').removeClass('error success');
				} else {
					msgBox.removeClass('error warn success');
				}

				msgBox.html(msg); 
			},

			'activateBtn': function (flag) {
				var obj = this;
				flag = typeof flag !== 'undefined' ? flag : 'default';
				if(flag == "send") {
					$(obj.sel.sendBtn).prop('disabled', false);	
					$(obj.sel.receiveBtn).prop('disabled', true);	
				} else if(flag == "receive") {
					$(obj.sel.receiveBtn).prop('disabled', false);	
					$(obj.sel.sendBtn).prop('disabled', true);
				} 
			},

			'deactivateBtn': function () {
				var obj = this;
				$(obj.sel.sendBtn).prop('disabled', true);
				$(obj.sel.receiveBtn).prop('disabled', true);
				$(obj.sel.sendBtn).off('click');
				$(obj.sel.receiveBtn).off('click');
			},

			'resetForm': function() {
				var obj = this;
				$(obj.sel.form).get()[0].reset();
				obj.deactivateBtn();
			},

			'inputStartLoading': function (input) {
				 $(input).closest('.form-group').addClass('loading');
			},
			'inputStopLoading': function (input) {
				setTimeout(function () {
					$(input).closest('.form-group').removeClass('loading');
				}, 400); 
			}
		};

		productUpdate.init();

	</script>
	
<?php else: // if user is not of the proper role, redirect to home ?>
	
	<div class="fixed-center">
		<h2>You cannot access this page.</h2>
		<p>Redirecting you to homepage.</p>
	</div>

	<script type="text/javascript">
		
		setTimeout(function () {
			window.location.href = "<?php echo BASE_URL; ?>";
		}, 1000);

	</script>
<?php endif;
	endif; ?>

</body>
</html>