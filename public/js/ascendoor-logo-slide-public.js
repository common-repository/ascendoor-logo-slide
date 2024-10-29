(function ($) {

	'use strict';

	var ascendoorLogoSlide = function ($container, options) {
		this.defaults = {
			clone: false,
			cols: 5,
			gap: 0,
			type: 'slidefade',
			random: false,
			interval: 3000,
			pauseHover: false,
			containerClass: '',
			containerID: '',
			itemClass: '',
			responsive: {
				mobile: {
				},
				tablet: {
				},
			}
		};

		// Extend defaults with settings passed on init
		this.settings = $.extend({}, this.defaults, options);

		var self = this,
			timeinterval,
			timeout,
			resizeTimeout,
			$children = $container.children(),
			cols = parseInt(this.settings.cols),
			gap = parseInt(this.settings.gap),
			changed = true,
			firstLoad = true;

		// Initialization of slider
		this.init = function () {
			self.setCols();
			self.setGap();

			if (true === changed) {
				clearInterval(timeinterval);
				clearTimeout(timeout);

				self.initializeSlides();

				if (true === self.settings.random) {
					$container.find('.logo-slide-slides').each(function () {
						var $this = $(this);

						self.slideRandom($this);
					});
				} else {
					self.slideWithInterval();
				};

				changed = false;
			}

			if (true === firstLoad) {
				// Pause on hover events
				if (true === self.settings.pauseHover) {
					$container.on('mouseenter onfocus', '.logo-slide-slides', self.pause.bind(this));
					$container.on('mouseleave onblur', '.logo-slide-slides', self.start.bind(this));
				}

				if ('object' !== typeof self.settings.responsive) {
					self.settings.responsive = {};
				}

				if (!$.isEmptyObject(self.settings.responsive)) {
					self.resize();
				}

				firstLoad = false;
			}
		};

		// Get slide interval
		this.getDuration = function () {
			return true !== self.settings.random
				? self.settings.interval
				: (Math.floor(Math.random() * 4) + 1) * 3000;
		}

		// Function for setting sizes for each item in slider
		this.setCols = function () {
			var currentWidth = $(window).width();

			if (480 >= currentWidth) {
				if ('undefined' !== typeof self.settings.responsive.mobile && 'undefined' !== self.settings.responsive.mobile.cols) {
					if (cols !== parseInt(self.settings.responsive.mobile.cols) && !isNaN(parseInt(self.settings.responsive.mobile.cols))) {
						cols = parseInt(self.settings.responsive.mobile.cols);
						changed = true;
					}
				} else {
					if ('undefined' !== typeof self.settings.responsive.tablet && 'undefined' !== self.settings.responsive.tablet.cols) {
						if (cols !== parseInt(self.settings.responsive.tablet.cols) && !isNaN(parseInt(self.settings.responsive.tablet.cols))) {
							cols = parseInt(self.settings.responsive.tablet.cols);
							changed = true;
						}
					}
				}
			} else if (768 >= currentWidth) {
				if ('undefined' !== typeof self.settings.responsive.tablet && 'undefined' !== self.settings.responsive.tablet.cols) {
					if (cols !== parseInt(self.settings.responsive.tablet.cols) && !isNaN(parseInt(self.settings.responsive.tablet.cols))) {
						cols = parseInt(self.settings.responsive.tablet.cols);
						changed = true;
					}
				} else {
					if (cols !== parseInt(self.settings.cols) && !isNaN(parseInt(self.settings.cols))) {
						cols = parseInt(self.settings.cols);
						changed = true;
					}
				}
			} else {
				if (cols !== parseInt(self.settings.cols) && !isNaN(parseInt(self.settings.cols))) {
					cols = parseInt(self.settings.cols);
					changed = true;
				}
			}

		};

		this.setGap = function () {
			var currentWidth = $(window).width();

			if (480 >= currentWidth) {
				if ('undefined' !== typeof self.settings.responsive.mobile && 'undefined' !== self.settings.responsive.mobile.gap) {
					if (gap !== parseInt(self.settings.responsive.mobile.gap) && !isNaN(parseInt(self.settings.responsive.mobile.gap))) {
						gap = parseInt(self.settings.responsive.mobile.gap);
						changed = true;
					}
				} else {
					if ('undefined' !== typeof self.settings.responsive.tablet && 'undefined' !== self.settings.responsive.tablet.gap) {
						if (gap !== parseInt(self.settings.responsive.tablet.gap) && !isNaN(parseInt(self.settings.responsive.table.gap))) {
							gap = parseInt(self.settings.responsive.tablet.gap);
							changed = true;
						}
					}
				}
			} else if (768 >= currentWidth) {
				if ('undefined' !== typeof self.settings.responsive.tablet && 'undefined' !== self.settings.responsive.tablet.gap) {
					if (gap !== parseInt(self.settings.responsive.tablet.gap) && !isNaN(parseInt(self.settings.responsive.tablet.gap))) {
						gap = parseInt(self.settings.responsive.tablet.gap);
						changed = true;
					}
				} else {
					if (gap !== parseInt(self.settings.gap) && !isNaN(parseInt(self.settings.gap))) {
						gap = parseInt(self.settings.gap);
						changed = true;
					}
				}
			} else {
				if (gap !== parseInt(self.settings.gap) && !isNaN(parseInt(self.settings.gap))) {
					gap = parseInt(self.settings.gap);
					changed = true;
				}
			}
		};

		// Generate and add the slider elements
		this.initializeSlides = function () {
			var childrenChunk = [];

			$container.html('');

			var tempChildren = [],
				_tempChildren = [];

			$children.each(function (index, element) {
				tempChildren.push(element.outerHTML);
				_tempChildren.push(element.outerHTML);
			});

			if (0 < tempChildren.length) {
				if (true === self.settings.clone) {
					while (tempChildren.length < cols * 2) {
						tempChildren = $.merge(tempChildren, _tempChildren);
					}
				}

				var $slideWrapper = $(document.createElement('div'));
				$slideWrapper.addClass('logo-slide-wrapper');

				if (self.settings.containerID) {
					$slideWrapper.attr('id', self.settings.containerID);
				}

				var $slideContainer = $(document.createElement('div'));
				$slideContainer.addClass(`logo-slide-container  logo-slide-cols-${cols} logo-cols-gap-${gap}`);

				if (self.settings.containerClass) {
					$slideContainer.addClass(self.settings.containerClass);
				}

				$slideWrapper.html($slideContainer);

				tempChildren.forEach(function (element, index) {
					var chunkIndex = index;
					if (cols <= chunkIndex) {
						chunkIndex = chunkIndex % cols;
					}

					if ('undefined' === typeof childrenChunk[chunkIndex]) {
						childrenChunk[chunkIndex] = [];
					}

					childrenChunk[chunkIndex].push(element);
				});

				if (0 < childrenChunk.length) {
					childrenChunk.forEach(function (slides) {
						var $slidesWrapper = $(document.createElement('div'));
						$slidesWrapper.addClass(`logo-slide-slides`);

						if (0 < slides.length) {
							slides.forEach(function (slide, index) {
								var status = 0 === index ? 'on' : 'out';

								var $slideEffect = $(document.createElement('div'));
								$slideEffect.addClass(`logo-slide-effect ${self.settings.type} ${status}`)

								if (self.settings.itemClass) {
									$slideEffect.addClass(self.settings.itemClass);
								}

								$slideEffect.html(slide);

								$slidesWrapper.append($slideEffect);
							});
						}

						$slideContainer.append($slidesWrapper)
					});
				}

				$container.html($slideWrapper);
			}
		};

		// Slide with interval time
		this.slideWithInterval = function () {
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
				timeinterval = setInterval(function () {
					if (!$container.prop('paused')) {
						updateEffect();
					}

				}, self.getDuration());
			};

			effectTimeOut();
			$container.find('.logo-slide-container').addClass('logo-slide-initialized');
		};

		// Slide random
		this.slideRandom = function ($element) {
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
				timeout = setTimeout(function () {
					if (!$element.prop('paused')) {
						updateEffect();
					}

					effectTimeOut();
				}, duration);
			};

			effectTimeOut();

			$container.find('.logo-slide-container').addClass('logo-slide-initialized');
		};

		// Resize handler function
		this.resize = function () {
			$(window).resize(function () {
				if (resizeTimeout) {
					clearTimeout(resizeTimeout);
					resizeTimeout = null;
				}

				resizeTimeout = setTimeout(function () {
					self.init();
				}, 250);
			});
		};

		// Pause on hover.
		this.pause = function (e) {
			var $this = $(e.currentTarget);

			if (true !== self.settings.random) {
				$container.addClass("paused").prop('paused', true);
			} else {
				$this.addClass("paused").prop('paused', true);
			}
		};

		// Start on hover out.
		this.start = function (e) {
			var $this = $(e.currentTarget);

			if (true !== self.settings.random) {
				$container.removeClass("paused").prop('paused', false);
			} else {
				$this.removeClass("paused").prop('paused', false);
			}
		};

		if (!$container.find('> .logo-slide-wrapper > .logo-slide-initialized').length) {
			// Finally init slider
			this.init();
		}
	};

	$.fn.ascendoorLogoSlide = function (options) {
		if (options === undefined) {
			options = {};
		}

		if ('object' === typeof options) {
			return this.each(function () {
				new ascendoorLogoSlide($(this), options);
			});
		}

	}

	if ($('[data-als]').length) {
		$('[data-als]').each(function () {
			var $this = $(this),
				options = $this.data('als');

			$this.ascendoorLogoSlide(options);
		});
	}
})(jQuery);