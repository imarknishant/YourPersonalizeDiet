<?php
/*
Template Name: Dashboard
*/
get_header('dashboard');

global $wpdb;
$currentUser = get_current_user_id();
$userData = get_userdata($currentUser);

if($userData->roles[0] == 'administrator'){
    ?>
<style>
/*
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
*/
</style>

<main id="dash-main">
<div class="get-fit-mainbox dashboard">
    <input type="hidden" id="ajax-url" value="<?php echo admin_url('admin-ajax.php'); ?>">
    <div id = "tabs-1">
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
                $plandetails = $plan->plan_type.' - '.'$'.$plan->amount;
                $planDuration = $plan->plan_duration;
                $startdate = date('Y-m-d',strtotime($plan->date_time));
                $startend = date('Y-m-d',strtotime("+4 week",strtotime($plan->date_time)));
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $name; ?></td>
                <td><?php echo $userdata->data->user_email; ?></td>
                <td><?php echo $plandetails; ?></td>
                <td><?php echo $startdate; ?></td>
                <td><?php echo $startend; ?></td>
                <td><a href="javascript:void(0);" onclick="delete_plan('<?php echo $plan->ID; ?>'); ">Delete Plan</a></td>
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
                <th>Plan details</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
    </div>
</div>
</div>
</div>
</main>

<?php
}else{
    $trns_data = $wpdb->get_results('SELECT * FROM plan_payments WHERE user_id='.$currentUser.' ORDER BY ID DESC');

    $plan_duration = (int)$trns_data[0]->plan_duration;
    $startend = date('M d, Y',strtotime("+".$plan_duration." week",strtotime($trns_data[0]->date_time)));
?>
    <style>
        .dashboard .heading h4 {
            color: #000 !important;
            font-weight: 600 !important;
            margin-bottom: 30px !important;
        }

        .dashboard .ssingle-radio {
            box-shadow: 0px 0px 10px #ccc;
            padding: 20px 35px;
            border-radius: 20px;
            background-color: #efeded;
            background-image: url(../images/plan-bg.png);
            background-position: right;
            background-size: inherit;
            background-repeat: no-repeat;
            width: 500px;
            height: 210px;
            position: relative;
            background: linear-gradient(to right, #4e6ec9 0%, #0191d0 51%, #244298 100%);
            margin-bottom: 40px;
        }

        .dashboard .ssingle-radio h3,
        h4,
        h5,
        h6 {
            color: #fff !important;
            font-weight: 400 !important;
            margin-bottom: 10px !important;
        }

        .dashboard .ssingle-radio h5 {
            font-size: 17px;
        }

        .dashboard .ssingle-radio h6 {
            font-size: 15px;
        }

        .dashboard .dash-profile {
            margin-bottom: 60px;
        }

        .dashboard .dashboard-recent-payment {
            border-top: 1px solid #dcdcdc;
            padding-top: 30px;
        }

        .dashboard .dashboard-table table {
            width: 100%;
            border: 1px solid #dcdcdc;
        }

        .dashboard .dashboard-table table tr th {
            text-align: inherit;
            padding: 20px 20px;
            color: #fff;
        }

        .dashboard .dashboard-table table tr td {
            padding: 20px 20px;
            color: #000;
        }

        .dashboard .dashboard-table table tr {
            border-bottom: 1px solid #dcdcdc;
        }

        .dashboard .dashboard-table table tr:first-child {
            background: linear-gradient(to right, #244298 0%, #0191d0 51%, #244298 100%);
        }

        .dashboard .dashboard-table table tr td .flex {
            display: flex;
            align-items: center;
        }

        .dashboard .dashboard-table table tr td .flex figure {
            width: 60px;
            height: 60px;
            margin-right: 20px;
        }

        .dashboard .dashboard-table table tr td .flex figure img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 100%;
        }

        .dashboard .dashboard-table table tr td .flex p {
            color: #000 !important;
            margin-bottom: 0;
            font-size: 16px;
        }

        .dashboard .dashboard-table table tr td span.completed {
            background: #0ca348;
        }

        .dashboard .dashboard-table table tr td span {
            padding: 6px 10px;
            color: #fff;
            border-radius: 4px;
            width: 110px;
            display: flex;
            justify-content: center;
        }
        .dashboard .dashboard-table table tr td span.pending{
            background: #db901f;
        }
    </style>
      <main id="dash-main">
        <div class="get-fit-mainbox dashboard">
            <div class="heading">
                <h4>Dashboard</h4>
            </div>

            <div class="dash-profile">

                <div class="ssingle-radio">
                    <h3><?php echo $trns_data[0]->plan_type; ?></h3>
                    <h4>$<?php echo $trns_data[0]->amount; ?></h4>
                    <h6>Due date: <?php echo $startend; ?></h6>
                </div>

                <div class="save-cancel-btns">
                    <a href="<?php echo get_the_permalink(14); ?>?upgrade=true&rid=<?php echo $trns_data[0]->ID;?>" class="btn">Upgrade Plan</a>
<!--                    <button type="submit" class="btn">Upgrade Plan</button>-->
                </div>



            </div>
            <div class="dashboard-recent-payment">
                <div class="heading">
                    <h4>Recent Payment</h4>
                </div>
                <div class="dashboard-table">
                    <table>
                        <tr>
                            <th>S.no</th>
                            <th>Meal Plan</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Staus</th>
                        </tr>

                        <?php 
                        $i=1;
                        foreach($trns_data as $data){
                            $date = date('M d, Y', strtotime($data->date_time));
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data->plan_type; ?></td>
                            <td><?php echo $date; ?></td>
                            <td>$<?php echo $data->amount; ?></td>
                            <td>
                                <span class="completed">Completed</span>
                            </td>
                        </tr>
                        <?php
                        $i++;
                        }?>
                        
                    </table>
                </div>
            </div>
        </div>


    </main>

<?php 
}
get_footer('dashboard');
?>