(function ($) {
  'use strict';
  AOS.init(
{disable: 'mobile'}
    );





  // $('.meat-checkbox').on('click', function(){
  // $(this).addClass('open');
  // });


//    $(document).ready(function(){
//      // $('.meat-checkbox').addClass('open')
//    $('.meat-checkbox').click(function(){
//      var $this = $(this);
//      if($this.hasClass('open')){
//        setTimeout(function(){
//          $this.removeClass('open')
//       }, 10);
//
//      }
//      else{
//        setTimeout(function(){
//          $this.addClass('open')
//       }, 10);
//      }
//    });
//  });

  $(".navbar-toggler").click(function(){
    $(this).toggleClass("new");
  });

  $(".show-sidebar-btn").click(function(){
    $('#dash-sidebar').toggleClass("new");
  });

  $(".show-sidebar-btn").click(function(){
    $(this).toggleClass("check");
  });



//   $('.single-radio').click(function(event){
    
//     $('.active-tab').removeClass('active-tab');

 
//     $(this).addClass('active-tab');


//     event.preventDefault();
// });

    
//
// 
//    $('.count').each(function () {
//      $(this).prop('Counter',0).animate({
//          Counter: $(this).text()
//      }, {
//          duration: 5000,
//          easing: 'swing',
//          step: function (now) {
//              $(this).text(Math.ceil(now));
//          }
//      });
//  });


  $(document).ready(function(){
    $(function () {
      $("#datepicker").datepicker({ 
            autoclose: true, 
            todayHighlight: true
      }).datepicker('update', new Date());
    });
    $('.datepicker').val("");   // Empty the input field on load.
    
});



$(document).ready(function() {

  // Gets the video src from the data-src on each button
  
  var $videoSrc;  
  $('.video-btn').click(function() {
      $videoSrc = $(this).data( "src" );
  });
  console.log($videoSrc);
  
    
    
  // when the modal is opened autoplay it  
  $('#myModal').on('shown.bs.modal', function (e) {
      
  // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
  $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" ); 
  })
    
  
  
  // stop playing the youtube video when I close the modal
  $('#myModal').on('hide.bs.modal', function (e) {
      // a poor man's stop video
      $("#video").attr('src',$videoSrc); 
  }) 
    
  // document ready  
  });
  

  $('.single-radio .form-group label').click(function () {
    $(this).parents(".single-radio").addClass('clicked').siblings().removeClass('clicked');
  });


  //Avoid pinch zoom on iOS
  document.addEventListener('touchmove', function (event) {
    if (event.scale !== 1) {
      event.preventDefault();
    }
  }, false);
})(jQuery)




// $('label.meal-plan').on('click',function () {
//   $('.single-radio').removeClass('clicked');
// });




// $('label.meal-plan-second').on('click',function () {
//   $('.single-radio').toggleClass('clickedd');
// });

// $('label.meal-plan-second').on('click',function () {
//   $('.single-radio').removeClass('clicked');
// });