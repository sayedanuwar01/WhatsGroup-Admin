	<?php 
			$username = $_SESSION['user'];
			$sql_query = "SELECT Password, Email 
					FROM tbl_user 
					WHERE Username = ?";
			
			// create array variable to store previous data
			$data = array();
			
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $username);			
				// Execute query
				$stmt->execute();
				// store result 
				$stmt->store_result();
				$stmt->bind_result($data['Password'], $data['Email']);
				$stmt->fetch();
				$stmt->close();
			}
			
			$previous_password = $data['Password'];
			$previous_email = $data['Email'];
			
			if(isset($_POST['btnChange'])){
				$email = $_POST['email'];
				$old_password = hash('sha256',$username.$_POST['old_password']);
				$new_password = hash('sha256',$username.$_POST['new_password']);
				$confirm_password = hash('sha256',$username.$_POST['confirm_password']);
				
				// create array variable to handle error
				$error = array();
				
				// check password
				if(!empty($_POST['old_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
					if(!empty($_POST['old_password'])){
						if($old_password == $previous_password){
							if(!empty($_POST['new_password']) || !empty($_POST['confirm_password'])){
								if($new_password == $confirm_password){
									// update password in user table
									$sql_query = "UPDATE tbl_user 
											SET Password = ?
											WHERE Username = ?";
									
									$stmt = $connect->stmt_init();
									if($stmt->prepare($sql_query)) {	
										// Bind your variables to replace the ?s
										$stmt->bind_param('ss', 
													$new_password, 
													$username);
										// Execute query
										$stmt->execute();
										// store result 
										$update_result = $stmt->store_result();
										$stmt->close();
									}
								}else{
									$error['confirm_password'] = " <span class='label label-danger'>New password don't match!</span>";
								}
							}else{
								$error['confirm_password'] = " <span class='label label-danger'>Please insert your new password and re new password!</span>";
							}
						}else{
							$error['old_password'] = " <span class='label label-danger'>Your old password is wrong!</span>";
						}
					}else{
						$error['old_password'] = " <span class='label label-danger'>Please insert your old password!</span>";
					}
				}
				
				if(empty($email)){
					$error['email'] = " <span class='label label-danger'>Please insert your email!</span>";
				}else{
					$valid_mail = "/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i";
					if (!preg_match($valid_mail, $email)){
						$error['email'] = " <span class='label label-danger'>your email format false!</span>";
						$email = "";
					}else{
						// update password in user table
						$sql_query = "UPDATE tbl_user 
								SET Email = ?
								WHERE Username = ?";
						
						$stmt = $connect->stmt_init();
						if($stmt->prepare($sql_query)) {	
							// Bind your variables to replace the ?s
							$stmt->bind_param('ss', 
										$email, 
										$username);
							// Execute query
							$stmt->execute();
							// store result 
							$update_result = $stmt->store_result();
							$stmt->close();
						}
					}
				}
				
				if ($update_result) {
					$error['update_user'] = "<div class='card-panel teal lighten-2'>
											    <span class='white-text text-darken-2'>
												    User Data Successfully Changed
											    </span>
											</div>";
				} else {
					$error['update_user'] = "<div class='card-panel red darken-1'>
											    <span class='white-text text-darken-2'>
												    Added Failed
											    </span>
											</div>";
				}
			}		

			$sql_query = "SELECT Email FROM tbl_user WHERE Username = ?";
			
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $username);			
				// Execute query
				$stmt->execute();
				// store result 
				$stmt->store_result();
				$stmt->bind_result($previous_email);
				$stmt->fetch();
				$stmt->close();
			}		
	?>

	<!-- START CONTENT -->
    <div class="main-panel">
        <div class="main-content">
          <div class="content-wrapper">
            <div class="container-fluid"><div class="row">
    <div class="col-sm-12">
        <h2 class="content-header">Admin Panel Setting</h2>
    </div>
</div>
        <!--breadcrumbs end-->	

        <!--start container-->
        <section class="input-validation">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Change Admin Details</h4>
                </div>
                <div class="card-body">
                    <div class="card-block">
		                 		<form method="post" class="col s12">
		                  			<div class="row">
		                    			<div class="input-field col s12">   
		                    				<?php echo isset($error['update_user']) ? $error['update_user'] : '';?>

											<div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <h5>Username<span class="required">*</span></h5>
                                        <div class="controls">
						                        <input class="form-control" type="text" name="username" id="username" value="<?php echo $username; ?>" disabled/>
						                        <label for="username">Username</label>
						                      </div>
						                    </div> 

											<div class="form-group">
														<h5>Email <span class="required">*</span></h5>
                                        <div class="controls">
												<input class="form-control" type="text" name="email" id="email" value="<?php echo $previous_email; ?>" />
						                        <label for="email"></label><?php echo isset($error['email']) ? $error['email'] : '';?>
						                      </div>
						                    </div>
										
											<div class="form-group">
														<h5>Old Password <span class="required">*</span></h5>
                                        <div class="controls">
						                        <input class="form-control" type="password" name="old_password" id="old_password" value="" />
						                        <label for="old_password"></label><?php echo isset($error['old_password']) ? $error['old_password'] : '';?>
						                      </div>
						                    </div>

											<div class="form-group">
														<h5>New Passowrd <span class="required">*</span></h5>
                                        <div class="controls">
						                        <input class="form-control" type="password" name="new_password" id="new_password" value="" />
						                        <label for="new_password"></label><?php echo isset($error['new_password']) ? $error['new_password'] : '';?>
						                      </div>
						                    </div>  

											<div class="form-group">
														<h5>ReType New Password <span class="required">*</span></h5>
                                        <div class="controls">
						                        <input class="form-control" type="password" name="confirm_password" id="confirm_password" value="" />
						                        <label for="confirm_password"></label><?php echo isset($error['confirm_password']) ? $error['confirm_password'] : '';?>
						                      </div>
						                    </div>	


											<button class="btn mr-1 btn-primary" type="submit" name="btnChange" enabled>Update
						                        <i class="mdi-content-send left"></i>
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
