<?php 
	// for sidebar active toggle
	$page = "admin_reports";

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
	<body class="admin_reports sidebar--collapsed">
<?php else: ?>
	<body class="admin_reports">
<?php endif; ?>	

	<div id="page">

		<?php require_once '_includes/nav.php'; // navigation ?>

		<?php require_once '_includes/admin_sidebar.php'; // admin sidebar ?>

		<!-- admin contents -->
		<div class="admin-container">
			<div class="admin-contents cf">
				<div class="admin-heading">
					<ul class="breadcrumbs">
						<li><a href="#">Admin</a></li>
						<li>Reports</li>
					</ul>
					<h1><span class="ico i-table"></span> Yield Reports</h1>
					<div class="report-selector">
						<ul>
							<li><a href="<?php echo BASE_URL; ?>admin_reports.php">Product Reports</a></li>
							<li><a href="#">Yield Reports</a></li>
						</ul>
					</div>
				</div>
				
				<!-- reports -->
				<div class="reports">
					<!-- report-controls -->
					<div class="report-controls">
						<div class="grid">
							<div class="col m-6-12 l-4-12">
								<div class="report-widget">
									<div class="report-heading">Search Yield</div>
									<form action="#" id="report_search_form">
										<div class="form-group required">
											<label for="report-model">Model ID</label>
											<input type="text" id="report-model" name="report-model" placeholder="enter model id" data-validation="required">
										</div>
										<input type="submit" class="button secondary small" value="Search" id="search_model">
									</form>
								</div>
							</div>
							<div class="col m-6-12 l-8-12">
								<div class="report-widget">
									<div class="report-heading">Search By Range</div>
									
									<div class="range-buttons grid">
										<div class="col s-6-12 l-3-12">
											<input type="button" class="button secondary small" value="1 day reports" id="report_oneday">
										</div>
										<div class="col s-6-12 l-3-12">
											<input type="button" class="button secondary small" value="7 day reports" id="report_oneweek">
										</div>
										<div class="col s-6-12 l-3-12">
											<input type="button" class="button secondary small" value="15 day reports" id="report_halfmonth">
										</div>
										<div class="col s-6-12 l-3-12">
											<input type="button" class="button secondary small" value="30 day reports" id="report_month">
										</div>
									</div>

									<form action="#" id="report_range_form">
										<div class="form-group">
											<div class="date-range grid">
												<div class="col s-6-12 l-4-12">
													<label for="start_date">From</label>
													<span class="date-picker">
														<input type="text" name="start_date" id="start_date" placeholder="yyyy-mm-dd" data-validation="required"> 
													</span>
												</div>
												<div class="col s-6-12 l-4-12">
													<label for="end_date">To</label>
													<span class="date-picker">
														<input type="text" name="end_date" id="end_date" placeholder="yyyy-mm-dd" data-validation="required">
													</span>
												</div>
												<div class="col l-4-12">
													<input type="submit" class="button secondary small" value="Generate Report" id="search_range">
													<input type="button" class="button secondary small" value="Download Report" id="download_range">
												</div>
											</div>

										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- report-controls -->
					
					<div class="report-no-records">
						<p>Search by model number or select date range.</p>
					</div>
					<div class="report-contents yield-report hidden">
						<div class="report-contents-header">
							<h4>Yield Reports</h4>
						</div>
						<div class="report-data-container">
							<div class="report-summary">
								<span class="report-cell">
									<span class="cell-content">
										<small>Model ID</small>
										<strong class="modelid"></strong>
									</span>
								</span>
								<span class="report-cell">
									<span class="cell-content">
										<small>From</small>
										<strong class="from-date"></strong>
									</span>
								</span>
								<span class="report-cell">
									<span class="cell-content">
										<small>To</small>
										<strong class="to-date"></strong>
									</span>
								</span>
								<span class="report-cell">
									<span class="cell-content">
										<small>Total Products Tested</small>
										<strong class="total-count">0</strong>
									</span>
								</span>
							</div>
							<div class="report-table">
								<table class="table">
									<thead>
										<tr>
											<th><span>#</span></th>
											<th class="table-col-full"><span>Model ID</span></th>
											<th class=""><span class="pass">Passed</span></th>
											<th class=""><span class="fail">Failed</span></th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2" class="text-right"><span>Total</span></td>
											<td><span class="total-passed">0</span></td>
											<td><span class="total-failed">0</span></td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="download-table" id="downloadData" style="display: none;">
								<table>
									<thead>
										<tr>
											<th>#</th>
											<th >Model ID</th>
											<th>Passed</th>
											<th>Failed</th>
										</tr>										
									</thead>
									<tbody>
										
									</tbody>
									<tfoot>
										
									</tfoot>									
								</table>
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

		$(function() {
			// datepicker
			$('#start_date, #end_date').datetimepicker({
			  	timepicker:false,
			  	format:'Y-m-d',
			  	// minDate:'-1970/01/30',//yesterday is minimum date(for today use 0 or -1970/01/01)
 				maxDate:'0'//today is maximum date calendar
			});	

			// reports
			var searchType = "modelno";
		 	$("#report_search_form").submit(function(e){
		 		e.preventDefault();
			  	searchType = "modelno";
			  	$('#myData').empty();
			  	generateReport(searchType);
		  	});
		  
		  	$("#report_range_form").submit(function(e){
		  		e.preventDefault();
			  	searchType = "datereport";
			  	$('#myData').empty();
			  	generateReport(searchType);
		  	});
		  	$("#report_oneday").click(function(){
			  	searchType = "oneday";
			  	$('#myData').empty();
			  	generateReport(searchType);
		  	});	  
		  	$("#report_oneweek").click(function(){
			  	searchType = "oneweek";
			  	$('#myData').empty();
			  	generateReport(searchType);
		  	});
		  	$("#report_halfmonth").click(function(){
			  	searchType = "halfmonth";
			  	$('#myData').empty();
			  	generateReport(searchType);
		  	});
		  	$("#report_month").click(function(){
			  	searchType = "month";
			  	$('#myData').empty();
			  	generateReport(searchType);
		  	});	
		  	$("#download_range").click(function(e){
		  		var html = $('#downloadData').html();
		  		//var html = $('.report-table').html();
			  // window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));		  
			  // e.preventDefault();

			  	var dt = new Date();
		        var day = dt.getDate();
		        var month = dt.getMonth() + 1;
		        var year = dt.getFullYear();
		        var hour = dt.getHours();
		        var mins = dt.getMinutes();
		        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
		        //creating a temporary HTML link element (they support setting file names)
		        var a = document.createElement('a');
		        //getting data from our div that contains the HTML table
		        var data_type = 'data:application/vnd.ms-excel';
		        var table_div = document.querySelector('.download-table');
		        var table_html = table_div.outerHTML.replace(/ /g, '%20');
		        a.href = data_type + ', ' + table_html;
		        //setting the file name
		        a.download = 'yield_table_' + postfix + '.xls';
		        //triggering the function
		        a.click();
		        //just in case, prevent default behaviour
		        e.preventDefault();
		  	});	 		  	 
		});


		function generateReport(searchType) {		
			
			var data = {	
				reportType : searchType,
				model : $('#report-model').val(),
				fromDate : $('#start_date').val(),
				toDate : $('#end_date').val()
			};

			page.loadAnim.start('.admin-container');

			jQuery.ajax({	
				type: "POST",
				url: "_modules/reports/get_yield.php",
				data: data,
				success: function(data){
					var jsonD = JSON.parse(data);
					console.log(jsonD);

					if(jsonD.length === 0) {
						page.createMsg('No records found for the selected serial number or date!', 'error');
						$('.report-contents').addClass('hidden');
						$('.report-no-records').html('<p>No records found!</p>').removeClass('hidden');
					} else {
						$('.report-table tbody').empty();
						$('.download-table tbody').empty();

						var totalPass = 0;
						var totalFail = 0;
						var startDate = jsonD[0].SourceDt;
						var endDate = jsonD[jsonD.length-1].SourceDt;

						$.each(jsonD, function(i, cur) {
							$('.report-table tbody').append(dataTemplate(i+1,cur));
							$('.download-table tbody').append(dataTemplate(i+1,cur));

							totalPass += parseInt(cur.Pass);
							totalFail += parseInt(cur.Fail);
						});

						$('.report-table .total-passed').text(totalPass);
						$('.report-table .total-failed').text(totalFail);
						$('.report-summary .total-count').text(totalPass);
						$('.report-summary .from-date').text(startDate);
						$('.report-summary .to-date').text(endDate);
						$('.download-table tbody').append(dataTemplateSum(totalPass, totalFail));


						if(searchType === "modelno") {
							$('.report-summary .modelid').text($('#report-model').val());
							$('.report-summary .modelid').closest('.report-cell').removeClass('hidden');
							$('.report-summary .from-date, .report-summary .to-date').closest('.report-cell').addClass('hidden');
						} else {
							$('.report-summary .modelid').closest('.report-cell').addClass('hidden');
							$('.report-summary .from-date, .report-summary .to-date').closest('.report-cell').removeClass('hidden');
						}

						$('.yield-report').removeClass('hidden');
						$('.report-no-records').addClass('hidden');
					}
				}, 
				error: function(error) {
					console.log(error);
				},
				complete: function() {
					page.loadAnim.stop('.admin-container');
				}
		 	});	
		}

		function dataTemplate(number, obj) {
			var data = "<tr>";
			data += '<td align="center"><span class="number">'+number+'</span></td>';
			data += '<td align="center"><span class="modelid">'+obj.ModelID+'</span></td>';
			data += '<td align="center"><span class="pass-count">'+obj.Pass+'</span></td>';
			data += '<td align="center"><span class="fail-count">'+obj.Fail+'</span></td>';
			data += '</tr>';

			return data;
		}
		function dataTemplateSum(totalPass, totalFail) {
			var data = "<tr>";
			data += '<td colspan="2" align="right">'+"Total"+'</td>';
			data += '<td align="center">'+totalPass+'</td>';
			data += '<td align="center">'+totalFail+'</td>';
			data += '</tr>';

			return data;
		}

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