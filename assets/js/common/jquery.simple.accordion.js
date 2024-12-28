"use strict";

/*
 *  jquery.simple.accordion - v0.1.0
 *
 *  Under MIT License
 */
;(function ($, window, document, undefined) {
	"use strict";

	// Create the defaults once

	var pluginName = "simpleAccordion",
		defaults = {
			useLinks: false,
			useAnimation: false
		};

	// The actual plugin constructor
	function simpleAccordion(element, options) {
		this.element = element;
		this.settings = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	// Avoid simpleAccordion.prototype conflicts
	$.extend(simpleAccordion.prototype, {
		init: function init() {
			this.setProps();
			this.addLister();

			if (!this.settings.useLinks) {
				this.removeLinks();
			}
		},
		setProps: function setProps() {
			if (this.settings.useAnimation) {
				$(this.element).addClass('simple-accordion-is-animation');
			} else {
				$(this.element).addClass('simple-accordion');
			}

			$(this.element).find('li').each(function (i, target) {
				var id = i + 1;
				var $listItem = $(target);
				var $anchor = $(target).children('a');
				var $list = $(target).children('ul');

				$listItem.addClass('simple-accordion-list-item').attr('data-simple-accordion-group', id);
				$anchor.addClass('simple-accordion-anchor').attr('data-simple-accordion-group', id);
				$list.addClass('simple-accordion-list').attr('data-simple-accordion-group', id);

				// has child?
				if ($list.length > 0) {
					$listItem.addClass('simple-accordion-has-child');
					// create button
					$anchor.append($('<span class="simple-accordion-btn" />').attr('data-simple-accordion-group', id));
				} else {
					$listItem.addClass('simple-accordion-no-child');
				}
			});
		},
		addLister: function addLister() {
			var _this = this;

			var targets = void 0;

			if (this.settings.useLinks) {
				targets = '.simple-accordion-btn';
			} else {
				targets = '.simple-accordion-anchor';
			}

			$(this.element).find(targets).on('click', function (e) {
				var id = $(e.currentTarget).data('simple-accordion-group');
				var $listItem = $(_this.element).find('li[data-simple-accordion-group=' + id + ']');
				var $list = $listItem.children('ul');

				// has child?
				if ($list.length > 0) {
					e.preventDefault();
				}

				_this.toggle(id);
			});
		},
		toggle: function toggle(id) {
			var $listItem = $(this.element).find('li[data-simple-accordion-group=' + id + ']');
			var $list = $(this.element).find('ul[data-simple-accordion-group=' + id + ']');

			// cssでheight: auto をtransitionで操作できないので、開閉アニメーションはslideToggleを利用する
			if (this.settings.useAnimation) {
				$list.stop(true, true).slideToggle();
			}

			if ($listItem.hasClass('is-open')) {
				$listItem.removeClass('is-open');
			} else {
				$listItem.addClass('is-open');
			}
		},
		removeLinks: function removeLinks() {
			$(this.element).find('.simple-accordion-has-child').children('a').removeAttr('href');
		}
	});

	// A really lightweight plugin wrapper around the constructor,
	// preventing against multiple instantiations
	$.fn[pluginName] = function (options) {
		return this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new simpleAccordion(this, options));
			}
		});
	};
})(jQuery, window, document);
