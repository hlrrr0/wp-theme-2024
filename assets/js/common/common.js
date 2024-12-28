/**
 * Copyright (c) DESIGN inc. All Rights Reserved.
 * http://www.design-inc.jp/
 */

jQuery(function($){
	// formで郵便番号から住所を自動出力する
	$('#zip').on('change', function(event) {
		AjaxZip3.zip2addr(this, '', 'pref', 'addr1');
	});

	// Googleマップを.googlemapに挿入
	if ($('.googlemap').length){
		$('.googlemap').append(cwp_vars.google_map_tag);
	}

	// ページ内スクロール
	$('a.scroll').on('click', function(){
		$('html,body').animate({scrollTop: $($(this).attr('href')).offset().top }, {'duration': 400, 'easing': 'easeOutCubic', 'queue': false});
		return false;
	});

	// 外部リンクに「target="_blank"」付与（クラス「a.noblank以外」）
	$('a[href^=http]').not('[href*="'+location.hostname+'"]').not('a.noblank').attr('target', '_blank');

	// 画像にリンクした場合、lightboxで開く
	$('a[href$="jpg"], a[href$="jpeg"], a[href$="JPG"], a[href$="JPEG"], a[href$="png"], a[href$="gif"]').not('.lightbox-group-item').addClass('lightbox');

	// lightbox
	$('.lightbox').lightGallery({
		selector: 'this',
		autoplayFirstVideo: false, // inline使用時にエラーが出るので設定
		getCaptionFromTitleOrAlt: false, // titleかaltを使ってキャプションを表示するかどうか
		fullScreen: false,
		share: false, // shareボタンの表示・非表示
		download: false // downloadボタンの表示・非表示
	});

	// lightbox-group
	$('.lightbox-group').lightGallery({
		selector: '.lightbox-group-item',
		showThumbByDefault: true, // デフォルトでサムネイル一覧を表示するかどうか
		getCaptionFromTitleOrAlt: false, // titleかaltを使ってキャプションを表示するかどうか
		fullScreen: false,
		share: false, // shareボタンの表示・非表示
		download: false // downloadボタンの表示・非表示
	});

	// slick: 画面幅100%のスライダー
	$('.slideshow-fullwidth').slick({
		dots: false,
		arrows: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		variableWidth: true,
		centerMode: true,
		infinite: cwp_vars.infinite_loop,
		speed: cwp_vars.speed,
		autoplay: cwp_vars.auto,
		autoplaySpeed: cwp_vars.pause
	});

	// slick: paginationをサムネイルにする
	$('.slideshow-thumbpager').slick({
		dots: true,
		arrows: false,
		fade: true,
		infinite: cwp_vars.infinite_loop,
		speed: cwp_vars.speed,
		autoplay: cwp_vars.auto,
		autoplaySpeed: cwp_vars.pause,
		customPaging : function(slider, i) {
			var thumb = $(slider.$slides[i]).find('img').attr('src');
			return '<a><img src="'+thumb+'"></a>';
		}
	});

	// slick: ticker mode
	$('.slideshow-ticker').slick({
		arrows: false,
		slidesToShow: 3,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 0,
		speed: 2000,
		cssEase:'linear'
	});

	// gallery-detail: carousel
	$('.gallery-detail-carousel').slick({
		dots: true,
		arrows: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
	});

	// slick: carousel
	$('.slideshow-carousel').slick({
		arrows: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		autoplay: true,

		// スマホ時オプション
		responsive: [
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1
				}
			}
		]
	});

	// slick: carousel
	$('.slideshow-carousel-col2').slick({
		arrows: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		autoplay: false,

		// スマホ時オプション
		responsive: [
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2
				}
			}
		]
	});

	// jquery.datetimepicker
	jQuery.datetimepicker.setLocale('ja');

	// 日付 + 時間指定
	$('.datetimepicker').datetimepicker();

	// 日付指定
	$('.datetimepicker-day').datetimepicker({
		timepicker: false,
		format: 'Y/m/d'
	});

	// jquery.simple.accordion.js
	$('.accordion').simpleAccordion({
		useAnimation: true,   // アニメーションの on, off
		useLinks: false       // リンクを利用するかどうか
	});

	// EC 詳細、一覧コメント欄で tableがはみ出ないようにする
	$('.detailpage-list-comment table, .detailpage-main-comment table').each(function() {
		if ($(this).parent().width() < $(this).width()) {
			$(this).width('100%');
		}
	});
});
