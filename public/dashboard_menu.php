<?php
	include_once('functions.php'); 
?>

<?php

	$sql_category = "SELECT COUNT(*) as num FROM tbl_category";
	$total_category = mysqli_query($connect, $sql_category);
	$total_category = mysqli_fetch_array($total_category);
	$total_category = $total_category['num'];

	$sql_news = "SELECT COUNT(*) as num FROM tbl_recipes";
	$total_news = mysqli_query($connect, $sql_news);
	$total_news = mysqli_fetch_array($total_news);
	$total_news = $total_news['num'];

?>

        <!--start container-->
        <div class="main-panel">
        <div class="main-content">
          <div class="content-wrapper">
            <div class="container-fluid"><!--Statistics cards Starts-->
<div class="row">
    <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="card bg-white">
            <div class="card-body">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-medium-5 card-title mb-0"><?php echo $total_category;?></h4>
                                        <span class="grey darken-1">Total Category</span>
                        </div>
                        <div class="media-right text-right">
                            <i class="icon-cup font-large-1 primary"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart" class="height-150 lineChartWidget WidgetlineChart mb-2">
                </div>
            </div>
        </div>
    </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="card bg-white">
            <div class="card-body">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-medium-5 card-title mb-0"><?php echo $total_news;?></h4>
                                        <span class="grey darken-1">Total Groups</span>
                        </div>
                        <div class="media-right text-right">
                            <i class="icon-wallet font-large-1 warning"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart1" class="height-150 lineChartWidget WidgetlineChart1 mb-2">
                </div>

            </div>
        </div>
    </div>

                            <div class="col-xl-4 col-lg-6 col-md-6 col-12">
        <div class="card bg-white">
            <div class="card-body">
                <div class="card-block pt-2 pb-0">
                    <div class="media">
                        <div class="media-body white text-left">
                            <h4 class="font-medium-5 card-title mb-0">0</h4>
                            <span class="grey darken-1">Notification</span>
                        </div>
                        <div class="media-right text-right">
                            <i class="icon-basket-loaded font-large-1 success"></i>
                        </div>
                    </div>
                </div>
                <div id="Widget-line-chart2" class="height-150 lineChartWidget WidgetlineChart2 mb-2">
                </div>
            </div>
        </div>
    </div>
</div>
            <!--card stats end-->
    </div>
</div>