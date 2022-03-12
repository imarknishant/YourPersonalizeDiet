function validation(el){

    var el_id = jQuery(el).attr('id');
    var totalVegRem = parseInt(jQuery("#total_veg_removed").val());
    var pre_count = parseInt(jQuery("#removed").val());
    
    /**** For Vagetable valdation only START****/
    
    if(!isNaN(totalVegRem)){
        
        if(jQuery(el).is(":checked")){
           totalVegRem--;
           var totalRemoved = pre_count-1;
           jQuery("#removed").val(totalRemoved);
           jQuery("#total_veg_removed").val(totalVegRem);
           jQuery('#'+el_id).parent().parent().removeClass('open');
           jQuery('#'+el_id).parent().parent().addClass('plus');
           jQuery('#'+el_id).addClass('veg_checkbox');
           setTimeout(function(){
               $('#'+el_id).parent().parent().removeClass('addover_lay');
           },100);
            
            jQuery(".meat-checkbox").each(function(){
               if(!jQuery(this).hasClass('open')){
                  jQuery(this).removeClass('addover_lay');
               }
           });
            
       }else{
           if(totalVegRem < 5){
               totalVegRem++;
               var totalRemoved = pre_count+1;
               jQuery("#removed").val(totalRemoved);
               jQuery("#total_veg_removed").val(totalVegRem);
               jQuery('#'+el_id).removeClass('veg_checkbox');
               jQuery('#'+el_id).parent().parent().addClass('open');
               jQuery('#'+el_id).parent().parent().removeClass('plus');
               
               if(totalVegRem == 5){
                   $('#'+el_id).parent().parent().removeClass('addover_lay');
                   jQuery(".meat-checkbox").each(function(){
                       if(jQuery(this).hasClass('open')){
                          jQuery(this).removeClass('addover_lay');
                       }else{
                          jQuery(this).addClass("addover_lay");
                          jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
                       }
                   });
               }
           }
       } 
    
    /**** For Vagetable valdation only END****/
        
    }else{
        
        if(jQuery(el).is(":checked")){
           var totalRemoved = pre_count-1;
           jQuery("#removed").val(totalRemoved);
           jQuery('#'+el_id).parent().parent().removeClass('open');
           jQuery('#'+el_id).parent().parent().addClass('plus');
           jQuery('#'+el_id).addClass('veg_checkbox');
           setTimeout(function(){
               $('#'+el_id).parent().parent().removeClass('addover_lay');
           },100);
            
            jQuery(".meat-checkbox").each(function(){
               if(!jQuery(this).hasClass('open')){
                  jQuery(this).removeClass('addover_lay');
               }
           });

       }else{
           
           if(pre_count < 10){
               
               var totalRemoved = pre_count+1;
               jQuery("#removed").val(totalRemoved);
               jQuery('#'+el_id).removeClass('veg_checkbox');
               jQuery('#'+el_id).parent().parent().addClass('open');
               jQuery('#'+el_id).parent().parent().removeClass('plus');

               if(pre_count == 9){
                   $('#'+el_id).parent().parent().removeClass('addover_lay');
                   jQuery(".meat-checkbox").each(function(){
                       if(jQuery(this).hasClass('open')){
                          jQuery(this).removeClass('addover_lay');
                       }else{
                          jQuery(this).addClass("addover_lay");
                          jQuery("#limit_msg").text('Maximum ingredients remove limit reached');
                       }
                   });
                   
               }

           }
       }
    }

}