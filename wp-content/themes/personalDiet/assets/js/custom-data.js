jQuery(document).ready(function(){
    
    $('body').bind('copy paste',function(e) {
        e.preventDefault(); return false; 
    });
    
    setTimeout(function(){
        jQuery(".label-test1").click();
        jQuery("#payButton").hide();
    },1000);
    
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
    
    /*** Add Validation to filter ***/

    var total_weeks = jQuery("#total_weeks").val();
    var nextweek = jQuery('input[name="current"]').val();
    if(nextweek == null){
        nextweek = 'week-1';
    }
    if(nextweek != ''){
        var weekcount = nextweek.split('-');
        if(total_weeks == weekcount[1]){
            jQuery('input[name="next-btn"]').attr("disabled", true);
        }else if(weekcount[1] == 1){
            jQuery('input[name="prev-btn"]').attr("disabled", true);
        }else{
            jQuery('input[name="prev-btn"]').attr("disabled", false);
            jQuery('input[name="next-btn"]').attr("disabled", false);
        }
    }
    
    /*** Calculate Removed Values Start ***/
    
//    jQuery("input[name='meat[]']").change(function(){
//        var count = parseInt(jQuery("#removed_meat").val());
//        alert(count);
//       if(jQuery(this).is(":checked")){
//           count--;
//           jQuery("#removed_meat").val(count);
//       }else{
//           count++;
//           jQuery("#removed_meat").val(count);
//       }
//    });
    
//    jQuery("input[name='meat[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_meat").val());
//    
//       if(jQuery(this).is(":checked")){
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_meat").val(totalRemoved);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(pre_count < 10){
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_meat").val(totalRemoved);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(pre_count == 9){
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//                   jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
//               }
//               
//           }else{
//               toastr.error('Maximum of 10 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
//           }
//       }        
//    });
    
//    jQuery("input[name='vegetables[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_veg").val());
//    var totalVegRem = parseInt(jQuery("#total_veg_removed").val());
//    
//       if(jQuery(this).is(":checked")){
//           
//           totalVegRem--;
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_veg").val(totalRemoved);
//           jQuery("#total_veg_removed").val(totalVegRem);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(totalVegRem < 5){
//               totalVegRem++;
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_veg").val(totalRemoved);
//               jQuery("#total_veg_removed").val(totalVegRem);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(totalVegRem == 5){
//                   
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//               }
//           }else{
//               toastr.error('Maximum of 5 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
////               $('.veg_checkbox').parent().parent().addClass("addover_lay");
//
//           }
//       }        
//    });
    
//    jQuery("input[name='fruits[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_fruits").val());
//    
//       if(jQuery(this).is(":checked")){
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_fruits").val(totalRemoved);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(pre_count < 10){
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_fruits").val(totalRemoved);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(pre_count == 9){
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//                   jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
//               }
//               
//           }else{
//               toastr.error('Maximum of 10 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
//           }
//       }        
//    });
    
//    jQuery("input[name='grains[]']").change(function(){
//       var count = parseInt(jQuery("#removed_grains").val());
//       if(jQuery(this).is(":checked")){
//           count--;
//           jQuery("#removed_grains").val(count);
//           $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//       }else{
//           count++;
//           if(count == 11){
//               toastr.error('Maximum 10 products allowed to remove');
//               $('.veg_checkbox').parent().parent().addClass("addover_lay");
//           }else{
//               jQuery("#removed_grains").val(count);
//           }
//       }
//    });
    
//    jQuery("input[name='grains[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_grains").val());
//    
//       if(jQuery(this).is(":checked")){
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_grains").val(totalRemoved);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(pre_count < 10){
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_grains").val(totalRemoved);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(pre_count == 9){
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//                   jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
//               }
//               
//           }else{
//               toastr.error('Maximum of 10 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
//           }
//       }        
//    });
    
    
//    jQuery("input[name='dairy[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_diary").val());
//    
//       if(jQuery(this).is(":checked")){
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_diary").val(totalRemoved);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(pre_count < 10){
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_diary").val(totalRemoved);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(pre_count == 9){
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//                   jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
//               }
//               
//           }else{
//               toastr.error('Maximum of 10 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
//           }
//       }        
//    });
    
//    jQuery("input[name='beans[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_beans").val());
//    
//       if(jQuery(this).is(":checked")){
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_beans").val(totalRemoved);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(pre_count < 10){
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_beans").val(totalRemoved);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(pre_count == 9){
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//                   jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
//               }
//               
//           }else{
//               toastr.error('Maximum of 10 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
//           }
//       }        
//    });
    
//    jQuery("input[name='allergies[]']").change(function(){
//    
//    var pre_count = parseInt(jQuery("#removed_allergies").val());
//    
//       if(jQuery(this).is(":checked")){
//           var totalRemoved = pre_count-1;
//           jQuery("#removed_allergies").val(totalRemoved);
//           jQuery(this).parent().parent().removeClass('open');
//           jQuery(this).parent().parent().addClass('plus');
//           jQuery(this).addClass('veg_checkbox');
//           setTimeout(function(){
//               $('.veg_checkbox').parent().parent().removeClass('addover_lay');
//           },100);
//            
//       }else{
//           if(pre_count < 10){
//               var totalRemoved = pre_count+1;
//               jQuery("#removed_allergies").val(totalRemoved);
//               jQuery(this).removeClass('veg_checkbox');
//               jQuery(this).parent().parent().addClass('open');
//               jQuery(this).parent().parent().removeClass('plus');
//               
//               if(pre_count == 9){
//                   $('.veg_checkbox').parent().parent().addClass("addover_lay");
//                   jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
//               }
//               
//           }else{
//               toastr.error('Maximum of 10 products allowed to remove');
//               jQuery(this).parent().parent().addClass('not-minus');
//           }
//       }        
//    });
    
    /*** Calculate Removed Values End ***/
    

    jQuery( "#next_week_plan" ).click(function(e){
        e.preventDefault();
        var ajax_url    = jQuery('#ajax').val();
        var date        = jQuery("#this_week_last_date").val();
        var userid      = jQuery("#user_id").val();
        var week      = jQuery("#week").val();
        jQuery.ajax({	           
            type: "POST",
            url: ajax_url+"/ajax/get_date_recipe.php", 
            data: {date:date,userid:userid,week:week},
            dataType: 'json',
            success: function(res){ 
                jQuery(".dash-meal-table").empty();
                jQuery(".dash-meal-table").html(res.html_d);
                
                jQuery("#meal_dates").text(res.showing_dates);
                jQuery("#this_week_last_date").val(res.next_date);
                
                jQuery("#week").val(res.next_week);
            } 
       });
    });
    
    
    jQuery('input[name="radio-group"]').change(function(){

        var mealtext = jQuery(this).data('meal');
        var mealprice = jQuery(this).val();
        var mealdur = jQuery(this).data('duration');
        
        jQuery(".selected-meal").text(mealtext);
        jQuery(".selected-meal-price").text('$'+mealprice);
        jQuery(".total").text('$'+mealprice);
    
        
        jQuery("#amount").val(mealprice);
        jQuery("#selected-price").val(mealprice);
        jQuery("#plan_type").val(mealtext);
        jQuery("#plan_dur").val(mealdur);
        
        /*** New Stripe ***/
        jQuery("#plan_price").val(mealprice);
        jQuery("#plan_name").val(mealtext);
        jQuery("#plan_description").val(mealtext);
        jQuery("#plan_duration").val(mealdur);
        
        /*** paypal ***/
        jQuery("#paypal_price").val(mealprice);
        jQuery("input[name='item_name']").val(mealtext);
       
    });
    
    setTimeout(function(){
        jQuery('input[name="radio-group"]').each(function(){
           jQuery(this).prop("checked",false); 
        });
        
        jQuery(".single-radio").each(function(){
            jQuery(this).removeClass('clicked');
        });
        
        jQuery(".selected-meal").text('Choose Plan');
        jQuery(".selected-meal-price").text('$0.0');
        jQuery(".total").text('$0.0');
        
        /*** New Stripe ***/
        jQuery("#plan_price").val('');
        jQuery("#plan_name").val('');
        jQuery("#plan_description").val('');
        jQuery("#plan_duration").val('');
        
        /*** paypal ***/
        jQuery("#paypal_price").val('');
        jQuery("input[name='item_name']").val('');
    },2000);
    
    /***** Make payment paypal START******/
    
    jQuery("#make_payment_paypal").click(function(e){
        e.preventDefault();
        var mealData = document.getElementById("plan_meal_data").value;
        var ajax_url = jQuery('#admin-ajax-url').val();
        
        var productName = document.getElementById("plan_name").value;
        var planDuration = document.getElementById("plan_duration").value;
        var plan_price = jQuery("#paypal_price").val();
        
        if(plan_price != ''){
        jQuery.ajax({	           
            type: "POST",
            url: ajax_url, 
            data: {mealData: mealData, 'action': 'save_data'},
            dataType: 'json',
            success: function(data){
                jQuery("input[name='custom']").val(productName+','+planDuration+','+data.lastid);
                /********************************/
                jQuery(".paypal_btn").click();
                /********************************/
            } 
       });
        }else{
            toastr.error("Please select plan");
        }
    });
    
    
    /***** Make payment paypal END *****/
    jQuery('input[name="health_Status_btn"]').click(function(e){
       var selvalue = jQuery('input[name="health"]:checked').val();
        if(selvalue == 'Pregnant'){
            jQuery('#myModal').modal('show');
            e.preventDefault();

        }else if(selvalue == 'Breastfeeding'){
            jQuery('#myModal').modal('show');
            e.preventDefault();
        }
    });

	/*********** Add custom <a> class to wp_nav_menu header ***********/
	
	jQuery('ul.navbar-nav>li>a').attr('class','nav-link');
    
    /***** Apply Promo code ******/
    
    jQuery("#apply_code").click(function(e){
        e.preventDefault();
        var actual_price = jQuery("#selected-price").val();
        var ajax_url     = jQuery('#admin-ajax-url').val();
        
        if(actual_price != ''){
            
            var data = jQuery("#promo-form").serialize();
            jQuery.ajax({	           
                type: "POST",
                url: ajax_url,
                data: data,
                dataType: 'json',
                success: function(data){
                    if(data.status == 1){
                        jQuery("#amount").val(data.new_amount);
                        jQuery(".total").text('$'+data.new_amount);
                        jQuery("#plan_price").val(data.new_amount);
                        jQuery("#c_amon").text(data.discount_amount);
                        jQuery("#myModal").modal('show');
                        
                        jQuery("#code").val('');
                        
                    }else{
                        toastr.error("Invalid code");
                    }
                }
           });
        }else{
            toastr.error("Please select price");
        }

    });
	
	/*************** User Signup **************/
	
   jQuery("form#signup_form").validate({

        rules: {
            
            email: {
                required: true,
            },
			
		}, 

        submitHandler: function(form){
        var signUpValues = jQuery('form#signup_form').serialize();
        var ajax_url     = jQuery('#admin-ajax-url').val();
            
		if($("#signup_terms").prop('checked') == true){
        
			jQuery.ajax({	           
				type: "POST",
				url: ajax_url, 
				data: signUpValues,
				dataType: 'json',
				success: function(data){ //alert(data);

					if(data.status == 1){
						toastr.error("Username already exists!");
					}else if(data.status == 2){
						toastr.error("Email already exists!")
					}else{
                        jQuery(".email-header").hide();
                        jQuery(".email-content").hide();
                        jQuery(".how-it-works").show();
                        
                        $('.count').each(function () {
                          $(this).prop('Counter',0).animate({
                              Counter: $(this).text()
                          }, {
                              duration: 5000,
                              easing: 'swing',
                              step: function (now) {
                                  $(this).text(Math.ceil(now));
                              }
                          });
                      });
                        
                        setTimeout(function(){
                            location.href = data.url;
                        },5100);
					}
				} 
		   });
		}else{
			toastr.error("Please accept terms and conditions!!")
		}
    }				
	});
    
    
    /******* Strip One time Payment start*******/
    
    jQuery("#save_data").click(function(){
        
    var mealData = document.getElementById("plan_meal_data").value;
    var ajax_url = jQuery('#admin-ajax-url').val();
    var plan_price = jQuery("#plan_price").val();
        
    if(plan_price != ''){
        jQuery.ajax({	           
            type: "POST",
            url: ajax_url, 
            data: {mealData: mealData, 'action': 'save_data'},
            dataType: 'json',
            success: function(data){
                jQuery("#meal_plan_id").val(data.lastid);
                /********************************/
                jQuery("#payButton").click();
                /********************************/
            } 
       });
    }else{
        toastr.error("Please select plan");
    }
        
    })
    var buyBtn = document.getElementById('payButton');
    if(buyBtn != null){
    var responseContainer = document.getElementById('paymentResponse');

    // Create a Checkout Session with the selected product
    var createCheckoutSession = function (stripe) {

        var link = document.getElementById("stripe_file_link").value;
        var productName = document.getElementById("plan_name").value;
        var productPrice = document.getElementById("plan_price").value;
        var productDesc = document.getElementById("plan_description").value;
        var mealplan_id = document.getElementById("meal_plan_id").value;
        var planDuration = document.getElementById("plan_duration").value;
        

        return fetch(link, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                checkoutSession: 1,
                productName: productName,
                productPrice: productPrice,
                productDescription: productDesc,
                mealplan_id: mealplan_id,
                planDuration: planDuration,
            }),
        }).then(function (result) {
            return result.json();
        });
    };

    // Handle any errors returned from Checkout
    var handleResult = function (result) {
        if (result.error) {
            responseContainer.innerHTML = '<p>'+result.error.message+'</p>';
        }
        buyBtn.disabled = false;
        buyBtn.textContent = 'Buy Now';
    };

    // Specify Stripe publishable key to initialize Stripe.js
//    var stripe = Stripe('pk_test_51JAa6SKQXlQ0vsKHsdKmlrRsW7EjvbVk9Z4adXAcId00fnvtHpKbXHp9SZCFYm0bGVqlRQJFuwNtsDU6rKsUdMr700yLbNmlap');
    var strip_p_k = document.getElementById("stripe_p_key").value;
    var stripe = Stripe(strip_p_k);

    buyBtn.addEventListener("click", function (evt) {


        buyBtn.disabled = true;
        buyBtn.textContent = 'Please wait...';

        createCheckoutSession().then(function (data) {
            if(data.sessionId){
                stripe.redirectToCheckout({
                    sessionId: data.sessionId,
                }).then(handleResult);
            }else{
                handleResult(data);
            }
        });
    });
    }
	/******* Strip One time Payment end*******/
});


$(document).on('click', '.toggle-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    
    var input = $("#pass_log_id");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.old-toggle-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    
    var input = $("#old_pass");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.new-toggle-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    
    var input = $("#new_pass");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

/*************** Login user **************/
    
   jQuery("#login_form").validate({
     rules: {
            
        login_email: {
            required: true,
             },
        login_password: {
            required: true,
        },

     },

    submitHandler: function(form){
      var loginUpValues = jQuery('#login_form').serialize();
      var ajax_url      = jQuery('#admin-ajax-url').val();

       jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: loginUpValues,
	        dataType: 'json',
	        success: function(data){
                if(data.status == 'success'){
                    toastr.success(data.message);
                    window.location = data.url;
                     setTimeout(function(){
                       location.reload();
                     },1500);
                }else{
                    toastr.error(data.message);
                }
            }
       });

    }

  });


/*************** Calculate BMS **************/
    
   jQuery("#bms_calculator").validate({
     rules: {
            
        gender: {
            required: true,
             },
        age: {
            required: true,
        },
         height: {
            required: true,
        },
         weight: {
            required: true,
        },

     },

    submitHandler: function(form){
      var formValues = jQuery('#bms_calculator').serialize();
      var ajax_url      = jQuery('#admin-ajax-url').val();

       jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: formValues,
	        dataType: 'json',
	        success: function(data){
                if(data.maintain_weight != '' && data.loss_weight != ''){
                    jQuery("#maintain_w_v").text(Math.round(data.maintain_weight)+' Calories/day');
                    jQuery("#loss_w_v").text(Math.round(data.loss_weight)+' Calories/day');
                }
            }

       });

    }

  });


    /*** Load More ***/

    jQuery("#load_more_faq").click(function(){
        var currentCount = parseInt(jQuery("input[name='current_number']").val());
        var ajax_url   = jQuery('#admin-ajax-url').val();
        
        jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: {currentCount:currentCount,action:'load_more_faq'},
	        success: function(data){
                if(data != ''){
                    jQuery("#accordion").append(data);
                    jQuery(".faq-load-btn").hide();
                }
            }

       });
        
    });

    /*** Load More Weight loss tips***/
    jQuery("#load_more").click(function(){
        var currentCount = parseInt(jQuery("input[name='current_number']").val());
        var ajax_url   = jQuery('#admin-ajax-url').val();
        $('#load_more').addClass('disabled');
        
        jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: {currentCount:currentCount,action:'load_more_weight_loss'},
            dataType:'json',
	        success: function(data){
                if(data != ''){
                    jQuery("#accordion").append(data.html);
                    jQuery("input[name='current_number']").val(currentCount+5);
                    $('#load_more').removeClass('disabled');
                    if(data.co < 5){
                        jQuery(".faq-load-btn").hide();
                    }
                }else{
                    jQuery(".faq-load-btn").hide();
                }
            }

       });
        
    });


/*************** Reset Password **************/
    
  jQuery("#reset_password").validate({
     rules: {
        password: {
            required: true,
            minlength : 5
             },
        re_password: {
            required: true,
            equalTo : "#password"
        },
     },
    submitHandler: function(form){
      var resetValues = jQuery('#reset_password').serialize();
      var ajax_url    = jQuery('#admin-ajax-url').val();
       jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: resetValues,
	        dataType: 'json',
	        success: function(data){
                if(data.status == 'success'){
                    toastr.success(data.message);                    
                     setTimeout(function(){
                         window.location = data.url;
                     },1500);
                    window.location = data.url;
                }else{ 
                    toastr.error(data.message);
                }
            }
       });
    }
  });

/*************** My Progress **************/
    
   jQuery("#progress_form").validate({
     rules: {  
        current_weight: {
            required: true,
        },
     }, 

    submitHandler: function(form){
      var form_data = jQuery("#progress_form").serialize();
      var ajax_url  = jQuery('#admin-ajax-url').val();

       jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: form_data,
            dataType: 'json',
	        success: function(data){
                if(data.status == 1){
                    toastr.success('Updated successfully');
                    
                    setTimeout(function(){
                        location.reload();
                    },2000);
                    
                }else{
                    toastr.error('Error Updating');
                }
            }
           
       });

    }

  });




/*************** Dashboard profile **************/
    
   jQuery("#dash_profile_form").validate({
     rules: {
            
        first_name: {
            required: true,
             },
        last_name: {
            required: true,
        },
     }, 

    submitHandler: function(form){
      var form_data = new FormData(jQuery("#dash_profile_form")[0]);
      var ajax_url  = jQuery('#admin-ajax-url').val();

       jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: form_data,
            contentType: false,
            processData: false,
	        dataType: 'json',
	        success: function(data){
                if(data.status==1){
                    toastr.success(data.message);
                    window.location.reload();
                }
            }
           
       });

    }

  });



/************************ Forgot password *******************/

   jQuery(document).ready(function(){
       
    jQuery("#forgot_pass_form").validate({
         rules: {
             
            email: {
                required: true,
                 },

         },

           submitHandler : function(form){
               
               var formData = jQuery("#forgot_pass_form").serialize();
               var ajax_url = jQuery('#admin-ajax-url').val();
               
               jQuery.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: formData,
                    dataType: 'json',
                    success: function(data){
                        if(data.status==1){
                            toastr.success("New password has been sent , please check your email!!..");
                            jQuery(".close").click();
                        }else{
                            
                            toastr.error("Something went wrong please try again...");
                        }
                    }
               });
  
           }
           
       });
                 
   });

/************************ Update password in dashboard *******************/

   jQuery(document).ready(function(){
       
    jQuery("#dash_pass_change_form").validate({
         rules: {
             
            old_pass: {
                required: true,
                 },
                          
            new_pass: {
                required: true,
                 },


         },

           submitHandler : function(form){
               
               var formData = jQuery("#dash_pass_change_form").serialize();
               var ajax_url = jQuery('#admin-ajax-url').val();
               
               jQuery.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: formData,
                    dataType: 'json',
                    success: function(data){
                        if(data.status==1){
                            toastr.success("Your password has been changed!!..");
                        }else{
                            
                            toastr.error("Something went wrong please try again...");
                        }
                    }
               });
  
           }
           
       });
                 
   });



/************************ Image preview **************/

     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }


jQuery(document).ready(function(){
       
    jQuery("#pay_with_stripe").validate({
         rules: {
             
            name: {
                required: true,
                 },
                          
            email: {
                required: true,
                 },
             
            phone_number: {
                required: true,
                 },
                          
            card_number: {
                required: true,
                 },
             
            exp_month: {
                required: true,
                 },
            exp_year: {
                required: true,
                 },
                          
            cv_code: {
                required: true,
                 },
         },

           submitHandler : function(form){
            submitForm();
           }
           
       });
                 
   });

/*********************** Stripe payment ********************/

/*** Stripe Card validation Function full payment ****/
   function submitForm(){
           Stripe.createToken({

           number: jQuery('.card_number').val(),

           cvc: jQuery('.card_cvv').val(),
               
           exp_month: jQuery('.ex_month').val(),

           exp_year: jQuery('.ex_year').val()

           }, stripeResponseHandler);      
   }

// Set your publishable key
Stripe.setPublishableKey('pk_test_f0K9ipIEGynaiIN4g9yXhlgA000S0Z6X0P');
// Callback to handle the response from stripe


function stripeResponseHandler(status, response) {

if (response.error) {
   // Display the errors on the form
   jQuery(".payment-status").html('<p>'+response.error.message+'</p>');
   
} else {
   var form$ = jQuery("#pay_with_stripe");
   // Get token id
   var token = response.id;
   // Insert the token into the form
   form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
   // Submit form to the server
       
       var price = jQuery("#amount").val();
    
       if(price != ''){
       var stripeValues = jQuery('#pay_with_stripe').serialize();
       var ajax_url = jQuery('#admin-ajax-url').val();
       var dataString = "token="+token+"&"+stripeValues;
       if(jQuery("input[name='stripe_terms']").is(":checked")){
           jQuery.ajax({
           type: "POST",
           url: ajax_url,
           data: dataString,
           dataType: 'json',
           success: function(res){
               if(res.status == 1){
                   toastr.success("Payment complete. Please check email!");
//                   setTimeout(function(){
//                       location.href = res.url;
//                   },1000);
               }else{
                   toastr.error("Error");
               }
           }
       });
       }else{
           toastr.error("Please accept terms");
       }
       }else{
           toastr.error("Please select plan");
       }
       
   }
}


/*** Change Recipe ***/

function change_recipe(rid,dkey,dname){
    
    var all = jQuery("#"+dname).val();

    var count = parseInt(jQuery("."+dkey+"_count").val());
    
    var ajax_url = jQuery('#admin-ajax-url').val();
        jQuery.ajax({
           type: "POST",
           url: ajax_url,
           data: {rid:rid, count:count, all:all, action:'change_recipe'},
           dataType: 'json',
           success: function(res){
               if(res.status == 1){
                  
                   jQuery("."+dkey+"_name").html(res.html_name);
                   jQuery("#"+dkey+"_calories_val").val(res.cal_val);
                   jQuery("."+dkey+"_link").html(res.html_link);
                   
                   jQuery("."+dkey+"_count").val(count+1);
                   
                   jQuery("#my_dataviz_"+dname).empty();
                   
                   setTimeout(function(){
                       /***** Ajax to create kilo chart *****/
                   info = [];
                   var numItems = jQuery('.'+dname).length;
                   var dataval = [];
                   textval = [];
                       domainval = [];
                   
                   for(var x=0; x<numItems; x++){
                       
                       var cv = jQuery("."+dname+'_'+x).val();
                       var dva = jQuery("."+dname+'_'+x).val();
                       var dietType = jQuery("."+dname+'diet_'+x).val();
                       dataval['a'+x] = dva;
                       domainval[x] = 'a'+x;
                       textval[x] = dietType+' '+ cv+' kcal';
                   }

                   var width = 400
                       height = 400
                       margin = 40

                      // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
                      var radius = Math.min(width, height) / 2 - margin

                      // append the svg object to the div called 'my_dataviz'
                      var svg = d3.select("#my_dataviz_"+dname)
                          .append("svg")
                          .attr("width", width)
                          .attr("height", height)
                          .append("g")
                          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                      // Create dummy data   
                      var data = dataval;
                      // set the color scale
                      var color = d3.scaleOrdinal()
                        .domain(domainval)
                        .range(d3.schemeDark2);

                       var textdata = textval;
                       

                      // Compute the position of each group on the pie:
                      var pie = d3.pie()
                        .sort(null) // Do not sort group by size
                        .value(function(d) {return d.value; })

                      var data_ready = pie(d3.entries(data))

                      // The arc generator
                      var arc = d3.arc()
                        .innerRadius(radius * 0)         // This is the size of the donut hole
                        .outerRadius(radius * 0.8)

                      // Another arc that won't be drawn. Just for labels positioning
                      var outerArc = d3.arc()
                        .innerRadius(radius * 0.9)
                        .outerRadius(radius * 0.9)

                      // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
                      svg
                        .selectAll('allSlices')
                        .data(data_ready)
                        .enter()
                        .append('path')
                        .attr('d', arc)
                        .attr('fill', function(d){ return(color(d.data.key)) })
                          .attr("class", 'path')
                        .attr("stroke", "white")
                        .style("stroke-width", "0px")
                        .style("opacity", 0.7)

                      // Add the polylines between chart and labels:
                      svg
                        .selectAll('allPolylines')
                        .data(data_ready)
                        .enter()
                        .append('polyline')
                          .attr("stroke", "red")
                          .attr("class", 'lines')
                          .style("fill", "none")
                          .attr("stroke-width", 1)
                          .attr('points', function(d) {
                            var posA = arc.centroid(d) // line insertion in the slice
                            var posB = outerArc.centroid(d) // line break: we use the other arc generator that has been built only for that
                            var posC = outerArc.centroid(d); // Label position = almost the same as posB
                            var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2 // we need the angle to see if the X position will be at the extreme right or extreme left
                            posC[0] = radius * 0.95 * (midangle < Math.PI ? 1 : -1); // multiply by 1 or -1 to put it on the right or on the left
                            return [posA, posB, posC]
                          });

                      // Add the polylines between chart and labels:

                      svg
                        .selectAll('allLabels')
                        .data(data_ready)
                        .enter()
                        .append('text')
                          .attr("class", 'text')
                          .text( function(d,i) { 
                          
                          return textdata[i];

                        } )
                          .attr('transform', function(d) {
                              var pos = outerArc.centroid(d);
                              var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                              pos[0] = radius * 0.99 * (midangle < Math.PI ? 1 : -1);
                              return 'translate(' + pos + ')';
                          })
                          .style('text-anchor', function(d) {
                              var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                              return (midangle < Math.PI ? 'start' : 'end')
                          });
                       
                       jQuery("#my_dataviz_"+dname).append('<figure class="center-logo"><img src="https://yourpersonalizediet.customerdevsites.com/wp-content/themes/personalDiet/images/logoo.jpg" alt="logoo"></figure>');
                   /***** Ajax to create kilo chart *****/
                   },600);
                   
               }else{
                   jQuery("."+dkey+"_name").html(res.html_name);
                   jQuery("#"+dkey+"_calories_val").val(res.cal_val);
                   jQuery("."+dkey+"_link").html(res.html_link);
                   
                   jQuery("."+dkey+"_count").val(0);
                   
                   jQuery("#my_dataviz_"+dname).empty();
                   
                   setTimeout(function(){
                       /***** Ajax to create kilo chart *****/
                   info = [];
                   var numItems = jQuery('.'+dname).length;
                   dataval = [];
                   textval = [];
                   domainval = [];
                   
                   for(var x=0; x<numItems; x++){
                       
                       var cv = jQuery("."+dname+'_'+x).val();
                       var dva = jQuery("."+dname+'_'+x).val();
                       var dietType = jQuery("."+dname+'diet_'+x).val();
                       dataval['a'+x] = dva;
                       domainval[x] = 'a'+x;
                       textval[x] = dietType+' '+ cv+' kcal';
                   }

                   var width = 400
                       height = 400
                       margin = 40

                      // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
                      var radius = Math.min(width, height) / 2 - margin

                      // append the svg object to the div called 'my_dataviz'
                      var svg = d3.select("#my_dataviz_"+dname)
                          .append("svg")
                          .attr("width", width)
                          .attr("height", height)
                          .append("g")
                          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                      // Create dummy data   
                      var data = {a0:270,a1:50,a2:215,a3:150,a4:250,};
                       
                      // set the color scale
                      var color = d3.scaleOrdinal()
                        .domain(domainval)
                        .range(d3.schemeDark2);

                       var textdata = textval;
                       

                      // Compute the position of each group on the pie:
                      var pie = d3.pie()
                        .sort(null) // Do not sort group by size
                        .value(function(d) {return d.value; })

                      var data_ready = pie(d3.entries(data))

                      // The arc generator
                      var arc = d3.arc()
                        .innerRadius(radius * 0)         // This is the size of the donut hole
                        .outerRadius(radius * 0.8)

                      // Another arc that won't be drawn. Just for labels positioning
                      var outerArc = d3.arc()
                        .innerRadius(radius * 0.9)
                        .outerRadius(radius * 0.9)

                      // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
                      svg
                        .selectAll('allSlices')
                        .data(data_ready)
                        .enter()
                        .append('path')
                        .attr('d', arc)
                        .attr('fill', function(d){ return(color(d.data.key)) })
                          .attr("class", 'path')
                        .attr("stroke", "white")
                        .style("stroke-width", "0px")
                        .style("opacity", 0.7)

                      // Add the polylines between chart and labels:
                      svg
                        .selectAll('allPolylines')
                        .data(data_ready)
                        .enter()
                        .append('polyline')
                          .attr("stroke", "red")
                          .attr("class", 'lines')
                          .style("fill", "none")
                          .attr("stroke-width", 1)
                          .attr('points', function(d) {
                            var posA = arc.centroid(d) // line insertion in the slice
                            var posB = outerArc.centroid(d) // line break: we use the other arc generator that has been built only for that
                            var posC = outerArc.centroid(d); // Label position = almost the same as posB
                            var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2 // we need the angle to see if the X position will be at the extreme right or extreme left
                            posC[0] = radius * 0.95 * (midangle < Math.PI ? 1 : -1); // multiply by 1 or -1 to put it on the right or on the left
                            return [posA, posB, posC]
                          });

                      // Add the polylines between chart and labels:

                      svg
                        .selectAll('allLabels')
                        .data(data_ready)
                        .enter()
                        .append('text')
                          .attr("class", 'text')
                          .text( function(d,i) { 
                          
                          return textdata[i];

                        } )
                          .attr('transform', function(d) {
                              var pos = outerArc.centroid(d);
                              var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                              pos[0] = radius * 0.99 * (midangle < Math.PI ? 1 : -1);
                              return 'translate(' + pos + ')';
                          })
                          .style('text-anchor', function(d) {
                              var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
                              return (midangle < Math.PI ? 'start' : 'end')
                          });
                       
                       jQuery("#my_dataviz_"+dname).append('<figure class="center-logo"><img src="https://yourpersonalizediet.customerdevsites.com/wp-content/themes/personalDiet/images/logoo.jpg" alt="logoo"></figure>');
                   /***** Ajax to create kilo chart *****/
                   },600);
               }
           }
       });
}


function update_weight(id){
    
    /*************** Update My Progress **************/
    
   jQuery("#edit_weight_"+id).validate({
     rules: {  
        updated_weight: {
            required: true,
        },
     }, 

    submitHandler: function(form){
      var form_data = jQuery("#edit_weight_"+id).serialize();
      var ajax_url  = jQuery('#admin-ajax-url').val();

       jQuery.ajax({	           
	        type: "POST",
	        url: ajax_url,
	        data: form_data,
            dataType: 'json',
	        success: function(data){
                if(data.status == 1){
                    toastr.success('Updated successfully');
                    
                    setTimeout(function(){
                        location.reload();
                    },2000);
                    
                }else{
                    toastr.error('Error Updating');
                }
            }
           
       });

    }

  });
}

function show_popup(recipe_id){
    
       var ajax_url = jQuery('#admin-ajax-url').val();
       jQuery.ajax({	           
        type: "POST",
        url: ajax_url,
        data: {recipe_id:recipe_id, action:'load_popup'},
        success: function(data){
            jQuery("#exampleModal").empty();
            if(data != ''){
                jQuery("#exampleModal").append(data);
                setTimeout(function(){
                    jQuery('#exampleModal').modal('show'); 
                },100);

            }
        }
       });
}


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

//function delete_plan(row_id){
//    
//       var ajax_url = jQuery('#admin-ajax-url').val();
//    if (confirm('Are you sure!')) {
//        jQuery.ajax({	           
//            type: "POST",
//            url: ajax_url,
//            data: {row_id:row_id, action:'delete_plan'},
//            dataType: 'json',
//            success: function(data){
//                if(data.status == 1){
//                    toastr.success('Updated successfully');
//                    window.location.reload();
//                }else{
//                    toastr.error('Error updating');
//                }
//            }
//           });
//    }
//
//}

function play_video(id){
    $('.ply-video').each(function() {
        $(this).get(0).pause();
    });
    
    $(".btnn-ply-"+id).hide();
    
    $('#video_'+id).get(0).play();
    
    $('#video_'+id).on('ended',function(){
      $(".btnn-ply-"+id).show();
    });
}