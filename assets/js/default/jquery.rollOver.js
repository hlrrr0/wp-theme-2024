/*
 * rollOver - jQuery Plugin
 *
 * Copyright (c) DESIGN inc. All Rights Reserved.
 * http://www.design-inc.jp/
 *
 */

(function($){
	$.fn.rollOver = function(options){
		
		var elements = this;
		
		var settings = $.extend({
			selectorFade: '.ro-fade img, .ro-fade input', //フェードクラス
			selectorReFade: '.ro-xfade img, .ro-xfade input', //フェードクラス（通常時が半透明）
			selectorSwitch: '.ro-switch img, .ro-switch input', //画像切替クラス
			selectorFadeswitch: '.ro-fswitch img, .ro-fswitch input', //フェード画像切替クラス
			selectorExclude: '.no-fade img', //除外クラス
			attachStr: '-on', //ロールオーバー画像の名前
			animateTime: 300, //アニメーションの時間
			fadeOpacity: 0.7, //フェードのパーセンテージ
			easing: 'easeOutCubic' //イージング
		}, options);
		
		var userAgent = window.navigator.userAgent.toLowerCase();

		// 除外セレクタ
		var selectorExclude = settings.selectorExclude + ', ' + settings.selectorReFade + ', ' + settings.selectorSwitch + ', ' + settings.selectorFadeswitch;

		$(settings.selectorFade).not(selectorExclude).each(function(){
			if (!$(this).attr('src').match('\.png$') || userAgent.indexOf('msie') == -1) {
				$(this).on({
					mouseenter: function(){
						$(this).animate({'opacity': settings.fadeOpacity}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					},
					mouseleave: function(){
						$(this).animate({'opacity': 1}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					}
				});
			}
		});
		
		$(settings.selectorReFade).each(function(){
			if (!$(this).attr('src').match('\.png$') || userAgent.indexOf('msie') == -1) {
				$(this).css({'opacity': settings.fadeOpacity});
				$(this).on({
					mouseenter: function(){
						$(this).animate({'opacity': 1}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					},
					mouseleave: function(){
						$(this).animate({'opacity': settings.fadeOpacity}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					}
				});
			}
		});
		
		$(settings.selectorFadeswitch).not('[src*="' + settings.attachStr + '."]').each(function(){
			if (!$(this).attr('src').match('\.png$') || userAgent.indexOf('msie') == -1) {
				var overImgSrc = $(this).attr('src').replace(new RegExp('(\.gif|\.jpg|\.png)$'), settings.attachStr + '$1');
				$(this).parent().css({'background-image': 'url("' + overImgSrc + '")'});
				
				$(this).on({
					mouseenter: function(){
						$(this).animate({'opacity': 0}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					},
					mouseleave: function(){
						$(this).animate({'opacity': 1}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					}
				});
			}
		});
		
		$(settings.selectorSwitch).not('[src*="' + settings.attachStr + '."]').each(function(){
			if (!$(this).attr('src').match('\.png$') || userAgent.indexOf('msie') == -1) {
				var imgSrc = $(this).attr('src');
				var overImgSrc = $(this).attr('src').replace(new RegExp('(\.gif|\.jpg|\.png)$'), settings.attachStr + '$1');
				
				$(this).on({
					mouseenter: function(){
						if ($(this).attr('src') === imgSrc) $(this).attr('src', overImgSrc);
					},
					mouseleave: function(){
						if ($(this).attr('src') === overImgSrc) $(this).attr('src', imgSrc);
					}
				});
			}
		});
		
		return this;
	};
})(jQuery);
