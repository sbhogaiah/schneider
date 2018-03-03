<?php 
	// for sidebar active toggle
	$page = "admin_charts";

	// check for cookie and change sidebar to collapse or expanded
	$sidebarCollapsed = false;
	if (isset($_COOKIE['sidebarCollapse'])) {
		$sidebarCollapsed = $_COOKIE['sidebarCollapse'];
	}

	// session
	require_once '_modules/session.php';

	// head 
	require_once '_includes/head.php';

	// check if correct user
	if($session_role == "admin" || $session_role == "guest"): 
?>

<?php if($sidebarCollapsed == "true"): // body classes ?>
	<body class="admin_charts sidebar--collapsed">
<?php else: ?>
	<body class="admin_charts">
<?php endif; ?>	

	<div id="page">

		<?php require_once '_includes/nav.php'; // navigation ?>

		<?php require_once '_includes/admin_sidebar.php'; // admin sidebar ?>

		<!-- admin contents -->
		<div class="admin-container">
			<div class="admin-contents cf loading-animation">
				<div class="admin-heading">
					<ul class="breadcrumbs">
						<li><a href="#">Admin</a></li>
						<li>Charts</li>
					</ul>
					<h1><span class="ico i-bar-chart"></span> People Performance Index</h1>
					<div class="chart-selector">
						<ul>
							<li><a href="#">People Performance Index</a></li>
							<li><a href="<?php echo BASE_URL; ?>bay_utilization.php">Bay Utilization</a></li>
							<!-- <li><a href="#">Efficiency Data</a></li> -->
						</ul>
					</div>
				</div>
				<!-- charts -->
				<div class="charts">	
					<!-- chart -->
					<div id="ppiChartContainer">
						<div class="grid">
							
							<div class="col x-10-12">
								<div class="grid">
									<div class="col m-6-12">
										<div class="chart-heading">
											<p>People Performance Index : 0 - 250 Kva</p>
										</div>
										<div class="chart-container">
											<canvas id="ppiChart1" width="400" height="200"></canvas>
										</div>
									</div>

									<div class="col m-6-12">
										<div class="chart-heading">
											<p>People Performance Index : 0 - 500 Kva</p>
										</div>
										<div class="chart-container">
											<canvas id="ppiChart2" width="400" height="200"></canvas>
										</div>
									</div>

									<div class="col m-6-12">
										<div class="chart-heading">
											<p>People Performance Index : 0 - 2000 Kva</p>
										</div>
										<div class="chart-container">
											<canvas id="ppiChart3" width="400" height="200"></canvas>
										</div>
									</div>

									<div class="col m-6-12">
										<div class="chart-heading">
											<p>People Performance Index : Drives</p>
										</div>
										<div class="chart-container">
											<canvas id="ppiChart4" width="400" height="200"></canvas>
										</div>
									</div>
								</div>
							</div>

							<div class="col x-2-12">
								<div class="chart-heading">
									<p>Legend</p>
								</div>
								<div class="legend-container">
									
								</div>
							</div>

						</div>
					</div>

				</div>
				
			</div>
		</div>
		
		<?php 
			require_once '_includes/footer.php'; // Footer
		?>

		<div class="modal" id="test-details">
			<div class="modal-contents">
				<button class="modal-close" data-modal-close="#test-details">&times;</button>
				<div class="modal-header">
					<h4>Test Details</h4>
				</div>
				<div class="modal-body">
					<p><strong><span class="active-bay"></span></strong></p>
					<table class="table">
						<thead>
							<tr>
								<th>Model ID</th>
								<th>Serial Number</th>
								<th>Tester</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="active-model"></span></td>
								<td><span class="active-serial"></span></td>
								<td><span class="active-user"></span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>

	<script type="text/javascript" src="js/chart-options.js"></script>

	<script>
		
		//dynamic charts
		$(function() {

			// People Performance ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			var ppi = {

				'sel': {
					'ppiCanvas1' : '#ppiChart1',
					'ppiCanvas2' : '#ppiChart2',
					'ppiCanvas3' : '#ppiChart3',
					'ppiCanvas4' : '#ppiChart4'
				},

				'chart1' : '',
				'chart2' : '',
				'chart3' : '',
				'chart4' : '',

				'colors' : {
					'downtime' : 'rgba(255, 32, 0, 0.75)',
					'progress' : 'rgba(255,192,0,0.75)',
					'remaining' : 'rgba(232, 232, 232, 0.75)',
					'excess' : 'rgba(20, 69, 193, 0.75)',
					'under' : 'rgba(244, 255, 34, 0.75)',
					'complete' : 'rgba(109, 198, 71,0.75)',
					'idle' : 'rgba(232, 232, 232, 0.75)'
				},

				'data1' : {
					'bays' : ['PC167A','PC167B','PC168A','PC168B','PC169A','PC169B'],
					'status' : ['idle','idle','idle','idle','idle','idle'],
					'downtime' : [0,0,0,0,0,0],
					'complete' : [0,0,0,0,0,0],
					'progress' : [0,0,0,0,0,0],
					'remaining' : [0,0,0,0,0,0],
					'excess' : [0,0,0,0,0,0],
					'under' : [0,0,0,0,0,0],
					'idle' : [12,12,12,12,12,12]
				},

				'data2' : {
					'bays' : ['PC170A','PC170B','PC171A','PC171B','PC172A','PC172B'],
					'status' : ['idle','idle','idle','idle','idle','idle'],
					'downtime' : [0,0,0,0,0,0],
					'complete' : [0,0,0,0,0,0],
					'progress' : [0,0,0,0,0,0],
					'remaining' : [0,0,0,0,0,0],
					'excess' : [0,0,0,0,0,0],
					'under' : [0,0,0,0,0,0],
					'idle' : [12,12,12,12,12,12]
				},

				'data3' : {
					'bays' : ['PC173A','PC173B','PC174A','PC174B','PC175A','PC175B'],
					'status' : ['idle','idle','idle','idle','idle','idle'],
					'downtime' : [0,0,0,0,0,0],
					'complete' : [0,0,0,0,0,0],
					'progress' : [0,0,0,0,0,0],
					'remaining' : [0,0,0,0,0,0],
					'excess' : [0,0,0,0,0,0],
					'under' : [0,0,0,0,0,0],
					'idle' : [12,12,12,12,12,12]
				},

				'data4' : {
					'bays' : ['F778','PC194','FT1248','BI167','FT1266','BI170','PC234'],
					'status' : ['idle','idle','idle','idle','idle','idle','idle'],
					'downtime' : [0,0,0,0,0,0,0],
					'complete' : [0,0,0,0,0,0,0],
					'progress' : [0,0,0,0,0,0,0],
					'remaining' : [0,0,0,0,0,0,0],
					'excess' : [0,0,0,0,0,0,0],
					'under' : [0,0,0,0,0,0,0],
					'idle' : [12,12,12,12,12,12,12]
				},

				'init' : function () {

					this.get();

					this.render();

					this.showTestDetails();

				},

				'get' : function () {

					$.ajax({
						url: '_modules/charts/get_ppi.php',
						method: 'GET',
						success: function(d) {
							if (d != 'No data found in database: get_ppi.php') {
								data = JSON.parse(d);
								// console.log(data);
								$.each(data, function(index, cur) {
									// for bays 0 - 5
									if (index < 6) {
										ppi.data1.status[index] = cur.Status;
										// if not idle
										if(cur.Status != "idle") {
											ppi.data1.idle[index] = 0;
											ppi.data1.downtime[index] = parseInt(cur.DownTime) / 60;
											
											var totalTime1 = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
											// calculate if excess
											if (cur.Status != 'complete') {
												if (totalTime1 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data1.status[index] = 'excess';
													ppi.data1.remaining[index] = 0;
													ppi.data1.progress[index] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data1.excess[index] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data1.progress[index] = parseInt(cur.UsageTime) / 60;
													ppi.data1.remaining[index] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
												}
											}
											// under
											if (cur.Status == 'complete') {
												if(totalTime1 < parseInt(cur.CycleTime) - parseInt(cur.Tolerance)) {
													ppi.data1.status[index] = 'under';
													ppi.data1.excess[index] = 0;
													ppi.data1.remaining[index] = 0;
													ppi.data1.under[index] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
													ppi.data1.complete[index] = parseInt(cur.UsageTime) / 60;
													ppi.data1.progress[index] = 0;
												} else if(totalTime1 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data1.progress[index] = 0;
													ppi.data1.status[index] = 'excess';
													ppi.data1.remaining[index] = 0;
													ppi.data1.complete[index] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data1.excess[index] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data1.progress[index] = 0;
													ppi.data1.complete[index] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
												}
											}

										} else { 
											ppi.data1.idle[index] = 12;
											ppi.data1.downtime[index] = 0;
											ppi.data1.remaining[index] = 0;
											ppi.data1.progress[index] = 0;
											ppi.data1.under[index] = 0;
											ppi.data1.excess[index] = 0;
											ppi.data1.complete[index] = 0;
										}

									} else if (index >= 6 && index < 12) { // for bays 6 - 11
										ppi.data2.status[index-6] = cur.Status;
										// if inprogress
										if(cur.Status != "idle") {
											ppi.data2.idle[index-6] = 0;
											ppi.data2.downtime[index-6] = parseInt(cur.DownTime) / 60;
											
											var totalTime2 = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
											// calculate if excess
											if (cur.Status != 'complete') {
												if (totalTime2 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data2.status[index-6] = 'excess';
													ppi.data2.remaining[index-6] = 0;
													ppi.data2.progress[index-6] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data2.excess[index-6] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data2.progress[index-6] = parseInt(cur.UsageTime) / 60;
													ppi.data2.remaining[index-6] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
												}
											}
											// under
											if (cur.Status == 'complete') {
												if(totalTime2 < parseInt(cur.CycleTime) - parseInt(cur.Tolerance)) {
													ppi.data2.status[index-6] = 'under';
													ppi.data2.excess[index-6] = 0;
													ppi.data2.remaining[index-6] = 0;
													ppi.data2.under[index-6] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
													ppi.data2.complete[index-6] = parseInt(cur.UsageTime) / 60;
													ppi.data2.progress[index-6] = 0;
												} else if(totalTime2 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data2.progress[index-6] = 0;
													ppi.data2.status[index-6] = 'excess';
													ppi.data2.remaining[index-6] = 0;
													ppi.data2.complete[index-6] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data2.excess[index-6] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data2.progress[index-6] = 0;
													ppi.data2.complete[index-6] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
												}
											}

										} else { 
											ppi.data2.idle[index-6] = 12;
											ppi.data2.downtime[index-6] = 0;
											ppi.data2.remaining[index-6] = 0;
											ppi.data2.progress[index-6] = 0;
											ppi.data2.under[index-6] = 0;
											ppi.data2.excess[index-6] = 0;
											ppi.data2.complete[index-6] = 0;
										}

									} else if (index >= 12 && index < 18) { // for bays 6 - 11
										ppi.data3.status[index-12] = cur.Status;
										// if inprogress
										if(cur.Status != "idle") {
											ppi.data3.idle[index-12] = 0;
											ppi.data3.downtime[index-12] = parseInt(cur.DownTime) / 60;
											
											var totalTime3 = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
											// calculate if excess
											if (cur.Status != 'complete') {
												if (totalTime3 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data3.status[index-12] = 'excess';
													ppi.data3.remaining[index-12] = 0;
													ppi.data3.progress[index-12] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data3.excess[index-12] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data3.progress[index-12] = parseInt(cur.UsageTime) / 60;
													ppi.data3.remaining[index-12] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
												}
											}
											// under
											if (cur.Status == 'complete') {
												if(totalTime3 < parseInt(cur.CycleTime) - parseInt(cur.Tolerance)) {
													ppi.data3.status[index-12] = 'under';
													ppi.data3.excess[index-12] = 0;
													ppi.data3.remaining[index-12] = 0;
													ppi.data3.under[index-12] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
													ppi.data3.complete[index-12] = parseInt(cur.UsageTime) / 60;
													ppi.data3.progress[index-12] = 0;
												} else if(totalTime3 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data3.progress[index-12] = 0;
													ppi.data3.status[index-12] = 'excess';
													ppi.data3.remaining[index-12] = 0;
													ppi.data3.complete[index-12] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data3.excess[index-12] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data3.progress[index-12] = 0;
													ppi.data3.complete[index-12] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
												}
											}
										} else { 
											ppi.data3.idle[index-12] = 12;
											ppi.data3.downtime[index-12] = 0;
											ppi.data3.remaining[index-12] = 0;
											ppi.data3.progress[index-12] = 0;
											ppi.data3.under[index-12] = 0;
											ppi.data3.excess[index-12] = 0;
											ppi.data3.complete[index-12] = 0;
										}

									} else if (index >= 18 && index < 25) { // for bays 6 - 11
										ppi.data4.status[index-18] = cur.Status;
										// if inprogress
										if(cur.Status != "idle") {
											ppi.data4.idle[index-18] = 0;
											ppi.data4.downtime[index-18] = parseInt(cur.DownTime) / 60;
											
											var totalTime4 = parseInt(cur.UsageTime) + parseInt(cur.DownTime);
											// calculate if excess
											if (cur.Status != 'complete') {
												if (totalTime4 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data4.status[index-18] = 'excess';
													ppi.data4.remaining[index-18] = 0;
													ppi.data4.progress[index-18] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data4.excess[index-18] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data4.progress[index-18] = parseInt(cur.UsageTime) / 60;
													ppi.data4.remaining[index-18] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
												}
											}
											// under
											if (cur.Status == 'complete') {
												if(totalTime4 < parseInt(cur.CycleTime) - parseInt(cur.Tolerance)) {
													ppi.data4.status[index-18] = 'under';
													ppi.data4.excess[index-18] = 0;
													ppi.data4.remaining[index-18] = 0;
													ppi.data4.under[index-18] =  Math.abs((parseInt(cur.CycleTime) - parseInt(cur.UsageTime)) / 60);
													ppi.data4.complete[index-18] = parseInt(cur.UsageTime) / 60;
													ppi.data4.progress[index-18] = 0;
												} else if(totalTime4 > parseInt(cur.Tolerance) + parseInt(cur.CycleTime)) {
													ppi.data4.progress[index-18] = 0;
													ppi.data4.status[index-18] = 'excess';
													ppi.data4.remaining[index-18] = 0;
													ppi.data4.complete[index-18] = Math.abs((parseInt(cur.CycleTime) - parseInt(cur.DownTime)) / 60);
													ppi.data4.excess[index-18] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime) - parseInt(cur.CycleTime)) / 60);
												} else {
													ppi.data4.progress[index-18] = 0;
													ppi.data4.complete[index-18] = Math.abs((parseInt(cur.UsageTime) + parseInt(cur.DownTime)) / 60);
												}
											}
										} else { 
											ppi.data4.idle[index-18] = 12;
											ppi.data4.downtime[index-18] = 0;
											ppi.data4.remaining[index-18] = 0;
											ppi.data4.progress[index-18] = 0;
											ppi.data4.under[index-18] = 0;
											ppi.data4.excess[index-18] = 0;
											ppi.data4.complete[index-18] = 0;
										}

									}
								});
							} else {
								console.log('No data from server!');
							}
						}
					});
				},

				'render': function() {
					var obj = this;
					var d1 = {
					    labels: ppi.data1.bays,
					    status: ppi.data1.status,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: ppi.colors.downtime,
							data: ppi.data1.downtime,
			            },{
			                label: 'Progress',
			                backgroundColor: ppi.colors.progress,
							data: ppi.data1.progress,
			            },{
			                label: 'Completed',
			                backgroundColor: ppi.colors.complete,
							data: ppi.data1.complete,
			            },{
			                label: 'Remaining',
			                backgroundColor: ppi.colors.remaining,
							data: ppi.data1.remaining,
			            },{
			                label: 'Under',
			                backgroundColor: ppi.colors.under,
							data: ppi.data1.under,
			            },{
			                label: 'Excess',
			                backgroundColor: ppi.colors.excess,
							data: ppi.data1.excess,
			            },{
			                label: 'Idle',
			                backgroundColor: ppi.colors.idle,
							data: ppi.data1.idle,
			            }]
					};

					var d2 = {
					    labels: ppi.data2.bays,
					    status: ppi.data2.status,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: ppi.colors.downtime,
							data: ppi.data2.downtime,
			            },{
			                label: 'Progress',
			                backgroundColor: ppi.colors.progress,
							data: ppi.data2.progress,
			            },{
			                label: 'Completed',
			                backgroundColor: ppi.colors.complete,
							data: ppi.data2.complete,
			            },{
			                label: 'Remaining',
			                backgroundColor: ppi.colors.remaining,
							data: ppi.data2.remaining,
			            },{
			                label: 'Under',
			                backgroundColor: ppi.colors.under,
							data: ppi.data2.under,
			            },{
			                label: 'Excess',
			                backgroundColor: ppi.colors.excess,
							data: ppi.data2.excess,
			            },{
			                label: 'Idle',
			                backgroundColor: ppi.colors.idle,
							data: ppi.data2.idle,
			            }]
					};

					var d3 = {
					    labels: ppi.data3.bays,
					    status: ppi.data3.status,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: ppi.colors.downtime,
							data: ppi.data3.downtime,
			            },{
			                label: 'Progress',
			                backgroundColor: ppi.colors.progress,
							data: ppi.data3.progress,
			            },{
			                label: 'Completed',
			                backgroundColor: ppi.colors.complete,
							data: ppi.data3.complete,
			            },{
			                label: 'Remaining',
			                backgroundColor: ppi.colors.remaining,
							data: ppi.data3.remaining,
			            },{
			                label: 'Under',
			                backgroundColor: ppi.colors.under,
							data: ppi.data3.under,
			            },{
			                label: 'Excess',
			                backgroundColor: ppi.colors.excess,
							data: ppi.data3.excess,
			            },{
			                label: 'Idle',
			                backgroundColor: ppi.colors.idle,
							data: ppi.data3.idle,
			            }]
					};

					var d4 = {
					    labels: ppi.data4.bays,
					    status: ppi.data4.status,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: ppi.colors.downtime,
							data: ppi.data4.downtime,
			            },{
			                label: 'Progress',
			                backgroundColor: ppi.colors.progress,
							data: ppi.data4.progress,
			            },{
			                label: 'Completed',
			                backgroundColor: ppi.colors.complete,
							data: ppi.data4.complete,
			            },{
			                label: 'Remaining',
			                backgroundColor: ppi.colors.remaining,
							data: ppi.data4.remaining,
			            },{
			                label: 'Under',
			                backgroundColor: ppi.colors.under,
							data: ppi.data4.under,
			            },{
			                label: 'Excess',
			                backgroundColor: ppi.colors.excess,
							data: ppi.data4.excess,
			            },{
			                label: 'Idle',
			                backgroundColor: ppi.colors.idle,
							data: ppi.data4.idle,
			            }]
					};

					ppi.chart1 = new Chart($(ppi.sel.ppiCanvas1), {
					    type: 'customIconsBar',
					    data: d1,
					    options: ppiChartOptions
					});

					ppi.chart2 = new Chart($(ppi.sel.ppiCanvas2), {
					    type: 'customIconsBar',
					    data: d2,
					    options: ppiChartOptions
					});

					ppi.chart3 = new Chart($(ppi.sel.ppiCanvas3), {
					    type: 'customIconsBar',
					    data: d3,
					    options: ppiChartOptions
					});

					ppi.chart4 = new Chart($(ppi.sel.ppiCanvas4), {
					    type: 'customIconsBar',
					    data: d4,
					    options: ppiChartOptions
					});

				},

				'showTestDetails' : function () {
					var ppi = this;

					function testInfo1(evt) {
						var activeElement = ppi.chart1.getElementAtEvent(evt);

						if(activeElement.length !== 0) {
							var elements = ppi.chart1.getDatasetAtEvent(evt);
							var activeElementBay = activeElement[0]._model.label;
							var status = ppi.chart1.config.data.status[activeElement[0]._index];

							if(status != 'idle') {
								// get details
								modal.openModal('#test-details');
								page.loadAnim.start('#test-details .modal-contents');

								$.ajax({
									url: '_modules/charts/get_test_details.php',
									method: 'GET',
									data: 'bay=' + activeElementBay,
									success: function(d) {
										var data = JSON.parse(d);
										
										var testerNames = data.Tester.split(',');
										testerNames.pop();
										testerNames = testerNames.join(', ');

										$('#test-details .active-bay').text(data.BayName);
										$('#test-details .active-serial').text(data.SerialNumber);
										$('#test-details .active-model').text(data.ModelID);
										$('#test-details .active-user').text(testerNames);
										page.loadAnim.stop('#test-details .modal-contents');
									}
								});
							}
						}
					}

					function testInfo2(evt) {
						var activeElement = ppi.chart2.getElementAtEvent(evt);
						if(activeElement.length !== 0) {
							var elements = ppi.chart2.getDatasetAtEvent(evt);
							var activeElementBay = activeElement[0]._model.label;
							var status = ppi.chart2.config.data.status[activeElement[0]._index];

							if(status != 'idle') {
								// get details
								modal.openModal('#test-details');
								page.loadAnim.start('#test-details .modal-contents');

								$.ajax({
									url: '_modules/charts/get_test_details.php',
									method: 'GET',
									data: 'bay=' + activeElementBay,
									success: function(d) {
										var data = JSON.parse(d);
										$('#test-details .active-bay').text(data.BayName);
										$('#test-details .active-serial').text(data.SerialNumber);
										$('#test-details .active-model').text(data.ModelID);
										$('#test-details .active-user').text(data.Tester.trim().substring(0,data.Tester.length-1));
										page.loadAnim.stop('#test-details .modal-contents');
									}
								});
							}
						}
					}

					function testInfo3(evt) {
						var activeElement = ppi.chart3.getElementAtEvent(evt);
						if(activeElement.length !== 0) {
							var elements = ppi.chart3.getDatasetAtEvent(evt);
							var activeElementBay = activeElement[0]._model.label;
							var status = ppi.chart3.config.data.status[activeElement[0]._index];

							if(status != 'idle') {
								// get details
								modal.openModal('#test-details');
								page.loadAnim.start('#test-details .modal-contents');

								$.ajax({
									url: '_modules/charts/get_test_details.php',
									method: 'GET',
									data: 'bay=' + activeElementBay,
									success: function(d) {
										var data = JSON.parse(d);
										$('#test-details .active-bay').text(data.BayName);
										$('#test-details .active-serial').text(data.SerialNumber);
										$('#test-details .active-model').text(data.ModelID);
										$('#test-details .active-user').text(data.Tester.trim().substring(0,data.Tester.length-1));
										page.loadAnim.stop('#test-details .modal-contents');
									}
								});
							}
						}
					}

					function testInfo4(evt) {
						var activeElement = ppi.chart4.getElementAtEvent(evt);
						if(activeElement.length !== 0) {
							var elements = ppi.chart4.getDatasetAtEvent(evt);
							var activeElementBay = activeElement[0]._model.label;
							var status = ppi.chart4.config.data.status[activeElement[0]._index];

							if(status != 'idle') {
								// get details
								modal.openModal('#test-details');
								page.loadAnim.start('#test-details .modal-contents');

								$.ajax({
									url: '_modules/charts/get_test_details.php',
									method: 'GET',
									data: 'bay=' + activeElementBay,
									success: function(d) {
										var data = JSON.parse(d);
										$('#test-details .active-bay').text(data.BayName);
										$('#test-details .active-serial').text(data.SerialNumber);
										$('#test-details .active-model').text(data.ModelID);
										$('#test-details .active-user').text(data.Tester.trim().substring(0,data.Tester.length-1));
										page.loadAnim.stop('#test-details .modal-contents');
									}
								});
							}
						}
					}

					$(ppi.sel.ppiCanvas1).on('click', testInfo1);
					$(ppi.sel.ppiCanvas2).on('click', testInfo2);
					$(ppi.sel.ppiCanvas3).on('click', testInfo3);
					$(ppi.sel.ppiCanvas4).on('click', testInfo4);
				}

			};

			function ppiInterval() {
				ppi.get();
				setTimeout(function() {
					ppi.chart1.update();
					ppi.chart2.update();
					ppi.chart3.update();
					ppi.chart4.update();
				},1000);
			}

			ppi.init();
			setTimeout(function () {
				page.loadAnim.stop('.admin-contents');
				$('.legend-container').html(ppi.chart1.generateLegend());
			}, 1000);
			ppiInterval();
			setInterval(ppiInterval, 10000);
		});

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