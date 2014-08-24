/*****************************************************************
 * **************************************************************
 * @MetCreative - Table of Contents
 * 1-) Document Ready State
 *    a- Kabubu Accordion
 *    b- Twitter Feed
 *    c- Testimonial Rotator
 *    d- Video Background
 *    e- Scroll Speed and Styling
 *    f- Gmaps JS for Google Maps
 *    h- Php Ajax Contact Form
 *    i- One By One Slider
 *    j- Custom Select Box
 *    h- Menu Aligning
 *    l- Reveal
 *    m- Back To Top Button
 *    n- DL Menu
 *    o- Full Screen Background Image
 * 2-) Window Load State
 *    a- Page Title Alignment
 *    b- Portfolio Detail Detail Slider
 *    c- Brand Carousel
 *    d- Portfolio Index
 *    e- Portfolio Isotope Categorization
 *    f- Masonry Portfolio
 *    g- PrettyPhoto
 *    h- Mobile Detection
 *    i- Skill Bar Filling
 *    j- Recent Works Categorized Carousel
 * 3-) Window Resize State
 *    a- Portfolio Lists Ordering on Resize
 * 4-) Global Functions
 *    a- Number Finder
 *    b- Sticky Header
 *    c- Header Width Adjustment
 *    d- Page Title H1 Tail
 * !Note: You can make search with one of the title above to find the block according to it
 * **************************************************************
 *****************************************************************/

/**
 * Document Ready state for jquery plugins
 * @usedPlugins jquery
 * @usedAt      global
 */
jQuery(document).ready(function ($) {

    /**
     * Kabubu Accordion
     * @usedPlugins jquery
     * @usedAt      Aboutus
     */
    $('.met_kabubu_accordion .met_kabubu_accordion_sign,.met_kabubu_accordion .met_kabubu_item_title').click(function (e) {
        e.preventDefault();

        var kabubuAccordion = $(this).parents('.met_kabubu_accordion');
        var activeOrNot = $(this).parents('.met_kabubu_accordion_item');

        if (activeOrNot.hasClass('met_kabubu_accordion_item_active')) {
            activeOrNot.removeClass('met_kabubu_accordion_item_active');
            activeOrNot.children('.met_kabubu_accordion_detail').children('.met_kabubu_accordion_descr').slideUp();
            activeOrNot.children('a').children('i.icon-minus').removeClass('icon-minus').addClass('icon-plus');
        } else {
            if (kabubuAccordion.children('.met_kabubu_accordion_item_active').length > 0) {
                kabubuAccordion.children('.met_kabubu_accordion_item_active').children('a').children('i.icon-minus').removeClass('icon-minus').addClass('icon-plus');
                kabubuAccordion.children('.met_kabubu_accordion_item_active').children('.met_kabubu_accordion_detail').children('.met_kabubu_accordion_descr').slideUp();
                kabubuAccordion.children('.met_kabubu_accordion_item_active').removeClass('met_kabubu_accordion_item_active');
            }

            activeOrNot.addClass('met_kabubu_accordion_item_active');
            activeOrNot.children('a').children('i.icon-plus').removeClass('icon-plus').addClass('icon-minus');
            activeOrNot.children('.met_kabubu_accordion_detail').children('.met_kabubu_accordion_descr').slideDown();
        }
    });

    /**
     * Twitter Feed
     * @usedPlugins jquery, easyticker
     * @usedAt      footer
     */
    $(".met_twitter_ticker").easyTicker({
        direction: "up",
        height: "175",
        mousePause: 1
    });

    /**
     * Testimonial Rotator
     * @usedPlugins jquery
     * @usedAt      index
     */
    if ($('.met_testimonial_rotator').length > 0) {
        var firstBox = $('.met_testimonial_rotator .met_testimonial_rotator_box:first-child');
        firstBox.addClass('met_testimonial_box_active');
        $('.met_testimonial_rotator').css('height', firstBox.height() + 'px');

        setInterval(function () {
            return testimonial_rotator()
        }, 5000);
    }
    function testimonial_rotator() {
        var activeBox = $('.met_testimonial_rotator .met_testimonial_rotator_box.met_testimonial_box_active');
        var activeIndex = activeBox.index() + 1;
        var nextIndex = $('.met_testimonial_rotator .met_testimonial_rotator_box:nth-child(' + (activeIndex + 1) + ')').length > 0 ? activeIndex + 1 : 1;
        var nextBox = $('.met_testimonial_rotator .met_testimonial_rotator_box:nth-child(' + nextIndex + ')');

        activeBox.removeClass('met_testimonial_box_active');
        nextBox.addClass('met_testimonial_box_active');
        $('.met_testimonial_rotator').css('height', nextBox.height() + 'px');
    }

    /**
     * Video Background
     * @usedPlugins jquery, videoBG
     * @usedAt      pages with background video
     */
    if ($('.met_video_background').length > 0 && $('.met_boxed_layout').length > 0) {
        $('.met_video_background').videoBG({
            mp4: 'assets/1.mp4',
            ogv: 'assets/1.ogv',
            webm: 'assets/1.webm',
            poster: 'assets/1.png',
            scale: true,
            zIndex: 0
        });
        $('.met_video_background .videoBG').css('width', $('body').width() + 'px');
        $(window).resize(function () {
            $('.met_video_background .videoBG').css('width', $('body').width() + 'px').css('height', $(window).height() + 'px');
        });
    }

    /**
     * Scroll Speed and Styling
     * @usedPlugins jquery,nicescroll
     * @usedAt      Every page that contains body tag with data-smooth-scrolling 1
     */
    if ($('body').attr('data-smooth-scrolling') == 1) {
        $("html").niceScroll({
            scrollspeed: 60,
            mousescrollstep: 35,
            cursorwidth: 10,
            cursorborder: '1px solid #171717',
            cursorcolor: '#d8d8d8',
            cursorborderradius: 10,
            autohidemode: false,
            cursoropacitymin: 0.1,
            cursoropacitymax: 1
        });
    }

    /**
     * Gmaps JS for Google Maps
     * @usedPlugins gmaps,gmaps sensor
     * @usedAt      Contact page
     */
    if ($('#map').length > 0) {
        var map = new GMaps({
            div: '#map',
            lat: -12.043333,
            lng: -77.028333
        });
        map.addMarker({
            lat: -12.043333,
            lng: -77.028333,
            title: 'MetCreative Office',
            icon: 'img/maps_marker.png'
        });
    }

    /**
     * Php Ajax Contact Form
     * @usedPlugins jquery
     * @usedAt      Contact Page
     $('.met_contact_form').bind('submit', function(){
		var form    = $(this);
		var me      = $(this).children('input[type=submit]');

		me.attr('disabled','disabled');

		$.ajax({
			type: "POST",
			url: "mail.php",
			data: form.serialize(),
			success: function(returnedInfo){

				var message = $('.met_contact_thank_you');
				returnedInfo == 1 ? message.show() : message.html('Our Mail Servers Are Currently Down').show();
				me.removeAttr('disabled');

			}
		});
		return false;
	});*/

    /**
     * One By One Slider
     * @usedPlugins jquery,onecarousel,jquery.migrate,bootstrap,style.css,animate.css
     * @usedAt      Index
     */
    $('#myCarousel').oneCarousel({
        easeIn: 'rotateIn',
        interval: 5000,
        pause: 'hover'
    });

    /**
     * Menu Aligning
     * @usedPlugins jquery
     * @usedAt      Header main menu
     */
    var menuCounter = 0;
    var menuLength = $('.met_main_menu > li').length;
    $('.met_main_menu > li').each(function () {
        menuCounter += 1;
        if (menuCounter == (menuLength - 1) || menuCounter == menuLength) {
            $(this).addClass('met_menu_border_radius');
            if ($(this).children('ul').length > 0) {
                $(this).children('ul').addClass('met_menu_to_left');
            }
        }
    });

    /**
     * Reveal
     * @usedPlugins jquery,reveal
     * @usedAt      Global
     */
    if ($('body').attr('data-reveal') == 1) {
        $('.scrolled').themepileStepsShowing();
    } else {
        $('.scrolled__item').css('opacity', '1');
    }

    /**
     * Back To Top Button
     * @usedPlugins jquery
     * @usedAt      global
     */
    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#back-to-top').addClass('back-to-top-on').removeClass('back-to-top-off');
        } else {
            $('#back-to-top').addClass('back-to-top-off').removeClass('back-to-top-on');
        }
    });

    $('#back-to-top').click(function () {
        $('body,html').animate({scrollTop: 0}, 800);
    });

    /**
     * DL Menu
     * @usedPlugins jquery, dlmenu
     * @usedAt      shortcode
     */
    $('#dl-menu').dlmenu({
        animationClasses: { 'in': 'dl-animate-in-3', 'out': 'dl-animate-out-3' }
    });

    /**
     * Full Screen Background Image
     * @usedPlugins jquery
     * @usedAt      Boxed Layout Body Background Image
     */
    if ($('#met_fullScreenImg').length > 0) {
        var FullscreenrOptions = {  width: window.innerWidth, height: window.innerHeight, bgID: '#met_fullScreenImg' };
        $.fn.fullscreenr(FullscreenrOptions);
    }

    pageTitleTail();
    $(window).bind('resize', pageTitleTail);

    sticky_header();
    $(window).bind('scroll', sticky_header);

    headerWidthAdjustment();
    $(window).bind('resize', headerWidthAdjustment);
});

/**
 * Window Load for Carousels/Sliders
 * @usedPlugins jquery
 * @usedAt      global
 */
$(window).load(function () {

    /**
     * Page Title Alignment
     * @usedPlugins jquery
     * @usedAt      Every page that contains page title
     */
    $('.met_page_title p').each(function () {
        var titleHeight = $(this).height();

        $(this).css('padding-top', ((160 - titleHeight) / 2) + 'px');
    });

    /**
     * Portfolio Detail Detail Slider
     * @usedPlugins jquery, caroufredsel
     * @usedAt      portfolio detail with slider
     */
    $('#carousel').carouFredSel({
        responsive: true,
        circular: false,
        auto: false,
        items: {
            visible: 1,
            width: 651
        },
        scroll: {
            fx: 'directscroll'
        }
    });

    $('#thumbs').carouFredSel({
        responsive: true,
        circular: false,
        infinite: false,
        auto: false,
        prev: '#prev',
        next: '#next',
        items: {
            visible: {
                min: 2,
                max: 6
            },
            width: 150,
            height: '66%'
        }
    });

    $('#thumbs a').click(function () {
        $('#carousel').trigger('slideTo', '#' + this.href.split('#').pop());
        $('#thumbs a').removeClass('selected');
        $(this).addClass('selected');
        return false;
    });

    /**
     * Brand Carousel
     * @usedPlugins jquery, caroufredsel
     * @usedAt      Bottom of Index
     */
    $(".met_brand_carousel").carouFredSel({
        scroll: {
            items: 1,
            duration: 1000,
            timeoutDuration: 2000
        },
        auto: false,
        prev: ".met_brand_carousel_wrap .met_recent_works_nav_prev",
        next: ".met_brand_carousel_wrap .met_recent_works_nav_next"
    });

    /**
     * Portfolio Index
     * @usedPlugins jquery, isotope, masonry
     * @usedAt      Index 2
     */
    var mainpage_portfolio = $('.met_mainpage_portfolio');
    mainpage_portfolio.isotope({
        resizable: true,
        cellsByRow: {
            columnWidth: mainpage_portfolio.width() / 4,
            rowHeight: 341
        }
    });
    // filter items when filter link is clicked
    $('.met_mainpage_portfolio_filters li a').click(function () {
        $('.met_mainpage_portfolio_filters li a.met_color3').removeClass('met_color3');
        $(this).addClass('met_color3');
        var selector = $(this).attr('data-filter');
        mainpage_portfolio.isotope({ filter: selector });
        return false;
    });

    /**
     * Portfolio Isotope Categorization
     * @usedPlugins jquery, isotope, masonry
     * @usedAt      Portfolio List Pages
     */
    var met_portfolio_list = $('div[class*="met_portfolio_list"]');
    var met_portfolio_number = findTheNumber(met_portfolio_list.attr('class'));
    if ($('body').width() <= 320) {
        met_portfolio_number = 1;
    } else if ($('body').width() <= 480) {
        met_portfolio_number = 2;
    } else if ($('body').width() < 780) {
        met_portfolio_number = 3;
    }
    met_portfolio_list.isotope({
        resizable: true,
        fitRows: true,
        masonry: { columnWidth: met_portfolio_list.width() / met_portfolio_number }
    });

    // filter items when filter link is clicked
    $('.met_filters li a').click(function () {
        $('.met_filters li a.met_color3').removeClass('met_color3');
        $(this).addClass('met_color3');
        var selector = $(this).attr('data-filter');
        met_portfolio_list.isotope({ filter: selector });
        return false;
    });

    /**
     * Masonry Portfolio
     * @usedPlugins jquery, isotope, masonry
     * @usedAt      Portfolio List 2 Pages
     */
    met_portfolio_2 = $('.met_mason_portfolio');
    met_portfolio_2.isotope({
        resizable: true,
        width: 300 * 4,
        masonry: { columnWidth: 300 }
    });

    // filter items when filter link is clicked
    $('.met_filters li a').click(function () {
        $('.met_filters li a.met_color3').removeClass('met_color3');
        $(this).addClass('met_color3');
        var selector = $(this).attr('data-filter');
        met_portfolio_2.isotope({ filter: selector });
        return false;
    });

    /**
     * PrettyPhoto
     * @usedPlugins jquery.prettyPhoto
     * @usedAt      global
     */
    var viewportWidth = $('body').innerWidth();
    /* As Photoswipe plugin doesn't support video links for vimeo and youtube, We exclude videos from seperating prettyphoto and photoswipe */
    $("a[rel='videoPretty']").prettyPhoto({
        allow_resize: true,
        changepicturecallback: function () {
            // 1024px is presumed here to be the widest mobile device. Adjust at will.
            if (viewportWidth < 1025) {
                $(".pp_pic_holder.pp_default").css("top", window.pageYOffset + "px");
            }
        }
    });

    /**
     * Mobile Detection
     * @usedPlugins jquery, mobile_detector, prettyPhoto, photoSwipe, klass
     * @usedAt      global
     */
    if (jQuery.browser.mobile) {
        var options = {enableMouseWheel: false, enableKeyboard: false, allowUserZoom: false, captionAndToolbarAutoHideDelay: 0};
        $("a[rel^='prettyPhoto']").photoSwipe(options);
    }
    else  /*If the device is not mobile then make this block of code work*/
    {
        $("a[rel^='prettyPhoto']").prettyPhoto({
            allow_resize: true,
            changepicturecallback: function () {
                // 1024px is presumed here to be the widest mobile device. Adjust at will.
                if (viewportWidth < 1025) {
                    $(".pp_pic_holder.pp_default").css("top", window.pageYOffset + "px");
                }
            }
        });
    }

    /**
     * Skill Bar Filling
     * @usedPlugins jquery
     * @usedAt      Shortcodes
     */
    $('.met_skill_filler').each(function () {
        $(this).css('width', $(this).attr('data-skill-percentage') + '%');
    });

    /**
     * Recent Works Categorized Carousel
     * @usedPlugins jquery,caroufredsel
     * @usedAt      Recent Works block on Index Page
     */
    var _visible = 4;
    var $pagers = $('.recent_work_carousel_categories a');
    var _onBefore = function () {
        $(this).find('img').stop().fadeTo(300, 1);
        $pagers.removeClass('selected');
    };

    $('.recent_works_carousel').carouFredSel({
        items: {
            visible: {
                min: 1,
                max: _visible
            },
            width: 282
        },
        //width: '100%',
        auto: false,
        responsive: true,
        scroll: {
            duration: 750
        },
        prev: {
            button: '.met_recent_works_nav a:first-child',
            items: 1,
            onBefore: _onBefore
        },
        next: {
            button: '.met_recent_works_nav a:last-child',
            items: 1,
            onBefore: _onBefore
        }
    });

    $pagers.click(function (e) {
        e.preventDefault();

        var group = $(this).attr('href').slice(1);
        var slides = $('.recent_works_carousel > div[data-carousel-category="' + group + '"]');
        var deviation = Math.floor(( _visible - slides.length ) / 2);
        if (deviation < 0) {
            deviation = 0;
        }

        $('.recent_works_carousel').trigger('slideTo', [ $('div[data-carousel-category="' + group + '"]'), -deviation ]);
        $('.recent_works_carousel div').stop().fadeTo(300, 0.4);
        slides.stop().fadeTo(300, 1);
        $pagers.removeClass('selected');
        $(this).addClass('selected');
    });

});

/**
 * Window Resize State
 * @usedAt      global
 */
$(window).resize(function () {

    /**
     * Portfolio Lists Ordering on Resize
     * @usedPlugins jquery, isotope/masonry
     * @usedAt      Portfolio List Pages
     */
    var met_portfolio_list = $('div[class*="met_portfolio_list"]');
    var met_portfolio_number = findTheNumber(met_portfolio_list.attr('class'));
    if ($('body').width() < 320) {
        met_portfolio_number = 1;
    } else if ($('body').width() < 480) {
        met_portfolio_number = 2;
    } else if ($('body').width() < 780) {
        met_portfolio_number = 3;
    }
    met_portfolio_list.isotope({
        // update columnWidth to a percentage of container width
        masonry: { columnWidth: met_portfolio_list.width() / met_portfolio_number }
    });
});

/**
 * Global Functions
 * @usedAt global
 */

/**
 * Number Finder
 * @usedAt global, portfolio isotope filtering
 */
function findTheNumber(s) {
    var patt = /\d/g;
    return patt.exec(s);
}

/**
 * Sticky Header
 * @usedAt      global,window scroll, dom ready
 */
function sticky_header() {
    if ($('body').width() > 330) {
        if ($('.met_fixed_header').length > 0) {
            if ($(this).scrollTop() != 0) {
                $('.met_logo_container').addClass('met_logo_up');
                $('.met_page_wrapper header').css('height', '100px');
            } else {
                $('.met_page_wrapper header').css('height', '160px');
                $('.met_logo_container').removeClass('met_logo_up');
            }
        }
    }
}

/**
 * Header Width Adjustment
 * @usedPlugins jquery
 * @usedAt      Fixed Header
 */
function headerWidthAdjustment() {
    if ($('.met_boxed_layout').length > 0 && $('.met_fixed_header').length > 0) {
        $('.met_page_wrapper > header').css('width', $('.met_page_wrapper').width() + 'px');
    }
}

/**
 * Page Title H1 Tail
 * @usedPlugins jquery
 * @usedAt      Page Titles
 */
function pageTitleTail() {
    if ($('.met_page_title').length > 0) {
        var wrapperWidth = $('.met_page_wrapper').width();
        var subtract = $('section.met_content').width();
        var tailWidth = (wrapperWidth - subtract) / 2;

        if ($('.met_page_title_tail').length < 1) {
            $('.met_page_title').prepend('<div class="met_page_title_tail"></div>');
        }

        $('.met_page_title_tail').css({
            width: tailWidth + 'px',
            left: '-' + tailWidth + 'px'
        });

        if ($('body').width() < 490) {

            if ($('.met_page_title_tail_right').length < 1) {
                $('.met_page_title').append('<div class="met_page_title_tail_right"></div>');
            }

            $('.met_page_title_tail_right').css({
                width: tailWidth + 'px',
                right: '-' + tailWidth + 'px'
            });

        }
    }
}