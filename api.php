<?php
 
 	include_once ('includes/config.php');
 
 	$connect->set_charset('utf8'); 
	
	if(isset($_GET['cat_id']))
	{		
			$query = "SELECT * FROM tbl_category c, tbl_recipes n WHERE c.cid = n.cat_id and c.cid = '".$_GET['cat_id']."' ORDER BY n.nid DESC";			
			$resouter = mysqli_query($connect, $query);
			
	}
	else if(isset($_GET['nid']))
	{		
			$id = $_GET['nid'];

			$query = "SELECT * FROM tbl_category c,tbl_recipes n WHERE c.cid = n.cat_id && n.nid = '$id'";					
			$resouter = mysqli_query($connect, $query);
			
	}	
	else if(isset($_GET['groups_page']))
	{
		// check page parameter
		$page = $_GET['groups_page'];
						
		// number of data that will be display per page		
		$offset = 20;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page){
			$from = ($page * $offset) - $offset;
		}else{
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if($from == 0){
	    	$query = "SELECT * FROM tbl_category c,tbl_recipes n WHERE c.cid=n.cat_id ORDER BY n.nid DESC LIMIT $offset";	
		}else{
	    	$query = "SELECT * FROM tbl_category c,tbl_recipes n WHERE c.cid=n.cat_id ORDER BY n.nid DESC LIMIT $from, $offset";	
		}	
		
		$resouter = mysqli_query($connect, $query);
	}
	else
	{	
			$query = "SELECT * FROM tbl_category ORDER BY cid DESC";			
			$resouter = mysqli_query($connect, $query);
	}
     
    $set = array();
     
    $total_records = mysqli_num_rows($resouter);
    if($total_records >= 1){
     
      while ($link = mysqli_fetch_array($resouter, MYSQLI_ASSOC)){
       
        $set ['Json'][] = $link;
      }
    }
    
	header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set)); 	 
	 
?>