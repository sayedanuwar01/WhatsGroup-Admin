	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store data from database
		$data = array();
		
		// get all data from menu table and category table
		$sql_query = "SELECT nid, news_heading, news_date, news_status, category_name, news_image, news_description 
				FROM tbl_recipes m, tbl_category c
				WHERE m.nid = ? AND m.cat_id = c.cid";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['nid'], 
					$data['news_heading'], 
					$data['news_date'], 
					$data['news_status'], 
					$data['category_name'],
					$data['news_image'],
					$data['news_description']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>

	<!-- START CONTENT -->
    <section id="content">

        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          	<div class="container">
            	<div class="row">
              		<div class="col s12 m12 l12">
               			<h5 class="breadcrumbs-title">Recipes Detail</h5>
		                <ol class="breadcrumb">
		                  <li><a href="dashboard.php">Dashboard</a>
		                  </li>
		                  <li><a href="#" class="active">Recipes Detail</a>
		                  </li>
		                </ol>
              		</div>
            	</div>
          	</div>
        </div>
        <!--breadcrumbs end-->


        <!--start container-->
        <div class="container">
          	<div class="section">
				<div class="row">
		            <div class="col s12 m12 l12">
		              	<div class="card-panel">
		              		<?php echo isset($error['update_data']) ? $error['update_data'] : '';?>
		                	<div class="row">
		                  		<div class="row">
		                    		<div class="input-field col s12">
		                    			<form method="post" class="col s12">

											<table class="bordered">
												<tr>
													<th width="20%">Recipes Name</th>
													<td><?php echo $data['news_heading']; ?></td>
												</tr>
													<tr>
													<th>Cooking Time</th>
													<td><?php echo $data['news_date']; ?></td>
												</tr>
												<tr>
													<th>Category</th>
													<td><?php echo $data['category_name']; ?></td>
												</tr>
												<tr>
													<th>Image</th>
													<td><img src="upload/<?php echo $data['news_image']; ?>" width="200" height="150"/></td>
												</tr>
												<tr>
													<th>Description</th>
													<td><?php echo $data['news_description']; ?></td>
												</tr>
											</table>
		
										</form>												          
		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
						<a href="edit-menu.php?id=<?php echo $ID; ?>"><button class="btn waves-effect waves-light indigo">Edit</button></a>
						<a href="delete-menu.php?id=<?php echo $ID; ?>"><button class="btn waves-effect waves-light indigo">Delete</button></a>
		            </div>
		        </div>
        	</div>
        </div>