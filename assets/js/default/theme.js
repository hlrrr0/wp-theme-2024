/**
 * Copyright (c) DESIGN inc. All Rights Reserved.
 * http://www.design-inc.jp/
 */

jQuery(function($){
	// jquery.rollOver.js
	$.fn.rollOver({
		selectorFade: 'a img, input[type="image"], button img',
		selectorExclude: '.no-fade img',
		animateTime: 300,
		fadeOpacity: 0.7,
		easing: 'easeOutCubic'
	});

	// jquery.droppy.js
	$('.gnav-list').droppy();
	
	// h1
	$('.page-title').prependTo('.page-title-area-inner').show();

	// パンクズ
	$('.breadcrumb-wrap').prependTo('#content-inner').show();

	// スライダー
	$('.slide-items').slick({
		dots: false, // true: paginationを使用する
		arrows: false, // true: 矢印ボタンを使用する
		fade: true, // true: フェードインを使用する
		infinite: cwp_vars.infinite_loop, // true: 無限ループする
		speed: cwp_vars.speed, // animationのスピード
		autoplay: cwp_vars.auto, // true: 自動スライドする
		autoplaySpeed: cwp_vars.pause // 停止する時間（自動スライド時）
	});

	// jquery.customScroll.js
	$('.scroll-area').customScroll({
		animateTime: 300,
		easing: 'easeOutCubic',
		scrollTimes: 100,
		autoplay: true
	});

	// スクロール時にページトップをフェードインする
	$(window).on('scroll', function() {
		if ($(this).scrollTop() > $(window).height() - $(window).height() * 0.5){
			$('.pagetop-btn-wrap').fadeIn();
		} else {
			$('.pagetop-btn-wrap').fadeOut();
		}
	});
});
