// smooth scroll for all element 'href'
$('a').click(function(e){

    var href = $(this).attr('href');
    var elemenHref = $(href);
  
  $('html,body').animate({
    scrollTop: elemenHref.offset().top - 65
  }, 1800, 'easeInOutExpo');
  
    e.preventDefault();
  
  });  


  // button to top onlclick and smooth scroll
  $('#btnTop').click(topFunction)
  function topFunction() {
    // $(document).scrollTop(0);
    // $('body').scrollTop = 0;
    $('html,body').animate({scrollTop:0},1000,'easeInOutExpo');
  }

  // button to up
  $('#btnTop').fadeOut();
$(window).scroll(function(){
    if(pageYOffset>=500){
      $('#btnTop').fadeIn("slow");    
    }else{
      $('#btnTop').fadeOut("slow");
    }
  });