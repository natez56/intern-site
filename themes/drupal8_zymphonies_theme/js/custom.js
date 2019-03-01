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
		
	$('selector').onmousehover( function(){
	$(this).find("div").html(
		<div>
			<a href="/home/submenu1">home submenu1</a>
			<a href="/home/submenu2">home submenu2</a>
			<a href="/home/submenu3">home submenu3</a>
		</div>
	)
	})
});