<?php 
/*
Template Name: Result Female
*/
session_start();
get_header();
global $wpdb;

$mt = explode(',',$_GET['meat_arr']);

$veg = explode(',',$_GET['vege_arr']);
$fruit = explode(',',$_GET['fruits_arr']);
$grain = explode(',',$_GET['grains_arr']);
$dairy = explode(',',$_GET['dairy_arr']);
$beans = explode(',',$_GET['beans_arr']);
if($_GET['allergies_arr'] != ''){
    $allergyy = explode(',',$_GET['allergies_arr']);
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
if(!in_array('Fish',$mt)){
    $allergy[] = "Salmon";
}
if(!in_array('Shellfish',$allergyy)){
    $allergy[] = "SHRIMP";
    $allergy[] = "SQUID";
    $allergy[] = "SCALLOPS";
    $allergy[] = "PRAWN";
    $allergy[] = "MUSSELS";
}
if(!in_array('Peanuts',$allergyy)){
    $allergy[] = "nuts";
}
if(!in_array('Milk',$dairy)){
    $allergy[] = "kefir";
}

$_SESSION['aleg'] = implode(',',$allergy);

$acf_input = array($meat,$vegi,$fru,$gra,$dai,$bea);

$inc_acf = call_user_func_array("array_merge", $acf_input);

$acfCount = count($inc_acf);

/**** Acf fields end ****/


$i_am = $_GET['gender'];
$i_want_to = $_GET['weight'];
$current_activity = $_GET['activity'];
$meat = $_GET['meat_arr'];
$no_of_meals = $_GET['meal'];
$show_message = false;

$im_target_weight = (int) $_GET['imperial_target_weight'];
$me_target_weight = (int) $_GET['metric_target_weight'];

$im_current_weight = (int) $_GET['imperial_weight'];
$me_current_weight = (int) $_GET['metric_weight'];

if($im_target_weight != ''){
    
    if($im_target_weight < $im_current_weight){
        $show_message = true;
        $calculated_weight = (int)$_GET['imperial_weight'] - (int)$_GET['imperial_target_weight'];
        $calculated_weight = ($calculated_weight/100)*80;
    }
    
}else if($me_target_weight != ''){

    if($me_target_weight < $me_current_weight){
        $show_message = true;
        $calculated_weight = (int)$_GET['metric_weight'] - (int)$_GET['metric_target_weight'];
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
$days = array('monday','tuesday','wednesday');
//$days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');

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
                            'terms' => 'week-1',
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
                            'terms' => 'week-1',
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
                            'terms' => 'week-1',
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
                            'terms' => 'week-1',
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
                        'terms' => 'week-1',
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
                    
//                    echo $fin.' ';
                    
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
                    
                    
//                    if(empty($recipes[$d][$key])){
////                        $recipes[$d][$key] = 'N/A';  
////                        $kilo_cal[$d][$diet] = 0;
////                        $change_recipe[$key] = 0;
//                        $subs = get_field('substitute_recepies',$fin);
//                        
//                        if($subs != ''){
//                            $recipes[$d][$key] = get_the_title($subs[0]->ID);  
//                            $kilo_cal[$d][$diet] = get_field('recipe_calories',$subs[0]->ID);
//                            $change_recipe[$key] = array($subs[0]->ID);
//                        }else{
//                            $recipes[$d][$key] = 'N/A';  
//                            $kilo_cal[$d][$diet] = 0;
//                            $change_recipe[$key] = 0;
//                        }
//                    }
//                    $recipes[$d][$key] = get_the_title($fin);  
//                    $kilo_cal[$d][$diet] = get_field('recipe_calories',$fin);
//                    $change_recipe[$key] = array($fin);
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
                    
                    
//                    if(empty($recipes[$d][$key])){
////                        $recipes[$d][$key] = 'N/A';  
////                        $kilo_cal[$d][$diet] = 0;
////                        $change_recipe[$key] = 0;
//                        $subs = get_field('substitute_recepies',$fin);
//                        
//                        if($subs != ''){
//                            $recipes[$d][$key] = get_the_title($subs[0]->ID);  
//                            $kilo_cal[$d][$diet] = get_field('recipe_calories',$subs[0]->ID);
//                            $change_recipe[$key] = array($subs[0]->ID);
//                        }else{
//                            $recipes[$d][$key] = 'N/A';  
//                            $kilo_cal[$d][$diet] = 0;
//                            $change_recipe[$key] = 0;
//                        }
//                    }
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

/***** getting recipes end *****/

$selectalg = $_GET['allergies'];

if($selectalg == ''){
    $selectalg = 0;
}


/**** Acf fields start ****/
$bea = get_field('beans',72);
$beanotselect = [];
if(!empty($bea)){
    foreach($bea as $be){
        if(!in_array($be['beans_name'],$beans)){
            $beanotselect[] = $be['beans_name'];
        }
        
    }
}
$cookiebea = implode(',',$beanotselect);

/**** Acf fields start ****/

/** Get Cookies Value **/
$cookieale = $_COOKIE['allergies'];
if(!empty($cookieale)){
    $alcook = explode(',',$cookieale);
    if($alcook[0] != 'null'){
        $alcount = count($alcook);
    }else{
        $alcount = 0;
    }
}

if($alcount == ''){
    $alcount = 0;
}

$checkremove = ($remove - $alcount);
if($checkremove > 0){
   $preUrl = modify_url(array('removed' => ($remove - $alcount)), $_SERVER['QUERY_STRING']); 
}else{
    $preUrl = modify_url(array('removed' => 0), $_SERVER['QUERY_STRING']);
}

?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>\css\quiz-result.css">

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri();?>/images/home-banner.jpg');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>

    <section class="how-it-works result-male">
        <div class="container">
            <div class="heading">
                <h2>Meal Planner</h2>
            </div>
            <?php if($show_message){ ?>
            <div class="loss-weight">
                <p>88% similar people lost more then 4 kg</p>
                <p>Based on your answers, you could lose up to <?php echo $calculated_weight; ?>kg in 6 weeks</p>
<!--                <p>Based on your answers, you could loss up to <?php //echo $calculated_weight; ?>kg in 6 weeks</p>-->
            </div>
            <?php }?>
         <div class="meal-banner">
             <figure>
                 <img src="<?php echo get_template_directory_uri();?>/assets/images/mealll.png">
             </figure>

             <div class="row">
                 
                 <?php 
                 $modal_recipe_ids = array();
                 foreach($recipes as $key=>$res){
                     $sd = explode('_',$key);
                     $day_name = substr($sd[0],0,3);
                     $day_name = strtoupper($day_name);
                     
                 ?>
                 <div class="col-md-12 col-lg-6 mrg-btm">
                     <div class="male-table">
                     <div class="heading">
                         <h3><?php echo $day_name; ?></h3>
                     </div>
                     <table>
                         <?php 
                             $i=0;
                             $graph_data = '';
                             $graph_domain = '';
                             $graph_cal = '';
                             
                             foreach($res as $r_key=>$r){ 
                             $dietType = explode("_",$r_key);
                             $diet = $dietType[1];
                            
                             if (strpos($diet, '-') !== false) {
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
                         <td class="<?php echo $r_key; ?>_name"><a href="javascript:void(0);" onclick="show_popup('<?php echo $change_recipe[$r_key][0]; ?>');"><?php echo $r; ?></a></td>
                         <td class="<?php echo $r_key; ?>_link youtube_link"> 
                             <?php 

                            $r_image = wp_get_attachment_url(get_post_thumbnail_id($change_recipe[$r_key][0]));
                                if($r_image == ''){
                                    $r_image = get_template_directory_uri().'/images/logo-icon.jpg';
                                }
                            ?>
                             <a href="<?php if($r_link != ''){ echo $r_link; }else{ echo 'javascript:void(0);'; }?>" target="_blank"><?php if($r_link != ''){ ?><span><i class="fa fa-youtube-play"></i></span><?php }?><img src="<?php echo $r_image; ?>"></a>
                         </td>
                             
                         <td><a href="javascript:void(0);" onclick="change_recipe(<?php echo $change_recipe[$r_key][0]; ?>,'<?php echo $r_key; ?>','<?php echo $day_name; ?>');" ><i class="fa fa-refresh" aria-hidden="true"></i></a></td>

                         <input type="hidden" class="<?php echo $r_key; ?>_count" value="0">
                             
                         <input type="hidden" id="<?php echo $r_key; ?>_calories_val" value="<?php echo $kilo_cal[$sd[0]][$dietType[1]]; ?>" class="<?php echo $day_name; ?> <?php echo $day_name.'_'.$i; ?>">
                             
                         <input type="hidden" id="<?php echo $r_key; ?>_diet_val" value="<?php echo $diet; ?>" class="<?php echo $day_name.'_diet'; ?> <?php echo $day_name.'diet_'.$i; ?>">
                             
                         </tr>
                         <?php
                                 
                             $dtype = $dietType[1];
                             $graph_data .= 'a'.$i.':'.$kilo_cal[$sd[0]][$dietType[1]].',';
                             $graph_domain .= '"a'.$i.'",';
                             if (strpos($dietType[1], '-') !== false) {
                             if(strpos($dietType[1], '1') !== false || strpos($dietType[1], '2') !== false){
                                 $dtype = 'snack';
                             }
                             }
                                 
                             $graph_cal .= '"'.$dtype.' '. $kilo_cal[$sd[0]][$dietType[1]].' kcal ",';
                                $i++;
                                 
                             }
                    
                         ?>
                         
                     </table>
                    </div>
                 </div>
                 <div class="col-md-12 col-lg-6">
                     <div class="chart">
                         <div class="heading">
                             <h4><?php echo $day_name; ?> kcal chart</h4>
                         </div>
                         <!-- Create a div where the graph will take place -->
                          <div id="my_dataviz_<?php echo $day_name; ?>" class="graph" style="text-align: center; position: relative;">
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
      var svg = d3.select("#my_dataviz_<?php echo $day_name; ?>")
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
          })
       
          
          // Add the polylines between chart and labels:
      
      
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
          })
       
       
          
   </script>
                 
                 <?php }?>
            

                <div class="prvs-order-btns">
                    <a href="<?php echo get_the_permalink(25).'?'.$preUrl; ?>" class="btn" >Previous</a>
                    <a href="<?php echo get_the_permalink(14); ?>?<?php echo $_SERVER['QUERY_STRING']; ?>" class="btn">Get Your Plan</a>
<!--                    <button type="submit" class="btn">Order</button>-->
                </div>
             </div>
         </div>
        </div>
    </section>
  </main>

   <script>
      // set the dimensions and margins of the graph
      var width = 400
          height = 400
          margin = 40
      
      // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
      var radius = Math.min(width, height) / 2 - margin
      
      // append the svg object to the div called 'my_dataviz'
      var svg = d3.select("#my_dataviz_tue")
        .append("svg")
          .attr("width", width)
          .attr("height", height)
        .append("g")
          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

      
      // Create dummy data
       
      var data = {a: <?php echo $kilo_cal['tuesday']['breakfast']; ?>, b: <?php echo $kilo_cal['tuesday']['snack-1']; ?>, c:<?php echo $kilo_cal['tuesday']['lunch']; ?>, d:<?php echo $kilo_cal['tuesday']['snack-2']; ?>, e:<?php echo $kilo_cal['tuesday']['dinner']; ?>}
      
      var textdata = ["Breakfast <?php echo $kilo_cal['tuesday']['breakfast']; ?> kcal ","Snack1 <?php echo $kilo_cal['tuesday']['snack-1']; ?> kcal","Lunch <?php echo $kilo_cal['tuesday']['lunch']; ?> kcal","Snack2 <?php echo $kilo_cal['tuesday']['snack-2']; ?> kcal","Dinner <?php echo $kilo_cal['tuesday']['dinner']; ?> kcal"];
      
      // set the color scale
      var color = d3.scaleOrdinal()
        .domain(["a", "b", "c", "d", "e"])
        .range(d3.schemeDark2);
      
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
          })
          
          // Add the polylines between chart and labels:
      
      
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
          })
          
   </script>

   <script>
      // set the dimensions and margins of the graph
      var width = 400
          height = 400
          margin = 40
      
      // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
      var radius = Math.min(width, height) / 2 - margin
      
      // append the svg object to the div called 'my_dataviz'
      var svg = d3.select("#my_dataviz_wed")
        .append("svg")
          .attr("width", width)
          .attr("height", height)
        .append("g")
          .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

      
      // Create dummy data
      var data = {a: <?php echo $kilo_cal['wednesday']['breakfast']; ?>, b: <?php echo $kilo_cal['wednesday']['snack-1']; ?>, c:<?php echo $kilo_cal['wednesday']['lunch']; ?>, d:<?php echo $kilo_cal['wednesday']['snack-2']; ?>, e:<?php echo $kilo_cal['wednesday']['dinner']; ?>}
      
      // set the color scale
      var color = d3.scaleOrdinal()
        .domain(["a", "b", "c", "d", "e"])
        .range(d3.schemeDark2);
       
       var textdata = ["Breakfast <?php echo $kilo_cal['wednesday']['breakfast']; ?> kcal ","Snack1 <?php echo $kilo_cal['wednesday']['snack-1']; ?> kcal","Lunch <?php echo $kilo_cal['wednesday']['lunch']; ?> kcal","Snack2 <?php echo $kilo_cal['wednesday']['snack-2']; ?> kcal","Dinner <?php echo $kilo_cal['wednesday']['dinner']; ?> kcal"];
      
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
          })
          
          // Add the polylines between chart and labels:
      
      
      // Add the polylines between chart and labels:
      svg
        .selectAll('allLabels')
        .data(data_ready)
        .enter()
        .append('text')
          .attr("class", 'text')
          .text( function(d,i) { 
          console.log(d.data.key); 
          return textdata[i]; } )
          .attr('transform', function(d) {
              var pos = outerArc.centroid(d);
              var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
              pos[0] = radius * 0.99 * (midangle < Math.PI ? 1 : -1);
              return 'translate(' + pos + ')';
          })
          .style('text-anchor', function(d) {
              var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
              return (midangle < Math.PI ? 'start' : 'end')
          })
          
   </script>

<!-- Modal Start -->
    <div class="recipe_modal modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    </div>
<!-- Modal End -->
<?php
get_footer();
?>