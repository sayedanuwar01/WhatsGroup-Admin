<?php
	include_once('functions.php'); 
?>

	<?php 
    	
    	$function = new functions;
		$data = array();
		
		if(isset($_GET['keyword'])){	
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		}else{
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if(empty($keyword)){
			$sql_query = "SELECT nid, news_heading, news_status, news_date, news_image, news_description, cid, category_name 
			    FROM tbl_new_recipes m, tbl_category c 
			    WHERE m.cat_id = c.cid 
			    ORDER BY m.nid DESC";
		}else{
			$sql_query = "SELECT nid, news_heading, news_status, news_date, news_image, news_description, cid, category_name 
				FROM tbl_new_recipes m, tbl_category c
				WHERE m.cat_id = c.cid AND news_heading LIKE ?
				ORDER BY m.nid DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {
			if(!empty($keyword)){
				$stmt->bind_param('s', $bind_keyword);
			}
			
			$stmt->execute();
			$stmt->store_result();
		    $stmt->bind_result($data['nid'], 
				$data['news_heading'], 
				$data['news_status'], 
				$data['news_date'],
				$data['news_image'], 
				$data['news_description'],  
				$data['cid'], 
				$data['category_name']
			);
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
						
		$offset = 10;
						
		if ($page){
			$from = ($page * $offset) - $offset;
		}else{
			$from = 0;	
		}	
		
		if(empty($keyword)){
			$sql_query = "SELECT nid, news_heading, news_status, news_date, news_image, news_description, cid, category_name 
					FROM tbl_new_recipes m, tbl_category c
					WHERE m.cat_id = c.cid 
					ORDER BY m.nid DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT nid, news_heading, news_status, news_date, news_image, news_description, cid, category_name 
					FROM tbl_new_recipes m, tbl_category c
					WHERE m.cat_id = c.cid AND news_heading LIKE ?
					ORDER BY m.nid DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if($stmt_paging ->prepare($sql_query)) {
		    if(empty($keyword)){
				$stmt_paging ->bind_param('ss', $from, $offset);
			}else{
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			$stmt_paging ->store_result();
			$stmt_paging->bind_result($data['nid'], 
				$data['news_heading'], 
				$data['news_status'], 
				$data['news_date'],
				$data['news_image'],
				$data['news_description'],  
				$data['cid'], 
				$data['category_name']
			);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

       	// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>

	<!-- START CONTENT -->
     <div class="main-panel">
        <div class="main-content">
          <div class="content-wrapper">
            <div class="container-fluid"><!--Extended Table starts-->
<div class="row">
    <div class="col-12">
        <h2 class="content-header">New Whatsapp Group Request</h2>
       
    </div>
</div>
<section id="extended">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap bar-success">
	                    <h4 class="card-title">Group List</h4>
	                </div>
	                <div class="card-body">
	                    <div class="card-block">
		                    <h5>No Groups Found!</h5>
		                </div>
				    </div>
		    	</div>	
			</div>
	    </div>	
	</div>
</section>
	<br><br><br><br><br><br><br><br><br><br>


	<?php 
		// otherwise, show data
		}else{
			$row_number = $from + 1;
	?>

	<!-- START CONTENT -->
    <div class="main-panel">
        <div class="main-content">
          <div class="content-wrapper">
            <div class="container-fluid"><!--Extended Table starts-->
<div class="row">
    <div class="col-12">
        <h2 class="content-header">All Whatsapp Group List</h2>
        <!--<p class="content-sub-header">Tables with some actions and with more feathers.</p>-->
    </div>
</div>
<section id="extended">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                        <div class="card-title-wrap bar-success">
                    <h4 class="card-title">New Group List</h4>
                        </div>
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <table class="table table-responsive-md text-center">
                            <thead>
                                <tr>
									<th>Group Name</th>
									<th>Group Link</th>
									<th>Category</th>
									<th>Verify</th>
									<th>Action</th>
								</tr>
							</thead>
										<?php 
											while ($stmt_paging->fetch()){
										?>
											<tbody>
												<tr>
												    <td><?php echo$data['news_heading']; ?></td>
															<td width="40%" class="text-truncate"><a href="<?php echo $data['news_date'];?>"><?php echo $data['news_date'];?></a></td>
															<td><button type="button" class="btn btn-sm btn-outline-danger round"><?php echo $data['category_name'];?></button></td>
															<td><a href="add_group.php?id=<?php echo $data['nid'];?>"  onclick="return confirm('Are you sure want to verify this Recipe?')"><button class="btn mr-1 btn-primary" type="submit" name="">Verify<i class="mdi-content-send right"></i></button></a></td>
															<td><a href="delete-new-menu.php?id=<?php echo $data['nid'];?>" onclick="return confirm('Are you sure want to delete this Recipe?')" class="danger p-0" data-original-title="" title=""><i class="fa fa-trash-o font-medium-3 mr-2"></i></a>
															</td>
													</tr>
												</tbody>
													<?php 
													} 
												}
											?>
										</table>

										<h4><?php $function->doPages($offset, 'new_recipes.php', '', $total_records, $keyword); ?></h4>

		                    		</div>
		                  		</div>
		                	</div>
		              	</div>
		            </div>
		        </div>
        	</div>
        </div>

	</section>
					
				