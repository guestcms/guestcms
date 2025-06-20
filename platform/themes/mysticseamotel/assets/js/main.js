/*---------------------------
	JS INDEX
	===================
	01. Main Menu
	02. Banner Slider
	03. ROOM Slider(Big)
	04. Testimonial Slider
	05. Latest Post Slider
	06. Feature Room Slider
	07. CounterUp
	08. Instagram Feed Slider
	09. Food Menu Slider
	10. Gallery Sliders & Popup
	11. Room Slider Two
	12. Banner Image Slider
	13. offCanvas Active
	14. init extra plugin
	15. Active Gallery And Video Popup
	16. Search Form
	17. Preloader
	18. Back to top
	19. Sticky header

-----------------------------*/

let mysticseamotelDoc

;(function ($) {
    'use strict'

    const isRTL = $('body').prop('dir') === 'rtl'

    mysticseamotelDoc = {
        init: function () {
            this.mainMenu()
            this.bannerSlider()
            this.roomSlider()
            this.testimonialSlider()
            this.latestPostlider()
            this.featureRoom()
            this.roomDetailsSlider()
            this.counterToUp()
            this.instaFeedSlider()
            this.menuSlider()
            this.gallery()
            this.roomSliderTwo()
            this.bannerImgSlider()
            this.offCanvas()
            this.extraPlugin()
            this.popUpExtra()
            this.searchForm()
        },

        //===== 01. Main Menu
        mainMenu() {
            // Variables
            let var_window = $(window),
                navContainer = $('.nav-container'),
                pushedWrap = $('.nav-pushed-item'),
                pushItem = $('.nav-push-item'),
                pushedHtml = pushItem.html(),
                pushBlank = '',
                navbarToggler = $('.navbar-toggler'),
                navMenu = $('.nav-menu'),
                navMenuLi = $('.nav-menu ul li'),
                closeIcon = $('.navbar-close')

            // navbar toggler
            navbarToggler.on('click', function () {
                navbarToggler.toggleClass('active')
                navMenu.toggleClass('menu-on')
            })

            // close icon
            closeIcon.on('click', function () {
                navMenu.removeClass('menu-on')
                navbarToggler.removeClass('active')
            })

            // adds toggle button to li items that have children
            navMenu.find('li a').each(function () {
                if ($(this).next().length > 0) {
                    $(this).parent('li').append('<span class="dd-trigger"><i class="fal fa-angle-down"></i></span>')
                }
            })

            // expands the dropdown menu on each click
            navMenu.find('li .dd-trigger').on('click', function (e) {
                e.preventDefault()
                $(this).parent('li').children('ul').stop(true, true).slideToggle(350)
                $(this).parent('li').toggleClass('active')
            })

            // check browser width in real-time
            function breakpointCheck() {
                let windoWidth = window.innerWidth
                if (windoWidth <= 991) {
                    navContainer.addClass('breakpoint-on')

                    pushedWrap.html(pushedHtml)
                    pushItem.hide()
                } else {
                    navContainer.removeClass('breakpoint-on')

                    pushedWrap.html(pushBlank)
                    pushItem.show()
                }
            }

            breakpointCheck()
            var_window.on('resize', function () {
                breakpointCheck()
            })
        },

        //===== 02. Banner Slider
        bannerSlider() {
            let bannerSliderOne = $('#bannerSlider')

            bannerSliderOne.on('init', function () {
                let $firstAnimatingElements = $('.single-banner:first-child').find('[data-animation]')
                doAnimations($firstAnimatingElements)
            })

            bannerSliderOne.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
                let $animatingElements = $('.single-banner[data-slick-index="' + nextSlide + '"]').find(
                    '[data-animation]'
                )
                doAnimations($animatingElements)
            })

            // active banner slider
            bannerSliderOne.slick({
                rtl: isRTL,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 5000,
                dots: false,
                fade: true,
                arrows: false,
                prevArrow: '<div class="slick-arrow prev-arrow"><i class="fal fa-arrow-left"></i></div>',
                nextArrow: '<div class="slick-arrow next-arrow"><i class="fal fa-arrow-right"></i></div>',
            })

            // Do for slider animation
            function doAnimations(elements) {
                let animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend'
                elements.each(function () {
                    let $this = $(this)
                    let $animationDelay = $this.data('delay')
                    let $animationType = 'animated ' + $this.data('animation')
                    $this.css({
                        'animation-delay': $animationDelay,
                        '-webkit-animation-delay': $animationDelay,
                    })
                    $this.addClass($animationType).one(animationEndEvents, function () {
                        $this.removeClass($animationType)
                    })
                })
            }
        },

        //===== 03. ROOM Slider (On Home Page One)
        roomSlider() {
            let sliderImg = $('.rooms-slider-one'),
                sliderContent = $('.room-content-slider'),
                countStatus = $('.slider-count'),
                countBig = $('.slider-count-big')

            sliderImg.slick({
                rtl: isRTL,
                slidesToShow: 3,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: false,
                dots: false,
                centerMode: true,
                centerPadding: '6%',
                asNavFor: sliderContent,
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 1,
                            centerPadding: '15%',
                        },
                    },
                ],
            })

            sliderContent.slick({
                rtl: isRTL,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: false,
                dots: true,
                asNavFor: sliderImg,
            })

            sliderContent.on('init reInit afterChange', function (event, slick, currentSlide) {
                if (!slick.$dots) {
                    return
                }
                let i = (currentSlide ? currentSlide : 0) + 1
                let statusText = i > 10 ? i : '0' + i
                countStatus.html('<span class="current">' + statusText + '</span>/' + slick.$dots[0].children.length)
                countBig.html('<span >' + statusText + '</span> ')
            })
        },

        //===== 04. Testimonial Slider
        testimonialSlider() {
            let tslider = $('.testimonial-slider')
            tslider.slick({
                rtl: isRTL,
                slidesToShow: 3,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: false,
                dots: true,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            })
        },

        //===== 05. Latest Post Slider
        latestPostlider() {
            let tslider = $('.latest-post-slider')
            let arrows = $('.latest-post-arrow')
            tslider.slick({
                rtl: isRTL,
                slidesToShow: 3,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: true,
                dots: false,
                prevArrow: '<div class="slick-arrow prev-arrow"><i class="fal fa-arrow-left"></i></div>',
                nextArrow: '<div class="slick-arrow next-arrow"><i class="fal fa-arrow-right"></i></div>',
                appendArrows: arrows,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            })
        },

        //===== 06. Feature Room Slider
        featureRoom() {
            let fslider = $('.feature-room-slider')
            let arrows = $('.feature-room-arrow')
            fslider.slick({
                rtl: isRTL,
                slidesToShow: 3,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: true,
                dots: false,
                prevArrow: '<div class="slick-arrow prev-arrow"><i class="fal fa-arrow-left"></i></div>',
                nextArrow: '<div class="slick-arrow next-arrow"><i class="fal fa-arrow-right"></i></div>',
                appendArrows: arrows,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            })
        },

        //===== 07. CounterUp
        counterToUp() {
            $('.counter-box').bind('inview', function (event, visible) {
                if (visible) {
                    $(this)
                        .find('.counter')
                        .each(function () {
                            let $this = $(this)
                            $({Counter: 0}).animate(
                                {Counter: $this.text()},
                                {
                                    duration: 2000,
                                    easing: 'swing',
                                    step: function () {
                                        $this.text(Math.ceil(this.Counter))
                                    },
                                }
                            )
                        })
                    $(this).unbind('inview')
                }
            })
        },

        //===== 08. Instagram Feed Slider
        instaFeedSlider() {
            let tslider = $('.instagram-slider')
            tslider.slick({
                rtl: isRTL,
                slidesToShow: 6,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: false,
                dots: false,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 4,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                ],
            })

            // Init magnificPopup on Instagram Gallery
            if (jQuery().magnificPopup) {
                $('.instagram-slider').each(function () {
                    // the containers for all your galleries
                    let additionalImages = $('.image a.insta-popup').not('.slick-slide.slick-cloned a.insta-popup')
                    additionalImages.magnificPopup({
                        type: 'image',
                        gallery: {
                            enabled: true,
                        },
                        mainClass: 'mfp-fade',
                    })
                })
            }
        },

        //===== 09. Food Menu SLider
        menuSlider() {
            let mslider = $('.menu-slider')
            let arrows = $('.menu-slider-arrow')
            mslider.slick({
                rtl: isRTL,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: true,
                dots: false,
                prevArrow: '<div class="slick-arrow prev-arrow"><i class="fal fa-arrow-left"></i></div>',
                nextArrow: '<div class="slick-arrow next-arrow"><i class="fal fa-arrow-right"></i></div>',
                appendArrows: arrows,
            })
        },

        //===== 10. Gallery Sliders & Popup
        gallery() {
            let gslider = $('.gallery-slider')
            gslider.slick({
                rtl: isRTL,
                slidesToShow: 3,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: false,
                dots: false,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                        },
                    },
                ],
            })

            // Init magnificPopup on Menu Gallery
            if (jQuery().magnificPopup) {
                $('.gallery-slider').each(function () {
                    // the containers for all your galleries
                    let additionalImages = $('.slick-slide a.gallery-popup').not(
                        '.slick-slide.slick-cloned a.gallery-popup'
                    )
                    additionalImages.magnificPopup({
                        type: 'image',
                        gallery: {
                            enabled: true,
                        },
                        mainClass: 'mfp-fade',
                    })
                })
            }
        },

        //===== 11. Room Slider Two (on Home Page Three)
        roomSliderTwo() {
            let sliderTwo = $('.rooms-slider-two')
            sliderTwo.slick({
                rtl: isRTL,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: false,
                infinite: true,
                autoplay: false,
                autoplaySpeed: 4000,
                arrows: true,
                dots: false,
                centerMode: true,
                centerPadding: '28%',
                prevArrow: '<div class="slick-arrow prev-arrow"><i class="fal fa-arrow-left"></i></div>',
                nextArrow: '<div class="slick-arrow next-arrow"><i class="fal fa-arrow-right"></i></div>',
                responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            centerPadding: '20%',
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            centerPadding: '15%',
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            centerPadding: '10%',
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            centerPadding: '5%',
                        },
                    },
                ],
            })
        },

        //===== Room Details Slider
        roomDetailsSlider() {
            let roomDetailsSlider = $('.room-details-slider')
            if (roomDetailsSlider.length) {
                let roomDetailsSliderNav = $('.room-details-slider-nav')
                roomDetailsSlider.slick({
                    rtl: isRTL,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    autoplay: false,
                    arrows: false,
                    dots: false,
                    asNavFor: '.room-details-slider-nav',
                })

                roomDetailsSlider.lightGallery({
                    selector: 'a',
                    thumbnail: true,
                    share: false,
                    fullScreen: false,
                    autoplay: false,
                    autoplayControls: false,
                    actualSize: false,
                })

                roomDetailsSliderNav.slick({
                    rtl: isRTL,
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    asNavFor: '.room-details-slider',
                    dots: false,
                    arrows: false,
                    centerMode: false,
                    focusOnSelect: true,
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 4,
                            },
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 3,
                            },
                        },
                    ],
                })
            }
        },

        //===== 12. Banner Image Slider
        bannerImgSlider() {
            let sliderOne = $('.hero-slider-one')
            sliderOne.slick({
                rtl: isRTL,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 4000,
                arrows: false,
                dots: false,
            })
        },

        //===== 13. offCanvas Active
        offCanvas() {
            // Set Click Function For open
            $('#offCanvasBtn').on('click', function (e) {
                e.preventDefault()
                $('.offcanvas-wrapper').addClass('show-offcanvas')
                $('.offcanvas-overly').addClass('show-overly')
            })
            // Set Click Function For Close
            $('.offcanvas-close').on('click', function (e) {
                e.preventDefault()
                $('.offcanvas-overly').removeClass('show-overly')
                $('.offcanvas-wrapper').removeClass('show-offcanvas')
            })
            // Set Click Function on Overly For open on
            $('.offcanvas-overly').on('click', function () {
                $(this).removeClass('show-overly')
                $('.offcanvas-wrapper').removeClass('show-offcanvas')
            })
        },

        //===== 14. init extra plugin
        extraPlugin() {
            // init nice selects
            $('select:not(.ignore-nice-select)').niceSelect()

            // init datepicker
            this.initDatePicker()

            // init wow js
            new WOW().init()
        },

        initDatePicker() {
            if ($('.date-picker').length > 0) {
                const date = new Date();
                const today = new Date(date.getFullYear(), date.getMonth(), date.getDate());

                $('.date-picker').each(function () {
                    const options = {
                        autoclose: true,
                        startDate: today,
                    }

                    const language = $(this).data('locale')

                    if (language) {
                        options.language = language
                    }

                    const dateFormat = $(this).data('date-format')

                    if (dateFormat) {
                        options.format = dateFormat
                    }

                    $(this).datepicker(options)
                })

                $(document).on('change', '#arrival-date', function () {
                    const date = $(this).datepicker('getDate')

                    date.setTime(date.getTime() + (1000 * 60 * 60 * 24))

                    $('#departure-date').datepicker('setDate', date).datepicker('option', 'startDate', date)
                })
            }
        },

        //===== 15. Active Gallery And Video Popup
        popUpExtra() {
            // Init magnificPopup on Popup Video
            if (jQuery().magnificPopup) {
                $('.popup-video').magnificPopup({
                    type: 'iframe',
                })

                // Init magnificPopup on Gallery
                $('.gallery-loop .popup-image').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true,
                    },
                    mainClass: 'mfp-fade',
                })
            }
        },

        //===== 16. Search Form
        searchForm() {
            // Set Click Function For open
            $('#searchBtn').on('click', function (e) {
                e.preventDefault()
                $('.search-form').slideToggle(350)
                $(this).toggleClass('active')
            })
        },
    }

    // Document Ready
    $(document).ready(function () {
        mysticseamotelDoc.init()
    })

    $(document).ready(function () {
        $('.room-book').on('click', function (e) {
            e.preventDefault()
            let confirmbtn = $(this).next('.confirm-btn')
            let itembox = $(this).parent().prev('.item-boxs')
            confirmbtn.toggleClass('d-flex')
            itembox.slideToggle('d-flex')
        })

        $('.remove-item').on('click', function (e) {
            e.preventDefault()

            let hideitem = $(this).closest('.item-boxs')
            let confirmbtn = hideitem.next('.actions').find('.confirm-btn')

            hideitem.hide()
            confirmbtn.toggleClass('d-flex')
        })
    })

    // Window Load
    $(window).on('load', function () {
        //===== 17. Preloader
        $('.preloader').fadeOut('slow', function () {
            $(this).remove()
        })

        //===== 18. Back to top
        $('#backToTop').on('click', function (e) {
            e.preventDefault()
            $('html, body').animate(
                {
                    scrollTop: '0',
                },
                1200
            )
        })
    })

    // Window Scroll
    $(window).on('scroll', function () {
        //===== 19. Sticky header
        let scroll = $(window).scrollTop()
        if (scroll < 150) {
            $('.sticky-header').removeClass('sticky-active')
        } else {
            $('.sticky-header').addClass('sticky-active')
        }

        //===== 20. Scroll Event on back to top
        if (scroll > 300) $('#backToTop').addClass('active')
        if (scroll < 300) $('#backToTop').removeClass('active')
    })

    $(document).ready(function () {
        $('.service-item').on('change', function () {
            const services = []
            $('.service-item:checked').each((i, el) => {
                services[i] = $(el).val()
            })

            $('body').css('cursor', 'progress')
            $('.custom-checkbox label').css('cursor', 'progress')

            let $checkoutButton = $(document).find('.payment-checkout-btn')
            $checkoutButton.prop('disabled', true)
            let $selectedPaymentMethod = $(document).find('.payment-checkout-form .list_payment_method input[name="payment_method"]:checked').val()

            $.ajax({
                type: 'GET',
                cache: false,
                url: '/ajax/calculate-amount',
                data: {
                    room_id: $('input[name=room_id]').val(),
                    start_date: $('input[name=start_date]').val(),
                    end_date: $('input[name=end_date]').val(),
                    rooms: $('input[name=rooms]').val(),
                    services,
                },
                success: (res) => {
                    if (!res.error) {
                        $('.total-amount-text').text(res.data.total_amount)
                        $('.amount-text').text(res.data.sub_total)
                        $('.tax-text').text(res.data.tax_amount)
                        $('.discount-text').text(res.data.discount_amount)
                        $('input[name=amount]').val(res.data.amount_raw)
                    }

                    $('body').css('cursor', 'default')
                    $('.custom-checkbox label').css('cursor', 'pointer')

                    $('.payment-checkout-form .list_payment_method').load(window.location.href + ' .payment-checkout-form .list_payment_method > *', function () {
                        $checkoutButton.prop('disabled', false)
                        $(document).find('.payment-checkout-form .list_payment_method input[value="' + $selectedPaymentMethod + '"]').prop('checked', true).trigger('change')
                    })
                },
                error: () => {
                    $('body').css('cursor', 'default')
                    $('.custom-checkbox label').css('cursor', 'pointer')
                },
            })
        })
    })

    let showError = (message) => {
        window.showAlert('alert-danger', message)
    }

    let showSuccess = (message) => {
        window.showAlert('alert-success', message)
    }

    let handleError = (data) => {
        if (typeof data.errors !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors)
        } else if (typeof data.responseJSON !== 'undefined') {
            if (typeof data.responseJSON.errors !== 'undefined') {
                if (data.status === 422) {
                    handleValidationError(data.responseJSON.errors)
                }
            } else if (typeof data.responseJSON.message !== 'undefined') {
                showError(data.responseJSON.message)
            } else {
                $.each(data.responseJSON, (index, el) => {
                    $.each(el, (key, item) => {
                        showError(item)
                    })
                })
            }
        } else {
            showError(data.statusText)
        }
    }

    let handleValidationError = (errors) => {
        let message = ''

        $.each(errors, (index, item) => {
            if (message !== '') {
                message += '<br />'
            }
            message += item
        })

        showError(message)
    }

    window.showAlert = (messageType, message) => {
        if (messageType && message !== '') {
            let alertId = Math.floor(Math.random() * 1000)

            let html =
                `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
                <span class="close fa fa-times-circle" data-dismiss="alert" aria-label="close"></span>
                <i class="fas fa-` +
                (messageType === 'alert-success' ? 'check-circle' : 'exclamation-circle') +
                ` message-icon"></i>
                ${message}
            </div>`

            $('#alert-container')
                .append(html)
                .ready(() => {
                    window.setTimeout(() => {
                        $(`#alert-container #${alertId}`).remove()
                    }, 6000)
                })
        }
    }

    const refreshCoupon = () => {
        const services = []
        $('.service-item:checked').each((i, el) => {
            services[i] = $(el).val()
        })

        let $checkoutButton = $(document).find('.payment-checkout-btn')

        $checkoutButton.prop('disabled', true)

        let $selectedPaymentMethod = $(document).find('.payment-checkout-form .list_payment_method input[name="payment_method"]:checked').val()

        $.ajax({
            url: '/ajax/calculate-amount',
            type: 'GET',
            data: {
                room_id: $('input[name=room_id]').val(),
                start_date: $('input[name=start_date]').val(),
                end_date: $('input[name=end_date]').val(),
                rooms: $('input[name=rooms]').val(),
                services,
            },
            success: ({error, message, data}) => {
                if (error) {
                    showError(message)

                    return
                }

                $('.total-amount-text').text(data.total_amount)
                $('input[name=amount]').val(data.amount_raw)
                $('.amount-text').text(data.sub_total)
                $('.discount-text').text(data.discount_amount)
                $('.tax-text').text(data.tax_amount)

                $('.payment-checkout-form .list_payment_method').load(window.location.href + ' .payment-checkout-form .list_payment_method > *', function () {
                    $checkoutButton.prop('disabled', false)
                    $(document).find('.payment-checkout-form .list_payment_method input[value="' + $selectedPaymentMethod + '"]').prop('checked', true).trigger('change')
                })

                const refreshUrl = $('.order-detail-box').data('refresh-url')

                $.ajax({
                    url: refreshUrl,
                    type: 'GET',
                    data: {
                        coupon_code: $('input[name=coupon_hidden]').val() ?? $('input[name=coupon_code]').val(),
                    },
                    success: ({error, message, data}) => {
                        if (error) {
                            showError(message)

                            return
                        }

                        $('.order-detail-box').html(data)
                    },
                    error: (error) => {
                        handleError(error)
                    },
                })
            },
            error: (error) => {
                handleError(error)
            },
        })
    }

    $(document)
        .on('click', '.toggle-coupon-form', () => $(document).find('.coupon-form').toggle('fast'))
        .on('click', '.apply-coupon-code', (e) => {
            e.preventDefault()

            const $button = $(e.currentTarget)

            $.ajax({
                url: $button.data('url'),
                type: 'POST',
                data: {
                    coupon_code: $('input[name=coupon_code]').val(),
                    _token: $button.closest('form').find('input[name="_token"]').val()
                },
                beforeSend: () => {
                    $button.addClass('button-loading')
                },
                success: ({error, message}) => {
                    if (error) {
                        showError(message)

                        return
                    }

                    showSuccess(message)

                    refreshCoupon()
                },
                error: (error) => {
                    handleError(error)
                },
                complete: () => {
                    $button.removeClass('button-loading')
                }
            })
        })
        .on('click', '.remove-coupon-code', (e) => {
            e.preventDefault()

            const $button = $(e.currentTarget)

            $.ajax({
                url: $button.data('url'),
                type: 'POST',
                data: {
                    _token: $button.closest('form').find('input[name="_token"]').val()
                },
                beforeSend: () => {
                    $button.addClass('button-loading')
                },
                success: ({message, error}) => {
                    if (error) {
                        showError(message)

                        return
                    }

                    showSuccess(message)

                    refreshCoupon()
                },
                error: (error) => {
                    handleError(error)
                },
                complete: () => {
                    $button.removeClass('button-loading')
                },
            })
        })

    require('../../../../../platform/plugins/language/resources/js/language-public')

    $('a.down-arrow').on('click', function (event) {
        event.preventDefault();
        const targetID = $(event.currentTarget).attr('href');

        $([document.documentElement, document.body]).animate({
            scrollTop: $(targetID).offset().top
        }, 1000);
    });

    $(document).on('click', '[data-bb-toggle="decrement-room"]', (e) => {
        const currentTarget = $(e.currentTarget)
        const $input = currentTarget.closest('.input-quantity').find('input')
        const inputName = $input.prop('name')
        const min = parseInt($input.prop('min'))

        let value = parseInt($input.val())
        if (value > min) {
            $input.val(value - 1)
            $(`[data-bb-toggle="filter-${inputName}-count"]`).text(value - 1)
        }
    })

    $(document)
        .on('click', '[data-bb-toggle="increment-room"]', (e) => {
            const currentTarget = $(e.currentTarget)
            const $input = currentTarget.closest('.input-quantity').find('input')
            const inputName = $input.prop('name')
            const max = parseInt($input.prop('max'))

            let value = parseInt($input.val())
            if (value < max) {
                $input.val(value + 1)
                $(`[data-bb-toggle="filter-${inputName}-count"]`).text(value + 1)
            }
        })
        .on('click', '[data-bb-toggle="toggle-guests-and-rooms"]', (e) => {
            const currentTarget = $(e.currentTarget)
            const $target = $(currentTarget.data('target'))

            $target.toggle('fast')
        })
})(jQuery)
