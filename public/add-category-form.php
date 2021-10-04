<?php
	include_once('functions.php'); 
?>

	<?php 
		if(isset($_POST['btnAdd'])){
			$category_name = $_POST['category_name'];
			
			// get image info
			$menu_image = $_FILES['category_image']['name'];
			$image_error = $_FILES['category_image']['error'];
			$image_type = $_FILES['category_image']['type'];
			
			// create array variable to handle error
			$error = array();
			
			if(empty($category_name)){
				$error['category_name'] = " <span class='label label-danger'>Must Insert!</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["category_image"]["name"]));
					
			if($image_error > 0){
				$error['category_image'] = " <span class='label label-danger'>You're not insert images!!</span>";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($extension, $allowedExts))){
			
				$error['category_image'] = " <span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
			}
			
			if(!empty($category_name) && empty($error['category_image'])){
				
				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
				$function = new functions;
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
				// upload new image
				$upload = move_uploaded_file($_FILES['category_image']['tmp_name'], 'upload/category/'.$menu_image);
		
				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_category (category_name, category_image)
						VALUES(?, ?)";
				
				$upload_image = $menu_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ss', 
								$category_name, 
								$upload_image
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
				
				if($result) {
					$error['add_category'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												   New Category Added Successfully
											    </span>
											</div>";
				} else {
					$error['add_category'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
				}
			}
			
		}
		
	?>

	<!-- START CONTENT -->
<div class="main-panel">
        <div class="main-content">
          <div class="content-wrapper">
            <div class="container-fluid"><div class="row">
    <div class="col-sm-12">
        <h2 class="content-header">Add New Group Link</h2>
    </div>
</div>
        <!--breadcrumbs end-->	

        <!--start container-->
        <section class="input-validation">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add New Group</h4>
                </div>
                <div class="card-body">
                    <div class="card-block">
		                 		<form method="post" class="col s12" enctype="multipart/form-data">
		                  			<div class="row">
		                    			<div class="input-field col s12">   
		                    				<?php echo isset($error['add_category']) ? $error['add_category'] : '';?>       
											
											<div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <h5>Enter Category Name <span class="required">*</span></h5>
                                        <div class="controls">
						                      	<label for="category_name"></label><?php echo isset($error['category_name']) ? $error['category_name'] : '';?>
						                        <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Enter Category Name" required/>						                      </div>
						                    </div> 	
						               

						                    <div class="form-group">
                                        <h5>Select Image <span class="required">*</span></h5>
                                        <div class="controls">
											<?php echo isset($error['category_image']) ? $error['category_image'] : '';?> <input type="file" name="category_image" id="category_image" value="" required/>
	                                                
											</div>
										
	                                            </div>
	                                        

	                                        <button class="btn mr-1 btn-primary"
	                                                type="submit" name="btnAdd"><?php if(isset($_GET['cat_id'])){?>Edit Category<?php }else {?>Add Category<?php }?>
	                                            <i class="mdi-content-send right"></i>
	                                        </button>	                                        

										</div>
						            </div>
						        </form>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>