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
					<h1><span class="ico i-table"></span> Product Reports</h1>
					<div class="report-selector">
						<ul>
							<li><a href="#">Product Reports</a></li>
							<li><a href="<?php echo BASE_URL; ?>yield_reports.php">Yield Reports</a></li>
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
									<div class="report-heading">Search Products</div>
									<form action="#" id="report_search_form">
										<div class="form-group required">
											<label for="report-serial">Serial Number</label>
											<input type="text" id="report-serial" name="report-serial" placeholder="enter serial number" data-validation="required">
										</div>
										<input type="submit" class="button secondary small" value="Search" id="search_serial">
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
						<p>Search by serial number or select date range.</p>
					</div>
					<div class="report-contents hidden">
						<div class="report-contents-header">
							<h4><strong>Search Results</strong> | <span class="total-records">0</span> records found</h4>
						</div>
						<div class="report-data-container">
							<ul class="report-data">
							</ul>
						</div>
						<div class="download-table" id="downloadData" style="display: none;">
							<table></table>
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
			var searchType = "serialno";
		  	$("#report_search_form").submit(function(e){
		  		e.preventDefault();
			  	searchType = "serialno";
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
		  		//var html = $('#downloadData').html();
		  	// 	var html = $('.download-table').html();
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
		        a.download = 'reports_table_' + postfix + '.xls';
		        //triggering the function
		        a.click();
		        //just in case, prevent default behaviour
		        e.preventDefault();
		  	});		  	  

			function generateReport(searchType) {		
				
				var data = {	
					reportType : searchType,
					sn : $('#report-serial').val(),
					fromDate : $('#start_date').val(),
					toDate : $('#end_date').val()
				};

				page.loadAnim.start('.admin-container');

				jQuery.ajax({	
					type: "POST",
					url: "_modules/reports/get_products.php",
					data: data,
					success: function(d){
						var jsonD = JSON.parse(d);
						console.log(jsonD);
						if(jsonD.length === 0) {
							page.createMsg('No records found for the selected serial number or date!', 'error');
							$('.report-contents').addClass('hidden');
							$('.report-no-records').html('<p>No records found!</p>').removeClass('hidden');
						} else {
							$('.total-records').text(jsonD.length);
							// console.log(jsonD);
							$('.report-data').empty();

							// add only once the heading row
							var headingrow = '';
							headingrow += "<tr align='center'><th>#</th>";
							headingrow += "<th>Serial No.</th>";
							headingrow += "<th>Model ID</th>";
							headingrow += "<th>Source IDF</th>";
							headingrow += "<th>Start Date</th>";
							headingrow += "<th>End Date</th>";
							headingrow += "<th>Test Status</th>";
							headingrow += "<th>Testers</th>";
							headingrow += "<th>UsageTime</th>";
							headingrow += "<th>DownTime</th>";
							headingrow += "<th>Total Time</th>";
							headingrow += "<th>Fail Reasons</th>";
							headingrow += "<th>Source Production Out (DT)</th>";
							headingrow += "<th>Source Production Out (OpID)</th>";
							headingrow += "<th>Source Partial Test Rxd (DT)</th>";
							headingrow += "<th>Source Partial Test Rxd (OpID)</th>";
							headingrow += "<th>Source Partial Test Send (DT)</th>";
							headingrow += "<th>Source Partial Test Send (OpID)</th>";
							headingrow += "<th>Source Logistics Rxd (DT)</th>";
							headingrow += "<th>Source Logistics Rxd (OpID)</th>";
							headingrow += "<th>Source Logistics Send (DT)</th>";
							headingrow += "<th>Source Logistics Send (OpID)</th>";
							headingrow += "<th>IDF4 In Logistics Rxd (DT)</th>";
							headingrow += "<th>IDF4 In Logistics Rxd (OpID)</th>";
							headingrow += "<th>IDF4 In Logistics Send (DT)</th>";
							headingrow += "<th>IDF4 In Logistics Send (OpID)</th>";
							headingrow += "<th>IDF4 In Production Rxd (DT)</th>";
							headingrow += "<th>IDF4 In Production Rxd (OpID)</th>";
							headingrow += "<th>IDF4 In Production Send (DT)</th>";
							headingrow += "<th>IDF4 In Production Send (OpID)</th>";
							headingrow += "<th>Test Infra Rxd (DT)</th>";
							headingrow += "<th>Test Infra Rxd (OpID)</th>";
							headingrow += "<th>Test Infra Send (DT)</th>";
							headingrow += "<th>Test Infra Send (OpID)</th>";
							headingrow += "<th>IDF4 Out Production Rxd (DT)</th>";
							headingrow += "<th>IDF4 Out Production Rxd (OpID)</th>";
							headingrow += "<th>IDF4 Out Production Send (DT)</th>";
							headingrow += "<th>IDF4 Out Production Send (OpID)</th>";
							headingrow += "<th>IDF4 Out Logistics Rxd (DT)</th>";
							headingrow += "<th>IDF4 Out Logistics Rxd (OpID)</th>";
							headingrow += "<th>IDF4 Out Logistics Send (DT)</th>";
							headingrow += "<th>IDF4 Out Logistics Send (OpID)</th>";
							headingrow += "</tr>";

							$('.download-table table').append(headingrow);

							$.each(jsonD, function(i, cur) {
								$('.report-data').append(dataTemplate(i+1, cur));

								$('.download-table table').append(dataTemplateTable(i+1, cur));
								//$('.report-data-table').append(dataTemplateTable(i+1, cur));
							});

							$('.report-item-table').find('td:contains("NA")').addClass('na');

							$('.report-contents').removeClass('hidden');
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

			function dataTemplate(number, stampObj) {

				// set defaults
				stampObj.Src_RxdPartTestDT = (stampObj.Src_RxdPartTestDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_RxdPartTestDT;
				stampObj.Src_RxdPartTestOpID = (stampObj.Src_RxdPartTestOpID === null) ? 'NA' : stampObj.Src_RxdPartTestOpID;
				stampObj.Src_SendPartTestDT = (stampObj.Src_SendPartTestDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_SendPartTestDT;
				stampObj.Src_SendPartTestOpID = (stampObj.Src_SendPartTestOpID === null) ? 'NA' : stampObj.Src_SendPartTestOpID;

				stampObj.Src_RxdLogisticDT = (stampObj.Src_RxdLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_RxdLogisticDT;
				stampObj.Idf4In_RxdLogisticDT = (stampObj.Idf4In_RxdLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_RxdLogisticDT;
				stampObj.Idf4In_RxdProductionDT = (stampObj.Idf4In_RxdProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_RxdProductionDT;
				stampObj.BayIn_TestInfraDT = (stampObj.BayIn_TestInfraDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.BayIn_TestInfraDT;
				stampObj.Idf4Out_RxdProductionDT = (stampObj.Idf4Out_RxdProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_RxdProductionDT;
				stampObj.Idf4Out_RxdLogisticDT = (stampObj.Idf4Out_RxdLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_RxdLogisticDT;
				stampObj.Src_RxdLogisticOpID = (stampObj.Src_RxdLogisticOpID === null) ? 'NA' : stampObj.Src_RxdLogisticOpID;
				stampObj.Idf4In_RxdLogisticOpID = (stampObj.Idf4In_RxdLogisticOpID === null) ? 'NA' : stampObj.Idf4In_RxdLogisticOpID;
				stampObj.Idf4In_RxdProductionOpID = (stampObj.Idf4In_RxdProductionOpID === null) ? 'NA' : stampObj.Idf4In_RxdProductionOpID;
				stampObj.BayIn_TestInfraOpID = (stampObj.BayIn_TestInfraOpID === null) ? 'NA' : stampObj.BayIn_TestInfraOpID;
				stampObj.Idf4Out_RxdLogisticOpID = (stampObj.Idf4Out_RxdLogisticOpID === null) ? 'NA' : stampObj.Idf4Out_RxdLogisticOpID;
				stampObj.Idf4Out_RxdProductionOpID = (stampObj.Idf4Out_RxdProductionOpID === null) ? 'NA' : stampObj.Idf4Out_RxdProductionOpID;

				stampObj.Src_SendProductionDT = (stampObj.Src_SendProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_SendProductionDT;
				stampObj.Src_SendLogisticDT = (stampObj.Src_SendLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_SendLogisticDT;
				stampObj.Idf4In_SendLogisticDT = (stampObj.Idf4In_SendLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_SendLogisticDT;
				stampObj.Idf4In_SendProductionDT = (stampObj.Idf4In_SendProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_SendProductionDT;
				stampObj.BayOut_TestInfraDT = (stampObj.BayOut_TestInfraDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.BayOut_TestInfraDT;
				stampObj.Idf4Out_SendProductionDT = (stampObj.Idf4Out_SendProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_SendProductionDT;
				stampObj.Idf4Out_SendLogisticDT = (stampObj.Idf4Out_SendLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_SendLogisticDT;

				stampObj.Src_SendProductionOpID = (stampObj.Src_SendProductionOpID === null) ? 'NA' : stampObj.Src_SendProductionOpID;
				stampObj.Src_SendLogisticOpID = (stampObj.Src_SendLogisticOpID === null) ? 'NA' : stampObj.Src_SendLogisticOpID;
				stampObj.Idf4In_SendLogisticOpID = (stampObj.Idf4In_SendLogisticOpID === null) ? 'NA' : stampObj.Idf4In_SendLogisticOpID;
				stampObj.Idf4In_SendProductionOpID = (stampObj.Idf4In_SendProductionOpID === null) ? 'NA' : stampObj.Idf4In_SendProductionOpID;
				stampObj.BayOut_TestInfraOpID = (stampObj.BayIn_TestInfraOpID === null) ? 'NA' : stampObj.BayIn_TestInfraOpID;
				stampObj.Idf4Out_SendLogisticOpID = (stampObj.Idf4Out_SendLogisticOpID === null) ? 'NA' : stampObj.Idf4Out_SendLogisticOpID;
				stampObj.Idf4Out_SendProductionOpID = (stampObj.Idf4Out_SendProductionOpID === null) ? 'NA' : stampObj.Idf4Out_SendProductionOpID;

				stampObj.EndDT = (stampObj.EndDT === null) ? 'NA' : stampObj.EndDT;

				stampObj.TestStartFinal = (stampObj.TestStartFinal === null) ? 'NA' : stampObj.TestStartFinal;
				stampObj.TestEndFinal = (stampObj.TestEndFinal === null) ? 'NA' : stampObj.TestEndFinal;

				var data = '<li class="report-item">';

				data += '<div class="report-item-summary">';
				data += '<span class="report-item-cell report-item-number"><span class="cell-content"><strong class="number">'+number+'</strong></span></span>';
				data += '<span class="report-item-cell"><span class="cell-content"><small>Serial No.</small><strong class="serial-number">'+stampObj.SerialNumber+'</strong></span></span>';
				data += '<span class="report-item-cell"><span class="cell-content"><small>Model ID</small><strong class="serial-number">'+stampObj.ModelID+'</strong></span></span>';
				data += '<span class="report-item-cell"><span class="cell-content"><small>Source IDF</small><strong class="model-id">IDF '+stampObj.idfID+'</strong></span></span>';
				data += '<span class="report-item-cell"><span class="cell-content"><small>Start Date</small><strong class="start-date">'+stampObj.SourceDT+'</strong></span></span>';
				data += '<span class="report-item-cell"><span class="cell-content"><small>End Date</small><strong class="end-date">'+stampObj.EndDT+'</strong></span></span>';

				if(stampObj.CurStatus) {
					data += '<span class="report-item-cell"><span class="cell-content"><small>Test Status</small><strong class="cur-status">'+stampObj.CurStatus+'</strong></span></span>';
				
					if(stampObj.Tester.length > 0) {
						var testerNames = stampObj.Tester.split(',');
						testerNames.pop();
						testerNames = testerNames.join(', ');
						
						data += '<span class="report-item-cell"><span class="cell-content"><small>Testers</small><strong class="tester-names">'+testerNames+'</strong></span></span>';
					}

					if(stampObj.UsageTime != '0') {

						var amount = parseFloat(stampObj.UsageTime);
						var hourval = Math.floor(amount/60);
						var minval = Math.round((amount/60 - hourval) * 60);

						data += '<span class="report-item-cell"><span class="cell-content"><small>UsageTime</small><strong class="usage-time">'+hourval+':'+minval+'</strong></span></span>';
					} else {
						data += '<span class="report-item-cell"><span class="cell-content"><small>UsageTime</small><strong class="usage-time">00:00</strong></span></span>';
					}

					if(stampObj.Downtime != '0') {

						var amount2 = parseFloat(stampObj.Downtime);
						var hourval2 = Math.floor(amount2/60);
						var minval2 = Math.round((amount2/60 - hourval2) * 60);

						data += '<span class="report-item-cell"><span class="cell-content"><small>DownTime</small><strong class="down-time">'+hourval2+':'+minval2+'</strong></span></span>';
					} else {
						data += '<span class="report-item-cell"><span class="cell-content"><small>DownTime</small><strong class="down-time">00:00</strong></span></span>';
					}

					var totalAmt = parseFloat(stampObj.Downtime) + parseFloat(stampObj.UsageTime);
					var totalHour = Math.floor(totalAmt/60);
					var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
					data += '<span class="report-item-cell"><span class="cell-content"><small>Total Time</small><strong class="total-time">'+totalHour+':'+totalMin+'</strong></span></span>';
				}

				if(stampObj.TestCompletedFinal == 1) {
					data += '<span class="report-item-cell"><span class="cell-content"><small>Final Test Start</small><strong class="final-start">'+stampObj.TestStartFinal+'</strong></span></span>';
					data += '<span class="report-item-cell"><span class="cell-content"><small>Final Test Start</small><strong class="final-end">'+stampObj.TestEndFinal+'</strong></span></span>';
				}

				data += '</div>';

				if(stampObj.FailReasons) {
					var reasons = stampObj.FailReasons.split(',');
					data += '<div class="fail-reasons"><strong>Reasons for failure of this products are:</strong>';
					reasons.forEach(function(e, i) {
						if(e) { 
							data += '<span class="reason">' + e + '</span>';
						}
					});
					data += '</div>';
				}

				// table
				if(stampObj.idfID != '4') {

					if(stampObj.idfID == '1' || stampObj.idfID == '8') {
						data += '<div class="report-item-table"><table class="table">';
						data += '<thead><tr><th><span>Time Stamp</span></th><th><span>IDF'+stampObj.idfID+' Production</span></th><th><span>IDF'+stampObj.idfID+' Partial Test</span></th><th><span>IDF'+stampObj.idfID+' Logistics</span></th><th><span>IDF4 Logistics (In)</span></th><th><span>IDF4 Production (In)</span></th><th><span>Test Infra</span></th><th><span>IDF4 Production (Out)</span></th><th><span>IDF4 Logistics (Out)</span></th></tr></thead>';
						data += '<tbody>';

						data += '<tr><th>Received at :</th><td>NA</td><td>'+stampObj.Src_RxdPartTestDT+'</td><td>'+stampObj.Src_RxdLogisticDT+'</td><td>'+stampObj.Idf4In_RxdLogisticDT+'</td><td>'+stampObj.Idf4In_RxdProductionDT+'</td><td>'+stampObj.BayIn_TestInfraDT+'</td><td>'+stampObj.Idf4Out_RxdProductionDT+'</td><td>'+stampObj.Idf4Out_RxdLogisticDT+'</td></tr>';
						data += '<tr><th>Received by :</th><td>NA</td><td>'+stampObj.Src_RxdPartTestOpID+'</td><td>'+stampObj.Src_RxdLogisticOpID+'</td><td>'+stampObj.Idf4In_RxdLogisticOpID+'</td><td>'+stampObj.Idf4In_RxdProductionOpID+'</td><td>'+stampObj.BayIn_TestInfraOpID+'</td><td>'+stampObj.Idf4Out_RxdProductionOpID+'</td><td>'+stampObj.Idf4Out_RxdLogisticOpID+'</td></tr>';

						data += '<tr><th>Sent at :</th><td>'+stampObj.Src_SendProductionDT+'</td><td>'+stampObj.Src_SendPartTestDT+'</td><td>'+stampObj.Src_SendLogisticDT+'</td><td>'+stampObj.Idf4In_SendLogisticDT+'</td><td>'+stampObj.Idf4In_SendProductionDT+'</td><td>'+stampObj.BayOut_TestInfraDT+'</td><td>'+stampObj.Idf4Out_SendProductionDT+'</td><td>'+stampObj.Idf4Out_SendLogisticDT+'</td></tr>';

						data += '<tr><th>Sent by :</th><td>'+stampObj.Src_SendProductionOpID+'</td><td>'+stampObj.Src_SendPartTestOpID+'</td><td>'+stampObj.Src_SendLogisticOpID+'</td><td>'+stampObj.Idf4In_SendLogisticOpID+'</td><td>'+stampObj.Idf4In_SendProductionOpID+'</td><td>'+stampObj.BayOut_TestInfraOpID+'</td><td>'+stampObj.Idf4Out_SendProductionOpID+'</td><td>'+stampObj.Idf4Out_SendLogisticOpID+'</td></tr>';

						data += '</tbody></table></div></li>';
					} else {
						data += '<div class="report-item-table"><table class="table">';
						data += '<thead><tr><th><span>Time Stamp</span></th><th><span>IDF'+stampObj.idfID+' Production</span></th><th><span>IDF'+stampObj.idfID+' Logistics</span></th><th><span>IDF4 Logistics (In)</span></th><th><span>IDF4 Production (In)</span></th><th><span>Test Infra</span></th><th><span>IDF4 Production (Out)</span></th><th><span>IDF4 Logistics (Out)</span></th></tr></thead>';
						data += '<tbody>';

						data += '<tr><th>Received at :</th><td>NA</td><td>'+stampObj.Src_RxdLogisticDT+'</td><td>'+stampObj.Idf4In_RxdLogisticDT+'</td><td>'+stampObj.Idf4In_RxdProductionDT+'</td><td>'+stampObj.BayIn_TestInfraDT+'</td><td>'+stampObj.Idf4Out_RxdProductionDT+'</td><td>'+stampObj.Idf4Out_RxdLogisticDT+'</td></tr>';
						data += '<tr><th>Received by :</th><td>NA</td><td>'+stampObj.Src_RxdLogisticOpID+'</td><td>'+stampObj.Idf4In_RxdLogisticOpID+'</td><td>'+stampObj.Idf4In_RxdProductionOpID+'</td><td>'+stampObj.BayIn_TestInfraOpID+'</td><td>'+stampObj.Idf4Out_RxdProductionOpID+'</td><td>'+stampObj.Idf4Out_RxdLogisticOpID+'</td></tr>';

						data += '<tr><th>Sent at :</th><td>'+stampObj.Src_SendProductionDT+'</td><td>'+stampObj.Src_SendLogisticDT+'</td><td>'+stampObj.Idf4In_SendLogisticDT+'</td><td>'+stampObj.Idf4In_SendProductionDT+'</td><td>'+stampObj.BayOut_TestInfraDT+'</td><td>'+stampObj.Idf4Out_SendProductionDT+'</td><td>'+stampObj.Idf4Out_SendLogisticDT+'</td></tr>';

						data += '<tr><th>Sent by :</th><td>'+stampObj.Src_SendProductionOpID+'</td><td>'+stampObj.Src_SendLogisticOpID+'</td><td>'+stampObj.Idf4In_SendLogisticOpID+'</td><td>'+stampObj.Idf4In_SendProductionOpID+'</td><td>'+stampObj.BayOut_TestInfraOpID+'</td><td>'+stampObj.Idf4Out_SendProductionOpID+'</td><td>'+stampObj.Idf4Out_SendLogisticOpID+'</td></tr>';

						data += '</tbody></table></div></li>';
					}

				} else {
					data += '<div class="report-item-table"><table class="table">';
					data += '<thead><tr><th><span>Time Stamp</span></th><th><span>IDF4 Production (In)</span></th><th><span>Test Infra</span></th><th><span>IDF4 Production (Out)</span></th><th><span>IDF4 Logistics (Out)</span></th></tr></thead>';
					data += '<tbody>';

					data += '<tr><th>Received at :</th><td>NA</td><td>'+stampObj.BayIn_TestInfraDT+'</td><td>'+stampObj.Idf4Out_RxdProductionDT+'</td><td>'+stampObj.Idf4Out_RxdLogisticDT+'</td></tr>';
					data += '<tr><th>Received by :</th><td>NA</td><td>'+stampObj.BayIn_TestInfraOpID+'</td><td>'+stampObj.Idf4Out_RxdProductionOpID+'</td><td>'+stampObj.Idf4Out_RxdLogisticOpID+'</td></tr>';

					data += '<tr><th>Sent at :</th><td>'+stampObj.Idf4In_SendProductionDT+'</td><td>'+stampObj.BayOut_TestInfraDT+'</td><td>'+stampObj.Idf4Out_SendProductionDT+'</td><td>'+stampObj.Idf4Out_SendLogisticDT+'</td></tr>';

					data += '<tr><th>Sent by :</th><td>'+stampObj.Idf4In_SendProductionOpID+'</td><td>'+stampObj.BayOut_TestInfraOpID+'</td><td>'+stampObj.Idf4Out_SendProductionOpID+'</td><td>'+stampObj.Idf4Out_SendLogisticOpID+'</td></tr>';

					data += '</tbody></table></div></li>';
				}


				return data;
			}

			function dataTemplateTable(number, stampObj) {

				// set defaults
				stampObj.Src_RxdPartTestDT = (stampObj.Src_RxdPartTestDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_RxdPartTestDT;
				stampObj.Src_RxdPartTestOpID = (stampObj.Src_RxdPartTestOpID === null) ? 'NA' : stampObj.Src_RxdPartTestOpID;
				stampObj.Src_SendPartTestDT = (stampObj.Src_SendPartTestDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_SendPartTestDT;
				stampObj.Src_SendPartTestOpID = (stampObj.Src_SendPartTestOpID === null) ? 'NA' : stampObj.Src_SendPartTestOpID;

				stampObj.Src_RxdLogisticDT = (stampObj.Src_RxdLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_RxdLogisticDT;
				stampObj.Idf4In_RxdLogisticDT = (stampObj.Idf4In_RxdLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_RxdLogisticDT;
				stampObj.Idf4In_RxdProductionDT = (stampObj.Idf4In_RxdProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_RxdProductionDT;
				stampObj.BayIn_TestInfraDT = (stampObj.BayIn_TestInfraDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.BayIn_TestInfraDT;
				stampObj.Idf4Out_RxdProductionDT = (stampObj.Idf4Out_RxdProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_RxdProductionDT;
				stampObj.Idf4Out_RxdLogisticDT = (stampObj.Idf4Out_RxdLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_RxdLogisticDT;
				stampObj.Src_RxdLogisticOpID = (stampObj.Src_RxdLogisticOpID === null) ? 'NA' : stampObj.Src_RxdLogisticOpID;
				stampObj.Idf4In_RxdLogisticOpID = (stampObj.Idf4In_RxdLogisticOpID === null) ? 'NA' : stampObj.Idf4In_RxdLogisticOpID;
				stampObj.Idf4In_RxdProductionOpID = (stampObj.Idf4In_RxdProductionOpID === null) ? 'NA' : stampObj.Idf4In_RxdProductionOpID;
				stampObj.BayIn_TestInfraOpID = (stampObj.BayIn_TestInfraOpID === null) ? 'NA' : stampObj.BayIn_TestInfraOpID;
				stampObj.Idf4Out_RxdLogisticOpID = (stampObj.Idf4Out_RxdLogisticOpID === null) ? 'NA' : stampObj.Idf4Out_RxdLogisticOpID;
				stampObj.Idf4Out_RxdProductionOpID = (stampObj.Idf4Out_RxdProductionOpID === null) ? 'NA' : stampObj.Idf4Out_RxdProductionOpID;

				stampObj.Src_SendProductionDT = (stampObj.Src_SendProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_SendProductionDT;
				stampObj.Src_SendLogisticDT = (stampObj.Src_SendLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Src_SendLogisticDT;
				stampObj.Idf4In_SendLogisticDT = (stampObj.Idf4In_SendLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_SendLogisticDT;
				stampObj.Idf4In_SendProductionDT = (stampObj.Idf4In_SendProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4In_SendProductionDT;
				stampObj.BayOut_TestInfraDT = (stampObj.BayOut_TestInfraDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.BayOut_TestInfraDT;
				stampObj.Idf4Out_SendProductionDT = (stampObj.Idf4Out_SendProductionDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_SendProductionDT;
				stampObj.Idf4Out_SendLogisticDT = (stampObj.Idf4Out_SendLogisticDT === "0000-00-00 00:00:00") ? 'NA' : stampObj.Idf4Out_SendLogisticDT;

				stampObj.Src_SendProductionOpID = (stampObj.Src_SendProductionOpID === null) ? 'NA' : stampObj.Src_SendProductionOpID;
				stampObj.Src_SendLogisticOpID = (stampObj.Src_SendLogisticOpID === null) ? 'NA' : stampObj.Src_SendLogisticOpID;
				stampObj.Idf4In_SendLogisticOpID = (stampObj.Idf4In_SendLogisticOpID === null) ? 'NA' : stampObj.Idf4In_SendLogisticOpID;
				stampObj.Idf4In_SendProductionOpID = (stampObj.Idf4In_SendProductionOpID === null) ? 'NA' : stampObj.Idf4In_SendProductionOpID;
				stampObj.BayOut_TestInfraOpID = (stampObj.BayIn_TestInfraOpID === null) ? 'NA' : stampObj.BayIn_TestInfraOpID;
				stampObj.Idf4Out_SendLogisticOpID = (stampObj.Idf4Out_SendLogisticOpID === null) ? 'NA' : stampObj.Idf4Out_SendLogisticOpID;
				stampObj.Idf4Out_SendProductionOpID = (stampObj.Idf4Out_SendProductionOpID === null) ? 'NA' : stampObj.Idf4Out_SendProductionOpID;
				stampObj.EndDT = (stampObj.EndDT === null) ? 'NA' : stampObj.EndDT;
				
				var usage_time = 0;
				var down_time = 0;
				var total_time = 0;

				if(stampObj.CurStatus) {
					if(stampObj.UsageTime != '0') {

						var amount = parseFloat(stampObj.UsageTime);
						var hourval = Math.floor(amount/60);
						var minval = Math.round((amount/60 - hourval) * 60);
						usage_time = hourval+':'+minval;

					} else {
						usage_time = "00:00";
					}

					if(stampObj.Downtime != '0') {
						var amount2 = parseFloat(stampObj.Downtime);
						var hourval2 = Math.floor(amount2/60);
						var minval2 = Math.round((amount2/60 - hourval2) * 60);
						down_time = hourval2+':'+minval2;
					} else {
						down_time = "00:00";
					}

					var totalAmt = parseFloat(stampObj.Downtime) + parseFloat(stampObj.UsageTime);
					var totalHour = Math.floor(totalAmt/60);
					var totalMin = Math.round((totalAmt/60 - totalHour) * 60);
					total_time = totalHour+':'+totalMin;					
				}

				var data_table = '';
				// data_table += "<tr align='center'><th>#</th>";
				// data_table += "<th>Serial No.</th>";
				// data_table += "<th>Model ID</th>";
				// data_table += "<th>Source IDF</th>";
				// data_table += "<th>Start Date</th>";
				// data_table += "<th>End Date</th>";
				// data_table += "<th>Test Status</th>";
				// data_table += "<th>Testers</th>";
				// data_table += "<th>UsageTime</th>";
				// data_table += "<th>DownTime</th>";
				// data_table += "<th>Total Time</th>";
				// data_table += "<th>Fail Reasons</th>";
				// data_table += "<th>Source Production Out (DT)</th>";
				// data_table += "<th>Source Production Out (OpID)</th>";
				// data_table += "<th>Source Partial Test Rxd (DT)</th>";
				// data_table += "<th>Source Partial Test Rxd (OpID)</th>";
				// data_table += "<th>Source Partial Test Send (DT)</th>";
				// data_table += "<th>Source Partial Test Send (OpID)</th>";
				// data_table += "<th>Source Logistics Rxd (DT)</th>";
				// data_table += "<th>Source Logistics Rxd (OpID)</th>";
				// data_table += "<th>Source Logistics Send (DT)</th>";
				// data_table += "<th>Source Logistics Send (OpID)</th>";
				// data_table += "<th>IDF4 In Logistics Rxd (DT)</th>";
				// data_table += "<th>IDF4 In Logistics Rxd (OpID)</th>";
				// data_table += "<th>IDF4 In Logistics Send (DT)</th>";
				// data_table += "<th>IDF4 In Logistics Send (OpID)</th>";
				// data_table += "<th>IDF4 In Production Rxd (DT)</th>";
				// data_table += "<th>IDF4 In Production Rxd (OpID)</th>";
				// data_table += "<th>IDF4 In Production Send (DT)</th>";
				// data_table += "<th>IDF4 In Production Send (OpID)</th>";
				// data_table += "<th>Test Infra Rxd (DT)</th>";
				// data_table += "<th>Test Infra Rxd (OpID)</th>";
				// data_table += "<th>Test Infra Send (DT)</th>";
				// data_table += "<th>Test Infra Send (OpID)</th>";
				// data_table += "<th>IDF4 Out Production Rxd (DT)</th>";
				// data_table += "<th>IDF4 Out Production Rxd (OpID)</th>";
				// data_table += "<th>IDF4 Out Production Send (DT)</th>";
				// data_table += "<th>IDF4 Out Production Send (OpID)</th>";
				// data_table += "<th>IDF4 Out Logistics Rxd (DT)</th>";
				// data_table += "<th>IDF4 Out Logistics Rxd (OpID)</th>";
				// data_table += "<th>IDF4 Out Logistics Send (DT)</th>";
				// data_table += "<th>IDF4 Out Logistics Send (OpID)</th>";
				// data_table += "</tr>";

				data_table += "<tr align='center'>";
				data_table += "<td>" + number + "</td>";
				data_table += "<td>" + stampObj.SerialNumber + "</td>";
				data_table += "<td>" + stampObj.ModelID + "</td>";
				data_table += "<td>" + stampObj.idfID + "</td>";
				data_table += "<td>" + stampObj.SourceDT + "</td>";
				data_table += "<td>" + stampObj.EndDT + "</td>";
				data_table += "<td>" + stampObj.CurStatus + "</td>";
				data_table += "<td>" + stampObj.Tester.trim().substring(0, stampObj.Tester.length-1) + "</td>";
				data_table += "<td>" + usage_time + "</td>";
				data_table += "<td>" + down_time + "</td>";
				data_table += "<td>" + total_time + "</td>";
				data_table += "<td>" + stampObj.FailReasons + "</td>";

				data_table += "<td>" + stampObj.Src_SendProductionDT + "</td>";
				data_table += "<td>" + stampObj.Src_SendProductionOpID + "</td>";

				data_table += "<td>" + stampObj.Src_RxdPartTestDT + "</td>";
				data_table += "<td>" + stampObj.Src_RxdPartTestOpID + "</td>";
				data_table += "<td>" + stampObj.Src_SendPartTestDT + "</td>";
				data_table += "<td>" + stampObj.Src_SendPartTestOpID + "</td>";

				data_table += "<td>" + stampObj.Src_RxdLogisticDT + "</td>";
				data_table += "<td>" + stampObj.Src_RxdLogisticOpID + "</td>";
				data_table += "<td>" + stampObj.Src_SendLogisticDT + "</td>";
				data_table += "<td>" + stampObj.Src_SendLogisticOpID + "</td>";

				data_table += "<td>" + stampObj.Idf4In_RxdLogisticDT + "</td>";
				data_table += "<td>" + stampObj.Idf4In_RxdLogisticOpID + "</td>";
				data_table += "<td>" + stampObj.Idf4In_SendLogisticDT + "</td>";
				data_table += "<td>" + stampObj.Idf4In_SendLogisticOpID + "</td>";

				data_table += "<td>" + stampObj.Idf4In_RxdProductionDT + "</td>";
				data_table += "<td>" + stampObj.Idf4In_RxdProductionOpID + "</td>";
				data_table += "<td>" + stampObj.Idf4In_SendProductionDT + "</td>";
				data_table += "<td>" + stampObj.Idf4In_SendProductionOpID + "</td>";

				data_table += "<td>" + stampObj.BayIn_TestInfraDT + "</td>";
				data_table += "<td>" + stampObj.BayIn_TestInfraOpID + "</td>";
				data_table += "<td>" + stampObj.BayOut_TestInfraDT + "</td>";
				data_table += "<td>" + stampObj.BayOut_TestInfraOpID + "</td>";

				data_table += "<td>" + stampObj.Idf4Out_RxdProductionDT + "</td>";
				data_table += "<td>" + stampObj.Idf4Out_RxdProductionOpID + "</td>";
				data_table += "<td>" + stampObj.Idf4Out_SendProductionDT + "</td>";
				data_table += "<td>" + stampObj.Idf4Out_SendProductionOpID + "</td>";

				data_table += "<td>" + stampObj.Idf4Out_RxdLogisticDT + "</td>";
				data_table += "<td>" + stampObj.Idf4Out_RxdLogisticOpID + "</td>";
				data_table += "<td>" + stampObj.Idf4Out_SendLogisticDT + "</td>";
				data_table += "<td>" + stampObj.Idf4Out_SendLogisticOpID + "</td>";

				data_table += "</tr>";

				return data_table;
			}
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