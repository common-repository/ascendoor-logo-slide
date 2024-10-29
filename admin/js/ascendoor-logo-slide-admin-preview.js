(function ($) {
	"use strict";

	$.fn.logoFadeSlideEffect = function (options) {
		var self = this,
			defaults = {
				type: "fade",
				duration: 5000,
				random: false,
				pauseOnHover: false,
				slidesClass: '',
				destroy: true
			},
			settings = $.extend({}, defaults, options);

		var effects = {
			init: function ($element) {
				this.$element = $element;

				this.$element.prop({
					random: settings.random,
					effectType: settings.type,
					pauseOnHover: settings.pauseOnHover,
				});

				if (settings.destroy === true && 'undefined' !== this.$element.prop('timeout')) {
					clearTimeout(this.$element.prop('timeout'));
				}

				if (settings.destroy === true && 'undefined' !== this.$element.prop('timeinterval')) {
					clearInterval(this.$element.prop('timeinterval'));
				}

				this.$children = this.$element.children();
				this.totalChildren = this.$children.length;

				this.$element.on(
					"mouseenter onfocus",
					".logo-slide-slides",
					this.pauseEffect.bind(this)
				);
				this.$element.on(
					"mouseleave onblur",
					".logo-slide-slides",
					this.startEffect.bind(this)
				);

				if (this.$element.hasClass('initialized')) {
					var self = this;

					if (true !== settings.random) {
						this.doEffect1(this.$element);
					} else {
						this.$element.children().each(function () {
							var $this = $(this);

							self.doEffect($this);
						});
					}
				} else {
					this.prepareEffect();
				}
			},
			getDuration: function () {
				return true !== settings.random
					? settings.duration
					: (Math.floor(Math.random() * 4) + 1) * 3000;
			},
			prepareEffect: function () {
				var self = this,
					items = [],
					type = settings.type.toString().toLowerCase();

				if (0 < this.totalChildren) {
					this.$children.each(function (index) {
						var $this = $(this);
						$this.addClass("logo-slide-slides " + settings.slidesClass);
						if ($this.children().length) {
							$this.children().each(function (index) {
								var $thisElem = $(this);
								if (0 === index) {
									$thisElem.wrap(
										'<div class="logo-slide-effect ' + type + ' on"></div>'
									);
								} else {
									$thisElem.wrap(
										'<div class="logo-slide-effect ' + type + ' out"></div>'
									);
								}
							});
						}

						items.push($this.get(0).outerHTML);
					});

					this.$element.html(items.join(""));

					this.doEffect1(this.$element);


					// this.$element.children().each(function () {
					// 	var $this = $(this);

					// });
				}
			},
			doEffect1: function ($container) {
				var self = this;
				var updateEffect = function () {

					$container.find('.logo-slide-slides').each(function (index, element) {
						var $element = $(element),
							$current = $element.find(".logo-slide-effect.on"),
							$next = $current.next();

						if ("undefined" === typeof $next || 0 === $next.length) {
							$next = $element.children().first();
						}

						$current.removeClass("on").addClass("out");
						$next.removeClass("out").addClass("on");
					});
				};

				var effectTimeOut = function () {
					var timeinterval = setInterval(function () {
						if (!$container.prop('paused')) {
							updateEffect();
						}

					}, settings.duration);

					self.$element.prop({
						timeinterval: timeinterval
					});
				};

				effectTimeOut();
			},
			doEffect: function ($element) {
				var self = this;

				var updateEffect = function () {
					var $current = $element.find(".logo-slide-effect.on"),
						$next = $current.next();

					if ("undefined" === typeof $next || 0 === $next.length) {
						$next = $element.children().first();
					}

					$current.removeClass("on").addClass("out");
					$next.removeClass("out").addClass("on");
				}

				var effectTimeOut = function () {
					var duration = self.getDuration();
					var timeout =  setTimeout(function () {
						if (!$element.prop('paused')) {
							updateEffect();
						}

						effectTimeOut();
					}, duration);

					self.$element.prop({
						timeout: timeout
					});
				};

				effectTimeOut();
			},
			pauseEffect: function (e) {
				e.preventDefault();
				var $this = $(e.currentTarget),
					$parent = $this.parent(),
					pauseOnHover = $parent.prop("pauseOnHover");

				if (true !== pauseOnHover) {
					return true;
				}

				var isRandom = $parent.prop("random");

				if (true !== isRandom) {
					$parent.addClass("paused").prop('paused', true);
				} else {
					$this.addClass("paused").prop('paused', true);
				}
			},
			startEffect: function (e) {
				e.preventDefault();
				var $this = $(e.currentTarget),
					$parent = $this.parent(),
					pauseOnHover = $parent.prop("pauseOnHover");

				if (true !== pauseOnHover) {
					return true;
				}

				var isRandom = $parent.prop("random");

				if (true !== isRandom) {
					$parent.removeClass("paused").prop('paused', false);
				} else {
					$this.removeClass("paused").prop('paused', false);
				}
			},
		};

		var initialize = function () {
			if (0 < self.length) {
				self.each(function () {
					var $this = $(this);

					effects.init($this);
				});
			}
		};

		initialize();

		return this;
	};
})(jQuery);
