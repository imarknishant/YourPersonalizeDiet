<?php
/*
Template Name: Set Payment details
*/
get_header();
?>


<input type="hidden" id="mws_access_key" value="AKIAIEHXG7NLOIBRWFUA">
<input type="hidden" id="mws_secret_key" value="9bc77d6af4edf0c9c4315f15a709d7602e93360413b0abe34e3ad35370a3b025">
<input type="hidden" id="merchant_id" value="A16DJE5TP2SAX9">
<input type="hidden" id="client_id" value="amzn1.application-oa2-client.f18f2b90f07c47a28cfcb404c7b6b046">
<div class="container">
            
<h2>Select Shipping and Payment Method</h2>
<div class="text-center" style="margin-top:40px;">
    <div id="addressBookWidgetDiv" style="width:400px; height:240px; display:inline-block;"></div>
    <div id="walletWidgetDiv" style="width:400px; height:240px; display:inline-block;"></div>
    <div style="clear:both;"></div>
    <form class="form-horizontal" style="margin-top:40px;" role="form" method="post" action="ConfirmPaymentAndAuthorize.php">
        <button id="place-order" class="btn btn-lg btn-success">Place Order</button>
        <div id="ajax-loader" style="display:none;"><img src="images/ajax-loader.gif" /></div>
    </form>
</div>

    <script type="text/javascript">
        new OffAmazonPayments.Widgets.AddressBook({
            sellerId: "A16DJE5TP2SAX9",
            onOrderReferenceCreate: function (orderReference) {

                /* make a call to the back-end that will set order reference details
                 * and get order reference details. This will set the order total
                 * to 19.95 and return order reference details.
                 *
                 * Get the AddressConsentToken to be sent to the API call
                 */
               var access_token = "<?php echo $_GET['access_token'];?>";

                $.post("Apicalls/GetDetails.php", {
                    orderReferenceId: orderReference.getAmazonOrderReferenceId(),
                    addressConsentToken: access_token,
                }).done(function (data) {
                   $("#get_details_response").html(data);
                });
            },
            onAddressSelect: function (orderReference) {
            },
            design: {
                designMode: 'responsive'
            },
            onError: function (error) {
                // your error handling code
            }
        }).bind("addressBookWidgetDiv");

        new OffAmazonPayments.Widgets.Wallet({
            sellerId: "A16DJE5TP2SAX9",
            onPaymentSelect: function (orderReference) {
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
<?php
get_footer();
?>

<script type='text/javascript'>
$(document).ready(function() {
    $('.start-over').on('click', function() {
        amazon.Login.logout();
        document.cookie = "amazon_Login_accessToken=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        window.location = 'index.php';
    });
    $('#place-order').on('click', function() {
        $(this).hide();
        $('#ajax-loader').show();
    });
});
</script>