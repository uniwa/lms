;(function ($, window, undefined) {
  'use strict';

  // var $doc = $(document);

  // $.fn.foundationAccordion        ? $doc.foundationAccordion() : null;
 
// custom js

  // menu
  $('.main-nav > li').hover(function(){
    $(this).addClass('active').find('.subnav').show();
  },function(){
      $(this).removeClass('active')
      $('.subnav').hide();
  })


  $('.slider').bxSlider({
    'pause' : '6000',
    'auto' : true,
    'pager' : false,
    'autoHover' : true,
    'prevText': '<img src="'+prevBtn+'" />',
    'nextText': '<img src="'+nextBtn+'" />'
  });

    var slider2 = $('.authors').bxSlider({
      controls: false,
      pager : false
    });

    $('.prev').click(function(){
      slider2.goToPrevSlide();
      return false;
    });

    $('.next').click(function(){
      slider2.goToNextSlide();
      return false;
    });

// tags accordion

var slider = $('#tag-accordion').bxSlider({
  controls: false,
  pager : false,
  mode: 'vertical',
  maxSlides: 3,
  minSlides: 3,
  moveSlides: 0
});

 $('.up').click(function(){
   slider.goToPrevSlide();
   return false;
 });

 $('.down').click(function(){
   slider.goToNextSlide();
   return false;
 });



// custom accordion

$('.accordion li.expandable').live('click' , function(){
  $('.accordion li.expandable').removeClass('opened');
    $(this).toggleClass('opened');
})

$('.accordion li.expandable > a').live('click' , function(e){
    e.preventDefault();
    return false;
})


  // Add to binder
  /*$(".add-to-binder").live('click', function(){
    if($(this).hasClass('add')){
      $('.binder #counter').html('1');
      $(this).removeClass('add').html('- Remove from binder');
    } else {
      $('.binder #counter').html('0');
      $(this).addClass('add').html('+ Add to binder');
    }
  })*/
  

})(jQuery, this);