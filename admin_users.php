<?php 
	// for sidebar active toggle
	$page = "admin_users";

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
	<body class="admin_users sidebar--collapsed">
<?php else: ?>
	<body class="admin_users">
<?php endif; ?>	

	<div id="page">

		<?php // navigation
			require_once '_includes/nav.php'; 
		?>

		<?php // admin sidebar
			require_once '_includes/admin_sidebar.php'; 
		?>
	
		<?php
			$query = mysqli_query($db, "SELECT * FROM users");
			$total = mysqli_num_rows($query);
		?>
		
		<!-- admin container -->
		<div class="admin-container">
			
			<!-- admin-contents -->
			<div class="admin-contents">
				
				<div class="admin-heading">
					<ul class="breadcrumbs">
						<li><a href="#">Admin</a></li>
						<li>User Management</li>
					</ul>
					<h1>User Management</h1>
				</div>
				
				<div class="admin-tables">
					<div class="add-user">
						<button class="button icon secondary" id="user-create-btn" data-modal-open="#user-create">
							<span class="ico i-person-add"></span>
							Add new user
						</button>
					</div>
			
					<div class="user-list">
						<div class="admin-sub-heading">
							<h3>All Users</h3>

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
											<input type="text" name="search-user" placeholder="Search by username / fullname">
											<button type="submit" id="search-record-btn"><span class="i-search-thick"></span></button>
										</form>
									</span>
								</div>
							</div>	
						</div>
						
						<div class="table-responsive">
							<table id="users-table" class="table hidden">
							<thead>
								<tr>
									<th><input type="checkbox" class="select-all"></th>
									<th class="table-col-full">Username</th>
									<th class="table-col-full">Name</th>
									<!-- <th class="table-col-full">Email</th> -->
									<th class="table-col-full">Role</th>
									<th class="table-col-full">Location</th>
									<th class="table-col-full">Last Login</th>
									<th class="table-col-btns">Edit</th>
									<th class="table-col-btns">Delete</th>
								</tr>
							</thead>
							<tbody id="user-rows">
							</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- user-create -->
				<div class="modal" id="user-create">
					<div class="modal-contents">
						<button class="modal-close" data-modal-close="#user-create">&times;</button>
						<div class="modal-header">
							<h4>New User</h4>
						</div>
						<div class="modal-body">
							<form action="#" method="POST" id="user-create-form" class="form-horizontal">
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="user_name">Username</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="user_name" name="user_name" data-validation="required">
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="full_name">Name</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="full_name" name="full_name" data-validation="required">
									</div>
								</div>
								<!-- <div class="form-group required">
									<div class="col s-2-5">
										<label for="user_email">Email</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="user_email" name="user_email" data-validation="required email">
									</div>
								</div> -->
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="user_pass_confirmation">Password</label>
									</div>
									<div class="col s-3-5">
										<input type="password" id="user_pass_confirmation" name="user_pass_confirmation" data-validation="required length" data-validation-length="min8">
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="user_pass">Re-type Password</label>
									</div>
									<div class="col s-3-5">
										<input type="password" id="user_pass" name="user_pass" data-validation="confirmation" data-validation-error-msg="Password does not match">
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="user_role">User Role</label>
									</div>
									<div class="col s-3-5">
										<select name="user_role" id="user_role" data-validation="required">
											<option disabled selected value="">Select a role</option>
											<option value="production">Production</option>
											<option value="logistic">Logistic</option>
											<option value="baytester">Testing</option>
										</select>
									</div>
								</div>
								<div class="form-group required">
									<div class="col s-2-5">
										<label for="user_role">Location</label>
									</div>
									<div class="col s-3-5">
										<select name="user_loc" id="user_loc" data-validation="required">
											<option disabled selected value="">Select a location</option>
											<option value="1">IDF 1</option>
											<option value="2">IDF 2</option>
											<option value="3">IDF 3</option>
											<option value="4">IDF 4</option>
											<option value="5">IDF 5</option>
											<option value="6">IDF 6</option>
											<option value="7">IDF 7</option>
											<option value="8">IDF 8</option>
											<option value="9">IDF 9</option>
											<option value="10">SAF</option>
											<option value="4">Test Infrastructure</option>
										</select>
									</div>
								</div>
								<input type="submit" class="button secondary" value="Create New User">
								<div class="form-messages">
									Fill all the fields.
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- user-create -->

				
				<!-- user-edit -->
				<div class="modal" id="user-edit">
					<div class="modal-contents">
						<button class="modal-close" data-modal-close="#user-edit">&times;</button>
						<div class="modal-header">
							<h4>Edit User</h4>
						</div>
						<div class="modal-body">
							<form action="#" method="POST" id="user-edit-form" class="form-horizontal">
								<div class="form-group">
									<div class="col s-2-5">
										<label for="user_name">Username</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="user_name" name="user_name">
									</div>
								</div>
								<div class="form-group">
									<div class="col s-2-5">
										<label for="full_name">Name</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="full_name" name="full_name">
									</div>
								</div>
								<!-- <div class="form-group">
									<div class="col s-2-5">
										<label for="user_email">Email</label>
									</div>
									<div class="col s-3-5">
										<input type="text" id="user_email" name="user_email" data-validation="required email">
									</div>
								</div> -->
								<div class="form-group">
									<div class="col s-2-5">
										<label for="user_pass_confirmation">Password</label>
									</div>
									<div class="col s-3-5">
										<input type="password" id="user_pass_confirmation" name="user_pass_confirmation" data-validation="length" data-validation-length="min8" data-validation-optional="true">
									</div>
								</div>
								<div class="form-group">
									<div class="col s-2-5">
										<label for="user_pass">Re-type Password</label>
									</div>
									<div class="col s-3-5">
										<input type="password" id="user_pass" name="user_pass" data-validation="confirmation" data-validation-error-msg="Password does not match">
									</div>
								</div>
								<div class="form-group">
									<div class="col s-2-5">
										<label for="user_role">User Role</label>
									</div>
									<div class="col s-3-5">
										<select name="user_role" id="user_role">
											<option disabled selected value="">Select a role</option>
											<option value="production">Production</option>
											<option value="logistic">Logistic</option>
											<option value="baytester">Testing</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col s-2-5">
										<label for="user_role">Location</label>
									</div>
									<div class="col s-3-5">
										<select name="user_loc" id="user_loc">
											<option disabled selected value="">Select a location</option>
											<option value="1">IDF 1</option>
											<option value="2">IDF 2</option>
											<option value="3">IDF 3</option>
											<option value="4">IDF 4</option>
											<option value="5">IDF 5</option>
											<option value="6">IDF 6</option>
											<option value="7">IDF 7</option>
											<option value="8">IDF 8</option>
											<option value="9">IDF 9</option>
											<option value="10">SAF</option>
											<option value="4">Test Infrastructure</option>
										</select>
									</div>
								</div>
								<input type="submit" class="button secondary" value="Update User">
								<div class="form-messages">
									Edit fields and update.
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- user-edit -->


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
					<button class="button primary" id="delete-user-btn" data-delete-count="" data-modal-confirm="true">Yes</button>
					<button class="button red" data-modal-confirm="false">No</button>
				</div>
			</div>
		</div>

		<?php // Footer
			require_once '_includes/footer.php'; 
		?>

	</div>

	<script>
		
		// scripts only for this page
		var record = {

			'sel' : {
				'table' : '#users-table',
				'tableRows' : '#user-rows',
				'adminContainer' : '.admin-container',
				'selectAllCheckbox': 'input.select-all',
				'checkboxes' : 'input.select-this',
				'currentlyShowing' : '.currently-showing',
				'totalRecords' : '.records-total',
				'createModal' : '#user-create',
				'recordCreateBtn' : '#user-create-btn',
				'recordCreateForm' : '#user-create-form',
				'editModal' : '#user-edit',
				'recordEditForm' : '#user-edit-form',
				'formMessages' : '.form-messages',
				'nextRecordBtn' : '#next-record',
				'prevRecordBtn' : '#prev-record',
				'confirmDeleteBtn' : '#delete-user-btn',
				'deleteMultipleRecordsBtn' : '#delete-multiple-records',
				'searchRecordForm' : '#search-record-form',
				'paginationControls' : '.pagination-controls',
				'showAllBtn' : '#show-all-records'
 			},

			'urls': {
				'load' : '_modules/get_users.php',
				'total' : '_modules/total_users.php',
				'create' : '_modules/create_user.php',
				'edit' : '_modules/update_user.php'
			},

			'messages' : {
				'userAlreadyExists' : 'Username already exists!',
				// 'emailAlreadyExists' : 'Email already exists!',
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

				// create user
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

				//check button when page loadss
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
							$.each(d, function (i, row) {
								rows += $records.createRowTemplate(row.UserID,row.Username,row.Fullname,row.Role,row.idfID,row.Lastlogin);
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
							// if(data == "err:1") {
							// 	$records.updateMessage($records.messages.emailAlreadyExists, 'error');
							// 	page.createMsg($records.messages.emailAlreadyExists, 'error');
							// } else 
							if(data == "err:2") {
								$records.updateMessage($records.messages.userAlreadyExists, 'error');
								page.createMsg($records.messages.userAlreadyExists, 'error');
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
						var id = $(this).data('user-id');
						idArray.push(id);
					});
					$($records.sel.confirmDeleteBtn).data('delete-count', 'multiple').data('user-id', idArray);
					modal.openModal('#confirm-delete');
				});	 
			},

			'deleteRecords': function () {
				var $records = this;
				$($records.sel.confirmDeleteBtn).click(function () {
					var id = $(this).data('user-id');
					var count = $(this).data('delete-count');
					var d = "user_id="+id+"&delete="+count;
		
					$.ajax({
						url: '_modules/delete_user.php',
						data: d,
						method: 'POST',
						success: function(data) {
							
							// update total after delete
							if(typeof id == 'object') {
								$records.total_rows -= id.length;
							} else {
								$records.total_rows--;
							}

							console.log($($records.sel.tableRows).find('tr').length);
							$records.start_index = 0;
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
					var id = $this.find('[type="submit"]').data('user-id');

					$.ajax({
						url: $records.urls.edit,
						method: 'POST',
						data: recordData+'&user_id='+id,
						success: function (data) {
							if(data == "User updated successfully!") {
								var rowToUpdate = $($records.sel.tableRows).find('[data-user-id='+id+']').closest('tr');
								var fieldstoUpdate = rowToUpdate.find('.username, .user-fullname, .user-role, .user-location');
								$this.find('input, select').not('[type="password"]').each(function (i, d) {
									 fieldstoUpdate.eq(i).text($(this).val());
								});

								$records.updateMessage(data, 'success');
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
					var user = $this.find('[name=search-user]').val();
					
					if(user.length>=3) {
						page.loadAnim.start('.admin-container');
						page.element.hide($records.sel.table); // hide the table
						var d = 'search-user='+user.trim();

						$.ajax({
							url: '_modules/search_user.php',
							data: d,
							method: 'GET',
							success: function (d) {
								 if(d == "err:1") {
								 	page.createMsg('No such record found!', 'error');
								 } else {
								 	var rowsData = JSON.parse(d);
									var rows = [];
									$.each(rowsData, function (i, row) {
										rows += $records.createRowTemplate(row.UserID,row.Username,row.Fullname,row.Role,row.idfID,row.Lastlogin);
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

			'createRowTemplate' : function (id,username,fullname,role,location,lastlogin) {
				var row = "";
				row += '<tr>';
				row += '<td><span><input type="checkbox" name="select-this" class="select-this" data-user-id="'+id+'"></span></td>';
				row += "<td><span class='username'>" + username + "</span></td>";
				row += "<td><span class='user-fullname'>" + fullname + "</span></td>";
				// row += "<td><span class='user-email'>" + email + "</span></td>";
				row += "<td><span class='user-role'>"+ role +"</span></td>";
				row += "<td><span class='user-location'>"+ location +"</span></td>";
				row += "<td><span class='user-lastlogin'>"+ lastlogin +"</span></td>";
				row += '<td class="table-col-btns"><span class="edit-btn" data-modal-open="#user-create" data-user-id="'+id+'"><span class="ico i-pencil-square"></span></span></td>';
				row += '<td class="table-col-btns"><span class="delete-btn" data-modal-open="#confirm-delete" data-user-id="'+id+'"><span class="ico i-close"></span></span></td>';
				row += '</tr>';

				return row;
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
					modal.openModal('#user-edit', function() {
						$records.configureUpdateForm($this);
					});
				});	
				$($records.sel.tableRows).find('.delete-btn').click(function(e) {
					e.preventDefault();
					var $this = this;
					modal.openModal('#confirm-delete');
					$($records.sel.confirmDeleteBtn).data('user-id', $(this).data('user-id')).data('delete-count', 'single');
				});
			},

			'configureUpdateForm': function (btn) {
				var $records = this;
				var curRow = $(btn).closest('tr');
				var userid = $(btn).data('user-id');
				var username = curRow.find('.username').text();
				var fullname = curRow.find('.user-fullname').text();
				// var email = curRow.find('.user-email').text();
				var role = curRow.find('.user-role').text();
				var location = curRow.find('.user-location').text();
				var lastlogin = curRow.find('.user-lastlogin').text();
				// configure form
				$($records.sel.editModal).find('[type="submit"]').data('user-id', userid);
				$($records.sel.editModal).find('.form-messages')
								.text('Edit fields and update.')
								.removeClass('success error');
				$($records.sel.editModal).find('[name="user_name"]').val(username);
				$($records.sel.editModal).find('[name="full_name"]').val(fullname);
				// $($records.sel.editModal).find('[name="user_email"]').val(email);
				$($records.sel.editModal).find('[name="user_role"] option').each(function() {
					if($(this).val() == role) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});
				$($records.sel.editModal).find('[name="user_loc"] option').each(function() {
					if($(this).val() == location) {
						$(this).prop('selected', true);
					} else {
						$(this).prop('selected', false);
					}
				});	 
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
			record.init();

			$('#user_role').on('change', function(evt) {
		      	if(this.value == 'baytester') {
		      		$('#user_loc option:last').prop('selected', true);
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