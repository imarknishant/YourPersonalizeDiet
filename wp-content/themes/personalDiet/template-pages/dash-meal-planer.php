<?php
/* Template Name: dash meal planer */
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

session_start();

get_header('dashboard');
global $wpdb;

$Days = array();
$Dates = array();
$dayName = array();
$days = array();
$allergy = array();

$userid = get_current_user_id();

$meal_data = $wpdb->get_results("SELECT * FROM plan_payments WHERE user_id = $userid ORDER BY ID DESC LIMIT 1");
$plan_duration = $meal_data[0]->plan_duration;

$dateStart = date('m/d/Y',strtotime($meal_data[0]->date_time));
for($j=0; $j<7; $j++){
    $dayName[] =  date('D',strtotime($meal_data[0]->date_time.' + '.$j.' day'));
//    $days[] = date('l',strtotime($meal_data[0]->date_time.' + '.$j.' day'));
}
$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

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

//$mt = explode(',',$meals['meat_arr']);
//$veg = explode(',',$meals['vege_arr']);
//$fruit = explode(',',$meals['fruits_arr']);
//$grain = explode(',',$meals['grains_arr']);
//$dairy = explode(',',$meals['dairy_arr']);
//$beans = explode(',',$meals['beans_arr']);
//$allergy = explode(',',$meals['allergies_arr']);

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

print_r($allergy);

if(in_array('Fish',$allergy) && !empty($allergy)){
    $allergy[] = "Salmon";
}
if(in_array('Shellfish',$allergy) && !empty($allergy)){
    $allergy[] = "SHRIMP";
    $allergy[] = "SQUID";
    $allergy[] = "SCALLOPS";
    $allergy[] = "PRAWN";
    $allergy[] = "MUSSELS";
}
if(in_array('Peanuts',$allergy) && !empty($allergy)){
    $allergy[] = "nuts";
}
if(in_array('Milk',$allergy) && !empty($allergy)){
    $allergy[] = "kefir";
}

if(empty($allergy)){
   $_SESSION['aleg'] = implode(',',$allergy); 
}


$acf_input = array($meat,$vegi,$fru,$gra,$dai,$bea);

$inc_acf = call_user_func_array("array_merge", $acf_input);

$acfCount = count($inc_acf);

print_r($allergy);

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

echo $menu .' fffffffff ';
/***** getting recipes start *****/
foreach($days as $d){
    
    echo $d.' frddrfrdf ';
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
                
                
                echo $post->ID .' sssss ';
                if(!empty($allergy)){
                    
                    foreach($allergy as $allgy){
                        $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$allgy.'%" AND `post_id`='.$post->ID);
                        $tmps = (array) $data;
                        
                        $data_1 = $wpdb->get_row('SELECT * FROM `wp_posts` WHERE `post_title` LIKE "%'.$allgy.'%" AND `ID`='.$post->ID);
                        $tmp = (array) $data_1;
                    
                        
                        if(!empty($data) || !empty($data_1)){
                            
                            $subs = get_field('substitute_recepies',$post->ID);

                            $finsub = (int)checkallergy($subs[0],$allergy);
                            
                            if($finsub == 0){
                                $final[] = $subs[0];
                            }else{                              
                                $subs_two = get_field('substitute_recepies',$subs[0]);
                                
                                $finsub_two = checkallergy($subs_two[0],$allergy);
                                
                                if($finsub_two == 0){
                                    $final[] = $subs_two[0];
                                }else{
                                    
                                    $subs_three = get_field('substitute_recepies',$subs_two[0]);
                                    $finsub_three = checkallergy($subs_three[0],$allergy);
                                    
                                    if($finsub_three == 0){
                                        
                                        $final[] = $subs_three[0];
                                    }else{
                                        
                                        $subs_four = get_field('substitute_recepies',$subs_three[0]);
                                        $finsub_four = checkallergy($subs_four[0],$allergy);
                                        
                                        if($finsub_four == 0){
                                            
                                            $final[] = $subs_four[0];
                                        }else{
                                            $final[] = 'nishu';
                                        }
                                        
                                    }
                                }
                                
                            }
                            
                        }else{
                           $final[] = $post->ID;
                            
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
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }else{
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }

                }else{
                    $data = $wpdb->get_row('SELECT * FROM `wp_postmeta` WHERE `meta_value` LIKE "%'.$includes.'%" AND `post_id`='.$fin);
                    if(!empty($data)){
                        $recipes[$d][$key] = get_the_title($fin);  
                        $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
                        $change_recipe[$key] = array($fin);
                    }else{
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

?>
<!--<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">-->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>\css\quiz-result.css">
<style>
.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
</style>
  <main id="dash-main">
    <div class="get-fit-mainbox">

        <div class="dash-meal-plan">
        <div class="heading dash-head">
            <h4>Meal Planner</h4>
            <h6 id="meal_dates"><?php echo $PlanStartDate; ?> - <?php echo $PlanEndDate; ?></h6>
        <ul>
                <li>
                    <div class="dropdown">
                      <button class="sort">Weekly View</button>
                      <div class="dropdown-content">
                          <?php 
                          $nextweekdates = $dateStart;
                          for($a=1; $a<=$plan_duration; $a++){ 
                              $prweek = $a;
                              $nxweek = $a;
                              
                              $c_w = explode('-',$weekCurr);
                              
                              if($c_w[1] > $a){
                                  $btntext = 'prev';
                              }else{
                                  $btntext = 'next';
                              }
                          ?>
                          <a href="?<?php echo $btntext; ?>-btn=>&week-<?php echo $a; ?>=<?php echo $nextweekdates; ?>&previous=week-<?php echo $prweek; ?>&current=week-<?php echo $a; ?>&next=week-<?php echo $nxweek; ?>">Week <?php echo $a; ?></a>
                          <?php 
                           $nextweekdates = date('m/d/Y',strtotime($nextweekdates. ' + 7 day')); 
                        
                            $prweek--;
                           }?>
                      </div>
                    </div>
<!--                    <button type="button" class="sort">Weekly View</button></li>-->
            <li>
            <form method="get" class="shp-week-form">
                <input type="submit" name="prev-btn" value="<">
                <span><a href="<?php echo get_the_permalink(19); ?>">Jump to today</a></span>
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
            </li>
        </ul>

        </div>
    
        <div class="dash-meal-table">
            <div class="row">
            <?php 
                $k=0;
                $modal_recipe_ids = array();
                 foreach($recipes as $key=>$res){
                     $sd = explode('_',$key);
                     $day_name = substr($sd[0],0,3);
                     $day_name = strtoupper($day_name);
                     
                 ?>
             <div class="col-md-12 col-lg-6 mrg-btm">
            <div class="male-table">
                <div class="heading">
                    <h3><?php echo $dayName[$k]; ?>, <?php echo $Dates[$k]; ?></h3>
                </div>
                <table>
                    <tbody>
                        <?php 
                             $i=0;
                             $graph_data = '';
                             $graph_domain = '';
                             $graph_cal = '';
                             $calories_val = array();
                             
                             $collarray = array();
                             foreach($res as $r_key=>$r){
                                 $collarray[$dayName[$k]][] = $change_recipe[$r_key][0];
                             $dietType = explode("_",$r_key);
                             $diet = $dietType[1];
                             if(strpos($diet, '-') !== false){
                                 if(strpos($diet, '1') !== false || strpos($diet, '2') !== false){
                                     $diet = 'snack';
                                 }
                             }else{
                                 $diet = $diet;
                             }
                             $r_link = get_field('you_tube_link',$change_recipe[$r_key][0]);
                             $modal_recipe_ids[$day_name][] = $change_recipe[$r_key][0];
                             ?>
                    <tr>
                         <td ><?php echo $diet; ?></td>
                         <td class="<?php echo $r_key; ?>_name"><a href="javascript:void(0);" onclick="show_popup('<?php echo $change_recipe[$r_key][0]; ?>');" ><?php echo $r; ?></a></td>
                         <td class="<?php echo $r_key; ?>_link"> 
                         <?php 

                            $r_image = wp_get_attachment_url(get_post_thumbnail_id($change_recipe[$r_key][0]));
                                if($r_image == ''){
                                    $r_image = get_template_directory_uri().'/images/logo-icon.jpg';
                                }
                            ?>
                        <a href="<?php if($r_link != ''){ echo $r_link; }else{ echo 'javascript:void(0);'; }?>" target="_blank"><?php if($r_link != ''){ ?><span><i class="fa fa-youtube-play"></i></span><?php }?><img src="<?php echo $r_image; ?>"></a>
                         </td>
                         <td><a href="javascript:void(0);" onclick="change_recipe(<?php echo $change_recipe[$r_key][0]; ?>,'<?php echo $r_key; ?>','<?php echo $dayName[$k]; ?>');" ><i class="fa fa-refresh" aria-hidden="true"></i></a></td>
                         <input type="hidden" class="<?php echo $r_key; ?>_count" value="0">
                         <input type="hidden" id="<?php echo $r_key; ?>_calories_val" value="<?php echo $kilo_cal[$sd[0]][$dietType[1]]; ?>" class="<?php echo $dayName[$k]; ?> <?php echo $dayName[$k].'_'.$i; ?>">
                        <input type="hidden" id="<?php echo $r_key; ?>_diet_val" value="<?php echo $diet; ?>" class="<?php echo $dayName[$k].'_diet'; ?> <?php echo $dayName[$k].'diet_'.$i; ?>">
                    </tr>
                <?php
                                 
                    $dtype = $dietType[1];
                    $graph_data .= 'a'.$i.':'.$kilo_cal[$sd[0]][$dietType[1]].',';
                    $graph_domain .= '"a'.$i.'",';
                    if (strpos($dietType[1], '-') !== false){
                         if(strpos($dietType[1], '1') !== false || strpos($dietType[1], '2') !== false){
                             $dtype = 'snack';
                         }
                     }
                    $graph_cal .= '"'.$dtype.' '. $kilo_cal[$sd[0]][$dietType[1]].' kcal ",';
                    $i++;
                }
                   
                ?>
                 <input type="hidden" id="<?php echo $dayName[$k]; ?>" value='<?php echo serialize($collarray); ?>'>
                </tbody>
                </table>
               </div>
            </div>
            <div class="col-md-12 col-lg-6">
             <div class="chart">
                 <div class="heading">
                     <h4><?php echo $dayName[$k]; ?> kcal chart</h4>
                 </div>
                 <!-- Create a div where the graph will take place -->
                  <div id="my_dataviz_<?php echo $dayName[$k]; ?>" class="graph" style="text-align: center; position: relative;">
                     <figure class="center-logo"><img src="<?php echo get_template_directory_uri(); ?>/images/logoo.jpg" alt="logoo"></figure>
                  </div>

             </div>
         </div>
            
            <!-- Load d3.js & color scale -->
<script src="https://d3js.org/d3.v4.js"></script>
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<script>
      // set the dimensions and margins of the graph
      var width = 400
          height = 400
          margin = 40
      
      // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
      var radius = Math.min(width, height) / 2 - margin
      
      // append the svg object to the div called 'my_dataviz'
      var svg = d3.select("#my_dataviz_<?php echo $dayName[$k]; ?>")
          .append("svg")
          .attr("width", width)
          .attr("height", height)
          .append("g")
          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
      
      // Create dummy data   
      var data = {<?php echo $graph_data; ?>}

      // set the color scale
      var color = d3.scaleOrdinal()
        .domain([<?php echo $graph_domain; ?>])
        .range(d3.schemeDark2);
       
       var textdata = [<?php echo $graph_cal; ?>];
      
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
          console.log(d.data.key); 
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
   </script>
        <?php
                $k++;
            }
        ?>
            </div>
    </div>
</div>
      
</div>
</main>
<!-- Modal Start -->
    <div class="recipe_modal modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    </div>
<!-- Modal End -->
<?php
get_footer('dashboard');
?>