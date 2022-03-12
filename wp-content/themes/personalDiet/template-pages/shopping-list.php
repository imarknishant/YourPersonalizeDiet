<?php
/* Template Name: shopping list */
get_header('dashboard');
global $wpdb;

$Days = array();
$Dates = array();
$dayName = array();
$days = array();
$allergy = array();

$userid = get_current_user_id();

$meal_data = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $userid");
$plan_duration = $meal_data[0]->plan_duration;

$dateStart = date('m/d/Y',strtotime($meal_data[0]->date_time));
for($j=0; $j<7; $j++){
    $dayName[] =  date('D',strtotime($meal_data[0]->date_time.' + '.$j.' day'));
//    $days[] = date('l',strtotime($meal_data[0]->date_time.' + '.$j.' day'));
}

/*** Calculate days ***/
if($_GET['next-btn'] != ''){
    /*** Variables ***/
    
    $week = $_GET['next'];
    $weekdata = explode('-',$week);
    $weekNumberC = $weekdata[1];
    $weekNumberP = $weekdata[1];
    $weekNumberN = $weekdata[1];
    
    $week = 'week-'.$weekNumberC;
//    $date = base64_decode($_GET[$week]);
    $date = $_GET[$week];
    
    $weekCurr = $_GET['next'];
    $weekPrev = 'week-'.--$weekNumberP;
    $weekNext = 'week-'.++$weekNumberN;
    
    $PlanStartDate = date('F d',strtotime($date)); 
    $PlanEndDate = date('F d',strtotime($date. ' + 6 day')); 
    
    $PlanStartDate_stamp = date('m/d/Y',strtotime($date));
    $PlanEndDate_stamp = date('m/d/Y',strtotime($date. ' + 6 day'));
    
    $currentStartDate = $PlanStartDate_stamp;
    $currentEndDate = $PlanEndDate_stamp;
    
    $previousStartDate = $_GET['st'];
    $previousEndDate = $_GET['ed'];
    
    /*** Calculate days ***/
    for($i=0; $i<1; $i++){
        for($j=0; $j<7; $j++){
            $Days[] =  date('l',strtotime($date.' + '.$j.' day'));
            $Dates[] = date('F d',strtotime($date.' + '.$j.' day'));
        }
    }
    
}else if($_GET['prev-btn'] != ''){
    
    /*** Variables ***/
    $week = $_GET['previous'];
    
    $weekdata = explode('-',$week);
    $weekNumberC = $weekdata[1];
    $weekNumberP = $weekdata[1];
    $weekNumberN = $weekdata[1];
    
    $week = 'week-'.$weekNumberC;
   
    $weekCurr = $_GET['previous'];
    $weekPrev = 'week-'.--$weekNumberP;
    $weekNext = 'week-'.++$weekNumberN;
    
    $date = $_GET[$week];
    
    $PlanStartDate = date('F d',strtotime($date)); 
    $PlanEndDate = date('F d',strtotime($date. ' + 6 day')); 
    
    $PlanStartDate_stamp = date('m/d/Y',strtotime($date));
    $PlanEndDate_stamp = date('m/d/Y',strtotime($date. ' + 6 day'));
    
    $currentStartDate = $PlanStartDate_stamp;
    $currentEndDate = $PlanEndDate_stamp;
    
    $previousStartDate = $_GET['st'];
    $previousEndDate = $_GET['ed'];
    
    /*** Calculate days ***/
    for($i=0; $i<1; $i++){
        for($j=0; $j<7; $j++){
            $Days[] =  date('l',strtotime($date.' + '.$j.' day'));
            $Dates[] = date('F d',strtotime($date.' + '.$j.' day'));
        }
    }
    
}else{
    $weekCurr = 'week-1';
    $weekPrev = 'week-0';
    $weekNext = 'week-2';
    
    $week = $weekCurr;
    
    /*** Variables ***/
    $PlanStartDate = date('F d',strtotime($meal_data[0]->date_time)); 

    $PlanEndDate = date('F d',strtotime($meal_data[0]->date_time. ' + 6 day')); 
    
    $PlanStartDate_stamp = date('m/d/Y',strtotime($meal_data[0]->date_time));
    $PlanEndDate_stamp = date('m/d/Y',strtotime($meal_data[0]->date_time. ' + 6 day'));
    
    $previousStartDate = $PlanStartDate_stamp;
    $previousEndDate = $PlanEndDate_stamp;
    
    $no_of_weeks = $meal_data[0]->plan_duration;
    for($i=0; $i<1; $i++){
        for($j=0; $j<7; $j++){
            $Days[] =  date('l',strtotime($meal_data[0]->date_time.' + '.$j.' day'));
            $Dates[] = date('F d',strtotime($meal_data[0]->date_time.' + '.$j.' day'));
        }
    } 
}


$meals = unserialize($meal_data[0]->meal_data);

$mt = explode(',',$meals['meat_arr']);

$veg = explode(',',$meals['vege_arr']);
$fruit = explode(',',$meals['fruits_arr']);
$grain = explode(',',$meals['grains_arr']);
$dairy = explode(',',$meals['dairy_arr']);
$beans = explode(',',$meals['beans_arr']);
if($meals['allergies_arr'] != ''){
    $allergyy = explode(',',$meals['allergies_arr']);
}else{
    $allergyy = array();
}

$input = array($mt,$veg,$fruit,$grain,$dairy,$beans);

$inc = call_user_func_array("array_merge", $input);

$selectCount = count($inc);

/**** Acf fields start ****/
$aller = get_field('allergies',67);

if(!empty($aller)){
    foreach($aller as $alle){
        if(!in_array($alle['allergies_name'],$allergyy)){
            $allergy[] = $alle['allergies_name'];
        }
        
    }
}

$meat = get_field('meats',63);

if(!empty($meat)){
    foreach($meat as $met){
        if(!in_array($met['meat_name'],$mt)){
            $allergy[] = $met['meat_name'];
        }
    }
}

$vegi = get_field('vegetables',65);

if(!empty($vegi)){
    foreach($vegi as $vegacf){
        if(!in_array($vegacf['vegetable_name'],$veg)){
            $allergy[] = $vegacf['vegetable_name'];
        }
    }
}

$fru = get_field('fruits',69);

if(!empty($fru)){
    foreach($fru as $fr){
        if(!in_array($fr['fruits_name'],$fruit)){
            $allergy[] = $fr['fruits_name'];
        }
        
    }
}

$gra = get_field('grains',74);

if(!empty($gra)){
    foreach($gra as $gr){
        if(!in_array($gr['grains_name'],$grain)){
            $allergy[] = $gr['grains_name'];
        }
        
    }
}

$dai = get_field('dairy',76);

if(!empty($dai)){
    foreach($dai as $da){
        if(!in_array($da['dairy_name'],$dairy)){
            $allergy[] = $da['dairy_name'];
        }
        
    }
}

$bea = get_field('beans',72);

if(!empty($bea)){
    foreach($bea as $be){
        if(!in_array($be['beans_name'],$beans)){
            $allergy[] = $be['beans_name'];
        }
    }
}

if(!in_array('Fish',$allergy) && !empty($allergy)){
    $allergy[] = "Salmon";
}
if(!in_array('Shellfish',$allergy) && !empty($allergy)){
    $allergy[] = "SHRIMP";
    $allergy[] = "SQUID";
    $allergy[] = "SCALLOPS";
    $allergy[] = "PRAWN";
    $allergy[] = "MUSSELS";
}
if(!in_array('Peanuts',$allergy) && !empty($allergy)){
    $allergy[] = "nuts";
}
if(!in_array('Milk',$allergy) && !empty($allergy)){
    $allergy[] = "kefir";
}

$_SESSION['aleg'] = implode(',',$allergy);

$acf_input = array($meat,$vegi,$fru,$gra,$dai,$bea);

$inc_acf = call_user_func_array("array_merge", $acf_input);

$acfCount = count($inc_acf);

/**** Acf fields end ****/
$i_am = $meals['gender'];
$i_want_to = $meals['weight'];
$current_activity = $meals['activity'];
$meat = $meals['meat_arr'];
$no_of_meals = $meals['meal'];
$show_message = false;

$im_target_weight = (int) $meals['imperial_target_weight'];
$me_target_weight = (int) $meals['metric_target_weight'];

$im_current_weight = (int) $meals['imperial_weight'];
$me_current_weight = (int) $meals['metric_weight'];

if($im_target_weight != ''){
    
    if($im_target_weight < $im_current_weight){
        $show_message = true;
        $calculated_weight = (int)$meals['imperial_weight'] - (int)$meals['imperial_target_weight'];
        $calculated_weight = ($calculated_weight/100)*80;
    }
    
}else if($me_target_weight != ''){

    if($me_target_weight < $me_current_weight){
        $show_message = true;
        $calculated_weight = (int)$meals['metric_weight'] - (int)$meals['metric_target_weight'];
        $calculated_weight = ($calculated_weight/100)*80;
    }
}

$menu = '';


$main = array('female','Lose Weight','Maintain Weight','Low','Medium');
$build = array('male','Build Up','High');
$user = array($i_am,$i_want_to,$current_activity);

$mm = array_intersect($main,$user);
$bm = array_intersect($build,$user);

if(count($mm) >=2){
    if(empty($meat)){
        $menu = 'vegan-menu';
    }else{
        $menu = 'main-menu';
    }
}elseif(count($bm) >=2){
    if(empty($meat)){
        $menu = 'vegan-menu';
    }else{
        $menu = 'build-up-menu';
    }
}

$recipes = array();
$kilo_cal = array();
$recipes_video = array();
$change_recipe = array();
$recipe_ids = array();

if($no_of_meals == 1){
    $diet_type = array('lunch');
}else if($no_of_meals == 2){
    $diet_type = array('breakfast','dinner');
}else if($no_of_meals == 3){
    $diet_type = array('breakfast','snack-2','dinner');
}else if($no_of_meals == 4){
    $diet_type = array('breakfast','lunch','snack-2','dinner');
}else if($no_of_meals == 5){
    $diet_type = array('breakfast','snack-1','lunch','snack-2','dinner');
}
$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
/***** getting recipes start *****/

foreach($days as $d){
    foreach($diet_type as $diet){
        
        if($no_of_meals == 1){
            
            if($diet == 'lunch'){
                
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
            
        }else if($no_of_meals == 2){
            
            if($diet == 'breakfast' || $diet == 'dinner'){
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                    array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
        }else if($no_of_meals == 3){
            
            if($diet == 'breakfast' ||  $diet == 'snack-2' || $diet == 'dinner' ){
                
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                    array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
            
        }else if($no_of_meals == 4){
            
            if($diet == 'breakfast' || $diet == 'dinner' ||  $diet == 'snack-2' || $diet == 'lunch'){
                $key = $d.'_'.$diet;
                $cal_key = $diet.'_cal';
                $args = array(
                'post_type' => 'recipes',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                        array(
                            'taxonomy' => 'menu-type',
                            'terms' => $menu,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'diet-type',
                            'terms' => $diet,
                            'field' => 'slug',
                        ),
                        array(
                            'taxonomy' => 'recipe-days',
                            'terms' => $d,
                            'field' => 'slug',
                        ),
                    array(
                            'taxonomy' => 'week',
                            'terms' => $week,
                            'field' => 'slug',
                        ),
                    ),
                );
            }
            
        }else if($no_of_meals == 5){
            $key = $d.'_'.$diet;
        
            $cal_key = $diet.'_cal';

            $args = array(
            'post_type' => 'recipes',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array(
                    array(
                        'taxonomy' => 'menu-type',
                        'terms' => $menu,
                        'field' => 'slug',
                    ),
                    array(
                        'taxonomy' => 'diet-type',
                        'terms' => $diet,
                        'field' => 'slug',
                    ),
                    array(
                        'taxonomy' => 'recipe-days',
                        'terms' => $d,
                        'field' => 'slug',
                    ),
                    array(
                        'taxonomy' => 'week',
                        'terms' => $week,
                        'field' => 'slug',
                    ),
                ),
            );
        }

        $loop = new WP_Query( $args );
        $final = array();
        $kia = array();
        
        if($loop->have_posts()){
            while ($loop->have_posts()){
                $loop->the_post();
                
                if(!empty($allergy)){
                    
                    foreach($allergy as $allgy){
                        $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$allgy.'%" AND `post_id`='.$post->ID);
                        $tmps = (array) $data;
                        
                        $data_1 = $wpdb->get_row('SELECT * FROM `wp_posts` WHERE `post_title` LIKE "%'.$allgy.'%" AND `ID`='.$post->ID);
                        $tmp = (array) $data_1;
                        
                        if(count($data) == 0 && count($data_1) == 0){
                            $final[] = $post->ID;
                        }else{
                    
                            $subs = get_field('substitute_recepies',$post->ID);
                            $finsub = checkallergy($subs[0],$allergy);
                            
                            if($finsub != 0){
                                $final[] = $subs[0];
                            }else{
                                $subs_two = get_field('substitute_recepies',$subs[0]);
                                $finsub_two = checkallergy($subs_two[0],$allergy);
                                
                                if($finsub_two != 0){
                                    $final[] = $subs_two[0];
                                }else{
                                    
                                    $subs_three = get_field('substitute_recepies',$subs_two[0]);
                                    $finsub_three = checkallergy($subs_three[0],$allergy);
                                    
                                    if($finsub_three != 0){
                                        $final[] = $subs_three[0];
                                    }else{
                                        
                                        $subs_four = get_field('substitute_recepies',$subs_three[0]);
                                        $finsub_four = checkallergy($subs_four[0],$allergy);
                                        
                                        if($finsub_four != 0){
                                            $final[] = $subs_four[0];
                                        }else{
                                            $final[] = 'nishu';
                                        }
                                        
                                    }
                                }
                                
                            }
                        }
                        /** Array of Recipe id to unset **/
                        
                        if(count($data) != 0 || count($data_1) != 0){
                             $kia[] = $post->ID;
                        }
                        
                        /** Array of Recipe id to unset **/
                    }
                    
                }else{
                   
                     $final[] = $post->ID;
                }
                }
            }
            
        wp_reset_query();
        $final = array_unique($final);

        foreach($kia as $ki){
            if (($keyssssssssssssssssss = array_search($ki, $final)) !== false) {
                unset($final[$keyssssssssssssssssss]);
            }
        }
        
        foreach($final as $fin){
            if($fin != "nishu"){
            foreach($inc as $includes){
                if($selectCount == $acfCount){
                    
                    $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$includes.'%" AND `post_id`='.$fin);
                    if(!empty($data)){
                        $recipe_ids[$key] = $fin;
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }else{
                        $recipe_ids[$key] = $fin;
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }

                }else{
                    $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$includes.'%" AND `post_id`='.$fin);
                    if(!empty($data)){
                        $recipe_ids[$key] = $fin;
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }else{
                        $recipe_ids[$key] = $fin;
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }
                    
                }
                
            }
          }else{
            $recipes[$d][$key] = 'Substitue contains allergy';  
            $kilo_cal[$d][$diet] = 0;
            $change_recipe[$key] = array(0);
        } 

        }
        
    }
}

//$all_recipes = array_unique($recipe_ids);
//$all_recipes = array_values($all_recipes);

?>
  <main id="dash-main">
    <div class="get-fit-mainbox">
        <div class="heading">
            <h4>Shopping List</h4>
        </div>

        <div class="plans-form">
            <p>Using plans from:</p>
            <form method="get" class="shp-week-form">
                <input type="submit" name="prev-btn" value="<">
                <span><?php echo $PlanStartDate; ?> - <?php echo $PlanEndDate; ?></span>
                <input type="submit" name="next-btn" value=">">
                <?php
                $nextweekdates = $dateStart;
                for($j=1; $j<=$plan_duration; $j++){
                ?>
                <input type="hidden" name="week-<?php echo $j; ?>" value="<?php echo $nextweekdates; ?>">
                <?php 
                $nextweekdates = date('m/d/Y',strtotime($nextweekdates. ' + 7 day'));
                }
                ?>
                <input type="hidden" name="previous" value="<?php echo $weekPrev; ?>">
                <input type="hidden" name="current" value="<?php echo $weekCurr; ?>">
                <input type="hidden" name="next" value="<?php echo $weekNext; ?>">
                <input type="hidden" id="total_weeks" value="<?php echo $plan_duration; ?>">
            </form>
<!--
            <strong>
                
                <a href="?pre_dt=<?php echo base64_encode($previousStartDate); ?>&week=<?php echo base64_encode($week); ?>&st=<?php echo $previousStartDate; ?>&ed=<?php echo $previousEndDate; ?>" class="prev"><i class="fa fa-angle-left"></i></a>
                
                <?php echo $PlanStartDate; ?> - <?php echo $PlanEndDate; ?>
                
                <a href="?nex_dt=<?php echo base64_encode($PlanEndDate_stamp); ?>&week=<?php echo base64_encode($week); ?>&st=<?php echo $currentStartDate; ?>&ed=<?php echo $currentEndDate; ?>" class="next" id="next_week_shopping_list"><i class="fa fa-angle-right"></i></a>
            
            </strong>
-->
        </div>
        <?php
        
        $dairy_data = array();
        $dairy_display = array();
        
        $va_data = array();
        $va_display = array();
        
        $fr_data = array();
        $fr_display = array();
        
        $me_data = array();
        $me_display = array();
        
        $sh_data = array();
        $sh_display = array();
        
        $fa_data = array();
        $fa_display = array();
        
        $ot_data = array();
        $ot_display = array();
        
        $gr_data = array();
        $gr_display = array();
        
        $be_data = array();
        $be_display = array();
        
        $nu_data = array();
        $nu_display = array();
        
        foreach($recipe_ids as $recipes){
            
            $d_ps = get_field('diary_products',$recipes);
 
            foreach($d_ps as $chak){
                
                $kkey = $chak['diary_products_name'].'-'.$chak['diary_quantity_type'];
                
                if (array_key_exists($kkey,$dairy_data)){
                    
                $dairy_data[$kkey] = ($dairy_data[$kkey] + (float)$chak['diary_products_quantity']);
                    
                $dairy_display[$kkey] = $chak;
                    
                $dairy_display[$kkey]['diary_products_quantity'] = $dairy_data[$kkey];
                    
                }else{
                    
                $dairy_data[$kkey] = $chak['diary_products_quantity'];
                    
                $dairy_display[$kkey] = $chak;
                    
                $dairy_display[$kkey]['diary_products_quantity'] = $chak['diary_products_quantity'];
                }
            }
            
            $v_ps = get_field('vegetables',$recipes);
        
            foreach($v_ps as $va_value){
                
            $kkey = $va_value['vegetables_name'].'-'.$va_value['vegetables_quantity_type'];
                
                if(array_key_exists($kkey,$va_data)){

                    $va_data[$kkey] = ($va_data[$kkey] + $va_value['vegetables_quantity']);

                    $va_display[$kkey] = $va_value;

                    $va_display[$kkey]['vegetables_quantity'] = $va_data[$kkey];

                }else{

                    $va_data[$kkey] = $va_value['vegetables_quantity'];

                    $va_display[$kkey] = $va_value;

                    $va_display[$kkey]['vegetables_quantity'] = $va_value['vegetables_quantity'];

                }
                
            }
            
            //print_r($va_display);
            
            $f_ps = get_field('fruits',$recipes);
            foreach($f_ps as $fr_value){
                
                $kkey = $fr_value['fruits_name'].'-'.$fr_value['fruits_quantity_type'];
                
                if (array_key_exists($kkey,$fr_data)){
                    $fr_data[$kkey] = ($fr_data[$kkey] + (float)$fr_value['fruits_quantity']);
                    
                    $fr_display[$kkey] = $fr_value;
                    
                    $fr_display[$kkey]['fruits_quantity'] = $fr_data[$kkey];
                }else{
                    $fr_data[$kkey] = $fr_value['fruits_quantity'];
                    
                    $fr_display[$kkey] = $fr_value;
                    
                    $fr_display[$kkey]['fruits_quantity'] = $fr_value['fruits_quantity'];
                }
            }
            
            $m_ps = get_field('meat',$recipes);
            foreach($m_ps as $me_value){
                
                $kkey = $me_value['meat_name'].'-'.$me_value['meat_quantity_type'];
                
                if (array_key_exists($kkey,$me_data)){
                    
                    $me_data[$kkey] = ($me_data[$kkey] + (float)$me_value['meat_quantity']);
                    
                    $me_display[$kkey] = $me_value;
                    
                    $me_display[$kkey]['meat_quantity'] = $me_data[$kkey];
                    
                }else{
                    
                    $me_data[$kkey] = $me_value['meat_quantity'];
                    
                    $me_display[$kkey] = $me_value;
                    
                    $me_display[$kkey]['meat_quantity'] = $me_value['meat_quantity'];
                }
                
            }
            
            $sh_ps = get_field('spices_&_herbs',$recipes);
            
            foreach($sh_ps as $sh_value){

                $kkey = $sh_value['spices_&_herbs_name'].'-'.$sh_value['spices_&_herbs_quantity_type'];
                
                if (array_key_exists($kkey,$sh_data)){
                    
                    $sh_data[$kkey] = ($sh_data[$kkey] + (float)$sh_value['spices_&_herbs_quantity']);
                    
                    $sh_display[$kkey] = $sh_value;
                    
                    $sh_display[$kkey]['spices_&_herbs_quantity'] = $sh_data[$kkey];
                    
                }else{
                    
                    $sh_data[$kkey] = $sh_value['spices_&_herbs_quantity'];
                    
                    $sh_display[$kkey] = $sh_value;
                    
                    $sh_display[$kkey]['spices_&_herbs_quantity'] = $sh_value['spices_&_herbs_quantity'];
                }
            }
            
            $fa_oi = get_field('fats_&_oils',$recipes);
            foreach($fa_oi as $fa_value){
                
                $kkey = $fa_value['fats_&_oils_name'].'-'.$fa_value['fats_&_oils_quantity_type'];
                
                if (array_key_exists($kkey,$fa_data)){
                    
                    $fa_data[$kkey] = ($fa_data[$kkey] + (float)$fa_value['fats_&_oils_quantity']);

                    $fa_display[$kkey] = $fa_value;

                    $fa_display[$kkey]['fats_&_oils_quantity'] = $fa_data[$kkey];
                }else{
                    
                    $fa_data[$kkey] = $fa_value['fats_&_oils_quantity'];
                    
                    $fa_display[$kkey] = $fa_value;
                    
                    $fa_display[$kkey]['fats_&_oils_quantity'] = $fa_value['fats_&_oils_quantity'];
                }
            }
            
            $oth = get_field('other',$recipes);
            foreach($oth as $ot_value){
                
                $kkey = $ot_value['other_name'].'-'.$ot_value['quantity_type'];
                
                if (array_key_exists($kkey,$ot_data)){
                    $ot_data[$kkey] = ($ot_data[$kkey] + (float)$ot_value['other_quantity']);
                    $ot_display[$kkey] = $ot_value;
                    $ot_display[$kkey]['other_quantity'] = $ot_data[$kkey];
                }else{
                    $ot_data[$kkey] = $ot_value['other_quantity'];
                    $ot_display[$kkey] = $ot_value;
                    $ot_display[$kkey]['other_quantity'] = $ot_value['other_quantity'];
                }
            }
            
            $gr = get_field('grains_and_breads',$recipes);
            foreach($gr as $gr_value){
                
                $kkey = $gr_value['grains_and_breads_name'].'-'.$gr_value['grains_and_breads_quantity_type'];
                
                if (array_key_exists($kkey,$gr_data)){
                    $gr_data[$kkey] = ($gr_data[$kkey] + (float)$gr_value['grains_and_breads_quantity']);
                    
                    $gr_display[$kkey] = $gr_value;
                    
                    $gr_display[$kkey]['grains_and_breads_quantity'] = $gr_data[$kkey];
                }else{
                    $gr_data[$kkey] = $gr_value['grains_and_breads_quantity'];
                    
                    $gr_display[$kkey] = $gr_value;
                    
                    $gr_display[$kkey]['grains_and_breads_quantity'] = $gr_value['grains_and_breads_quantity'];
                }
            }
            
            $be = get_field('beans_&_legumes',$recipes);
            foreach($be as $be_value){
                
                $kkey = $be_value['beans_&_legumes_name'].'-'.$be_value['beans_&_legumes_quantity_type'];
                
                if (array_key_exists($kkey,$be_data)){
                    $be_data[$kkey] = ($be_data[$kkey] + (float)$be_value['beans_&_legumes_quantity']);
                    $be_display[$kkey] = $be_value;
                    $be_display[$kkey]['beans_&_legumes_quantity'] = $be_data[$kkey];
                }else{
                    $be_data[$kkey] = $be_value['beans_&_legumes_quantity'];
                    $be_display[$kkey] = $be_value;
                    $be_display[$kkey]['beans_&_legumes_quantity'] = $be_value['beans_&_legumes_quantity'];
                }
            }
            
            $nu = get_field('Nuts',$recipes);
            foreach($nu as $nu_value){
                
                $kkey = $nu_value['Nuts_name'].'-'.$nu_value['Nuts_quantity_type'];
                
                if (array_key_exists($kkey,$nu_data)){
                    $nu_data[$kkey] = ($nu_data[$kkey] + (float)$nu_value['Nuts_quantity']);
                    $nu_display[$kkey] = $nu_value;
                    $nu_display[$kkey]['Nuts_quantity'] = $nu_data[$kkey];
                }else{
                    $nu_data[$kkey] = $nu_value['Nuts_quantity'];
                    $nu_display[$kkey] = $nu_value;
                    $nu_display[$kkey]['Nuts_quantity'] = $nu_value['Nuts_quantity'];
                }
            }
            
        }

       ?>

        <div class="product-lists">
            <?php if(!empty($dairy_display)){ ?>
            <div class="single-list">
                <div class="heading">
                    <h4>Diary Products</h4>
                </div>
                <table>
                    <?php foreach($dairy_display as $key=>$d_p){ 
                    $x_key = explode('-',$key);
                    ?>
                    <tr>
                        <td>
                            <figure>
                                <img src="<?php echo $d_p['diary_products_image']; ?>">
                            </figure>

                            <div class="fig-content">
                                <h6><?php echo $x_key[0]; ?></h6>
                                <p><?php echo $d_p['diary_products_short_desc']; ?></p>
                            </div>
                        </td>
                        <td>
                            <h6><?php echo $d_p['diary_products_quantity'].' '.$d_p['diary_quantity_type']; ?></h6>
<!--                            <p><?php //echo $d_p['diary_products_grams']; ?></p>-->
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php }?>

            <?php if(!empty($va_display)){ ?>
            <div class="single-list">
              <div class="heading">
                  <h4>Vegetables</h4>
              </div>

              <table>
               <?php foreach($va_display as $key=>$v_p){ 
                  $x_key = explode('-',$key);
                  ?>
                  <tr>
                      <td>
                          <figure>
                              <img src="<?php echo $v_p['vegetables_image']; ?>">
                          </figure>
                          <div class="fig-content">
                              <h6><?php echo $x_key[0]; ?></h6>
                              <p><?php echo $v_p['vegetables_short_desc']; ?></p>
                          </div>
                      </td>
                      <td>
                          <h6><?php echo $v_p['vegetables_quantity'].' '.$v_p['vegetables_quantity_type']; ?></h6>
<!--                          <p><?php //echo $v_p['vegetables_grams']; ?></p>-->
                      </td>
                  </tr>
                  <?php } ?>
              </table>
          </div>
            <?php }?>

            <?php if(!empty($fr_display)){ ?>
            <div class="single-list">
            <div class="heading">
                <h4>Fruits</h4>
            </div>

            <table>
                <?php foreach($fr_display as $key=>$f_p){
                $x_key = explode('-',$key);
                ?>
                <tr>
                    <td>
                        <figure>
                            <img src="<?php echo $f_p['fruits_image']; ?>">
                        </figure>
                        <div class="fig-content">
                            <h6><?php echo $x_key[0]; ?></h6>
                            <p><?php echo $f_p['fruits_short_desc']; ?></p>
                        </div>
                    </td>
                    <td>
                        <h6><?php echo $f_p['fruits_quantity'].' '.$f_p['fruits_quantity_type']; ?></h6>
<!--                        <p><?php //echo $f_p['fruits_grams']; ?></p>-->
                    </td>
                </tr>
                <?php } ?>
               
            </table>
        </div>
            <?php }?>

            <?php if(!empty($me_display)){ ?>
            <div class="single-list">
              <div class="heading">
                  <h4>Meat</h4>
              </div>

              <table>
                  <?php foreach($me_display as $key=>$m_p){
                  $x_key = explode('-',$key);
                  ?>
                  <tr>
                      <td>
                          <figure>
                              <img src="<?php echo $m_p['meat_image']; ?>">
                          </figure>
                          <div class="fig-content">
                              <h6><?php echo $x_key[0]; ?></h6>
                              <p><?php echo $m_p['meat_short_desc']; ?></p>
                          </div>
                      </td>
                      <td>
                        <h6><?php echo $m_p['meat_quantity'].' '.$m_p['meat_quantity_type']; ?></h6>
<!--                        <p><?php //echo $m_p['meat_grams']; ?></p>-->
                      </td>
                  </tr>
                  <?php } ?>
              </table>
          </div>
            <?php }?>

            <?php if(!empty($sh_display)){ ?>
            <div class="single-list">
                    <div class="heading">
                        <h4>Spices & Herbs</h4>
                    </div>

                    <table>
                        <?php foreach($sh_display as $key=>$s_p){ 
                        $x_key = explode('-',$key);
                        ?>
                        <tr>
                            <td>
                                <figure>
                                    <img src="<?php echo $s_p['spices_&_herbs_image']; ?>">
                                </figure>
                                <div class="fig-content">
                                    <h6><?php echo $x_key[0]; ?></h6>
                                    <p><?php echo $s_p['spices_&_herbs_short_desc']; ?></p>
                                </div>
                            </td>
                            <td>
                                <h6><?php echo $s_p['spices_&_herbs_quantity'].' '.$s_p['spices_&_herbs_quantity_type']; ?></h6>
    <!--                            <p><?php //echo $s_p['spices_&_herbs_grams']; ?></p>-->
                            </td>
                        </tr>
                        <?php } ?>

                    </table>
                </div>
            <?php }?>

            <?php if(!empty($fa_display)){ ?>
            <div class="single-list">
              <div class="heading">
                  <h4>Fats & Oils</h4>
              </div>

              <table>
                  <?php foreach($fa_display as $key=>$f_p){ 
                  $x_key = explode('-',$key);
                  ?>
                  <tr>
                      <td>
                          <figure>
                              <img src="<?php echo $f_p['fats_&_oils_image']; ?>">
                          </figure>

                          <div class="fig-content">
                              <h6><?php echo $x_key[0]; ?></h6>
                              <p><?php echo $f_p['fats_&_oils_short_desc']; ?></p>
                          </div>
                      </td>


                      <td>
                          <h6><?php echo $f_p['fats_&_oils_quantity'].' '.$f_p['fats_&_oils_quantity_type']; ?></h6>
<!--                          <p><?php //echo $s_p['fats_&_oils_grams']; ?></p>-->
                      </td>
                  </tr>
                  <?php
                   }
                  ?>

              </table>
            </div>
            <?php }?>

            <?php if(!empty($gr_display)){ ?>
            <div class="single-list">
                <div class="heading">
                    <h4>GRAINS AND BREADS</h4>
                </div>

                <table>
                    <?php
                    foreach($gr_display as $key=>$gr_p){
                    $x_key = explode('-',$key);
                    ?>
                    <tr>
                        <td>
                            <figure>
                                <img src="<?php echo $gr_p['grains_and_breads_image']; ?>">
                            </figure>
                            <div class="fig-content">
                                <h6><?php echo $x_key[0]; ?></h6>
                                <p><?php echo $gr_p['grains_and_breads_short_desc']; ?></p>
                            </div>
                        </td>
                        <td>
                            <h6><?php echo $gr_p['grains_and_breads_quantity'].' '.$gr_p['grains_and_breads_quantity_type'];  ?></h6>
<!--                            <p><?php //echo $o_p['other_grams']; ?></p>-->
                        </td>
                    </tr>
                    <?php 
                    }
                    ?>
                </table>
            </div>
            <?php }?>
            
            <?php if(!empty($be_display)){?>
            <div class="single-list">
                <div class="heading">
                    <h4>BEANS & LEGUMES</h4>
                </div>

                <table>
                    <?php
                    foreach($be_display as $key=>$be_p){
                        $x_key = explode('-',$key);
                    ?>
                    <tr>
                        <td>
                            <figure>
                                <img src="<?php echo $be_p['beans_&_legumes_image']; ?>">
                            </figure>
                            <div class="fig-content">
                                <h6><?php echo $x_key[0]; ?></h6>
                                <p><?php echo $be_p['beans_&_legumes_short_desc']; ?></p>
                            </div>
                        </td>
                        <td>
                            <h6><?php echo $be_p['beans_&_legumes_quantity'].' '.$be_p['beans_&_legumes_quantity_type'];; ?></h6>
<!--                            <p><?php //echo $o_p['other_grams']; ?></p>-->
                        </td>
                    </tr>
                    <?php 
                    }
                    ?>
                </table>
            </div>
            <?php }?>
            
            <?php if(!empty($nu_display)){?>
            <div class="single-list">
                <div class="heading">
                    <h4>Nuts</h4>
                </div>

                <table>
                    <?php
                    foreach($nu_display as $key=>$nu_p){
                        $x_key = explode('-',$key);
                    ?>
                    <tr>
                        <td>
                            <figure>
                                <img src="<?php echo $nu_p['Nuts_image']; ?>">
                            </figure>
                            <div class="fig-content">
                                <h6><?php echo $x_key[0]; ?></h6>
                                <p><?php echo $nu_p['Nuts_short_desc']; ?></p>
                            </div>
                        </td>
                        <td>
                            <h6><?php echo $nu_p['Nuts_quantity'].' '.$nu_p['Nuts_quantity_type']; ?></h6>
<!--                            <p><?php //echo $o_p['other_grams']; ?></p>-->
                        </td>
                    </tr>
                    <?php 
                    }
                    ?>
                </table>
            </div>
            <?php }?>
            
            <?php if(!empty($ot_display)){?>
            <div class="single-list">
                <div class="heading">
                    <h4>Other</h4>
                </div>

                <table>
                    <?php
                    foreach($ot_display as $key=>$o_p){
                        $x_key = explode('-',$key);
                    ?>
                    <tr>
                        <td>
                            <figure>
                                <img src="<?php echo $o_p['other_image']; ?>">
                            </figure>
                            <div class="fig-content">
                                <h6><?php echo $x_key[0]; ?></h6>
                                <p><?php echo $o_p['other_short_desc']; ?></p>
                            </div>
                        </td>
                        <td>
                            <h6><?php echo $o_p['other_quantity'].' '.$o_p['quantity_type']; ?></h6>
<!--                            <p><?php //echo $o_p['other_grams']; ?></p>-->
                        </td>
                    </tr>
                    <?php 
                    }
                    ?>
                </table>
            </div>
            <?php }?>

        </div>
        
    </div>
  </main>
<?php 
get_footer('dashboard');
?>