<div class="sidebar">
	<div class="sidebar--collapse-btn">
	</div>
	<div class="admin-sidebar">
		<ul class="admin-sidebar--links">
			<li class="admin_charts_btn <?php echo ($page == "admin_charts" ? "active" : "")?>"><a href="<?php echo BASE_URL; ?>admin_charts.php"><span class="ico i-bar-chart"></span> <span class="label">Charts and Graphs</span></a>
			</li>
			<li class="admin_reports_btn <?php echo ($page == "admin_reports" ? "active" : "")?>"><a href="<?php echo BASE_URL; ?>admin_reports.php"><span class="ico i-table"></span> <span class="label">Reports</span></a>
			</li>
			<?php if($session_role == "admin"): ?>
			<li class="admin_users_btn <?php echo ($page == "admin_users" ? "active" : "")?>"><a href="<?php echo BASE_URL; ?>admin_users.php"><span class="ico i-person-stalker"></span> <span class="label">User Management</span></a></li>
			<li class="admin_cycles_btn <?php echo ($page == "admin_cycles" ? "active" : "")?>"><a href="<?php echo BASE_URL; ?>admin_cycles.php"><span class="ico i-timer"></span> <span class="label">Cycle Configuration</span></a></li>
			<?php endif; ?>
		</ul>
	</div>
</div>