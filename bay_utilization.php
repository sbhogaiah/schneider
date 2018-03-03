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
					<h1><span class="ico i-bar-chart"></span> Bay Utilization
						<span class="date-control">
							<span class="date-picker"><input type="text" id="date-selector" placeholder="yyyy-mm-dd"></span>
							<input type="button" id="date-selector-btn" value="Submit">
						</span>
					</h1>
					<div class="chart-selector">
						<ul>
							<li><a href="<?php echo BASE_URL; ?>admin_charts.php">People Performance Index</a></li>
							<li><a href="#">Bay Utilization</a></li>
							<!-- <li><a href="#">Efficiency Data</a></li> -->
						</ul>
					</div>
				</div>

				<!-- charts -->
				<div class="charts">	
					<div id="bayUtilContainer" class="">
						<div class="grid">
							
							<div class="col x-10-12">
								<div class="grid">
									<div class="col m-6-12 chart-col">
										<div class="chart-heading">
											<p>Bay Utilization : 0 - 250 Kva</p>
										</div>
										<div class="chart-container">
											<canvas id="bayChart1" width="400" height="200"></canvas>
										</div>
									</div>

									<div class="col m-6-12 chart-col">
										<div class="chart-heading">
											<p>Bay Utilization : 0 - 500 Kva</p>
										</div>
										<div class="chart-container">
											<canvas id="bayChart2" width="400" height="200"></canvas>
										</div>
									</div>

									<div class="col m-6-12 chart-col">
										<div class="chart-heading">
											<p>Bay Utilization : 0 - 2000 Kva</p>
										</div>
										<div class="chart-container">
											<canvas id="bayChart3" width="400" height="200"></canvas>
										</div>
									</div>

									<div class="col m-6-12 chart-col">
										<div class="chart-heading">
											<p>Bay Utilization : Drives</p>
										</div>
										<div class="chart-container">
											<canvas id="bayChart4" width="400" height="200"></canvas>
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

	</div>

	<script type="text/javascript" src="js/chart-options.js"></script>

	<script>
		
		//dynamic charts
		$(function() {

			// datepicker
			$('#date-selector').datetimepicker({
			  	timepicker:false,
			  	format:'Y-m-d',
			  	minDate:'-1970/01/30',//yesterday is minimum date(for today use 0 or -1970/01/01)
 				maxDate:'0'//today is maximum date calendar
			});

			// refresh page at midnight
			var now = new Date();
			var night = new Date(
			    now.getFullYear(),
			    now.getMonth(),
			    now.getDate() + 1, // the next day, ...
			    0, 0, 0 // ...at 00:00:00 hours
			);
			var msTillMidnight = night.getTime() - now.getTime();
			setTimeout(function () {
				window.location.reload();
			}, msTillMidnight);

			// bay utlization ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			var bayUtil = {

				'sel': {
					'bayCanvas1' : '#bayChart1',
					'bayCanvas2' : '#bayChart2',
					'bayCanvas3' : '#bayChart3',
					'bayCanvas4' : '#bayChart4'
				},

				'chart1' : '',
				'chart2' : '',
				'chart3' : '',
				'chart4' : '',

				'colors' : {
					'downtime' : 'rgba(255, 32, 0, 0.75)',
					'uptime' : 'rgba(109, 198, 71,0.75)',
					'idle' : 'rgba(232, 232, 232, 0.75)'
				},

				'data1' : {
					'bays' : ['PC167','PC168','PC169'],
					'downtime' : [0,0,0],
					'uptime' : [0,0,0],
					'idle' : [24,24,24]
				},
				'data2' : {
					'bays' : ['PC170','PC171','PC172'],
					'downtime' : [0,0,0],
					'uptime' : [0,0,0],
					'idle' : [24,24,24]
				},
				'data3' : {
					'bays' : ['PC173','PC174','PC175'],
					'downtime' : [0,0,0],
					'uptime' : [0,0,0],
					'idle' : [24,24,24]
				},
				'data4' : {
					'bays' : ['F778','PC194','FT1248','BI167','FT1266','BI170','PC234'],
					'downtime' : [0,0,0,0,0,0,0],
					'uptime' : [0,0,0,0,0,0,0],
					'idle' : [24,24,24,24,24,24,24]
				},

				'init' : function () {
					this.get();
					this.render();
				},

				'resetData' : function () {
					bayUtil.data1 = {
						'bays' : ['PC167','PC168','PC169'],
						'downtime' : [0,0,0],
						'uptime' : [0,0,0],
						'idle' : [24,24,24]
					};
					bayUtil.data2 = {
						'bays' : ['PC170','PC171','PC172'],
						'downtime' : [0,0,0],
						'uptime' : [0,0,0],
						'idle' : [24,24,24]
					};
					bayUtil.data3 = {
						'bays' : ['PC173','PC174','PC175'],
						'downtime' : [0,0,0],
						'uptime' : [0,0,0],
						'idle' : [24,24,24]
					};
					bayUtil.data4 = {
						'bays' : ['F778','PC194','FT1248','BI167','FT1266','BI170','PC234'],
						'downtime' : [0,0,0,0,0,0,0],
						'uptime' : [0,0,0,0,0,0,0],
						'idle' : [24,24,24,24,24,24,24]
					};
				},

				'get' : function (date) {
					$.ajax({
						url: '_modules/charts/get_bayutil.php',
						data: 'date='+date,
						method: 'GET',
						success: function(d) {
							if (d != 'No data found!') {
								
								jsondata = JSON.parse(d);

								//data1
								$.each(jsondata, function(indexJSON, curJSON) {
									$.each(bayUtil.data1.bays, function(index, cur) {
										if(curJSON.BayActualName == cur) {
												
											bayUtil.data1.downtime[index] = parseInt(curJSON.DownTime) / 60;
											bayUtil.data1.uptime[index] = parseInt(curJSON.UsageTime) / 60;
											bayUtil.data1.idle[index] = (1440 - (parseInt(curJSON.DownTime) + parseInt(curJSON.UsageTime))) / 60;
										}
									});
								});

								//data2
								$.each(jsondata, function(indexJSON, curJSON) {
									$.each(bayUtil.data2.bays, function(index, cur) {

										if(curJSON.BayActualName == cur) {		
											bayUtil.data2.downtime[index] = parseInt(curJSON.DownTime) / 60;
											bayUtil.data2.uptime[index] = parseInt(curJSON.UsageTime) / 60;
											bayUtil.data2.idle[index] = (1440 - (parseInt(curJSON.DownTime) + parseInt(curJSON.UsageTime))) / 60;
										} 
									});

								});

								//data3
								$.each(jsondata, function(indexJSON, curJSON) {
									$.each(bayUtil.data3.bays, function(index, cur) {
											
										if(curJSON.BayActualName == cur) {
												
											bayUtil.data3.downtime[index] = parseInt(curJSON.DownTime) / 60;
											bayUtil.data3.uptime[index] = parseInt(curJSON.UsageTime) / 60;
											bayUtil.data3.idle[index] = (1440 - (parseInt(curJSON.DownTime) + parseInt(curJSON.UsageTime))) / 60;
										} 

									});

								});

								//data4
								$.each(jsondata, function(indexJSON, curJSON) {
									$.each(bayUtil.data4.bays, function(index, cur) {
											
										if(curJSON.BayActualName == cur) {
												
											bayUtil.data4.downtime[index] = parseInt(curJSON.DownTime) / 60;
											bayUtil.data4.uptime[index] = parseInt(curJSON.UsageTime) / 60;
											bayUtil.data4.idle[index] = (1440 - (parseInt(curJSON.DownTime) + parseInt(curJSON.UsageTime))) / 60;
										} 

									});

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
					    labels: bayUtil.data1.bays,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: bayUtil.colors.downtime,
							data: bayUtil.data1.downtime,
			            },{
			                label: 'Uptime',
			                backgroundColor: bayUtil.colors.uptime,
							data: bayUtil.data1.uptime,
			            },{
			                label: 'Idle',
			                backgroundColor: bayUtil.colors.idle,
							data: bayUtil.data1.idle,
			            }]
					};

					var d2 = {
					    labels: bayUtil.data2.bays,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: bayUtil.colors.downtime,
							data: bayUtil.data2.downtime,
			            },{
			                label: 'Uptime',
			                backgroundColor: bayUtil.colors.uptime,
							data: bayUtil.data2.uptime,
			            },{
			                label: 'Idle',
			                backgroundColor: bayUtil.colors.idle,
							data: bayUtil.data2.idle,
			            }]
					};

					var d3 = {
					    labels: bayUtil.data3.bays,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: bayUtil.colors.downtime,
							data: bayUtil.data3.downtime,
			            },{
			                label: 'Uptime',
			                backgroundColor: bayUtil.colors.uptime,
							data: bayUtil.data3.uptime,
			            },{
			                label: 'Idle',
			                backgroundColor: bayUtil.colors.idle,
							data: bayUtil.data3.idle,
			            }]
					};

					var d4 = {
					    labels: bayUtil.data4.bays,
			            datasets: [{
			                label: 'Downtime',
			                backgroundColor: bayUtil.colors.downtime,
							data: bayUtil.data4.downtime,
			            },{
			                label: 'Uptime',
			                backgroundColor: bayUtil.colors.uptime,
							data: bayUtil.data4.uptime,
			            },{
			                label: 'Idle',
			                backgroundColor: bayUtil.colors.idle,
							data: bayUtil.data4.idle,
			            }]
					};

					bayUtil.chart1 = new Chart($(bayUtil.sel.bayCanvas1), {
					    type: 'stackedPercentBar',
					    data: d1,
					    options: bayUtilChartOptions
					});

					bayUtil.chart2 = new Chart($(bayUtil.sel.bayCanvas2), {
					    type: 'stackedPercentBar',
					    data: d2,
					    options: bayUtilChartOptions
					});

					bayUtil.chart3 = new Chart($(bayUtil.sel.bayCanvas3), {
					    type: 'stackedPercentBar',
					    data: d3,
					    options: bayUtilChartOptions
					});

					var lastChartOptions = bayUtilChartOptions;
					lastChartOptions.scales.xAxes[0].categoryPercentage = 0.9;

					bayUtil.chart4 = new Chart($(bayUtil.sel.bayCanvas4), {
					    type: 'stackedPercentBar',
					    data: d4,
					    options: lastChartOptions
					});

				}

			};
			
			bayUtil.init();

			setTimeout(function () {
				page.loadAnim.stop('.admin-contents');
				$('.legend-container').html(bayUtil.chart1.generateLegend());
			}, 1000);
			

			function bayIntervalFunc() {
				bayUtil.get('today');
				setTimeout(function () {
					bayUtil.chart1.update();
					bayUtil.chart2.update();
					bayUtil.chart3.update();
					bayUtil.chart4.update();
				}, 1000);
			}

			bayIntervalFunc();

			// interval for realtime
			var bayInterval = setInterval(bayIntervalFunc, 10000);
				
			// date extend
			function formatDate(date) {
			    var d = new Date(date),
			        month = '' + (d.getMonth() + 1),
			        day = '' + d.getDate(),
			        year = d.getFullYear();

			    if (month.length < 2) month = '0' + month;
			    if (day.length < 2) day = '0' + day;

			    return [year, month, day].join('-');
			}

			// for old date
			$('#date-selector-btn').on('click', function () {
				var dateObj = new Date();
				var today = formatDate(dateObj);
				var selectedDate = $('#date-selector').val();

				if(selectedDate) {

					page.loadAnim.start('.admin-contents');
					clearInterval(bayInterval);
					bayUtil.resetData();

					bayUtil.chart1.destroy();
					bayUtil.chart2.destroy();
					bayUtil.chart3.destroy();
					bayUtil.chart4.destroy();

					if(today != selectedDate) {

						bayUtil.get(selectedDate);
						setTimeout(function () {
							bayUtil.render();
							page.loadAnim.stop('.admin-contents');
						}, 500);

					} else {
						bayUtil.get('today');
						bayUtil.render();
						bayInterval = setInterval(bayIntervalFunc, 10000);
						setTimeout(function () {
							 page.loadAnim.stop('.admin-contents'); 
						}, 500);
					}
				} else {
					page.createMsg('Select date before clicking submit.', 'error');
				}
			});

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