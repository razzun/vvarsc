<?php include_once('../functions/function_auth_admin.php'); ?>

<?php 
	$newMemberPassword = strip_tags(isset($_GET[pw]) ? $_GET[pw] : '');
?>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="../admin/?page=admin_mem">&#8672; Back to Member Management Home</a>
	</div>
</div>
<h2 id="MainPageHeaderText">Member Management</h2>
<div id="TEXT">
	<div class="divinfo_container">
		<p>
			Member Created/Updated Successfully
			<br />
			New Password: <? echo $newMemberPassword; ?>
		</p>
	</div>
</div>