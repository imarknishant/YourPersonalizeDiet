<?php
/*
Template Name: Success
*/
get_header();
global $wpdb;

// Include Stripe PHP library  
require_once dirname(__DIR__).'/Stripe/init.php';  

$emaildata = array();
$payment_id = $statusMsg = ''; 
$ordStatus = 'error'; 
 
$id = $_GET['mealplan_id'];
$savedData = $wpdb->get_results("SELECT * FROM save_data WHERE ID = $id");
$quiz_data = base64_decode($savedData[0]->meal_data);
$unse_quiz_data = unserialize($quiz_data);

$user_id = $unse_quiz_data['user_id'];
 
$meal_dur = $_GET['plan_duration'];
$plan_type = $_GET['plan_type'];

// Check whether stripe checkout session is not empty 
if(!empty($_GET['session_id'])){ 
        $session_id = $_GET['session_id']; 
         
    /*** Check if test mode or Live mode ***/
$keysType = get_field("stripe_test_keys","option");
if( $keysType[0] == 'yes'){
  $k = get_field("testing_secret_key","option");
}else{
  $k = get_field("live_secret_key","option");
}
        // Set API key 
//        \Stripe\Stripe::setApiKey('sk_test_51JAa6SKQXlQ0vsKHaPmOW2VcCSXQU1KGYRKlEqNEJ2Y0x5pQTdupa2aYwLoXDP2Zm3GB6jLQXp2ATz7aRZVuh44x00EVSfJuCw'); 
        \Stripe\Stripe::setApiKey($k); 
         
        // Fetch the Checkout Session to display the JSON result on the success page 
        try { 
            $checkout_session = \Stripe\Checkout\Session::retrieve($session_id); 
        }catch(Exception $e) {  
            $api_error = $e->getMessage();  
        } 
         
        if(empty($api_error) && $checkout_session){ 
            // Retrieve the details of a PaymentIntent 
            try { 
                $intent = \Stripe\PaymentIntent::retrieve($checkout_session->payment_intent); 
            } catch (\Stripe\Exception\ApiErrorException $e) { 
                $api_error = $e->getMessage(); 
            } 
             
            // Retrieves the details of customer 
            try { 
                // Create the PaymentIntent 
                $customer = \Stripe\Customer::retrieve($checkout_session->customer); 
            } catch (\Stripe\Exception\ApiErrorException $e) { 
                $api_error = $e->getMessage(); 
            } 
             
            if(empty($api_error) && $intent){  
                // Check whether the charge is successful 
                if($intent->status == 'succeeded'){ 
                    // Customer details 
                    $name = $customer->name; 
                    $email = $customer->email; 
                     
                    // Transaction details  
                    $transactionID = $intent->id; 
                    $paidAmount = $intent->amount; 
                    $paidAmount = ($paidAmount/100); 
                    $paidCurrency = $intent->currency; 
                    $paymentStatus = $intent->status;
                    $transectionDateTime = date('Y-m-d H:i:s');
                    
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
                            'payment_method' => 'stripe',
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
                        $emaildata[] = $_GET['plan_type'];
                        $emaildata[] = $transectionDateTime;
                    
                    
                    // If the order is successful 
                    if($paymentStatus == 'succeeded'){ 
                        $statusMsg = 'Your Payment has been Successful!'; 
                        send_email($emaildata);
                    }else{ 
                        $statusMsg = "Your Payment has failed!"; 
                    } 
                }else{ 
                    $statusMsg = "Transaction has been failed!"; 
                } 
            }else{ 
                $statusMsg = "Unable to fetch the transaction details! $api_error";  
            } 
             
            $ordStatus = 'success'; 
        }else{ 
            $statusMsg = "Transaction has been failed! $api_error";  
        } 
     
    
}else{ 
    $statusMsg = "Invalid Request!"; 
} 
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
    
<style>
    .status {
        height: 65vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }    
    .thnk {
        padding: 50px 70px;
        background: rgba(255,255,255,0.5);
        color: #000;
        font-size: 35px;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 0px 0px 30px -3px rgba(0,0,0,0.15);
    }
    .status.example{
	height: calc(100% - 141px - 257px);
        flex-direction: column;
}
h1.thnk {
    background: #fff;
    font-weight: 600;
    width: 100%;
    margin: auto;
    max-width: 1200px;
    text-align: center;
    margin: 50px 0;
    }
@media csreen and (max-width:1499px){
	h1.thnk {
    max-width: 900px;
    padding: 30px;
}
.status.example{
	height: calc(100% - 120px - 171px);
    flex-direction: column;
}
}
</style>
</head>
<body style="height: 100vh; overflow: hidden">
    <div class="status">
        <h1 class="thnk">Thank you for your payment. It has been processed successfully.</h1>
        <a href="<?php echo get_the_permalink(8); ?>" class="btn">Go to home</a>
    </div>
</body>
</html>
<?php
get_footer();
?>