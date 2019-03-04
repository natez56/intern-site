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

{/* <ul class="test-class" id="sm-15517228168742236-2" role="group" aria-hidden="true" aria-labelledby="sm-15517228168742236-1" aria-expanded="false" style="width: 20em; display: none; top: auto; left: 0px; margin-left: 0px; margin-top: 0px; min-width: 10em; max-width: 20em;">
                                                                                                                        <li class="c-menu-__item">
                                <a href="/home/submenu" title="testing twig out">sub home menubwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahha</a>
                                                                                </li>
                                    </ul> */}

//the plan
//1. append the links in the menu using the <a> and <li>
//2. fiddle with it until it works
//3. adjust the size of the container to match the links via
//		flex? some sort of auto adjusting size, auto margin or auto size or auto grid
//		size attributes

	$('.has-submenu').hover(function() {
		// alert('this is working now, maybe?')
		//<a href="/home/submenu" title="testing twig out">sub home menu</a>
		// $(".has-submenu").append("bwaahhaha");
		$(".test-class").css("min-width", "");
		$(".test-class").css("max-width", "");
		// $(".test-class").removeAttr("min-width");
		// $(".test-class").removeAttr("max-width");
		// $(".test-class").css("width", "auto");
		$(".test-class").css("height", "auto");

{/* <li class="c-menu-__item">
                                <a href="/home/submenu" title="testing twig out">sub home menubwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahhabwahahha</a>
                                                                                </li> */}

		// $(".c-menu-__item").append("bwahahha");
		// $("[title='testing twig out']").append("<li>bwahahha</li>");
		// $("[title='testing twig out']").append("<li>bwahahha22222</li>");
		// $("[title='testing twig out']").append("<li>bwahahha33333</li>");
		$(".c-menu-__item").append("<li><a href='/home/submenu1'>submenu1</a></li>").fadeIn();
		// $(".c-menu-__item).append("<li><a href='/home/submenu2'>submenu2</a></li>");
		// $("[title='testing twig out']").append("<li><a href='/home/submenu3'>submenu3</a></li>");
		// $("[title='testing twig out']").append("<li><a href='/home/submenu4'>submenu4</a></li>");
		// $("[title='testing twig out']").append("<li><a href='/home/submenu5'>submenu5</a></li>");
	})
	// setTimeout(function() {
	// 	$(".c-menu-__item").find("li").remove();
	// }, 3000));
	setTimeout(function(){
		$('.c-menu-__item').mouseleave(function() {
			$('.c-menu-__item').find("li").remove();
		});
	}, 3000)

})