/**
 * customScroll - jQuery Plugin
 *
 * Copyright (c) DESIGN inc. All Rights Reserved.
 * http://www.design-inc.jp/
 *
 */

(function($){
	$.fn.customScroll = function(options){
		
		var elements = this;
		
		var settings = $.extend({
			animateTime: 300,
			easing: 'easeOutCubic',
			scrollTimes: 100 //スクロール量の加算
		}, options);
				
		return elements.each(function(index, value){		
			var elementWidth = parseInt($(value).width());
			var elementHeight = parseInt($(value).height());
			var barWidth = parseInt($(value).find('.cs-bar').width());
			var barHeight = parseInt($(value).find('.cs-bar').height());
			var barInnerWidth = parseInt($(value).find('.cs-bar-inner').innerWidth());
			var barInnerHeight = parseInt($(value).find('.cs-bar-inner').innerHeight());
			var contentWidth = parseInt($(value).find('.cs-content').width());
			var contentHeight = parseInt($(value).find('.cs-content').outerHeight());

			// 中の要素の方が大きい時のみ処理
			if (elementHeight < contentHeight) {
				var drag = $(this).find('.cs-drag');
				var content = $(this).find('.cs-content');
				
				// .cs-dragの高さと最大マージン
				var dragHeight = barHeight / (contentHeight / elementHeight);
				var dragMax = barInnerHeight - dragHeight;
				
				// .cs-contentの最大マージン
				var contentMax = contentHeight - elementHeight;
				
				// .cs-dragに高さを付与
				drag.css({'height': dragHeight});
				
				/**
				 * スクロール
				 */
				$(this).on('mousewheel', function(event, delta){
					// .cs-drag
					var dragMarginTop = parseInt(drag.css('margin-top'));
					var dragNewMarginTop = dragMarginTop - (Math.floor((delta * settings.scrollTimes) * (dragMax / contentMax)));
					if (dragNewMarginTop <= 0) dragNewMarginTop = 0;
					if (dragNewMarginTop > dragMax) dragNewMarginTop = dragMax;
					drag.animate({'margin-top': dragNewMarginTop}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					
					// .cs-content
					var contentMarginTop = parseInt(content.css('margin-top'));
					var contentNewMarginTop = contentMarginTop + (Math.floor(delta * settings.scrollTimes));
					if (contentNewMarginTop >= 0) contentNewMarginTop = 0;
					if (-contentNewMarginTop > contentMax) contentNewMarginTop = -(contentMax);
					content.animate({'margin-top': contentNewMarginTop}, {'duration': settings.animateTime, 'easing': settings.easing, 'queue': false});
					
					return false;
				});
				
				/**
				 * ドラッグ
				 */
				drag.on('mousedown', function(e){
					var mousePosition = e.pageY;
					var dragMarginTop = parseInt(drag.css('margin-top'));
					var contentMarginTop = parseInt(content.css('margin-top'));
					$(document).on('mousemove', function(e){
						var mouseLength = mousePosition - e.pageY;
						
						// .cs-drag
						var dragNewMarginTop = dragMarginTop - mouseLength;
						if (dragNewMarginTop <= 0) dragNewMarginTop = 0;
						if (dragNewMarginTop > dragMax) dragNewMarginTop = dragMax;
						drag.css({'margin-top': dragNewMarginTop});
						
						// .cs-content
						var contentNewMarginTop = contentMarginTop + (mouseLength * (contentMax / dragMax));
						if (contentNewMarginTop >= 0) contentNewMarginTop = 0;
						if (-contentNewMarginTop > contentMax) contentNewMarginTop = -(contentMax);
						content.css({'margin-top': contentNewMarginTop});
					});
					$(document).on('mouseup', function(){
						$(document).off('mousemove');
					});
					return false;
				});
			}
		});
	};
})(jQuery);
