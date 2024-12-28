/**
 * Copyright (c) DESIGN inc. All Rights Reserved.
 * http://www.design-inc.jp/
 */

jQuery(function($){
	// mainimage
	$('.slideshow ul').slick({
		dots: false, // true: paginationを使用する
		arrows: false, // true: 矢印ボタンを使用する
		fade: true, // true: フェードインを使用する
		infinite: cwp_vars.infinite_loop, // true: 無限ループする
		speed: cwp_vars.speed, // animationのスピード
		autoplay: cwp_vars.auto, // true: 自動スライドする
		autoplaySpeed: cwp_vars.pause // 停止する時間（自動スライド時）
	});

	// gallery-detail
	$('.gallery-detail-main ul').slick({
		autoplay: false,
		dots: true,
		arrows: false
	});

	// スクロール時にページトップをフェードインする
	$(window).on('scroll', function() {
		if ($(this).scrollTop() > $(window).height() - $(window).height() * 0.5){
			$('.pagetop-btn-wrap').fadeIn();
		} else {
			$('.pagetop-btn-wrap').fadeOut();
		}
	});

	// drawer
	(function($){
		$target = $('body');
		$menuBtns = $('.drawer-btn, .drawer-bg');
		$menu = $('.drawer-menu');

		// init
		$target.addClass('is-drawer-closed');
		$menu.css({'top': $('#header').outerHeight()});

		// add event
		$menuBtns.on('click', function(e) {
			e.preventDefault();

			// 閉じた状態
			if ($target.hasClass('is-drawer-closed') || !$target.hasClass('is-drawer-open')) {
				$target.removeClass('is-drawer-closed').addClass('is-drawer-open');
			}
			// 開いた状態
			else {
				$target.removeClass('is-drawer-open').addClass('is-drawer-closed');
			}
		});
	})(jQuery);
});
