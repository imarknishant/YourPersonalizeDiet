<?php
/* Template Name: dash progress */
get_header('dashboard');
global $wpdb;

$Days = array();
$Dates = array();
$weight_arr = array();
$goal_arr = array();

$userid = get_current_user_id();
$meal_data = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $userid");
$meals = unserialize($meal_data[0]->meal_data);

if($meals['imperial_weight'] != ''){
   $curentWeight =  $meals['imperial_weight'];
    $goalWeight = $meals['imperial_target_weight'];
}else{
    $curentWeight =  $meals['metric_weight'];
    $goalWeight = $meals['metric_target_weight'];
}

$gender = get_field('gender','user_'.get_current_user_id());

if($gender == "male"){
    $img = get_template_directory_uri().'/assets/images/male.jpg';
}else{
    $img = get_template_directory_uri().'/assets/images/my-progress.jpg';
}
?>
<style>
.highcharts-figure, .highcharts-data-table table {
  min-width: 360px; 
  max-width: 800px;
  margin: 1em auto;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
  padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}
</style>
  <main id="dash-main">
    <div class="get-fit-mainbox">
        <div class="heading">
            <h4>My Progress</h4>
        </div>

        <div class="weight-time">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-6">
                  <div class="left">
                    <div class="head">
                        <h6>Weight <?php echo $curentWeight; ?> kg</h6>
                        <div class="right">
                        <button type="button" class="btn goal-btn">Goal <?php echo $goalWeight; ?> kg <i class="fa fa-bullseye" aria-hidden="true"></i></button>
                        <strong>lbs/ <cite> kg</cite></strong>
                      </div>
                    </div>

                    <div class="main">
                      <figure>
                        <img src="<?php echo $img; ?>">
                      </figure>

                    <form id="progress_form">
                      <div class="form-group">
                        <label>Enter Today's Weight</label>
                        <input type="number" class="form-control" placeholder="49 kg" name="current_weight" step=".1" required>
                      </div>
                      <input type="submit" class="btn" value="Save">
                      <input type="hidden" name="action" value="save_my_progress">
                      <input type="hidden" name="user_id" value="<?php echo $userid; ?>">
                    </form>


                    <div class="all-history">
                      <div class="heading">
                        <h4>All History</h4>
                      </div>

                      <table>
                        <tr>
                          <th>Date</th>
                          <th>Weight</th>
                          <th>Action</th>
                        </tr>
                        <?php 
                        /*** Get Progress History ***/
                        $i=0;
                        $progressData = $wpdb->get_results("SELECT * FROM my_progress WHERE user_id = $userid");
                        foreach($progressData as $p_data){
                            $date = date('d F Y',strtotime($p_data->date));
                        ?>
                        <tr>
                          <td><?php echo $date; ?></td>
                          <td><?php echo $p_data->weight; ?> kg</td>
                          <td>
                              <a href="javascript:void(0);"  data-toggle="modal" data-target="#myModal_<?php echo $i; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                          </a>
                            </td>
                        </tr>
                          
                          
                    <!-- Modal -->
                    <div id="myModal_<?php echo $i; ?>" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <form id="edit_weight_<?php echo $i; ?>" method="post">
                              <div class="form-group">
                                <label>Enter Today's Weight</label>
                                <input type="number" class="form-control" placeholder="49 kg" name="updated_weight" value="<?php echo $p_data->weight; ?>" required>
                              </div>
                              <input type="submit" class="btn" value="Save" onclick="update_weight(<?php echo $i; ?>);">
                              <input type="hidden" name="action" value="update_my_progress">
                              <input type="hidden" name="user_id" value="<?php echo $userid; ?>">
                              <input type="hidden" name="row_id" value="<?php echo $p_data->ID; ?>">
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                        <?php 
                            $weight_arr[] = (int)$p_data->weight;
                            
                            $goal_arr[] = (int)$goalWeight;
                            
                            $i++;
                        }
                          
                          array_unshift($weight_arr, (int)$curentWeight);
                          array_unshift($goal_arr, (int)$goalWeight);
                          
                          ?>

                      </table>
                    </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-6">
                  <div class="fig-progress-box">
                    <div class="heading">
                      <h4>Your Weight (In kg) Over Time</h4>
                    </div>
<!--

                    <figure>
                      <img src="<?php //echo get_template_directory_uri(); ?>/assets/images/piee.png">
                    </figure>
-->
                      <figure class="highcharts-figure">
                      <div id="container"></div>
                    </figure>
<!--                      <div id="speedChart"></div>-->
<!--                      <canvas id="speedChart" width="600" height="400"></canvas>-->
                  </div>
                </div>
            </div>
        </div>
      
    </div>
  </main>
<?php 
get_footer('dashboard');
?>

<script>
var labels = ['0', '10', '20', '30', '40', '50', '60', '70'];
    

Highcharts.chart('container', {

    title: {
        text: ''
    },
    yAxis: {
        title: {
            text: 'Weight in KG'
        }
    },
    xAxis: {

        labels: {
            enabled: false
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Weight',
        data: <?php echo json_encode($weight_arr); ?>,
        color: '#0190cf'
    }, {
        name: 'Goal',
        data: <?php echo json_encode($goal_arr); ?>,
        color: '#0cd624'
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});

</script>
