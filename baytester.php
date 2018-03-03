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
	<div id="page" class="loading-animation"><!-- #page -->
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
					<div class="active-testers" class="hidden">
						Active testers: <span class="value"><span class="t_name">Set tester name...</span></span> 
						<span class="i-plus" id="tester-name" data-model-open="#add-tester"></span>
						<span class="i-edit hidden" id="edit-tester-name" data-model-open="#edit-tester"></span>
					</div>
					<form id="product_form" action="" method="post">
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
										<div class="form-group final-checkbox">
											<label>
												<input type="checkbox" value="enable final test" id="final-test-check" disabled>
												<span>Check this box, if you want do a <strong>Final Test</strong> for this product, before burning test.</span>
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
									<div class="form-group">	
										<input type="button" class="button hidden" id="pause-btn" value="Pause & resume later" disabled>
									</div>
									<div class="form-group">	
										<input type="button" class="button hidden" id="continue-btn" value="Continue Test" disabled>
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

		// modal event
		$('#tester-name').on('click', function() {
			modal.openModal('#add-tester');
		});

		// modal event
		$('#edit-tester-name').on('click', function() {

			$('#edit-tester .input-boxes').empty();
			$('.active-testers .t_name').each(function() {
				$('#edit-tester .input-boxes').append('<span class="box-input"><input type="text" value="'+$(this).text()+'"><span class="i-close"></span></span>');
			});

			$('#edit-tester .input-boxes .i-close').on('click', function() {
				$(this).closest('.box-input').remove();
			});

			modal.openModal('#edit-tester');
		});

		// disable bays if testing in progress
		$(function() { 
			// for ie, changed $this.is(':disabled') to $this.prop('disabled')
			var bayNameOptions = $('#bay-name option');
			
			bayNameOptions.each(function(i) {
				var $this = $(this);
				if(i < bayNameOptions.length-7 && i > 0) {
					if($this.prop('disabled')) {
						var txt = $(this).text();

						var bayLetter = txt.substring(txt.length-1, txt.length);

						if(bayLetter == 'A' && txt != 'PC169A' && txt != 'PC169B') {
							$(this).next('option').prop('disabled', true);
						} else if(bayLetter == 'B') {
							$(this).prev('option').prop('disabled', true);
						}
					}
				}
			});

		});

		// individual ppi
		var indivPPI = {
			'sel': {
				'ppiCanvas' : '#test-chart'
			},
			'chart' : '',
			'chartInterval' : '',
			'colors' : {
				'downtime' : 'rgba(255, 32, 0, 0.75)',
				'progress' : 'rgba(255,192,0,0.75)',
				'remaining' : 'rgba(232, 232, 232, 0.75)',
				'excess' : 'rgba(20, 69, 193, 0.75)',
				'under' : 'rgba(244, 255, 34, 0.75)',
				'complete' : 'rgba(109, 198, 71,0.75)',
				'idle' : 'rgba(232, 232, 232, 0.75)'
			},
			'data' : {
				'bays' : ['bay'],
				'status' : ['idle'],
				'downtime' : [0],
				'complete' : [0],
				'progress' : [0],
				'remaining' : [0],
				'excess' : [0],
				'under' : [0],
				'idle' : [12]
			},
			'init' : function () {

				this.render();

			},
			'resetData' : function() {
				indivPPI.data = {
					'bays' : ['bay'],
					'status' : ['idle'],
					'downtime' : [0],
					'complete' : [0],
					'progress' : [0],
					'remaining' : [0],
					'excess' : [0],
					'under' : [0],
					'idle' : [12]
				};
			},
			'startInterval' : function (bayname) {
				function interval() {
					indivPPI.get(bayname);

					setTimeout(function() {
						indivPPI.chart.update();
					}, 200); 
				}

				interval();

				if(indivPPI.chartInterval) {
					clearInterval(indivPPI.chartInterval);
				}

				indivPPI.chartInterval = setInterval(interval, 10000);
			},
			'stopInterval' : function () {

				clearInterval(indivPPI.chartInterval);
				
			},
			'get' : function (bayname) {

				$.ajax({
					url: '_modules/charts/get_indiv_ppi.php',
					data: 'bayname='+bayname,
					method: 'GET',
					success: function(d) {
						if (d != 'No data found in database: get_ppi.php') {
							
							var cur = JSON.parse(d);
						
							indivPPI.data.status[0] = cur.Status;

							// if not idle
							if(cur.Status != "idle") {

								indivPPI.data.idle[0] = 0;
								indivPPI.data.downtime[0] = parseInt(cur.DownTime) / 60;
								
								var totalTime = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
								// calculate if excess
								if (cur.Status != 'complete') {
									if (totalTime > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
										indivPPI.data.status[0] = 'excess';
										indivPPI.data.remaining[0] = 0;
										indivPPI.data.progress[0] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
										indivPPI.data.excess[0] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
									} else {
										indivPPI.data.progress[0] = parseInt(cur.UsageTime) / 60;
										indivPPI.data.remaining[0] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
									}
								}

								// under
								if (cur.Status == 'complete') {
									if(totalTime < parseInt(cur.CycleTime) - parseInt(cur.Tolerance)) {
										indivPPI.data.status[0] = 'under';
										indivPPI.data.excess[0] = 0;
										indivPPI.data.remaining[0] = 0;
										indivPPI.data.under[0] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
										indivPPI.data.complete[0] = parseInt(cur.UsageTime) / 60;
										indivPPI.data.progress[0] = 0;
									} else if(totalTime > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
										indivPPI.data.progress[0] = 0;
										indivPPI.data.status[0] = 'excess';
										indivPPI.data.remaining[0] = 0;
										indivPPI.data.complete[0] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
										indivPPI.data.excess[0] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
									} else {
										indivPPI.data.progress[0] = 0;
										indivPPI.data.complete[0] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
									}
								}

							} else { 
								indivPPI.data.idle[0] = 12;
								indivPPI.data.downtime[0] = 0;
								indivPPI.data.remaining[0] = 0;
								indivPPI.data.progress[0] = 0;
								indivPPI.data.under[0] = 0;
								indivPPI.data.excess[0] = 0;
								indivPPI.data.complete[0] = 0;
							}

						} else {
							console.log('No data from server!');
						}
					}
				});

			},

			'render' : function() {
				var obj = this;

				var d = {
				    labels: indivPPI.data.bays,
				    status: indivPPI.data.status,
		            datasets: [{
		                label: 'Downtime',
		                backgroundColor: indivPPI.colors.downtime,
						data: indivPPI.data.downtime,
		            },{
		                label: 'Progress',
		                backgroundColor: indivPPI.colors.progress,
						data: indivPPI.data.progress,
		            },{
		                label: 'Completed',
		                backgroundColor: indivPPI.colors.complete,
						data: indivPPI.data.complete,
		            },{
		                label: 'Remaining',
		                backgroundColor: indivPPI.colors.remaining,
						data: indivPPI.data.remaining,
		            },{
		                label: 'Under',
		                backgroundColor: indivPPI.colors.under,
						data: indivPPI.data.under,
		            },{
		                label: 'Excess',
		                backgroundColor: indivPPI.colors.excess,
						data: indivPPI.data.excess,
		            },{
		                label: 'Idle',
		                backgroundColor: indivPPI.colors.idle,
						data: indivPPI.data.idle,
		            }]
				};

				ppiChartOptions.scales.yAxes[0].scaleLabel.display = false;
				ppiChartOptions.scales.xAxes[0].scaleLabel.display = false;

				indivPPI.chart = new Chart($(indivPPI.sel.ppiCanvas), {
				    type: 'customIconsBar',
				    data: d,
				    options: ppiChartOptions
				});
			}
		};

		indivPPI.init();
		
		// testing process
		// product time stamp
		var testProcess = {
			'sel': {
				'receiveBtn' : '#receive_product',
				'sendBtn' : '#send_product',
				'form' : '#product_form',
				'messages': '.form-messages',
				'testStartBtn': '#start-btn',
				'testStopBtn': '#stop-btn',
				'testReasonSelect': '#failure-reason',
				'testResumeBtn': '#resume-btn',
				'testRemoveBtn': '#remove-btn',
				'testCompleteBtn': '#complete-btn',
				'testFailControls': '#test-fail-controls',
				'standbyRemarks' : '#standby_remarks',
				'baySelect': '#bay-name',
				'checkProductBtn': '#checkproduct',
				'serialNum': '#serialnum',
				'modelId': '#modelid',
				'testerNameBlock' : '.active-testers',
				'testerNameBtn' : '#tester-name',
				'addTesterForm': '#add-tester-form',
				'editTesterForm': '#edit-tester-form',
				'finalTestCheck': '#final-test-check',
				'pretestControls': '.pretest-controls',
				'testPauseBtn': '#pause-btn',
				'testContinueBtn': '#continue-btn'
			},
			'testerActivityData' : '',
			'idf': parseInt('<?php echo $session_location; ?>'),
			'init': function () {
				var obj = this;

				//check test activity as page loads
				$(window).on('load', function() {
					obj.checkTestStatus();
				});

				// start - check product scanned already present
				$(obj.sel.checkProductBtn).on('click', function () {
					if($(obj.sel.serialNum).val().trim().length > 0 && $(obj.sel.modelId).val().trim().length > 0) {
						obj.checkData(this); 
					} else {
						obj.updateMessage('Please enter or scan Serial Number and Model ID', 'error');
					}
				}); // end

				// add tester
				obj.addTester();

				// edit tester
				obj.editTester();
			},

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

							obj.testerActivityData = data;
							
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
									case '4':
										obj.testStage4(data.SerialNumber, data.ModelID, data.BayID);
										break;
									default:
										obj.testStage0();
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
								obj.deactivateTestBtns();
							}
						} else { console.log('no data from server!'); }
					},
					error: function(data) { 
						console.log(data); 
						page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
					}
				});
			},

			// test to be started
			'testStage0': function () {
				var obj = this;
				
				obj.resetForm();
				$(obj.sel.baySelect).find('option:first').prop('selected', true);
				$(obj.sel.baySelect).prop('disabled', true);
				$(obj.sel.checkProductBtn).prop('disabled', false);
				$(obj.sel.serialNum).prop('disabled', false);
				$(obj.sel.modelId).prop('disabled', false);

				// pre test control
				$(obj.sel.finalTestCheck).prop('disabled', true);
				$(obj.sel.testStartBtn).prop('disabled', true);
				$('.pretest-controls').removeClass('hidden');

				// buttons	
				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testFailControls).addClass('hidden');
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');
				$(obj.sel.testerNameBlock).addClass('hidden');
				$(obj.sel.testPauseBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testContinueBtn).prop('disabled', true).addClass('hidden');

				// deactivate send / receive button
				obj.deactivateBtn();
				
				// show in progress
				$('.in-progress').addClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				obj.updateMessage('Scan or enter product details.');
			},

			// in progress
			'testStage1': function (serial, model, bayid) {
				var obj = this;
				// deactivate Check product status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				// active test details
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				obj.deactivateBaySelect();

				// pre test control
				$(obj.sel.finalTestCheck).prop('disabled', true);
				$(obj.sel.testStartBtn).prop('disabled', true);
				$('.pretest-controls').addClass('hidden');

				$(obj.sel.testStopBtn).prop('disabled', true).removeClass('hidden');
				$(obj.sel.testRemoveBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testReasonSelect).prop('disabled', false).removeClass('hidden');	
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testCompleteBtn).prop('disabled', false).removeClass('hidden');
				$(obj.sel.testFailControls).removeClass('hidden');
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');
				$(obj.sel.testerNameBlock).removeClass('hidden');
				$(obj.sel.testPauseBtn).prop('disabled', false).removeClass('hidden');
				$(obj.sel.testContinueBtn).prop('disabled', true).addClass('hidden');

				// activate reason select
				$(obj.sel.testReasonSelect).on('change', function() {
					$(obj.sel.testStopBtn).prop('disabled', false);
					$(obj.sel.testRemoveBtn).prop('disabled', false);
					obj.stopTest(); // stop test 
					$(obj.sel.testerNameBtn).removeClass('hidden');
				});
				
				// activate complete test
				obj.completeTest();

				// activate pause test
				obj.pauseTest();

				// show in progress
				$('.in-progress').removeClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				page.createMsg('Test in progress!');
				obj.updateMessage('Test in progress!');

				// chart start
				var bayName = $(obj.sel.baySelect).find('option[value="'+ bayid +'"]').html();
				indivPPI.startInterval(bayName);
			},

			// fail
			'testStage2': function (serial, model, bayid) {
				var obj = this;
				// deactivate Check product status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				// active test details
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				obj.deactivateBaySelect();

				// pre test control
				$(obj.sel.finalTestCheck).prop('disabled', true);
				$(obj.sel.testStartBtn).prop('disabled', true);
				$('.pretest-controls').addClass('hidden');

				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testRemoveBtn).prop('disabled', false).removeClass('hidden');
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testResumeBtn).prop('disabled', false).removeClass('hidden');	
				$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testFailControls).removeClass('hidden');
				$(obj.sel.standbyRemarks).prop('disabled', false).removeClass('hidden');
				$(obj.sel.testerNameBlock).removeClass('hidden');
				$(obj.sel.testPauseBtn).prop('disabled', false).removeClass('hidden');
				$(obj.sel.testContinueBtn).prop('disabled', true).addClass('hidden');

				//activate resume 
				obj.resumeTest();
				obj.removeTest();

				// activate pause test
				obj.pauseTest();
				
				// show in progress
				$('.in-progress').addClass('hidden');
				$('.in-error').removeClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				page.createMsg('Test failed!');
				obj.updateMessage('Test failed! Resume after correcting problem.', 'error');

				// chart start
				var bayName = $(obj.sel.baySelect).find('option[value="'+ bayid +'"]').html();
				indivPPI.startInterval(bayName);
			},

			// complete
			'testStage3': function (serial, model, bayid) {
				var obj = this;
				// deactivate Check product status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				// active test details
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				obj.deactivateBaySelect();

				// pre test control
				$(obj.sel.finalTestCheck).prop('disabled', true);
				$(obj.sel.testStartBtn).prop('disabled', true);
				$('.pretest-controls').addClass('hidden');

				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testRemoveBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testCompleteBtn).prop('disabled', true).removeClass('hidden');
				$(obj.sel.testFailControls).addClass('hidden');
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');
				$(obj.sel.testerNameBlock).removeClass('hidden');
				$(obj.sel.testPauseBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testContinueBtn).prop('disabled', true).addClass('hidden');

				// activate send button
				obj.activateBtn('send');
				obj.updateData('stage=9');
				
				// show in progress
				$('.in-progress').addClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				page.createMsg('Test Completed!');
				obj.updateMessage('Test Completed! The product is ready to be dispatched.');

				// stop chart interval
				var bayName = $(obj.sel.baySelect).find('option[value="'+ bayid +'"]').html();
				indivPPI.startInterval(bayName);

				setTimeout(function() {
					indivPPI.stopInterval();
				}, 5000);
			},

			// pause
			'testStage4': function (serial, model, bayid) {
				var obj = this;
				// deactivate Check product status
				$(obj.sel.checkProductBtn).prop('disabled', true);
				// active test details
				$(obj.sel.serialNum).val(serial).prop('disabled', true);
				$(obj.sel.modelId).val(model).prop('disabled', true);
				$(obj.sel.baySelect).find('option[value="'+ bayid +'"]').prop('selected', true);
				obj.deactivateBaySelect();

				// pre test control
				$(obj.sel.finalTestCheck).prop('disabled', true);
				$(obj.sel.testStartBtn).prop('disabled', true);
				$('.pretest-controls').addClass('hidden');

				$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testRemoveBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
				$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testFailControls).addClass('hidden');
				$(obj.sel.standbyRemarks).prop('disabled', true).addClass('hidden');
				$(obj.sel.testerNameBlock).addClass('hidden');
				$(obj.sel.testPauseBtn).prop('disabled', true).addClass('hidden');
				$(obj.sel.testContinueBtn).prop('disabled', false).removeClass('hidden');
				
				// activate continue test
				obj.continueTest();

				// show in progress
				$('.in-progress').removeClass('hidden');
				$('.in-error').addClass('hidden');
				$('.in-complete').addClass('hidden');

				// messages
				page.createMsg('Test paused!');
				obj.updateMessage('Test paused!');

				// chart start
				var bayName = $(obj.sel.baySelect).find('option[value="'+ bayid +'"]').html();
				indivPPI.startInterval(bayName);
			},

			'activateBaySelect': function () {
				var obj = this;
				$(obj.sel.baySelect).off('change');
				$(obj.sel.baySelect).prop('disabled', false);
				$(obj.sel.baySelect).on('change', function() {
					obj.activateTestBtns('start');
					obj.startTest(); // start test 
				});
			},
			'deactivateBaySelect': function () {
				var obj = this;
				$(obj.sel.baySelect).prop('disabled', true);
				$(obj.sel.baySelect).off('change');
			},

			'startTest': function() {
				var obj = this;
				// console.log('start test');
				$(obj.sel.testStartBtn).off('click'); // remove extra events
				$(obj.sel.testStartBtn).on('click', function(e) {
					e.preventDefault();

					var isFinalTest = $(obj.sel.finalTestCheck).prop('checked');

					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var formData;

					if(isFinalTest) {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&isFinal=yes';
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&isFinal=no';
					}

					$.ajax({
						url: '_modules/test/start.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							// console.log('start test:' + d);
							if (d) {
								var data = JSON.parse(d);
								obj.testStage1(serial, model, bayID);
							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},

			'pauseTest': function() {
				var obj = this;
				// console.log('pause test');
				$(obj.sel.testPauseBtn).off('click'); // remove extra events
				$(obj.sel.testPauseBtn).on('click', function(e) {
					e.preventDefault();

					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).find('option:selected').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var formData;


					if(obj.testerActivityData.TestType == 'final') {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&isFinal=yes';
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&isFinal=no';
					}

					$.ajax({
						url: '_modules/test/pause.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							if (d) {
								var data = JSON.parse(d);
								obj.testStage4(serial, model, bayID);
							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},

			'continueTest': function() {
				var obj = this;
				// console.log('continue test');
				$(obj.sel.testContinueBtn).off('click'); // remove extra events
				$(obj.sel.testContinueBtn).on('click', function(e) {
					e.preventDefault();

					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).find('option:selected').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var formData;

					if(obj.testerActivityData.TestType == 'final') {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&isFinal=yes';
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&isFinal=no';
					}

					$.ajax({
						url: '_modules/test/continue.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							if (d) {
								var data = JSON.parse(d);
								
								if(data.stage == 'fail') {
									obj.testStage2(serial, model, bayID);
								} else {
									obj.testStage1(serial, model, bayID);
								}
							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},

			'stopTest': function() {
				var obj = this;
				// console.log('stop test');
				$(obj.sel.testStopBtn).off('click'); // remove extra events
				$(obj.sel.testStopBtn).on('click', function(e) {
					e.preventDefault();

					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).find('option:selected').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var faildesc = $(obj.sel.testReasonSelect).val();
					var formData;

					if(obj.testerActivityData.TestType == 'final') {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=yes';
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=no';
					}

					$.ajax({
						url: '_modules/test/stop.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							// console.log('start test:' + d);
							if (d) {
								var data = JSON.parse(d);
								obj.testStage2(serial, model, bayID);
								// console.log(data);
							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},


			'resumeTest': function() {
				var obj = this;
				// console.log('resume test');
				$(obj.sel.testResumeBtn).off('click'); // remove extra events
				$(obj.sel.testResumeBtn).on('click', function(e) {
					e.preventDefault();

					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).find('option:selected').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var faildesc = $(obj.sel.testReasonSelect).val();
					var formData;

					if(obj.testerActivityData.TestType == 'final') {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=yes';
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=no';
					}

					$.ajax({
						url: '_modules/test/resume.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							// console.log('start test:' + d);
							if (d) {
								var data = JSON.parse(d);
								obj.testStage1(serial, model, bayID);
								// console.log(data);
							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},

			// remove
			'removeTest': function() {
				var obj = this;
				$(obj.sel.testRemoveBtn).off('click'); // remove extra events
				$(obj.sel.testRemoveBtn).on('click', function(e) {
					e.preventDefault();

					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).find('option:selected').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var faildesc = $(obj.sel.testReasonSelect).val();
					var remarks = $(obj.sel.standbyRemarks).val();
					var formData;

					if(obj.testerActivityData.TestType == 'final') {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=yes'+'&standby_remarks='+remarks;
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=no'+'&standby_remarks='+remarks;
					}

					$.ajax({
						url: '_modules/test/remove.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							// console.log('Remove test:' + d);
							if (d) {
								var data = JSON.parse(d);
								
								obj.testStage0();

								setTimeout(function() {
									location.reload();
								}, 1000);

							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},

			'completeTest': function() {
				var obj = this;
				// console.log('complete test');
				$(obj.sel.testCompleteBtn).off('click'); // remove extra events
				$(obj.sel.testCompleteBtn).on('click', function(e) {
					e.preventDefault();

					// var isFinalTest = $(obj.sel.finalTestCheck).prop('checked');
					var name = '<?php echo $session_username; ?>';
					var bayName = $(obj.sel.baySelect).find('option:selected').text().trim();
					var bayID = $(obj.sel.baySelect).find('option:selected').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var faildesc = $(obj.sel.testReasonSelect).val();
					var formData;
					
					if(obj.testerActivityData.TestType == 'final') {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=yes';
					} else {
						formData = 'serialnum='+serial+'&modelid='+model+'&username='+name+'&bayid='+bayID+'&bayname='+bayName+'&faildesc='+faildesc+'&isFinal=no';
					}

					console.log(formData);
					console.log(obj.testerActivityData.TestType);

					$.ajax({
						url: '_modules/test/complete.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							if (d) {
								var data = JSON.parse(d);
								console.log(data);
								if(data.TestType == 'final') {
									obj.testStage0();
									setTimeout(function() {
										location.reload();
									}, 1000);
								} else {
									obj.testStage3(serial, model, bayID);
								}
								// console.log(data);
							}  else { console.log('no data from server!'); }
						},
						error: function(data) { 
							console.log(data); 
							page.createMsg('There has been an error getting data! Try refreshing the page.', 'error');
						}
					});

				});
			},

			'activateTestBtns': function (flag) {
				var obj = this;
				if(flag == "start") {
					
					// pre test control
					$(obj.sel.finalTestCheck).prop('disabled', false);
					$(obj.sel.testStartBtn).prop('disabled', false);
					$('.pretest-controls').removeClass('hidden');

					$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');
					$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	
					$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
					$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');
					$(obj.sel.testFailControls).addClass('hidden');	
				} else if(flag == "complete") {

					// pre test control
					$(obj.sel.finalTestCheck).prop('disabled', true);
					$(obj.sel.testStartBtn).prop('disabled', true);
					$('.pretest-controls').addClass('hidden');

					$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');
					$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');		
					$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
					$(obj.sel.testCompleteBtn).prop('disabled', false).removeClass('hidden');
					$(obj.sel.testFailControls).addClass('hidden');	
				} else if(flag == "stop") {

					// pre test control
					$(obj.sel.finalTestCheck).prop('disabled', true);
					$(obj.sel.testStartBtn).prop('disabled', true);
					$('.pretest-controls').addClass('hidden');

					$(obj.sel.testStopBtn).prop('disabled', false).removeClass('hidden');
					$(obj.sel.testReasonSelect).prop('disabled', false).removeClass('hidden');
					$(obj.sel.testResumeBtn).prop('disabled', true).addClass('hidden');	
					$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');
					$(obj.sel.testFailControls).removeClass('hidden');	
				} else if(flag == "resume") {
					
					// pre test control
					$(obj.sel.finalTestCheck).prop('disabled', true);
					$(obj.sel.testStartBtn).prop('disabled', true);
					$('.pretest-controls').addClass('hidden');

					$(obj.sel.testStopBtn).prop('disabled', true).addClass('hidden');	
					$(obj.sel.testReasonSelect).prop('disabled', true).addClass('hidden');	
					$(obj.sel.testResumeBtn).prop('disabled', false).removeClass('hidden');	
					$(obj.sel.testCompleteBtn).prop('disabled', true).addClass('hidden');
					$(obj.sel.testFailControls).removeClass('hidden');	
				} 
				// reset events
				$(obj.sel.finalTestCheck).off('click');
				$(obj.sel.testStartBtn).off('click');	
				$(obj.sel.testStopBtn).off('click');		
				$(obj.sel.testResumeBtn).off('click');	
				$(obj.sel.testCompleteBtn).off('click');
			},

			'deactivateTestBtns': function() {
				var obj = this;
				$(obj.sel.testStartBtn).prop('disabled', true);	
				$(obj.sel.testStopBtn).prop('disabled', true);	
				$(obj.sel.testReasonSelect).prop('disabled', true);	
				$(obj.sel.testResumeBtn).prop('disabled', true);
				$(obj.sel.testRemoveBtn).prop('disabled', true);	
				$(obj.sel.testCompleteBtn).prop('disabled', true);
				$(obj.sel.testFailControls).addClass('hidden');

				// reset events
				$(obj.sel.testStartBtn).off('click');	
				$(obj.sel.testStopBtn).off('click');		
				$(obj.sel.testResumeBtn).off('click');	
				$(obj.sel.testRemoveBtn).off('click');
				$(obj.sel.testCompleteBtn).off('click');
			},	

			// receive send update product details functionality
			'updateData': function (stage) {
				var obj = this;
				$(obj.sel.receiveBtn+ ',' +obj.sel.sendBtn).on('click', function() {
					var loc = '<?php echo $session_location; ?>';
					var name = '<?php echo $session_username; ?>';
					var serial = $(obj.sel.serialNum).val().trim();
					var model = $(obj.sel.modelId).val().trim();
					var formData = 'serialnum='+serial+'&modelid='+model+'&idfId='+loc+'&username='+name+'&'+stage;
					// console.log(formData);
					page.loadAnim.start('.testing-box');
					$.ajax({
						url: '_modules/update_prod_data.php',
						data: formData,
						method: 'POST',
						success: function (d) {
							// console.log(d);
							if(d == 'Product received successfully!' || d == 'Product sent successfully!') {
								obj.updateMessage(d, 'success');
								page.createMsg(d, 'success');
								obj.activateBaySelect();
								// obj.resetForm();

								if(stage == 'stage=9') {
									
									obj.testStage0();

									// reset chart
									indivPPI.chart.destroy();
									indivPPI.resetData();
									setTimeout(function() {
										location.reload();
									}, 1000);
								}

							} else {
								obj.updateMessage('Error executing your action. Try again!', 'error');
								page.createMsg('Error executing your action. Try again!', 'error');
								// obj.resetForm();
							}
						},
						error: function (d) { console.log(d); },
						complete: function (d) {
							obj.deactivateBtn();
							page.loadAnim.stop('.testing-box');
						}
					});
				});	
			},

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
								obj.deactivateBtn();
							} else {
								//product found
								var data = JSON.parse(d);
								// console.log(data);
								// product status
								switch(data.ProductStage) {
									case "1":
										obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "1.5":
										obj.updateMessage('Product dispatched from IDF' + data.idfID + ' production. Not yet dispatched from IDF4 logistics. Can\'t proceed.', 'warn');
										obj.deactivateBtn();
										obj.deactivateTestBtns();
										obj.deactivateBaySelect();
									break;
									case "1.8":
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

											if(data.TestCompletedFinal == 1) {
												$(obj.sel.pretestControls).addClass('final-completed');
											} else {
												$(obj.sel.pretestControls).removeClass('final-completed');
											}
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
			},

			'addTester': function() {
				var obj = this;

				$(obj.sel.addTesterForm).on('submit', function(e) {
					e.preventDefault();

					var tester = $('#set-tester').val().trim();
					var serial = $(obj.sel.serialNum).val().trim();

					formData = 'set-tester=' + tester + '&serialnum=' + serial;
					console.log(formData);
					$.ajax({
						url: '_modules/test/add_tester.php',
						method: 'POST',
						data: formData,
						success: function(d) {
							console.log(d);
							// Set tester name...
							if($(obj.sel.testerNameBlock).find('.value').text() == 'Set tester name...') {
								$(obj.sel.testerNameBlock).find('.value').text('');
							}
							$(obj.sel.testerNameBlock).find('.value').prepend('<span class="t_name">' + tester + '</span>');
							modal.closeModal('#add-tester');

							$('#edit-tester-name').removeClass('hidden');
						},
						error: function(d) {
							console.log(d);
						}
					});

				});
			},
			'editTester': function() {
				var obj = this;

				$('#edit-tester-form').on('submit', function(e) {
						
					e.preventDefault();

					var cur = $(this);
					var tester = '';

					cur.find('.input-boxes input').each(function() {
						tester = tester + $(this).val() + ',';
					});

					var serial = $(obj.sel.serialNum).val().trim();

					formData = 'set-tester=' + tester + '&serialnum=' + serial;
					// console.log(formData);
					$.ajax({
						url: '_modules/test/edit_tester.php',
						method: 'POST',
						data: formData,
						success: function(d) {
							if(d=="Updated!") {
								$(obj.sel.testerNameBlock).find('.value').text('');
								cur.find('.input-boxes input').each(function() {
									$(obj.sel.testerNameBlock).find('.value').append('<span class="t_name">' + $(this).val() + '</span>');
								});
								modal.closeModal('#edit-tester');
							}
						},
						error: function(d) {
							console.log(d);
						}
					});

				});
			}
		};


		testProcess.init();


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