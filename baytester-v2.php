<?php 
	// for sidebar active toggle
	$page = "bay_testing";
	// session
	require_once '_modules/session.php';
	// head 
	require_once '_includes/head.php'; 
	// check if correct user
	if($session_role == "baytester" && $session_location == "4"): 
?>

<?php //page functionality
	$get_bays_query = "SELECT id, BayName, Status FROM ppi";
	$run_get_bays_query = mysqli_query($db, $get_bays_query) or die('Can\'t get bays! Refresh page.');
?>
<body class="home">
	<div id="page"><!-- #page -->
		<?php require_once '_includes/nav.php'; // navigation ?>
		<!-- page-header -->
		<header class="small-page-header">
			<div class="wrap">
				<h3>Test Infrastructure</h3>
			</div>
		</header>
		<!-- contents -->
		<div class="wrap">
			<div class="col l-2-12">
				<div class="test-instructions">
					<h4>Instructions</h4>
					<ol>
						<li>Scan or enter product details. Then, check product details.</li>
						<li>Receive product.</li>
						<li>Select bay, start testing.</li>
						<li>Stop testing, if test fails.</li>
						<li>Resume testing, after stopping test.</li>
						<li>If there is another failure during testing, stop and resume testing again.</li>
						<li>Complete testing.</li>
						<li>Send product.</li>
					</ol>
					<span class="note">
						<strong>Note:</strong> In case of any problems, please contact administration.
					</span>
				</div>
			</div>
			<div class="col l-8-12">
				<!-- testing-box -->
				<div class="testing-box cf">
					<div class="active-testers hidden">
						Active testers: <span class="value"><span class="t_name">Set tester name...</span></span> 
						<span class="i-plus" id="tester-name" data-model-open="#add-tester"></span>
						<span class="i-edit hidden" id="edit-tester-name" data-model-open="#edit-tester"></span>
					</div>
					<form id="product_form" action="#" method="post">
						<div class="form-header">
							<h4>Testing Bay Details</h4>
							<span class="status-msg in-progress hidden">Test in progress...</span>
							<span class="status-msg in-error hidden">Test failed!</span>
							<span class="status-msg in-complete hidden">Test Completed!</span>
						</div>
						<div class="grid">
							<div class="col m-5-12">	
								<div class="form-group required">
									<label for="modelid">Model ID</label>
									<input type="text" id="modelid" name="modelid" placeholder="model id" data-validation="required">
								</div>
								<div class="form-group required">
									<label for="serialnum">Serial No.</label>
									<input type="text" id="serialnum" name="serialnum" placeholder="product id" data-validation="required">
								</div>
								<div class="form-group">
									<button type="button" id="checkproduct" class="button general">Check Product Status</button>
								</div>
								<div class="form-group required">
									<label for="bay-name">Bay No.</label>
									<select name="bay-name" id="bay-name" disabled>
										<option selected disabled>Select a bay, before testing</option>
									<?php while ($bay = mysqli_fetch_assoc($run_get_bays_query)) { ?>
										<option value="<?php echo $bay['id'];?>" <?php if($bay['Status']!='idle') { ?>disabled<?php } ?>><?php echo $bay['BayName'];?></option>
									<?php }	?>
								</select>
								</div>
							</div>
							<div class="col m-7-12">
								<div class="testing-controls">
									<div class="pretest-controls">
										<div class="test-box-heading">
											<strong>Pre-test controls</strong>
										</div>
										<div class="form-group">
											<label>
												<input type="checkbox" value="enable final test">
												Check this box, if you want do a <strong>Final Test</strong> for this product, before burning test.
											</label>
										</div>
										<div class="form-group">
											<input type="button" class="button" id="start-btn" value="Start Test" disabled>
										</div>
									</div>
									<div class="outline-box cf hidden" id="test-fail-controls"><!-- Fail Controls -->
										<span class="outline-box-heading">If test fails</span>
										<div class="form-group">
											<select name="failure-reason" id="failure-reason" disabled>
												<option selected disabled>Select reason for failure</option>
												<option value="Power Failure">Power Failure</option>
												<option value="Bay Down">Bay Down</option>
												<option value="Unit Failure">Unit Failure</option>
											</select>
										</div>
										<div class="form-group">
											<input type="button" class="button" id="stop-btn" value="Stop Test" disabled>
											<input type="button" class="button" id="resume-btn" value="Resume Test" disabled>
										</div>
										<div class="form-group">
											<!-- <label for="standby_remarks">Major Failure?</label> -->
											<textarea name="standby_remarks" id="standby_remarks" placeholder="In case of any major failure, enter some remarks about the failure and remove the product from bay."></textarea>
										</div>
										<div class="form-group">
											<input type="button" class="button general" id="remove-btn" value="Major failure! Send to rework" disabled>
										</div>
									</div><!-- Fail Controls -->
									<div class="form-group">
										<input type="button" class="button hidden" id="complete-btn" value="Complete Test" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="form-messages">Scan or enter product details.</div>
						<div class="current-datetime cf">
							<p class="col l-1-2">
								<em>Today's Date</em>
								<span class="date"><?php echo date('d/m/y'); ?></span>
							</p>
							<p class="col l-1-2">
								<em>Current Time</em>
								<span class="time"><?php echo date('H:i:s'); ?></span>
							</p>
						</div>
						<div class="form-footer grid">
							<div class="col l-1-2">	
								<input type="button" class="button primary" id="receive_product" value="Received from production" disabled>
							</div>
							<div class="col l-1-2">	
								<input type="button" class="button secondary" id="send_product" value="Send to production" disabled>
							</div>
						</div>
					</form>
				</div>
				<!-- testing-box -->
			</div>
			<div class="col m-2-12">
				<div class="test-chart">
					<h4>Test Chart</h4>
					<div class="chart-container">
						<canvas id="test-chart" width="100%"></canvas>
					</div>
				</div>
			</div>
		</div>
		
		<!-- add tester box -->
		<div class="modal" id="add-tester">
			<div class="modal-contents">
				<button class="modal-close" data-modal-close="#add-tester">&times;</button>
				<div class="modal-header">
					<h4>Add tester</h4>
				</div>
				<div class="modal-body">
					<form id="add-tester-form">
						<div class="form-header">
							<h4>Set Tester</h4>
						</div>
						<div class="form-group">
							<label for="set-tester">Name</label>
							<input type="text" id="set-tester" name="set-tester" placeholder="enter your name..." data-validation="required">
						</div>
						<input type="submit" class="button primary" value="Add">
					</form>
				</div>
			</div>
		</div>

		<!-- edit tester box -->
		<div class="modal" id="edit-tester">
			<div class="modal-contents">
				<button class="modal-close" data-modal-close="#edit-tester">&times;</button>
				<div class="modal-header">
					<h4>Edit tester</h4>
				</div>
				<div class="modal-body">
					<form id="edit-tester-form">
						<div class="form-header">
							<h4>Edit Tester</h4>
						</div>
						<div class="input-boxes">
							
						</div>
						<input type="submit" class="button primary" value="Commit">
					</form>
				</div>
			</div>
		</div>

		<!-- contents -->
		<?php require_once '_includes/footer.php'; // Footer ?>
	</div><!-- #page -->
	
	<script type="text/javascript" src="js/chart-options.js"></script>

<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
	<script>

		// When the document is ready 
		$(function() {
			testProcess.init();
		});

		// variable definition
		var testProcess;
		
		// test process object
		testProcess = {
			'sel': {
				'receiveBtn' : '#receive_product',
				'sendBtn' : '#send_product',
				'form' : '#product_form',
				'messages': '.form-messages',
				'startBtn': '#start-btn',
				'stopBtn': '#stop-btn',
				'reasonSelect': '#failure-reason',
				'resumeBtn': '#resume-btn',
				'removeBtn': '#remove-btn',
				'completeBtn': '#complete-btn',
				'failControls': '#test-fail-controls',
				'standbyRemarks' : '#standby_remarks',
				'baySelect': '#bay-name',
				'checkProductBtn': '#checkproduct',
				'serialNum': '#serialnum',
				'modelId': '#modelid',
				'testerNameBlock' : '.active-testers',
				'testerNameBtn' : '#tester-name',
				'addTesterForm': '#add-tester-form',
				'editTesterForm': '#edit-tester-form'
			},
			'idf': parseInt('<?php echo $session_location; ?>'),
			
			/*
			 * Initialize the methods supposed to start at page load
			 */
			'init': function () {
				var obj = this;

				//check test activity as page loads
				obj.checkTestStatus();



				// start - check product scanned already present
				$(obj.sel.checkProductBtn).on('click', function () {
					if($(obj.sel.serialNum).val().trim().length > 0 && $(obj.sel.modelId).val().trim().length > 0) {
						obj.checkData(this); 
					} else {
						obj.updateMessage('Please enter or scan Serial Number and Model ID', 'error');
					}
				}); // end

				// // add tester
				// obj.addTester();

				// // edit tester
				// obj.editTester();
			},

			/*
			 * Check Test Status
			 * And set the proper page view based on if test is running or stopped or completed
			 */
			'checkTestStatus': function() {
				var obj = this;
				var name = '<?php echo $session_username; ?>';
				var formData = 'username='+name;
				$.ajax({
					url: '_modules/test/check_activity.php',
					method: 'POST',
					data: formData,
					success: function(d) {
						if(d) {
							page.loadAnim.stop('#page');
							var data = JSON.parse(d);

							if (data.TestStage) { // test running

								switch (data.TestStage) {
									case '1':
										obj.testStage1(data.SerialNumber, data.ModelID, data.BayID);
										break;
									case '2':
										obj.testStage2(data.SerialNumber, data.ModelID, data.BayID);
										break;
									case '3':
										obj.testStage3(data.SerialNumber, data.ModelID, data.BayID);
										break;
									default:
										obj.testStage0();
										console.log('No test is running!');
										break;
								}

								// tester
								if(data.Tester) {
									var testerNames = data.Tester.trim().substring(0, data.Tester.length-1).split(',');

									$('#edit-tester-name').removeClass('hidden');
									$(obj.sel.testerNameBlock).find('.value').text('');
									$.each(testerNames, function(i, e) {
										$(obj.sel.testerNameBlock).find('.value').append('<span class="t_name">'+e+'</span>');
									});
								}

							} else { // no test
								console.log('No test is running!');
								obj.testStage0();
							}
						} else { console.log('no data from server!'); }
					},
					error: function(data) { 
						page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
					}
				});
			},

			/*
			 * Check Data of product
			 * When check product status button is clicked, and ready the product for testing stages
			 */
			'checkData': function (btn) {
				var obj = this; 
				var loc = '<?php echo $session_location; ?>';
				var name = '<?php echo $session_username; ?>';
				var serial = $(obj.sel.serialNum).val().trim();
				var model = $(obj.sel.modelId).val().trim();
				var formData = 'serialnum='+serial+'&modelid='+model+'&idfId='+loc+'&username='+name;
				obj.inputStartLoading('#serialnum');

				$.ajax({
					url: '_modules/check_product.php',
					method: 'post',
					data: formData,
					success: function(d) {
						if(d != "Product not found!" && d != "No such model!") {
							if(d == 'Serial and ModelID do not match!') {
								obj.updateMessage('Product Serial No. and Model ID entered doesn\'t match database record', 'warn');
								obj.deactLogBtns();
							} else {
								//product found
								var data = JSON.parse(d);

								// product status
								switch(data.ProductStage) {
									case "1":
										obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "2":
										obj.updateMessage('Product received at IDF' + data.idfID + ' logistics. Not yet received at IDF4 logistics. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "3":
										obj.updateMessage('Product dispatched from IDF' + data.idfID + ' logistics. Not yet received at IDF4 logistics. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "4":
										obj.updateMessage('Product received at IDF4 logistics. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "5":
										obj.updateMessage('Product dispatched from IDF4 logistics! Not yet dispatched from IDF4 production. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "6":
										obj.updateMessage('Product received at ID4 production! Not yet dispatched from IDF4 production. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "7":
										obj.updateMessage('Product dispatched from IDF4 production. Ready to be received.');
										obj.activateBtn('receive');
										obj.updateData('stage=8');
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "8":
										if(data.CurStatus !== 'Testing') {
											obj.updateMessage('Product received at Test Infrastructure. To start test, select a bay.');
											obj.activateBaySelect();
										} else {
											obj.updateMessage('Product is being tested in another bay. Can\'t proceed.', 'warn');
											obj.deactivateBaySelect();
										}
									break;
									case "9":
										obj.updateMessage('Product already tested.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "10":
										obj.updateMessage('Product already tested.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "11":
										obj.updateMessage('Product already tested.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "12":
										obj.updateMessage('Product already tested.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "13":
										obj.updateMessage('Product already dispatched to the customer. No actions remaining.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
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

			/*
			 * Test Stage 0
			 * Before the test starts
			 */
			'testStage0': function () {
				var obj = this;

				// reset the form, remove the values 
				obj.resetForm();

				// serial, model, and check Status
				$(obj.sel.checkProductBtn).prop('disabled', false);
				$(obj.sel.serialNum).prop('disabled', false);
				$(obj.sel.modelId).prop('disabled', false);

				// Bay select box
				$(obj.sel.baySelect).find('option:first').prop('selected', true);
				$(obj.sel.baySelect).prop('disabled', true);

				// test start
				$(obj.sel.testStartBtn).prop('disabled', true).removeClass('hidden');

				// test stop
				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');

				// test fail reason
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	

				// test resum
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	

				// test complete
				$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');

				// test fail control box
				$(obj.sel.testFailControls).addClass('hidden');

				// major failure remoarks
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');

				// tester name add
				$(obj.sel.testerNameBlock).addClass('hidden');

				// disable receive and send button
				obj.deactLogBtns();

				// hide current status of test
				$('.in-progress').addClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				obj.updateMessage('Scan or enter product details.');
			},

			/*
			 * Test Stage 1
			 * Test Start and running normally
			 */
			'testStage1': function (serial, model, bayid) {
				var obj = this;

				// serial, model, and check Status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);

				// Bay select box
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				$(obj.sel.baySelect).prop('disabled', true);

				// test start
				$(obj.sel.testStartBtn).prop('disabled', true).addClass('hidden');

				// test stop
				$(obj.sel.testStopBtn).prop('disabled', true).removeClass('hidden');

				// test fail reason
				$(obj.sel.testReasonSelect).prop('disabled', false).removeClass('hidden');	

				// test resum
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	

				// test complete
				$(obj.sel.testCompleteBtn).prop('disabled', false).removeClass('hidden');

				// test fail control box
				$(obj.sel.testFailControls).addClass('hidden');

				// major failure remoarks
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');

				// tester name add
				$(obj.sel.testerNameBlock).removeClass('hidden');

				// disable receive and send button
				obj.deactLogBtns();

				// hide current status of test
				$('.in-progress').removeClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				page.createMsg('Test in progress!');
				obj.updateMessage('Test in progress!');
			},

			/*
			 * Test Stage 2
			 * Test Stopped after failure
			 */
			'testStage2': function (serial, model, bayid) {
				var obj = this;

				// serial, model, and check Status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);

				// Bay select box
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				$(obj.sel.baySelect).prop('disabled', true);

				// test start
				$(obj.sel.testStartBtn).prop('disabled', true).addClass('hidden');

				// test stop
				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');

				// test fail reason
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	

				// test resum
				$(obj.sel.testResumeBtn).prop('disabled', false).removeClass('hidden');	

				// test complete
				$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');

				// test fail control box
				$(obj.sel.testFailControls).removeClass('hidden');

				// major failure remoarks
				$(obj.sel.standbyRemarks).prop('disabled', false).removeClass('hidden');

				// tester name add
				$(obj.sel.testerNameBlock).removeClass('hidden');

				// disable receive and send button
				obj.deactLogBtns();

				// hide current status of test
				$('.in-progress').addClass('hidden');
				$('.in-error').removeClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				page.createMsg('Test in progress!');
				obj.updateMessage('Test in progress!');
			},

			/*
			 * Test Stage 3
			 * Test Complete 
			 */
			'testStage3': function (serial, model, bayid) {
				var obj = this;

				// serial, model, and check Status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);

				// Bay select box
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				$(obj.sel.baySelect).prop('disabled', true);

				// test start
				$(obj.sel.testStartBtn).prop('disabled', true).addClass('hidden');

				// test stop
				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');

				// test fail reason
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	

				// test resum
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	

				// test complete
				$(obj.sel.testCompleteBtn).prop('disabled', true).removeClass('hidden');

				// test fail control box
				$(obj.sel.testFailControls).addClass('hidden');

				// major failure remoarks
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');

				// tester name add
				$(obj.sel.testerNameBlock).addClass('hidden');

				// activate send button
				obj.activateBtn('send');
				obj.updateData('stage=9');

				// hide current status of test
				$('.in-progress').addClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').removeClass('hidden');

				// messages
				page.createMsg('Test Completed!');
				obj.updateMessage('Test Completed! The product is ready to be dispatched.');
			},

			/*
			 * Activate receive and send buttons 
			 */
			'actLogBtns': function (flag) {
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

			/*
			 * Deactivate receive and send buttons 
			 */
			'deactLogBtns': function () {
				var obj = this;
				$(obj.sel.sendBtn).prop('disabled', true);
				$(obj.sel.receiveBtn).prop('disabled', true);
				$(obj.sel.sendBtn).off('click');
				$(obj.sel.receiveBtn).off('click');
			},

			/*
			 * To display status messages in the window 
			 */
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

			/*
			 * Product Form Reset 
			 */
			'resetForm': function() {
				var obj = this;
				$(obj.sel.form).get()[0].reset();
				obj.deactLogBtns();
			},

			/*
			 * Input box loading animation
			 */
			'inputStartLoading': function (input) {
				 $(input).closest('.form-group').addClass('loading');
			},

			'inputStopLoading': function (input) {
				setTimeout(function () {
					$(input).closest('.form-group').removeClass('loading');
				}, 400); 
			},
			
		};

	</script>
<!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

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