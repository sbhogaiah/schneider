<?php 
	// for sidebar active toggle
	$page = "production";
	
	// session
	require_once '_modules/session.php';
	
	// head 
	require_once '_includes/head.php'; 

	// check if correct user
	if($session_role == "production"): 

?>
<?php //page functionality
	// TODO: check model id is in database before submit
?>
<body class="production">
	
	<div id="page"><!-- #page -->

		<?php // navigation
			require_once '_includes/nav.php'; 
		?>

	
		<!-- page-header -->
		<header class="small-page-header">
			<div class="wrap">
				<h3>Production Management</h3>
			</div>
		</header>

		<!-- contents -->
		<div class="wrap">
			<!-- login-box -->
			<div class="production-box cf">
				<form id="product_form" method="post">
					<div class="form-header">
						<h4>Production (IDF <?php echo $session_location; ?>)</h4>
					</div>
					<div class="grid">
						<div class="col m-1-2">	
							<div class="form-group">
								<label for="modelid">Model ID</label>
								<input type="text" id="modelid" name="modelid" placeholder="model id" autocomplete="off" data-validation="required">
							</div>
							<div class="form-group">
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
					<div class="idf4-btn <?php if($session_location != "4") { echo 'hidden'; } ?>">
						<div class="col m-2-2">
							<label>
								<input type="checkbox" id="drives_checkbox" disabled>
								Select if the product type is Drives, and manufactured at IDF 4.
							</label>
						</div>
						<div class="col m-1-2">	
							<input type="button" class="button primary" id="idf4_receive_product" value="Receive" disabled>
						</div>
						<div class="col m-1-2">	
							<input type="button" class="button secondary" id="idf4_send_product" value="Send" disabled>
						</div>
					</div>
					<div class="non-idf4-btn <?php if($session_location == "4") { echo 'hidden'; } ?>">
					<?php if($session_location != "1" && $session_location != "8") : ?>
						<div class="center-block m-1-2">	
							<input type="button" class="button secondary" id="new_product" value="Send to logistics" disabled>
						</div>
					<?php else: ?>
						<div class="col m-1-2">	
							<input type="button" class="button secondary" id="new_product" value="Send to logistics" disabled>
						</div>
						<div class="col m-1-2">	
							<input type="button" class="button secondary" id="new_product2" value="Send for partial testing" disabled>
						</div>
					<?php endif; ?>
					</div>
					</div>
				</form>
			</div>

		</div>


		<?php // Footer
			require_once '_includes/footer.php'; 
		?>

	</div><!-- #page -->

	<!-- TODO - handle empty model id -->
	
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
				'idf4ReceiveBtn' : '#idf4_receive_product',
				'idf4SendBtn' : '#idf4_send_product',
				'newProductBtn' : '#new_product, #new_product2',
				'form' : '#product_form',
				'messages': '.form-messages',
				'nonIdf4SendBtn': '.non-idf4-btn',
				'idf4Btn': '.idf4-btn',
				'drivesCheckbox': '#drives_checkbox'
			},

			'idf': parseInt('<?php echo $session_location; ?>'),

			'init': function () {
				
				var obj = this;

				// check product scanned already present
				$('#checkproduct').on('click', function () {
					$(obj.sel.drivesCheckbox).prop('checked', false);
					$(obj.sel.drivesCheckbox).prop('disabled', true);

					if($('#serialnum').val().trim().length > 0 && $('#modelid').val().trim().length > 0) {
						obj.checkData(this); 
					} else {
						obj.updateMessage('Please enter or scan Serial Number and Model ID', 'error');
					}
				});

			},

			'createData': function (btn) {
				 
				var obj = this;

				$(btn).on('click', function() {
					var loc = '<?php echo $session_location; ?>';
					var name = '<?php echo $session_username; ?>';
					var formData = $(obj.sel.form).serialize()+'&idfId='+loc+'&username='+name;
					page.loadAnim.start('.production-box');
					$.ajax({
						url: '_modules/create_product.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							obj.updateMessage(d, 'success');
							page.createMsg(d, 'success');
							obj.resetForm();
							$(obj.sel.drivesCheckbox).prop('disabled', true);
						},
						error: function (d) { console.log(d); },
						complete: function (d) {
							page.loadAnim.stop('.production-box');
						}
					});
				});
			
			},

			'updateData': function (stage) {
				 
				var obj = this;
				$(obj.sel.idf4ReceiveBtn+ ',' +obj.sel.idf4SendBtn).on('click', function() {
					var loc = '<?php echo $session_location; ?>';
					var name = '<?php echo $session_username; ?>';
					var formData = $(obj.sel.form).serialize()+'&idfId='+loc+'&username='+name+ '&' +stage;
					page.loadAnim.start('.production-box');
					$.ajax({
						url: '_modules/update_prod_data.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							console.log('Updated: Success');
							if(d == 'Product received successfully!' || d == 'Product sent successfully!') {
								obj.updateMessage(d, 'success');
								page.createMsg(d, 'success');
								obj.resetForm();
								$(obj.sel.idf4ReceiveBtn).val('Receive');
								$(obj.sel.idf4SendBtn).val('Send');
							} else {
								obj.updateMessage('Error executing your action. Try again!', 'error');
								page.createMsg('Error executing your action. Try again!', 'error');
								obj.resetForm();
							}
						},
						error: function (d) { console.log(d); },
						complete: function (d) {
							page.loadAnim.stop('.production-box');
						}
					});
				});	

			},

			'drivesCheck' : function () {
				var obj = this;
				$(obj.sel.drivesCheckbox).off('click');
				$(obj.sel.drivesCheckbox).on('click', function() {

					// console.log($(this).prop('checked'));
					if($(this).prop('checked')) {
						obj.activateBtn('idf4', 'send');
						$(obj.sel.idf4SendBtn).val('Send to Test Infra');
						obj.createData(obj.sel.idf4SendBtn);
					} else {
						obj.deactivateBtn('idf4','send');
					}

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
						// console.log(d);
						if(d != "Product not found!" && d != "No such model!") {
							$(obj.sel.drivesCheckbox).prop('disabled', true);
							if(d == 'Serial and ModelID do not match!') {
								obj.updateMessage('Product Serial No. and Model ID entered doesn\'t match database record', 'warn');
								obj.deactivateBtn();
							} else {
								//product found
								var data = JSON.parse(d);
								if(obj.idf != 4) {
									obj.updateMessage('Product already present in database! Check Serial Number', 'error');
									page.createMsg('Product already present in database! Check Serial Number', 'error');
									obj.deactivateBtn('nonidf4');
								} else {
									// console.log('idf4 and already present. ' + 'Stage: ' + data.ProductStage);
									obj.deactivateBtn('idf4');
									
									// product status
									switch(data.ProductStage) {
										case "1":
											obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "1.5":
											obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
											obj.deactivateBtn();
										break;
										case "1.8":
											obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
											obj.deactivateBtn();
										break;
										case "2":
											obj.updateMessage('Product received at IDF' + data.idfID + ' logistics. Not yet received at IDF4 logistics. Can\'t proceed.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "3":
											obj.updateMessage('Product dispatched from IDF' + data.idfID + ' logistics. Not yet received at IDF4 logistics. Can\'t proceed.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "4":
											obj.updateMessage('Product received at IDF4 logistics. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "5":
											obj.updateMessage('Product dispatched from IDF4 logistics. Ready to be received.');
											$(obj.sel.idf4ReceiveBtn).val('Receive from IDF4 Logistics');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
											obj.activateBtn('idf4', 'receive');
											obj.updateData('stage=6');
										break;
										case "6":
											obj.updateMessage('Product received at IDF4 production. Ready to be dispatched.');
											$(obj.sel.idf4SendBtn).val('Send to Test Infra');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
											obj.activateBtn('idf4', 'send');
											obj.updateData('stage=7');
										break;
										case "7":
											obj.updateMessage('Product already dispatched from IDF4 production. Product not yet tested.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "8":
											obj.updateMessage('Product received at Test Infrastructure. Product not yet tested.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "9":
											obj.updateMessage('Product sent from Test Infrastructure after testing. Ready to be received.');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
											$(obj.sel.idf4ReceiveBtn).val('Receive from Test Infra');
											obj.activateBtn('idf4', 'receive');
											obj.updateData('stage=10');
										break;
										case "10":
											obj.updateMessage('Product received at IDF4 production after testing. Ready to be dispatched.');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
											$(obj.sel.idf4SendBtn).val('Send to IDF4 Logistics');
											obj.activateBtn('idf4', 'send');
											obj.updateData('stage=11');
										break;
										case "11":
											obj.updateMessage('Product already sent from IDF4 production after testing.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "12":
											obj.updateMessage('Product already sent. Product received at IDF4 logistics after testing.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
										case "13":
											obj.updateMessage('Product already sent. Product sent from IDF4 logistics after testing to customer.', 'warn');
											$(obj.sel.drivesCheckbox).prop('disabled', true);
										break;
									}

								}
							}
						} else if(d == "Product not found!") {
							obj.deactivateBtn('nonidf4');
							obj.deactivateBtn('idf4');
							$(obj.sel.drivesCheckbox).prop('disabled', true);
							//product not found
							if(obj.idf != 4) {
								obj.updateMessage('Product not present in database. Press Send.');
								obj.activateBtn('nonidf4');
								obj.createData(obj.sel.newProductBtn);
							} else if(obj.idf == 4) {
								obj.updateMessage('Product not present in database.', 'warn');
								$(obj.sel.drivesCheckbox).prop('disabled', false);
								obj.drivesCheck();
							}
						} else if(d == "No such model!") {
							//Model not found
							obj.updateMessage('Unknown model number, please check with system admin, if problem persists.', 'error');
							$(obj.sel.drivesCheckbox).prop('disabled', true);
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

			'activateBtn': function (btn, flag) {
				var obj = this;
				flag = typeof flag !== 'undefined' ? flag : 'default';
				if(btn == 'nonidf4') {
					page.element.hide(obj.sel.idf4Btn);
					$(obj.sel.idf4Btn).find('input[type="button"]').prop('disabled', true);

					page.element.show(obj.sel.nonIdf4SendBtn);
					$(obj.sel.nonIdf4SendBtn).find('input[type="button"]').prop('disabled', false);
				} else if(btn == 'idf4') {
					page.element.hide(obj.sel.nonIdf4SendBtn);
					$(obj.sel.nonIdf4SendBtn).find('input[type="button"]').prop('disabled', true);
					page.element.show(obj.sel.idf4Btn);
					
					if(flag == "send") {
						$(obj.sel.idf4Btn).find('input#idf4_send_product').prop('disabled', false);
					} else if(flag == "receive") {
						$(obj.sel.idf4Btn).find('input#idf4_receive_product').prop('disabled', false);	
					} else {
						$(obj.sel.idf4Btn).find('input[type="button"]').prop('disabled', false);	
					}
					
				}
				$(obj.sel.nonIdf4SendBtn).find('input[type="button"]').off('click');
				$(obj.sel.idf4Btn).find('input[type="button"]').off('click');
			},

			'deactivateBtn': function (btn) {
				var obj = this;
				if(btn == 'nonidf4') {
					page.element.hide(obj.sel.idf4Btn);
					$(obj.sel.idf4Btn).find('input[type="button"]').prop('disabled', true);
					page.element.show(obj.sel.nonIdf4SendBtn);
					$(obj.sel.nonIdf4SendBtn).find('input[type="button"]').prop('disabled', true);
				} else if(btn == 'idf4') {
					page.element.hide(obj.sel.nonIdf4SendBtn);
					page.element.show(obj.sel.idf4Btn);
					$(obj.sel.nonIdf4SendBtn).find('input[type="button"]').prop('disabled', true);
					$(obj.sel.idf4Btn).find('input[type="button"]').prop('disabled', true);
				}
				$(obj.sel.nonIdf4SendBtn).find('input[type="button"]').off('click');
				$(obj.sel.idf4Btn).find('input[type="button"]').off('click');
			},

			'resetForm': function() {
				var obj = this;
				$(obj.sel.form).get()[0].reset();
				if(obj.idf == 4) {
					obj.deactivateBtn('idf4');
				} else {
					obj.deactivateBtn('nonidf4');
				}
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

		// console.log("<?php // echo BASE_URL; ?>");
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
<?php endif; ?>

</body>
</html>