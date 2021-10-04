	<?php
        
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
				
		$sql_query = "SELECT news_heading, cat_id, news_status, news_date, recipe_person, recipe_calorie, news_image, news_description FROM tbl_new_recipes  WHERE nid = ?";
		 
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
			
			$total_records = $stmt->num_rows;
			echo "<script>alert('total_records $total_records');</script>";
			
			$fetch_data = $data['news_heading'];
			echo "<script>alert('fetch_data $fetch_data');</script>";
		}
		
		if($total_records > 0){
		   // insert data to main group table
    		$sql_query = "INSERT INTO tbl_recipes (news_heading, cat_id, news_status, news_date, recipe_person, recipe_calorie, news_image, news_description) 
    			VALUE (?, ?, ?, ?, ?, ?, ?, ?)";
    			
			$stmt_insert = $connect->stmt_init();
    		if($stmt_insert->prepare($sql_query)) {
    			$stmt_insert->bind_param('ssssssss', $data['news_heading'],
        			$data['cat_id'], 
    				$data['news_status'], 
    				$data['news_date'], 
    				$data['recipe_person'], 
    				$data['recipe_calorie'],
    				$data['news_image'], 
    				$data['news_description']
    			);
    			$stmt_insert->execute();
    			$insert_result = $stmt_insert->store_result();
    			$stmt_insert->close();
    		}
    		
    		if($insert_result) {
    		     // delete data from menu table
        	 	$sql_query = "DELETE FROM tbl_new_recipes 
        				WHERE nid = ?";
        		
        		$stmt = $connect->stmt_init();
        		if($stmt->prepare($sql_query)) {
        			$stmt->bind_param('s', $ID);
        			$stmt->execute();
        			$delete_result = $stmt->store_result();
        			$stmt->close();
        		}
        		
        		if($delete_result){
        		    header("location: new_recipes.php");
        		}
			}
		}

	?>