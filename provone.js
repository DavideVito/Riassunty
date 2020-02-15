$(() => {

	//On Scroll Functionality
	$(window).scroll(() => {
		//alert(1)
		var windowTop = $(window).scrollTop();
		windowTop > 100 ? $('nav').addClass('navShadow') : $('nav').removeClass('navShadow');
		windowTop > 100 ? $('ul').css('top', '100px') : $('ul').css('top', '160px');
	});

	//Click Logo To Scroll To Top
	$('#logo').on('click', () => {

		$('html,body').animate({
			scrollTop: 0
		}, 500);

	});
	console.log($('a[href*="#"]'))

	//Smooth Scrolling Using Navigation Menu


	//Toggle Menu
	$('#menu-toggle').on('click', () => {
		$('#menu-toggle').toggleClass('closeMenu');
		$('ul').toggleClass('showMenu');

		$('li').on('click', () => {
			$('ul').removeClass('showMenu');
			$('#menu-toggle').removeClass('closeMenu');
		});
	});

});