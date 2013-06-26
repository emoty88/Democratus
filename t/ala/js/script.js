/*
 * All the plugins init are in this file
 **/
var map;
$(document).ready(function() {
  
  // activate the second carousel
  $('#slider-carousel').carousel();
  $('#testimonials-carousel').carousel();
  
  // init the google map plugin

  
  // sliding contact form
  $('.contact-form-btn').click( function(){
    if($(this).hasClass('closes')) {
      $('.contact-form-inner').slideDown();
      $(this).removeClass('closes').addClass('open');
    } else {
      $('.contact-form-inner').slideUp();
       $(this).removeClass('open').addClass('closes');
    }
  });
  



});