<?php
	class functions {	
	
		function get_random_string($valid_chars, $length) {
    
			// start with an empty random string
			$random_string = "";

			// count the number of chars in the valid chars string so we know how many choices we have
			$num_valid_chars = strlen($valid_chars);

			// repeat the steps until we've created a string of the right length
			for ($i = 0; $i < $length; $i++)
			{
				// pick a random number from 1 up to the number of valid chars
				$random_pick = mt_rand(1, $num_valid_chars);

				// take the random character out of the string of valid chars
				// subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
				$random_char = $valid_chars[$random_pick-1];

				// add the randomly-chosen char onto the end of our string so far
				$random_string .= $random_char;
			}

			// return our finished random string
			return $random_string;
		}// end of get_random_string()
		
		function sanitize($string) {
			include 'includes/config.php';
			// check string value
			$string = mysqli_escape_string($connect, trim(strip_tags(stripslashes($string))));
			return $string;
		}// end of sanitize()
		
		function check_integer($which) {
			if(isset($_GET[$which])){
				if (intval($_GET[$which])>0) {
					return intval($_GET[$which]);
				} else {
					return false;
				}
			}
			return false;
		}//end of check_integer()

		function get_current_page() {
			if(($var=$this->check_integer('page'))) {
				//return value of 'page', in support to above method
				return $var;
			} else {
				//return 1, if it wasnt set before, page=1
				return 1;
			}
		}//end of method get_current_page()
		
		function doPages($page_size, $thepage, $query_string, $total=0, $keyword) {
			$index_limit = 10;
			$query='';
			
			if(strlen($query_string)>0){
				$query = "&amp;".$query_string;
			}
			$current = $this->get_current_page();
			
			$total_pages=ceil($total/$page_size);
			$start=max($current-intval($index_limit/2), 1);
			$end=$start+$index_limit-1;

			echo '<div id="page_num" class="body">';
			echo '<ul class="pagination">';

			if($current==1) {
				echo '<li class="disabled"><a><i class="mdi-navigation-chevron-left"></i></a></li>';
			} else {
				$i = $current-1;
				echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" rel="nofollow" title="go to page '.$i.'"><i class="mdi-navigation-chevron-left"></i></a></li>';
			}
			if($start > 1) {
				$i = 1;
				echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
			}

			for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
				if($i==$current) {
					echo '<li class="active"><a>'.$i.'</a></li>';
				} else {
					echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
				}
			}

			if($total_pages > $end){
				$i = $total_pages;
				echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" title="go to page '.$i.'">'.$i.'</a></li>';
			}

			if($current < $total_pages) {
				$i = $current+1;
				echo '<li><a href="'.$thepage.'?page='.$i.$query.'&keyword='.$keyword.'" rel="nofollow" title="go to page '.$i.'"><i class="mdi-navigation-chevron-right"></i></a></li>';
			} else {
				echo '<li class="disabled"><a><i class="mdi-navigation-chevron-right"></i></a></li>';
			}
			
			echo '</ul>';
  
			if ($total != 0) {
				echo '<br><div class="container"><h5>( total '.$total.' )</h5></div></div>';
			} else {
				echo '</div>';
			};
		}
		
		
	}
?>