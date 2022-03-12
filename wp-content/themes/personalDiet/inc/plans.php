<?php
add_action( 'admin_menu', 'wpse_91693_register' );

function wpse_91693_register(){
    add_menu_page(
        'Plans',     // page title
        'Plans',     // menu title
        'administrator',   // capability
        'plans',     // menu slug
        'wpse_91693_render' // callback function
    );
}

function wpse_91693_render(){
    global $wpdb;
?>
<style>
.wp-core-ui select {
    padding: 0 24px 0 8px !important;
}
.dataTable i.fa.fa-pencil {
    border: 1px solid;
    padding: 4px;
    color: #2271b1;
}
.dataTable i.fa.fa-trash {
    border: 1px solid;
    padding: 4px;
    color: #d63638;
}
a.delete:hover {
    background: white;
    color: #ff0303;
}
 a.delete {
    border: 1px solid;
    padding: 2px;
    color: white;
}
.head {
    display: flex;
    margin-top: 7px;
    font-size: 20px;
}
#tabs-1{
    font-size: 14px;
    margin-top: 55px;
    }
 .ui-widget-header {
    background:#b9cd6d;
    border: 1px solid #b9cd6d;
    color: #FFFFFF;
    font-weight: bold;
 }
    
    .my-plan .row{
        display:flex;
        align-items: flex-end;
    }
    
    .my-plan .form-group{
        margin-bottom: 0px;
    }
    
    div#wpbody-content .ui-widget-content {
    background: #efefef;
}
    
    .my-plan h4 {
    margin: 10px 0 20px;
    font-size: 28px;
    font-weight: 600;
}
    
    .plan-tabel {
    margin-top: 55px;
}
    
.my-plan table th {
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 16px;
    font-weight: 600;
}
.my-plan table td {
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 14px;
    font-weight: 500;
}
</style>
<link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">
<input type="hidden" id="ajax-url" value="<?php echo admin_url('admin-ajax.php'); ?>">
<div id="tabs-1">
        <?php
        if(isset($_GET['wid']) && $_GET['wid'] != ''){
            $Days = array();
            $Dates = array();
            $weight_arr = array();
            $goal_arr = array();

            $userid = $_GET['wid'];
            $meal_data = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $userid");
            $meals = unserialize($meal_data[0]->meal_data);

            if($meals['imperial_weight'] != ''){
               $curentWeight =  $meals['imperial_weight'];
                $goalWeight = $meals['imperial_target_weight'];
            }else{
                $curentWeight =  $meals['metric_weight'];
                $goalWeight = $meals['metric_target_weight'];
            }

            $gender = get_field('gender','user_'.$userid);

            if($gender == "male"){
                $img = get_template_directory_uri().'/assets/images/male.jpg';
            }else{
                $img = get_template_directory_uri().'/assets/images/my-progress.jpg';
            }
        ?>
        <div class="weight-time">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="left">
                    <div class="head">
                        <h4>Weight <?php echo $curentWeight; ?> kg</h4>
                        <div class="right">
                        <button type="button" class="btn goal-btn">Goal <?php echo $goalWeight; ?> kg <i class="fa fa-bullseye" aria-hidden="true"></i></button>
                        <strong>lbs/ <cite> kg</cite></strong>
                      </div>
                    </div>

                    <div class="main">

                    <div class="all-history">
                      <div class="heading">
                        <h4>All History</h4>
                      </div>

                      <table class="table">
                        <tr>
                          <th>Date</th>
                          <th>Weight</th>
                        
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
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="fig-progress-box">
                    <div class="heading">
                      <h4>Your Weight (In kg) Over Time</h4>
                    </div>

                      <figure class="highcharts-figure">
                      <div id="container"></div>
                    </figure>
                  </div>
                </div>
            </div>
        </div>

        <?php }else{ ?>
        <ul>
            <li><a href = "#tabs-2">Plans</a></li>
            <li><a href = "#tabs-3">All Payments</a></li>
         </ul>
        <div id = "tabs-2">
             <div class="my-plan">
                <h4>My Plan</h4>
                 <form id="plans">
                 <div class="row">
                 <div class="col-md-4 col-12">
                     <div class="form-group">
                     <label>Plan Name</label>
                     <input type="text" class="form-control" placeholder="Plane name" name="plane_name" required>
                     </div>
                 </div>
                <div class="col-md-4 col-12">
                     <div class="form-group">
                     <label>Plan Duration (in weeks)</label>
                     <input type="number" class="form-control" placeholder="Plane duration" name="plane_duration" required>
                     </div>
                 </div>
                 <div class="col-md-4 col-12">
                     <div class="form-group">
                     <label>Plan Price</label>
                     <input type="number" class="form-control" placeholder="Plane price" name="plane_price" required>
                     </div>
                 </div>
                 <div class="col-md-4 col-12">
                    <div class="plan-btn">
                     <input type="submit" class="btn btn-default" value="Save">
                    </div>
                 </div>
                 </div>
                     <input type="hidden" name="action" value="add_plan">
                 </form>
                
                 <div class="plan-tabel">
        <table id="myTable_plan" class="display" style="width:100%">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Plan Name</th>
                <th>Plan Duration</th>
                <th>Plan Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $all_plans = $wpdb->get_results("SELECT * FROM my_plans");
            $i = 1 ;
            foreach($all_plans as $plan){
                
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><input type="text" name="p_name" id="n_<?php echo $plan->ID; ?>" readonly value="<?php echo $plan->plane_name; ?>"></td>
                <td><input type="number" name="p_duration" id="p_<?php echo $plan->ID; ?>" readonly value="<?php echo $plan->plane_duration; ?>"></td>
                <td><input type="number" name="p_price" id="pr_<?php echo $plan->ID; ?>" readonly value="<?php echo $plan->plane_price; ?>"></td>
                <td>
                    <a href="javascript:void(0);" onclick="edit_plan('<?php echo $plan->ID; ?>'); "><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="javascript:void(0);" onclick="remove_plan('<?php echo $plan->ID; ?>'); "><i class="fa fa-trash" aria-hidden="true"></i></a>
                    <a href="javascript:void(0);" id="update_plan_btn" class="btn btn-default" onclick="update_plan('<?php echo $plan->ID; ?>');" disabled >Update</a>
                </td>
            </tr>
            <?php
                $i++;
            }
            ?>
           
           
        </tbody>
        <tfoot>
            <tr>
                <th>S.No</th>
                <th>Plan Name</th>
                <th>Plan Duration</th>
                <th>Plan Price</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
                 </div>
        
             </div>
           
         </div>
        <div id = "tabs-3">
             <div class="my-plan">
            <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Progress</th>
                <th>Plan details</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $all_plans = $wpdb->get_results("SELECT * FROM plan_payments");
            $i = 1 ;
            foreach($all_plans as $plan){
                $userdata = get_userdata($plan->user_id);
                $name = get_user_meta($plan->user_id,'first_name',true).' '.get_user_meta($plan->user_id,'last_name',true);
                $plandetails = $plan->plan_type.' - '.'£'.$plan->amount;
                $planDuration = $plan->plan_duration;
                $startdate = date('Y-m-d',strtotime($plan->date_time));
                $startend = date('Y-m-d',strtotime("+4 week",strtotime($plan->date_time)));
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $userdata->data->user_email; ?></td>
                <td><a href="?page=plans&wid=<?php echo $plan->user_id; ?>" target="_blank" class="progress">View Progress</a></td>
                <td><?php echo $plandetails; ?></td>
                <td><?php echo $startdate; ?></td>
                <td><?php echo $startend; ?></td>
                <td><a href="javascript:void(0);" class="delete" onclick="delete_plan('<?php echo $plan->ID; ?>'); ">Delete Plan</a></td>
            </tr>
            <?php
                $i++;
            }
            ?>
           
           
        </tbody>
        <tfoot>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Progress</th>
                <th>Plan details</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
                 </div>
         </div>
        <?php }?>
</div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
$(document).ready( function (){
    $('#myTable_plan').DataTable();
    $('#myTable').DataTable();
    $( "#tabs-1" ).tabs();
    
    $('#tabs li a').click(function(){
      var t = $(this).attr('id');

      if($(this).hasClass('inactive')){ //this is the start of our condition 
        $('#tabs li a').addClass('inactive');           
        $(this).removeClass('inactive');

        $('.container').hide();
        $('#'+ t + 'C').fadeIn('slow');
     }
    });
    
    jQuery("#plans").submit(function(e){
        e.preventDefault();
        var ajaxurl = jQuery("#ajax-url").val();
        var formData = jQuery("#plans").serialize();
        jQuery.ajax({
            type:"POST",
            url: ajaxurl,
            data: formData,
            dataType:'json',
            success: function(data){
                if(data.status == 1){
                    alert('Added successfully');
                    location.reload();
                }else{
                    alert('Error');
                }
            }
        });
    })
    
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
    
} );

    
/*** Delete Plan for user ***/
function delete_plan(rowid){
    if(confirm("Are you sure you want to delete this?")){
        var ajaxurl = jQuery("#ajax-url").val();
        jQuery.ajax({
            type:"POST",
            url: ajaxurl,
            data: {rowid:rowid, action:'delete_plan'},
            dataType:'json',
            success: function(data){
                if(data.status == 1){
                    alert('Removed successfully');
                }else{
                    alert('Error');
                }
            }
        });
    }
}
    
/*** Delete plan ***/
function remove_plan(rowid){
    if(confirm("Are you sure you want to delete this?")){
        var ajaxurl = jQuery("#ajax-url").val();
        jQuery.ajax({
            type:"POST",
            url: ajaxurl,
            data: {rowid:rowid, action:'remove_plan'},
            dataType:'json',
            success: function(data){
                if(data.status == 1){
                    alert('Removed successfully');
                    location.reload();
                }else{
                    alert('Error');
                }
            }
        });
    }
}
    
    
/*** Edit Plan ***/
function edit_plan(rowid){
    
    jQuery("#n_"+rowid).attr("readonly", false);
    jQuery("#p_"+rowid).attr("readonly", false);
    jQuery("#pr_"+rowid).attr("readonly", false);
    jQuery("#update_plan_btn").attr("disabled", false);
}

/*** Update plan ***/
function update_plan(rowid){
    if(confirm("Are you sure you want to update the plan?")){
        var ajaxurl = jQuery("#ajax-url").val();
        var planName = jQuery("#n_"+rowid).val();
        var planDuration = jQuery("#p_"+rowid).val();
        var planPrice = jQuery("#pr_"+rowid).val();
        
        jQuery.ajax({
            type:"POST",
            url: ajaxurl,
            data: {rowid:rowid, planName:planName, planDuration:planDuration, planPrice:planPrice, action:'update_plan'},
            dataType:'json',
            success: function(data){
                if(data.status == 1){
                    alert('Plan Updated successfully');
                    location.reload();
                }else{
                    alert('Error');
                }
            }
        });
    }
}
    
</script>
<?php
}
?>