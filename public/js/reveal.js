;(function ( $, window, document, undefined ) {

	var pluginName = "themepileStepsShowing",
		defaults = {
			"onStartAnimation" : null,
			"onEndAnimation" : null
		};

	// The actual plugin constructor
	function themepileStepsShowing( element, options ) {

		this.element    = element;
		this.options    = $.extend( {}, defaults, options );
		this.close   = $(this.element).find(this.options.closeButton);

		this._defaults = defaults;
		this._name = pluginName;

		this.scrollPosition = 0;
		this.currentItemIndex = 0;
		this.offsetTop = $(window).height();
		this.item = $(this.element).find('.scrolled__item');

		$(window).on('scroll', $.proxy(this, 'onScroll'));
		this.onScroll();

	}

	themepileStepsShowing.prototype = {
		onScroll : function() {
			this.scrollPosition = $(window).scrollTop();
			this.getCurrentItem();
		},

		getCurrentItem : function() {
			if(this.scrollPosition>=$(this.element).offset().top-this.offsetTop) {
				(this.options.onStartAnimation) && this.options.onStartAnimation.apply(this);
				if(!$(this.element).hasClass('scrolled_status_progress')) {
					this.startItemsAnimation(this.currentItemIndex);
					$(this.element).addClass('scrolled_status_progress')
				}
			}
		},

		startItemsAnimation : function(index) {

			$(this.item).eq(index).animate(
				{
					'opacity' : 1
				},
				{
					easing: "swing",
					duration: 600,
					step: $.proxy(this, 'onAnimationStep')
				});
		},
		onAnimationStep : function(now, fx ) {
			if(now>0.2) {
				this.currentItemIndex+=1;
				if(this.currentItemIndex==this.item.length) {
					$(this.element).addClass('scrolled_status_show');
				} else {
					this.startItemsAnimation(this.currentItemIndex);
				}
			}
		}
	};

	$.fn[pluginName] = function ( options ) {
		return this.each(function () {
			if (!$.data(this, pluginName)) {
				$.data(this, pluginName, new themepileStepsShowing( this, options ));
			}
		});
	};

})( jQuery, window, document );