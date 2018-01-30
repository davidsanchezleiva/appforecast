(function($){
  $(function(){

    $('.button-collapse').sideNav();
    $('.parallax').parallax();
	
    $('.collapsible').collapsible();
    $('.tooltipped').tooltip({delay: 50});
      
    $('.modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .7 // Opacity of modal background
      }
    );
      

  }); // end of document ready
})(jQuery); // end of jQuery name space