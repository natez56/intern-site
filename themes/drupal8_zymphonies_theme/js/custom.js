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

		
/* <ul class="test-class sm-nowrap" id="sm-1551718174147889-2" role="group" aria-hidden="true" aria-labelledby="sm-1551718174147889-1" aria-expanded="false" style="width: auto; display: none; top: auto; left: 0px; margin-left: 0px; margin-top: 0px; min-width: 10em; max-width: 20em;">
 <li class="c-menu-__item">
<a href="/home/submenu" title="testing twig out">sub home menu</a>
</li>
</ul>*/
		
	// $('#sm-1551718174147889-2').onmousehover( function(){
	// 	// event.preventDefault() ??? 
	// 	alert('this is the correct selector');
 	// // $(this).find("div").html(
	// // 	<div>
	// // 		<a href="/home/submenu1">home submenu1</a>
	// // 		<a href="/home/submenu2">home submenu2</a>
	// // 		<a href="/home/submenu3">home submenu3</a>
	// // 	</div>
	// )
	// })

	// <a href="/" data-target="#" data-toggle="dropdown" class="has-submenu" id="sm-15517204866664756-1" aria-haspopup="true" aria-controls="sm-15517204866664756-2" aria-expanded="false"><span class="sub-arrow">+</span>
  //                               Home
  //                           </a>

	$('.has-submenu').click(function() {
		alert('this is working now, maybe?')
	});
});