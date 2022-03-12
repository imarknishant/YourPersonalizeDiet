<?php
/* Template Name: calculator */
get_header();
while(have_posts()): the_post();
?>

  <main id="main">
    <section class="home-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/home-banner.jpg');"  data-aos="fade-left" data-aos-duration="1500">
      <div class="container">
        <div class="home-banner-content">
          <h1>YOUR MEAL <span>PLANNER</span></h1>
        </div>
      </div>
    </section>


   <section class="calculator">
       <div class="container">
           <div class="heading">
               <h2>Calorie Calculator</h2>
           </div>

           <?php the_content(); ?>

           <div class="calculator-result">
               <div class="row">
                   <div class="col-lg-5">
                       <div class="calculator-box">
                           <div class="heading">
                               <h3>Calculator</h3>
                           </div>
                           <form id="bms_calculator" method="post">
                               <div class="form-group">
                                   <label>Gender</label>
                                   <select class="form-control" name="gender">
                                       <option value="male">Male</option>
                                       <option value="female">Female</option>
                                   </select>
                               </div>

                               <div class="form-group">
                                   <label>Age</label>
                                   <input type="number" class="form-control" placeholder="30 Years" name="age">
                               </div>

                               <div class="form-group">
                                <label>Height</label>
                                <input type="number" class="form-control" placeholder="15.24 cm" name="height">
                            </div>

                            <div class="form-group">
                                <label>Weight</label>
                                <input type="number" class="form-control" placeholder="40 kg" name="weight">
                            </div>

                            <div class="calculate-clear-btns">
                                <input type="submit" class="btn" value="Calculate">
                                <input type="reset" class="btn" value="Clear">
                                <input type="hidden" value="calculate_bms" name="action">
<!--
                                <button type="submit" class="btn">Calculate</button>
                                <button type="submit" class="btn">Clear</button>
-->
                            </div>
                           </form>
                       </div>
                   </div>
                   <div class="col-lg-7">
                       <div class="result-box">
                           
                           <div class="heading">
                               <h3>Result</h3>
                           </div>
                           <div class="result-box-content">
<!--                           <h4>Body Fat: 0.0%</h4>-->
                           <table>
                            <tr>
                                <td>Maintain Weight</td>
                                <td id="maintain_w_v">0 Calories/day</td>
                            </tr>

                            <tr>
                                <td>Lose Weight</td>
                                <td id="loss_w_v">0 Calories/day</td>
                            </tr>
                           </table>
                        </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </section>
  </main>
  
<?php
endwhile;

get_footer();
?>