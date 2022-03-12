<?php
// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("nishant.mehra@imarkinfotech.com","My subject",$msg);

include('wp-config.php');
$response = array();
global $wpdb;

$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
}

/** paypal email **/
$sandbox = get_field("sandbox_mode","option");
if($sandbox[0] == 'Yes'){
    $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    $pay_email = get_field("sandbox_email","option");
}else{
    $url = 'https://www.paypal.com/cgi-bin/webscr';
    $pay_email = get_field("live_paypal_email","option");
}

//    $url = "https://www.paypal.com/cgi-bin/webscr";  
$curl_result = $curl_err = ''; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$curl_result = @curl_exec($ch);
$curl_err = curl_error($ch);

curl_close($ch);

$custom = explode(',',$_REQUEST['custom']);
$plan_type = $custom[0];
$meal_dur = $custom[1];
$id = $custom[2];

// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("nishant.mehra@imarkinfotech.com","second My subject",$msg);

$savedData = $wpdb->get_results("SELECT * FROM save_data WHERE ID = $id");
$quiz_data = base64_decode($savedData[0]->meal_data);
$unse_quiz_data = unserialize($quiz_data);

$user_id = $unse_quiz_data['user_id'];

$transectionDateTime = date('Y-m-d H:i:s');
$paymentStatus = $_POST['payment_status'];
$payer_email = $_POST['payer_email'];
$transactionID = $_POST['txn_id'];
$paidAmount = $_POST['mc_gross'];
$payment_mode = "paypal";

// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("nishant.mehra@imarkinfotech.com","third My subject",$msg);
//$custom = explode(',',$_REQUEST['custom']);
//$booking_id = (int)$custom[0];
//$no_of_lesson = (int)$custom[1];

// Customer details 
$name = get_user_meta($user_id,'first_name',true);
$email = $payer_email; 

//Check if plan already opt
$previousPlan = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $user_id");
if(!empty($previousPlan)){
    $planStartDate = $previousPlan[0]->date_time;
    $planDuration = $previousPlan[0]->plan_duration;

    $planEndDate =  date('Y-m-d',strtotime($planStartDate.' + '.$planDuration.' week'));
    if(strtotime($planEndDate) > strtotime($currentDate)){

    }else{
        $wpdb->query("UPDATE plan_payments SET plan_type = $plan_type, transaction_id = $transactionID, date_time = $transectionDateTime, amount = $paidAmount, payment_status = $paymentStatus, meal_data = $quiz_data, plan_duration = $meal_dur WHERE user_id = $user_id");
    }
}else{
    // Insert transaction data into the database 

    $result = $wpdb->insert('plan_payments',array(
        'user_id' => $user_id,
        'plan_type' => $plan_type,
        'transaction_id' => $transactionID,
        'date_time' => $transectionDateTime,
        'amount' => $paidAmount,
        'payment_method' => 'paypal',
        'payment_status' => $paymentStatus,
        'meal_data' => $quiz_data,
        'plan_duration' => $meal_dur,
    ));

    update_field( 'field_60dda422d8e54', 'yes', 'user_'.$user_id);
}

//                    send_invoice_email($user_id,$transectionDateTime,$plan_type,$paidAmount); 
/**** send email to client and admin ****/
    $emaildata[] = $user_id;
    $emaildata[] = $paidAmount;
    $emaildata[] = $plan_type;
    $emaildata[] = $transectionDateTime;

    send_email($emaildata);
?>