<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>
<?php 
	if ($ENABLE_RTL_MODE == 'true') {
		include('public/table-category-rtl.php');
	} else {
		include('public/table-category.php');
	}
?>
<?php include('public/footer.php'); ?>