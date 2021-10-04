<?php

    include_once ('includes/config.php');
    
	$connect->set_charset('utf8'); 
   
    $name = $_POST['name'];
    $cid = $_POST['category'];
    $link = $_POST['link'];
    $image = $_POST['image'];
    
    $sql = "SELECT * FROM tbl_category WHERE cid='$cid'";
    $result = mysqli_query($connect, $sql);
    
    if ($result->num_rows > 0) {
       $sql = "INSERT INTO tbl_new_recipes (news_heading, cat_id, news_date, news_image) VALUES ('$name','$cid','$link','$image')";
        
        $result = mysqli_query($connect, $sql);
        if($result){
            echo "Data Inserted";
        }else{
          echo "Failed";
        }
        mysqli_close($connect);
        
    }else{
        $category_name = $cid;
        $sql = "INSERT INTO tbl_category (category_name, category_image) VALUES ('$category_name', '$image')";
        
        $result = mysqli_query($connect, $sql);
        if($result){
             $cid = mysqli_insert_id($connect);
             
             $sql = "INSERT INTO tbl_new_recipes (news_heading, cat_id, news_date, news_image) VALUES ('$name','$cid','$link','$image')";
        
            $result = mysqli_query($connect, $sql);
            if($result){
                echo "Data Inserted";
            }else{
              echo "Failed";
            }
        }else{
          echo "Failed";
        }
        mysqli_close($connect);
    }

?>

















