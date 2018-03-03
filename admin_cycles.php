<?php 
	// for sidebar active toggle
	$page = "admin_cycles";

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
	if($session_role == "admin"): 
?>
<?php if($sidebarCollapsed == "true"): // body classes ?>
	<body class="admin_charts sidebar--collapsed">
<?php else: ?>
	<body class="admin_cycles">
<?php endif; ?>	

	<div id="page">

		<?php // navigation
			require_once '_includes/nav.php'; 
		?>

		<?php // admin sidebar
			require_once '_includes/admin_sidebar.php'; 
		?>

		<?php
			$query = mysqli_query($db, "SELECT * FROM productcycles");
			$total = mysqli_num_rows($query);
		?>

		<?php // admin contents ?>
		<div class="admin-container loading-animation">

			<div class="admin-contents">
				
				<div class="admin-heading">
					<ul class="breadcrumbs">
						<li><a href="#">Admin</a></li>
						<li>Cycle Configuration</li>
					</ul>
					<h1>Cycle Configuration</h1>
				</div>
				
				<div class="admin-tables">
					<div class="add-cycle">
						<button class="button icon secondary" id="cycle-create-btn" data-modal-open="#cycle-create">
							<span class="ico i-timer"></span>
							New Test Cycle
						</button>
					</div>
			
					<div class="product-cycle-list">
						<div class="admin-sub-heading">
							<h3>All Cycles</h3>

							<div class="record-controls">
								<div class="control pagination-controls">
									<button id="prev-record" class="button" disabled>Previous</button>
									<button id="next-record" class="button">Next</button>			
									<span class="currently-showing"></span> of <span class="records-total"></span> records found!
								</div>
								<div class="control">
									<button id="show-all-records" class="button hidden">Show All</button>
								</div>
								<div class="control right">
									<button id="delete-multiple-records" class="button hidden">Delete Selected</button>
								</div>
								<div class="control right">
									<span class="search-records">
										<form action="#" id="search-record-form" method="GET"> 
											<input type="text" name="search-model" placeholder="Search by model id / family">
											<button type="submit" id="search-record-btn"><span class="i-search-thick"></span></button>
										</form>
									</span>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table id="cycles-table" class="table hidden">
								<thead>
									<tr>
										<th><input type="checkbox" name="select-all" class="select-all"></th>
										<th class="table-col-full">Model Id</th>
										<th class="table-col-full">Family</th>
										<th class="table-col-full">Cycle Time(hh:mm)</th>
										<th class="table-col-full">Tolerance</th>
										<th class="table-col-full">Rating</th>
										<th class="table-col-btns">Edit</th>
										<th class="table-col-btns">Delete</th>
									</tr>
								</thead>
								<tbody id="cycle-rows">	
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- cycle-create -->
				<div class="modal" id="cycle-create">
					<div class="modal-contents">
						<button class="modal-close" data-modal-close="#cycle-create">&times;</button>
						<div class="modal-header">
							<h4>New Test Cycle</h4>
						</div>
						<div class="modal-body">
							<form action="#" method="POST" id="cycle-create-form" class="form-horizontal">
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="model_id">Model Id</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="model_id" name="model_id" data-validation="required">
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="product_family">Product Family</label>
									</div>
									<div class="col s-3-5">
										<select name="product_family" id="product_family" data-validation="required">
											<option value="DC Box 600MM">DC Box 600MM</option>
											<option value="LV Box">LV Box</option>
											<option value="PV BOX 1.36W">PV BOX 1.36W</option>
											<option value="Xantrex - Cheetah">Xantrex - Cheetah</option>
											<option value="Xantrex - Cheetah IEC">Xantrex - Cheetah IEC</option>
											<option value="Xantrex - Cheetah II">Xantrex - Cheetah II</option>
											<option value="Fortis-HHP">Fortis-HHP</option>
											<option value="Fortis-MHP">Fortis-MHP</option>
											<option value="HHP India">HHP India</option>
											<option value="HHP PACY">HHP PACY</option>
											<option value="KALA">KALA</option>
											<option value="KALA S6">KALA S6</option>
											<option value="MHP Export">MHP Export</option>
											<option value="EPS 1100">EPS 1100</option>
											<option value="EPS 7000">EPS 7000</option>
											<option value="EPS 8000">EPS 8000</option>
											<option value="EPS 8000 - ETO">EPS 8000 - ETO</option>
											<option value="EPS Make over">EPS Make over</option>
											<option value="Blue Door">Blue Door</option>
											<option value="Symmetra">Symmetra</option>
											<option value="Symmetra - Laguna">Symmetra - Laguna</option>
											<option value="Valhal">Valhal</option>
											<option value="Galaxy 5000 - Sierra">Galaxy 5000 - Sierra</option>
											<option value="Galaxy 5500">Galaxy 5500</option>
											<option value="Galaxy 5500 Marine">Galaxy 5500 Marine</option>
											<option value="Galaxy 7000">Galaxy 7000</option>
											<option value="Galaxy 7000 Shore Cnxtn">Galaxy 7000 Shore Cnxtn</option>
											<option value="Galaxy 9000">Galaxy 9000</option>
											<option value="PMM">PMM</option>
											<option value="STS APJ">STS APJ</option>
										</select>
									</div>
								</div>
								<div class="form-group required">
									<span class="col s-2-5">
										<label for="cycle_time">Cycle Time (H:M)</label>
									</span>
									<span class="col s-1-4">
										<input type="text" name="cycle_time" id="cycle_time" placeholder="hh:mm" data-validation="required" autocomplete="off">
									</span>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="rating">Rating (kVA)</label>
									</div>
									<div class="col s-3-5">
										<select name="rating" id="rating" data-validation="required">
											<option value="20">20 </option>
											<option value="40">40 </option>
											<option value="60">60 </option>
											<option value="80">80 </option>
											<option value="100">100 </option>
											<option value="120">120 </option>
											<option value="160">160 </option>
											<option value="200">200 </option>
											<option value="250">250 </option>
											<option value="300">300 </option>
											<option value="400">400 </option>
											<option value="500">500 </option>
											<option value="600">600 </option>
											<option value="800">800 </option>
											<option value="1000">1000 </option>
											<option value="1200">1200 </option>
											<option value="1400">1400 </option>
											<option value="1600">1600 </option>
										</select>
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="cycle_tol">Tolerance (M)</label>
									</div>
									<div class="col s-3-5">
										<input type="text" name="cycle_tol" id="cycle_tol" placeholder="mins" data-validation="required">
									</div>
								</div>
								<input type="submit" class="button secondary" value="Create New Cycle">
								<div class="form-messages">
									Fill all the fields.
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- cycle-create -->

				<!-- cycle-edit -->
				<div class="modal" id="cycle-edit">
					<div class="modal-contents">
						<button class="modal-close" data-modal-close="#cycle-edit">&times;</button>
						<div class="modal-header">
							<h4>Edit Test Cycle</h4>
						</div>
						<div class="modal-body">
							<form action="#" method="POST" id="cycle-edit-form" class="form-horizontal">
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="model_id">Model Id</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="model_id" name="model_id" data-validation="required">
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="product_family">Product Family</label>
									</div>
									<div class="col s-3-5">
										<select name="product_family" id="product_family" data-validation="required">
											<option value="DC Box 600MM">DC Box 600MM</option>
											<option value="LV Box">LV Box</option>
											<option value="PV BOX 1.36W">PV BOX 1.36W</option>
											<option value="Xantrex - Cheetah">Xantrex - Cheetah</option>
											<option value="Xantrex - Cheetah IEC">Xantrex - Cheetah IEC</option>
											<option value="Xantrex - Cheetah II">Xantrex - Cheetah II</option>
											<option value="Fortis-HHP">Fortis-HHP</option>
											<option value="Fortis-MHP">Fortis-MHP</option>
											<option value="HHP India">HHP India</option>
											<option value="HHP PACY">HHP PACY</option>
											<option value="KALA">KALA</option>
											<option value="KALA S6">KALA S6</option>
											<option value="MHP Export">MHP Export</option>
											<option value="EPS 1100">EPS 1100</option>
											<option value="EPS 7000">EPS 7000</option>
											<option value="EPS 8000">EPS 8000</option>
											<option value="EPS 8000 - ETO">EPS 8000 - ETO</option>
											<option value="EPS Make over">EPS Make over</option>
											<option value="Blue Door">Blue Door</option>
											<option value="Symmetra">Symmetra</option>
											<option value="Symmetra - Laguna">Symmetra - Laguna</option>
											<option value="Valhal">Valhal</option>
											<option value="Galaxy 5000 - Sierra">Galaxy 5000 - Sierra</option>
											<option value="Galaxy 5500">Galaxy 5500</option>
											<option value="Galaxy 5500 Marine">Galaxy 5500 Marine</option>
											<option value="Galaxy 7000">Galaxy 7000</option>
											<option value="Galaxy 7000 Shore Cnxtn">Galaxy 7000 Shore Cnxtn</option>
											<option value="Galaxy 9000">Galaxy 9000</option>
											<option value="PMM">PMM</option>
											<option value="STS APJ">STS APJ</option>
										</select>
									</div>
								</div>
								<div class="form-group required">
									<span class="col s-2-5">
										<label for="cycle_time">Cycle Time (H:M)</label>
									</span>
									<span class="col s-1-4">
										<input type="text" name="cycle_time" id="cycle_time" placeholder="hh:mm" data-validation="required" autocomplete="off">
									</span>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="rating">Rating (kVA)</label>
									</div>
									<div class="col s-3-5">
										<select name="rating" id="rating" data-validation="required">
											<option value="20">20 </option>
											<option value="40">40 </option>
											<option value="60">60 </option>
											<option value="80">80 </option>
											<option value="100">100 </option>
											<option value="120">120 </option>
											<option value="160">160 </option>
											<option value="200">200 </option>
											<option value="250">250 </option>
											<option value="300">300 </option>
											<option value="400">400 </option>
											<option value="500">500 </option>
											<option value="600">600 </option>
											<option value="800">800 </option>
											<option value="1000">1000 </option>
											<option value="1200">1200 </option>
											<option value="1400">1400 </option>
											<option value="1600">1600 </option>
										</select>
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="cycle_tol">Tolerance (M)</label>
									</div>
									<div class="col s-3-5">
										<input type="text" name="cycle_tol" id="cycle_tol" placeholder="mins" data-validation="required">
									</div>
								</div>
								<input type="submit" class="button secondary" value="Update Cycle">
								<div class="form-messages">
									Fill all the fields.
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- cycle-edit -->

			</div>
			<!-- admin-contents -->
		</div>
		<!-- admin-container -->

		<!-- confirm box -->
		<div class="modal" id="confirm-delete">
			<div class="modal-contents">
				<div class="modal-header">
					<h4>Confirm</h4>
				</div>
				<div class="modal-body">
					<p><big>Are you sure you want to delete the selected rows?</big></p>
				</div>
				<div class="modal-footer">
					<button class="button primary" id="delete-cycle-btn" data-delete-count="" data-modal-confirm="true">Yes</button>
					<button class="button red" data-modal-confirm="false">No</button>
				</div>
			</div>
		</div>
		
		<?php require_once '_includes/footer.php'; ?>

	</div>
	
	<script>
		
		// scripts only for this page
		// scripts only for this page
		var record = {

			'sel' : {
				'table' : '#cycles-table',
				'tableRows' : '#cycle-rows',
				'adminContainer' : '.admin-container',
				'selectAllCheckbox': 'input.select-all',
				'checkboxes' : 'input.select-this',
				'currentlyShowing' : '.currently-showing',
				'totalRecords' : '.records-total',
				'createModal' : '#cycle-create',
				'recordCreateBtn' : '#cycle-create-btn',
				'recordCreateForm' : '#cycle-create-form',
				'editModal' : '#cycle-edit',
				'recordEditForm' : '#cycle-edit-form',
				'formMessages' : '.form-messages',
				'nextRecordBtn' : '#next-record',
				'prevRecordBtn' : '#prev-record',
				'confirmDeleteBtn' : '#delete-cycle-btn',
				'deleteMultipleRecordsBtn' : '#delete-multiple-records',
				'searchRecordForm' : '#search-record-form',
				'paginationControls' : '.pagination-controls',
				'showAllBtn' : '#show-all-records'
 			},

			'urls': {
				'load' : '_modules/get_cycles.php',
				'total' : '_modules/total_cycles.php',
				'create' : '_modules/create_cycle.php',
				'edit' : '_modules/update_cycle.php',
				'delete' : '_modules/delete_cycle.php'
			},

			'messages' : {
				'cycleAlreadyExists' : 'Cycle already exists!',
				'noRecords' : 'There are no more records in the database!'
			},

			'limit_per_page' : 10,
			'start_index' : 0,
			'total_rows' : parseInt('<?php echo $total; ?>'),

			'init' : function () {

				var $records = this;
				// page load
				page.loadAnim.start($records.sel.adminContainer);
				$records.loadRows($records.start_index, $records.limit_per_page);

				// selectAll checkbox
				$records.activateSelectAll();

				// create Cycle
				modal.openEvent($records.sel.recordCreateBtn);

				// activate create form
				$records.createRecord();

				// activate update form
				$records.updateRecord();

				// next
				$($records.sel.nextRecordBtn).on('click', function() {
					$records.nextRecord(this);
				});
				// prev
				$($records.sel.prevRecordBtn).on('click', function() {
					$records.prevRecord(this);
				});

				// multiple delete activate
				$records.activateDeleteMultiple();

				// delete records
				$records.deleteRecords();

				// search records
				$records.searchRecords();

				// show all btn functionality
				$records.showAllRecords();

				//check button when page loads
				if($records.total_rows > $records.limit_per_page) {
					$($records.sel.nextRecordBtn).prop('disabled', false);
				} else {
					$($records.sel.nextRecordBtn).prop('disabled', true);
				}

			},

			'loadRows': function (s, l) {
				$records = this;

				var limitsData = 'start_index=' + s + '&limit_per_page=' + l;

				$.ajax({
					url: $records.urls.load,
					data: limitsData,
					success: function (data) {
						if(data == "no more records!") {
							page.createMsg($records.messages.noRecords, 'error');
							$records.navigatorStatus();
						} else {
							var d = JSON.parse(data);
							var rows = '';
							console.log(d);
							$.each(d, function (i, row) {
								rows += $records.createRowTemplate(row.ProductCycleID,row.ModelID,row.FamilyName,row.CycleTime,row.CycTol,row.Rating);
							});
							$($records.sel.tableRows).html(rows); // add rows to table
							page.element.show($records.sel.table); // show the hidden table
							$records.activateCheckboxes(); // activate checkbox func on new rows
							$records.navigatorStatus(); // update navivator status
							$records.activateEditDeleteBtns(); //edit delete function
						}
					},
					error: function (d) { console.log(d); },
					complete: function () { page.loadAnim.stop($records.sel.adminContainer); }
				});
			},

			'createRecord': function () {
				var $records = this;
				$($records.sel.recordCreateForm).on('submit', function(e) {

					e.preventDefault();
					var $this = $(this);
					var recordData = $this.serialize();

					$.ajax({
						url: $records.urls.create,
						method: 'POST',
						data: recordData,
						success: function (data) {
							// console.log(data);
							if(data == "err:1") {
								$records.updateMessage($records.messages.cycleAlreadyExists, 'error');
								page.createMsg($records.messages.cycleAlreadyExists, 'error');
							} else {
								$records.updateMessage(data, 'success');
								page.createMsg(data);
								$this.get()[0].reset();
								// increment total after adding
								$records.total_rows += 1;

								// refresh data to first page
								$records.start_index = 0;
								$records.loadRows($records.start_index, $records.limit_per_page);
								$records.navigatorStatus(); // update navivator status
								$records.buttonsUpdate(); // update button display
							}
						},
						error: function(d) { console.log(d); },
						complete: function () { page.loadAnim.stop($records.sel.adminContainer); }
					});

				});
			},

			'activateDeleteMultiple': function () {
				var $records = this;
				$($records.sel.deleteMultipleRecordsBtn).click(function(e) {
					e.preventDefault();
					var $this = this;
					var selectedCheckboxes = $($records.sel.table).find('.select-this:checked');
					var idArray = [];

					selectedCheckboxes.each(function() {
						var id = $(this).data('cycle-id');
						idArray.push(id);
					});
					$($records.sel.confirmDeleteBtn).data('delete-count', 'multiple').data('cycle-id', idArray);
					modal.openModal('#confirm-delete');
				});	 
			},

			'deleteRecords': function () {
				var $records = this;
				$($records.sel.confirmDeleteBtn).click(function () {
					var id = $(this).data('cycle-id');
					var count = $(this).data('delete-count');
					var d = "cycle_id="+id+"&delete="+count;
		
					$.ajax({
						url: $records.urls.delete,
						data: d,
						method: 'POST',
						success: function(data) {
							
							// update total after delete
							if(typeof id == 'object') {
								$records.total_rows -= id.length;
							} else {
								$records.total_rows--;
							}

							//check if all deleted 
							$records.loadRows($records.start_index, $records.limit_per_page);
							$records.navigatorStatus();
							$records.buttonsUpdate(); // update button display
							$records.deselectCheckboxes();
							modal.closeModal('#confirm-delete');
							
						},
						error: function (d) { console.log(d); }
					});
				});
			},

			'updateRecord': function () {

				var $records = this;
				$($records.sel.recordEditForm).on('submit', function(e) {

					e.preventDefault();
					page.loadAnim.stop($records.sel.adminContainer);
					var $this = $(this);
					var recordData = $this.serialize();
					var id = $this.find('[type="submit"]').data('cycle-id');

					$.ajax({
						url: $records.urls.edit,
						method: 'POST',
						data: recordData+'&product_cycle_id='+id,
						success: function (data) {
							console.log(data);
							if(data == "Cycle Configuration Updated!") {

								var rowToUpdate = $($records.sel.table).find('[data-cycle-id='+id+']').closest('tr');
								
								rowToUpdate.find('.model-id').text($this.find('#model_id').val());
								rowToUpdate.find('.model-family').text($this.find('#product_family').val());
								rowToUpdate.find('.cycle-time').text($this.find('#cycle_time').val());
								rowToUpdate.find('.model-tolerance').text($this.find('#cycle_tol').val());
								rowToUpdate.find('.model-rating').text($this.find('#rating').val());

								$records.updateMessage(data, 'success');
							} else {
								$records.updateMessage('Cannot update row! Try again.', 'error');
							}
						},
						error: function(d) { console.log(d); },
						complete: function () { page.loadAnim.stop($records.sel.adminContainer); }
					});

				});

			},

			'searchRecords': function () {
				$($records.sel.searchRecordForm).on('submit', function (e) {
					e.preventDefault();

					var $this = $(this);
					var model = $this.find('[name=search-model]').val();
					
					if(model.length>=3) {
						page.loadAnim.start('.admin-container');
						page.element.hide($records.sel.table); // hide the table
						var d = 'search-model='+model.trim();

						$.ajax({
							url: '_modules/search_cycle.php',
							data: d,
							method: 'GET',
							success: function (d) {
								 if(d == "err:1") {
								 	page.createMsg('No such record found!', 'error');
								 } else {
								 	var rowsData = JSON.parse(d);
									var rows = [];
									$.each(rowsData, function (i, row) {
										console.log(row);
										rows += $records.createRowTemplate(row.ProductCycleID,row.ModelID,row.FamilyName,row.CycleTime,row.CycTol,row.Rating);
									});
									$($records.sel.tableRows).html(rows); // add rows to table
									page.element.show($records.sel.table); // show the hidden table
									$records.activateCheckboxes(); // activate checkbox func on new rows
									$records.activateEditDeleteBtns(); //edit delete function

									page.element.hide($records.sel.paginationControls);
									page.element.show($records.sel.showAllBtn);
								 }
							},
							error: function (d) { console.log(d); },
							complete: function() { 
								page.loadAnim.stop('.admin-container'); 
								page.element.show($records.sel.table);
							}
						});
					} else {
						page.createMsg('Query too small to search, minimum 3 letters required!', 'error');
					}
				});
			},

			'showAllRecords' : function () {
				$records = this;
				$($records.sel.showAllBtn).on('click', function() {
					$records.start_index = 0;
					
					$records.loadRows($records.start_index, $records.limit_per_page);
					$records.navigatorStatus(); // update navivator status
					$records.buttonsUpdate(); // update button display

					$records.deselectCheckboxes($records.sel.table);
					page.element.show($records.sel.paginationControls);
					page.element.hide($records.sel.showAllBtn);
				});

			},

			'nextRecord': function (btn) {
				var $records = this;
				// enable prev
				$($records.sel.prevRecordBtn).prop('disabled', false);
				// disable next
				if(($records.start_index + $records.limit_per_page*2) >= $records.total_rows) {
					$(btn).prop('disabled', true);
					$records.start_index = $records.total_rows-$records.limit_per_page;
				} else {
					$records.start_index += $records.limit_per_page;
				}	
				$records.loadRows($records.start_index, $records.limit_per_page);

			},


			'prevRecord': function (btn) {
				var $records = this;
				// enable prev
				$($records.sel.nextRecordBtn).prop('disabled', false);
				// disable next
				if(($records.start_index - $records.limit_per_page) <= 0) {
					$(btn).prop('disabled', true);
					$records.start_index = 0;
				} else {
					$records.start_index -= $records.limit_per_page;
				}
				$records.loadRows($records.start_index, $records.limit_per_page);
			},

			'updateMessage': function (msg, flag) {

				var $records = this;
				var msgBox = $($records.sel.formMessages);

				flag = typeof flag !== 'undefined' ? flag : 'default';

				if(flag == "error") {
					msgBox.removeClass('success').addClass('error');
				} else if(flag == "success") {
					msgBox.addClass('success').removeClass('error');
				} else {
					msgBox.removeClass('success').removeClass('error');
				}

				msgBox.html(msg); 
			},

			'createRowTemplate' : function (id,modelid,family,cycletime,tolerance,rating) {
				var cycleTimeHH = numHelpers.leftPad(Math.floor( cycletime / 60));
				var cycleTimeMM = numHelpers.rightPad(cycletime % 60);
				var data = "";
				data += '<tr>';
				data += '<td><span><input type="checkbox" name="select-this" class="select-this" data-cycle-id="'+id+'"></span></td>';
				data += "<td><span class='model-id'>" + modelid + "</span></td>";
				data += "<td><span class='model-family'>" + family + "</span></td>";
				data += "<td><span class='cycle-time'>" + cycleTimeHH +':'+ cycleTimeMM + "</span></td>";
				data += "<td><span class='model-tolerance'>"+ tolerance +"</span></td>";
				data += "<td><span class='model-rating'>"+ rating +"</span></td>";
				data += '<td class="table-col-btns"><span class="edit-btn" data-modal-open="#cycle-create" data-cycle-id="'+id+'"><span class="ico i-pencil-square"></span></span></td>';
				data += '<td class="table-col-btns"><span class="delete-btn" data-modal-open="#confirm-delete" data-cycle-id="'+id+'"><span class="ico i-close"></span></span></td>';
				data += '</tr>';

				return data;
			},

			'navigatorStatus': function () {
				var $records = this;

				var currentNum = $($records.sel.tableRows).find('tr').length + $records.start_index;
				
				$($records.sel.currentlyShowing).text(currentNum);
				$($records.sel.totalRecords).text($records.total_rows); 

				if(currentNum === 0) {
					$($records.sel.currentlyShowing).text(0);
					$($records.sel.totalRecords).text(0);
					page.element.hide($($records.sel.searchRecordForm));
				} else {
					page.element.show($($records.sel.searchRecordForm));
				}
				
			},

			'buttonsUpdate': function () {
				var $records = this;
				var currentNum = $($records.sel.tableRows).find('tr').length + $records.start_index;
				
				if(currentNum === 0) {
					$($records.sel.currentlyShowing).text(0);
					$($records.sel.totalRecords).text(0);
					$($records.sel.prevRecordBtn).prop('disabled', true);
					$($records.sel.nextRecordBtn).prop('disabled', true);
				} else {
					if($records.total_rows <= $records.limit_per_page) {
						$($records.sel.nextRecordBtn).prop('disabled', true);
						$($records.sel.prevRecordBtn).prop('disabled', true);
					} else if($records.total_rows > $records.limit_per_page) {
						$($records.sel.nextRecordBtn).prop('disabled', false);
					}
				}
			},

			'activateEditDeleteBtns': function() {
				$($records.sel.tableRows).find('.edit-btn').click(function(e) {
					e.preventDefault();
					var $this = this;
					modal.openModal('#cycle-edit', function() {
						$records.configureUpdateForm($this);
					});
				});	
				$($records.sel.tableRows).find('.delete-btn').click(function(e) {
					e.preventDefault();
					var $this = this;
					modal.openModal('#confirm-delete');
					$($records.sel.confirmDeleteBtn).data('cycle-id', $(this).data('cycle-id')).data('delete-count', 'single');
				});
			},

			'configureUpdateForm': function (btn) {
				var $records = this;
				var curRow = $(btn).closest('tr');
				var cycleid = $(btn).data('cycle-id');
				var modelid = curRow.find('.model-id').text();
				var family = curRow.find('.model-family').text();
				var cycletime = curRow.find('.cycle-time').text();
				var tolerance = curRow.find('.model-tolerance').text();
				var rating = curRow.find('.model-rating').text();
				
				// configure form
				$($records.sel.editModal).find('[type="submit"]').data('cycle-id', cycleid);
				$($records.sel.editModal).find('.form-messages')
								.text('Edit fields and update.')
								.removeClass('success error');
				$($records.sel.editModal).find('[name="model_id"]').val(modelid);
				$($records.sel.editModal).find('[name="product_family"] option').each(function() {
					if($(this).val() == family) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});
				$($records.sel.editModal).find('[name="cycle_time"]').val(cycletime);
				$($records.sel.editModal).find('[name="rating"] option').each(function() {
					if($(this).val() == rating) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});
				$($records.sel.editModal).find('[name="cycle_tol"]').val(tolerance);	 
			},

			'activateSelectAll': function () {
				var $records = this;
				$($records.sel.selectAllCheckbox).on('click', function() {
					var allCheckboxes = $(this).closest('table').find($records.sel.checkboxes);
					var check = $(this).is(':checked');
					if(check) {
						allCheckboxes.prop('checked', true);
						$($records.sel.deleteMultipleRecordsBtn).removeClass('hidden');
					} else {
						allCheckboxes.prop('checked', false);
						$($records.sel.deleteMultipleRecordsBtn).addClass('hidden');
					}
				});
			},

			'activateCheckboxes': function () {
				var $records = this;

				$($records.sel.checkboxes).on('click', function() {
					var mainCheckbox = $(this).closest('table').find($records.sel.selectAllCheckbox);
					var allCheckboxes = $(this).closest('table').find($records.sel.checkboxes);
					var check = $(this).is(':checked');
					var allCount = allCheckboxes.length;
					var countChecked = allCheckboxes.filter(':checked').length;
					if(allCount == countChecked) {
						mainCheckbox.prop('indeterminate', false);
						mainCheckbox.prop('checked', true);
						$($records.sel.deleteMultipleRecordsBtn).removeClass('hidden');
					} else if (countChecked === 0) {
						mainCheckbox.prop('indeterminate', false);
						mainCheckbox.prop('checked', false);
						$($records.sel.deleteMultipleRecordsBtn).addClass('hidden');
					}  else if (countChecked > 0) {
						mainCheckbox.prop('indeterminate', true);
						mainCheckbox.prop('checked', false);
						$($records.sel.deleteMultipleRecordsBtn).removeClass('hidden');
					}
				});
			},

			'deselectCheckboxes' : function() {
				var mainCheckbox = $($records.sel.table).find('input.select-all');
				var allCheckboxes = $($records.sel.table).find('input.select-this');
				mainCheckbox.prop('indeterminate', false);
				mainCheckbox.prop('checked', false);
				allCheckboxes.prop('indeterminate', false);
				allCheckboxes.prop('checked', false);
				$($records.sel.deleteMultipleRecordsBtn).addClass('hidden');
			}

		};

		$(function () {
			// datetimepicker
			$('[name = "cycle_time"]').datetimepicker({
			  datepicker:false,
			  format:'H:i'
			});
			record.init();
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