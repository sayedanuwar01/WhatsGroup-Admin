<?php
	include_once('functions.php'); 
	require_once("thumbnail_images.class.php");
?>

	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store category data
		$category_data = array();
			
		$sql_query = "SELECT cid, category_name 
				FROM tbl_category 
				ORDER BY cid DESC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$store_result = $stmt_category->store_result();
			$stmt_category->bind_result($category_data['cid'], 
				$category_data['category_name']
				);
			$stmt_category->fetch();
				
		}
		
		if($store_result){
    			
    		$sql_query = "SELECT news_image FROM tbl_recipes WHERE nid = ?";
    		
    		$stmt = $connect->stmt_init();
    		if($stmt->prepare($sql_query)) {	
    			// Bind your variables to replace the ?s
    			$stmt->bind_param('s', $ID);
    			// Execute query
    			$stmt->execute();
    			// store result 
    			$stmt->store_result();
    			$stmt->bind_result($previous_news_image);
    			$stmt->fetch();
    		}
		}
		
		$sql_query = "SELECT news_heading, cat_id, news_status, news_date, recipe_person, recipe_calorie, news_image, news_description FROM tbl_recipes  WHERE nid = ?";
		 
        $data = array();
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {
		    $stmt->bind_param('s', $ID);
			
			$stmt->execute();
			$stmt->store_result();
		    $stmt->bind_result($data['news_heading'], 
				$data['cat_id'], 
				$data['news_status'], 
				$data['news_date'], 
				$data['recipe_person'], 
				$data['recipe_calorie'],
				$data['news_image'], 
				$data['news_description']
			);
			$stmt->fetch();
		}
		
		if(isset($_POST['btnEdit'])){
			
			$news_heading = $_POST['news_heading'];
			$cid = $_POST['cid'];
			$news_date = $_POST['news_date'];
			$news_description = $_POST['news_description'];
			
			// get image info
			$news_image = $_FILES['news_image']['name'];
			$image_error = $_FILES['news_image']['error'];
			$image_type = $_FILES['news_image']['type'];
				
			// create array variable to handle error
			$error = array();
			
			if(empty($news_heading)){
				$error['news_heading'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
				
			if(empty($cid)){
				$error['cid'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}				
				
			if(empty($news_date)){
				$error['news_date'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}			

			if(empty($news_description)){
				$error['news_description'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["news_image"]["name"]));
			
			if(!empty($news_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					
					$error['news_image'] = "*<span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
				}
			}
			
					
			if( !empty($news_heading) && 
				!empty($cid) && 
				!empty($news_date) && 
				!empty($news_description) && 
				empty($error['news_image'])){
				
				if(!empty($news_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['news_image']['name']);
					$function = new functions;
					$news_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink('upload/'."$previous_news_image");
					$delete = unlink('upload/thumbs/'."$previous_news_image");
					
					// upload new image
					$unggah = 'upload/'.$news_image;
					$upload = move_uploaded_file($_FILES['news_image']['tmp_name'], $unggah);

					error_reporting(E_ERROR | E_PARSE);
					copy($news_image, $unggah);
									 
											$thumbpath= 'upload/thumbs/'.$news_image;
											$obj_img = new thumbnail_images();
											$obj_img->PathImgOld = $unggah;
											$obj_img->PathImgNew =$thumbpath;
											$obj_img->NewWidth = 250;
											$obj_img->NewHeight = 250;
											if (!$obj_img->create_thumbnail_images()) 
												{
												echo "Thumbnail not created... please upload image again";
													exit;
												}	 
	  
					// updating all data
					$sql_query = "UPDATE tbl_recipes 
							SET news_heading = ? , cat_id = ?, news_date = ?, news_image = ?, news_description = ? 
							WHERE nid = ?";
					
					$upload_image = $news_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssssss', 
									$news_heading, 
									$cid, 
									$news_date, 
									$upload_image,
									$news_description,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					// updating all data except image file
					$sql_query = "UPDATE tbl_recipes 
							SET news_heading = ? , cat_id = ?, 
							news_date = ?, news_description = ? 
							WHERE nid = ?";
							
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssss', 
									$news_heading, 
									$cid,
									$news_date, 
									$news_description,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}
					
				// check update result
				if($update_result) {
					$error['update_data'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												    Recipes Successfully Updated
											    </span>
											</div>";
				} else {
					$error['update_data'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Update Failed
											    </span>
											</div>";
				}
				header('location: recipes.php');
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
                    <h4 class="card-title">Edit Group</h4>
                </div>
                <div class="card-body">
                    <div class="card-block">
		                 	<form method="post" class="col s12" enctype="multipart/form-data">
		                  		<div class="row">
		                    		<div class="input-field col s12">   
		                    		<?php echo isset($error['update_data']) ? $error['update_data'] : '';?>   
            
								        <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <h5>Enter Gruop Name <span class="required">*</span></h5>
                                        <div class="controls">
									                    <input type="text" class="form-control" name="news_heading" id="news_heading" value="<?php echo $data['news_heading']; ?>" required/>
									                    <label for="news_heading"></label><?php echo isset($error['news_heading']) ? $error['news_heading'] : '';?>
									                  </div>
									                </div>

									                <div class="form-group">
														<h5>Group Link <span class="required">*</span></h5>
                                        <div class="controls">
								                        <input type="text" class="form-control" name="news_date" id="news_date" value="<?php echo $data['news_date']; ?>" required/>
								                        <label for="news_date"> <?php echo isset($error['news_date']) ? $error['news_date'] : '';?></label>
								                      </div>
								                    </div>  						                  	

								                    <div class="form-group">
														<h5>Choose Category<span class="required">*</span></h5>
                                        <div class="controls">
			                                            <select class="form-control" name="cid">
															<?php while($stmt_category->fetch()){ 
																if($category_data['cid'] == $data['cat_id']){?>
																	<option value="<?php echo $category_data['cid']; ?>" selected="<?php echo $data['cat_id']; ?>" ><?php echo $category_data['category_name']; ?></option>
																<?php }else{ ?>
																	<option value="<?php echo $category_data['cid']; ?>" ><?php echo $category_data['category_name']; ?></option>
																<?php }} ?>
														</select>
			                                            <label>Category</label><?php echo isset( $data['cat_id']) ?  $data['cat_id'] : '';?></div>	
		                                            </div>    			                    

								                   
								                    <div class="form-group">
                                        <h5>Select Image <span class="required">*</span></h5>
                                        <div class="controls">
															<?php echo isset($data['news_image']) ? $data['news_image'] : '';?>
															<input type="file" name="news_image" id="news_image" value="" />

								                                </div>	
							                            </div>					                    
									                  	

	              							<div class="form-group">
														<h5>Group Description <span class="required">*</span></h5>
                                        <div class="controls">
								                        <input type="text" class="form-control" name="news_description" id="news_description" value="<?php echo $data['news_description'];?>" required/>
								                        <label for="news_description"> </label>
								                      </div>
								                    </div>

													<div class="row">
														<div class="input-field col s12">
					                                        <button class="btn mr-1 btn-primary"
					                                                type="submit" name="btnEdit">Update
					                                            <i class="mdi-content-send right"></i>
					                                        </button>		
				                                        </div>
			                                        </div>									                    										                    
							                  	</div>
							                </div>

									</div>
						        </div>
						    </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>