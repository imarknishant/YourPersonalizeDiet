<?php
//namespace AmazonPay;
//
//include(dirname(__DIR__).'/template-pages/amazon-pay/AmazonPay/Client.php');

/* Template Name: Choose your plan */
get_header();
global $wpdb;

if($_GET['upgrade'] == 'true'){
   $userid = get_current_user_id();
   $rowid = (int)$_GET['rid'];
   $trns_data = $wpdb->get_results('SELECT * FROM plan_payments WHERE ID='.$rowid.' ORDER BY ID DESC');
    
   $quizData = $trns_data[0]->meal_data;
   $currentDate = date('Y-m-d');     
}else{
   $userid = $_GET['user_id'];
   $quizData = $_GET;
   $currentDate = date('Y-m-d'); 
}

/***** Amazon Pay *****/

//$config = array('merchant_id'   => 'A16DJE5TP2SAX9',
//  'access_key'   => 'AKIAIEHXG7NLOIBRWFUA',
//  'secret_key'   => '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',
//  'client_id'    => 'amzn1.application-oa2-client.364efbea1379435d9ee7d14c16fea721',
//  'region'       => 'UK',
//  'sandbox'      => true
//);

//$client = new Client($config);

// Also you can set the sandbox variable in the config() array of the Client class by

//$client->setSandbox(true);

?> 
<!--<script async='async' src='https://static-eu.payments-amazon.com/OffAmazonPayments/uk/sandbox/lpa/js/Widgets.js'></script>-->


  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-banner.jpg');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>
    <section class="how-it-works choose-your-plan">
        <div class="container">
            <div class="heading">
                <h2>Choose Your Plan</h2>
            </div>
         <div class="choose-plan-custom-radio-btns">
             <?php
             $plans = $wpdb->get_results("SELECT * FROM my_plans");
             $i=0;
             foreach($plans as $pl){
             ?>
             <div class="single-radio active-tab">
                 <div class="form-group">
                     <input type="radio" class="form-control first-plan" id="test<?php echo $i; ?>" name="radio-group" value="<?php echo $pl->plane_price; ?>" data-meal="<?php echo $pl->plane_name; ?>" data-duration="<?php echo $pl->plane_duration; ?>">
                     <label for="test<?php echo $i; ?>" class="meal-plan label-test1"><?php echo $pl->plane_name; ?> <cite>$<?php echo $pl->plane_price; ?></cite></label>
                     
                 </div>
<!--                 <span>Per Week</span>-->
             </div>
             <?php 
                 $i++;
             }
             ?>
         </div>


         <div class="choose-table">
           <table>
             <tr>
               <td class="selected-meal">Choose Plan</td>
               <td class="selected-meal-price">$0</td>
             </tr>
            <tr>
               <td class="promo-code">Promo Code</td>
               <td class="promo-code-field">
                   <form id="promo-form" method="post">
                        <input type="text" class="field" id="code" name="discount_code" required>
                        <input type="submit" value="Apply Now" id="apply_code">
                        <input type="hidden" id="selected-price" name="actual_price">
                        <input type="hidden" name="action" value="get_discount">
                   </form>
                </td>
             </tr>
             <tr>
              <td>Total</td>
              <td class="total">$0</td>
            </tr>
           </table>
         </div>


         <div class="pay-btns">
<?php
//Check if plan already active//
$previousPlan = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $userid ORDER BY ID DESC");
$planStartDate = $previousPlan[0]->date_time;
$planDuration = $previousPlan[0]->plan_duration;
                  
$planEndDate =  date('Y-m-d',strtotime($planStartDate.'+'.$planDuration.' week'));
if(strtotime($planEndDate) > strtotime($currentDate)){
?>
    <div class="plan_active">
        <p>Your current plan is active. Please <a href="<?php echo get_the_permalink(17); ?>" class="contact_support">contact support</a> to upgrade your plan.</p>
    </div>  
<?php
}else{
    ?>
    <ul class="nav nav-tabs">
        <?php
        /**** Check if stipe is enable ****/
        $enableStripe = get_field("enable__disable_stripe_payment","option");
  
        if($enableStripe[0] == 'Enable Strip'){
        ?>
        <li class="active"><a data-toggle="tab" href="#pay-with-card">Pay With Card <img src="<?php echo get_template_directory_uri(); ?>/assets/images/card.png"></a></li>
        <?php }?>
        
<!--        <li><a data-toggle="tab" href="#pay-with-amazon">Pay With Amazon <img src="<?php echo get_template_directory_uri(); ?>/assets/images/amazon.png"></a></li>-->
        
        <?php
        /**** Check if paypal is enable ****/
        $enablePaypal = get_field("enable__disable_paypal_payment","option");
  
        if($enablePaypal[0] == 'Enable'){
            
        /** paypal email **/
        $sandbox = get_field("sandbox_mode","option");
        if($sandbox[0] == 'Yes'){
            $link = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            $pay_email = get_field("sandbox_email","option");
        }else{
            $link = 'https://www.paypal.com/cgi-bin/webscr';
            $pay_email = get_field("live_paypal_email","option");
        } 
            
        ?>
        
        <li><a data-toggle="tab" href="#pay-with-paypal">Pay With Paypal <img src="<?php echo get_template_directory_uri(); ?>/assets/images/paypal.png"></a></li>
        
        <?php }?>
    </ul>    
    <?php
}
?> 
             <button class="stripe-button btn btn-info btn-lg blue-btn" id="payButton" style="padding-top: 10px;padding-bottom: 11px;  vertical-align: middle;text-transform: uppercase;margin-left: 5px;" style="display:none;">Checkout with Stripe</button>
            <div class="tab-content">
            <div id="pay-with-card" class="tab-pane fade">
          
            <div id="buynow" style="display:block;">
            <a href="javascript:void(0);" class="stripe-button btn btn-info btn-lg blue-btn" id="save_data">Checkout</a>
            
              <input type="hidden" id="stripe_file_link" value="<?php echo get_template_directory_uri(); ?>/ajax/stripe_charge.php">
              <input type="hidden" id="plan_price" value="">
              <input type="hidden" id="plan_name" value="">
              <input type="hidden" id="plan_description" value="">
              <?php
              /**** Check if test keys or live ****/
              $keysType = get_field("stripe_test_keys","option");
              if($enableStripe[0] == 'Enable Strip' && $keysType[0] == 'yes'){
                  $k = get_field("testing_publish_key","option");
              }else{
                  $k = get_field("live_publish_key","option");
              }
              ?>
              <input type="hidden" id="stripe_p_key" value="<?php echo $k; ?>">
              <?php if($_GET['upgrade'] == 'true'){ ?>
              <input type="hidden" id="plan_meal_data" value='<?php echo base64_encode($quizData); ?>'>
              <?php }else{ ?>
              <input type="hidden" id="plan_meal_data" value='<?php echo base64_encode(serialize($quizData)); ?>'>
              <?php }?>
              
              <input type="hidden" id="plan_duration" value=''>
              <input type="hidden" id="meal_plan_id" value=''>
          </div>
            <div id="paymentResponse"></div>
                  
            </div>
<!--
            <div id="pay-with-amazon" class="tab-pane fade">
                <div id="AmazonPayButton"></div>
                  
                <script type="text/javascript">
                    window.onAmazonLoginReady = function () {
                        amazon.Login.setClientId('amzn1.application-oa2-client.364efbea1379435d9ee7d14c16fea721');
                    };
                    window.onAmazonPaymentsReady = function() {
                            showButton();
                    };
                    
                    function showButton() {               
                        var authRequest;
                        OffAmazonPayments.Button("AmazonPayButton", "A16DJE5TP2SAX9", {
                            type: "PwA",
                            color: "Gold",
                            size: "large",
                            language: "en-UK",
                            authorization: function () {
                                loginOptions = { scope: "profile payments:widget payments:shipping_address", popup: true };
                                authRequest = amazon.Login.authorize(loginOptions, "https://amzn.github.io/amazon-pay-sdk-samples/set.html");
                            },
                            onError: function(error) {
                                        // your error handling code.
                                        // alert("The following error occurred: " 
                                        //        + error.getErrorCode() 
                                        //        + ' - ' + error.getErrorMessage());
                                    }
                        });
                    };
//                  function showButton(){
//                    var authRequest; 
//                    OffAmazonPayments.Button("AmazonPayButton", "A16DJE5TP2SAX9", { 
//                      type:  "PwA", 
//                      color: "LightGray", 
//                      size:  "small", 
//
//                      authorization: function() { 
//                      loginOptions = {scope: "profile", 
//                        popup: "true"}; 
//                      authRequest = amazon.Login.authorize (loginOptions, 
//                        "https://yourpersonalizediet.customerdevsites.com/set-payment-detail/"); 
//                      } 
//                   }); 
//                  }
                </script>
                  
                <div id="addressBookWidgetDiv" style="width:400px; height:240px;"></div>
                <div id="walletWidgetDiv" style="width:400px; height:240px;"></div>
                <script type='text/javascript' src='https://static-eu.payments-amazon.com/OffAmazonPayments/uk/sandbox/js/Widgets.js'></script>
                <script type="text/javascript">
                new OffAmazonPayments.Widgets.AddressBook({
                    sellerId: 'A16DJE5TP2SAX9',
                    onOrderReferenceCreate: function (orderReference) {
                       orderReferenceId = orderReference.getAmazonOrderReferenceId();
                    },
                    onAddressSelect: function () {
                        // do stuff here like recalculate tax and/or shipping
                    },
                    design: {
                        designMode: 'responsive'
                    },
                    onError: function (error) {
                        // your error handling code
                    }
                }).bind("addressBookWidgetDiv");

                new OffAmazonPayments.Widgets.Wallet({
                    sellerId: 'A16DJE5TP2SAX9',
                    onPaymentSelect: function () {
                    },
                    design: {
                        designMode: 'responsive'
                    },
                    onError: function (error) {
                        // your error handling code
                    }
                }).bind("walletWidgetDiv");
            </script>
              </div>
-->
            <div id="pay-with-paypal" class="tab-pane fade" >
                <button class="btn btn-info btn-lg blue-btn" id="make_payment_paypal">Checkout</button>
                <form action="<?php echo $link; ?>" method="post" style="display:none;">
                    <!-- Identify your business so that you can collect the payments -->
                    <input type="hidden" name="business" value="<?php echo $pay_email; ?>">
                    <!-- Specify a subscriptions button. -->
                    <input type="hidden" name="cmd" value="_xclick">
                    <!-- Specify details about the subscription that buyers will purchase -->
                    <input type="hidden" name="item_name" value="New Payment" >
                    <input type="hidden" name="item_number" value="1" >
                    <input type="hidden" name="amount" id="paypal_price" value="<?php echo $price; ?>" >
                    <input type="hidden" name="shipping" value="0" >
                    <input type="hidden" name="no_note" value="1" >
                    <!-- Set recurring payments until canceled. -->
                    <input type="hidden" name="currency_code" value="USD" />
                    <!-- Custom variable user ID -->
                    <input type="hidden" name="custom" value="<?php echo $userid; ?>">
                    <!-- Specify urls -->
                    <input name="return" value="<?php echo get_the_permalink(1736); ?>" type="hidden">
                    <input name="notify_url" value="https://yourpersonalizeddiet.com/ipn.php" type="hidden">
                    <!-- Display the payment button -->
                    <input class="btn btn-info btn-lg blue-btn paypal_btn" type="submit" value="Make Payment">
                </form>
            </div>
            </div>
          </div>
        </div>      
  </section>
</main>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <p>Hurray..! You just saved $<span id="c_amon"></span> on your order</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
get_footer();
?>
