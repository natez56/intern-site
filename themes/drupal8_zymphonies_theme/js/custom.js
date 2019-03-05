jQuery(document).ready(function($){

	//Main menu
	$('#main-menu').smartmenus();
	
	//Mobile menu toggle
	$('.navbar-toggle').click(function(){
		$('.region-primary-menu').slideToggle();
	});

	//Mobile dropdown menu
	if ( $(window).width() < 767) {
		$(".region-primary-menu li a:not(.has-submenu)").click(function () {
			$('.region-primary-menu').hide();
	    });
	}

	//flexslider
	jQuery('.flexslider').flexslider({
    	animation: "slide"	
		});
	
	//this function appends a dropdown menu on hover using jquery
	$('.has-submenu').hover(function() {
		$("[title='testing twig out']").remove();
		$(".dagosMenu").remove();
		$(".c-menu-__item").append("<div class='dagosMenu'></div>")
		$(".dagosMenu").append("<li><a href='/home/submenu1'>submenu1</a></li>");
		$(".dagosMenu").append("<li><a href='/home/submenu2'>submenu2</a></li>");
		$(".dagosMenu").append("<li><a href='/home/submenu3'>submenu3</a></li>");
		$(".dagosMenu").append("<li><a href='/home/submenu4'>submenu4</a></li>");
		$(".dagosMenu").append("<li><a href='/home/submenu5'>submenu5</a></li>");
	});
})