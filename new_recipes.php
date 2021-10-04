<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>
<?php 
	if ($ENABLE_RTL_MODE == 'true') {
		include('public/table-new-recipes-rtl.php');
	} else {
		include('public/table-new-recipes.php');
	}
?><?php include('public/footer.php'); ?>
