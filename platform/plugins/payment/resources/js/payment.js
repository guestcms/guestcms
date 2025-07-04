'use strict'

var BPayment = BPayment || {}

BPayment.initResources = function () {
    let paymentMethod = $(document).find('input[name=payment_method]:checked').first()

    if (!paymentMethod.length) {
        paymentMethod = $(document).find('input[name=payment_method]').first()
        paymentMethod.trigger('click').trigger('change')
    }

    if (paymentMethod.length) {
        paymentMethod.closest('.list-group-item').find('.payment_collapse_wrap').addClass('show')
    }

    if ($('.stripe-card-wrapper').length > 0) {
        new Card({
            // a selector or DOM element for the form where users will
            // be entering their information
            form: '.payment-checkout-form', // *required*
            // a selector or DOM element for the container
            // where you want the card to appear
            container: '.stripe-card-wrapper', // *required*

            formSelectors: {
                numberInput: 'input#stripe-number', // optional — default input[name="number"]
                expiryInput: 'input#stripe-exp', // optional — default input[name="expiry"]
                cvcInput: 'input#stripe-cvc', // optional — default input[name="cvc"]
                nameInput: 'input#stripe-name', // optional - defaults input[name="name"]
            },

            width: 350, // optional — default 350px
            formatting: true, // optional - default true

            // Strings for translation - optional
            messages: {
                validDate: 'valid\ndate', // optional - default 'valid\nthru'
                monthYear: 'mm/yyyy', // optional - default 'month/year'
            },

            // Default placeholders for rendered fields - optional
            placeholders: {
                number: '•••• •••• •••• ••••',
                name: 'Full Name',
                expiry: '••/••',
                cvc: '•••',
            },

            masks: {
                cardNumber: '•', // optional - mask card number
            },

            // if true, will log helpful messages for setting up Card
            debug: false, // optional - default false
        })
    }
}

BPayment.init = function () {
    BPayment.initResources()

    $(document).on('change', '.js_payment_method', function (event) {
        event.preventDefault()

        $('.payment_collapse_wrap').removeClass('collapse').removeClass('show').removeClass('active')

        $(event.currentTarget)
            .closest('.list-group-item')
            .find('.payment_collapse_wrap')
            .addClass('show')
            .addClass('active')
    })

    $(document)
        .off('click', '.payment-checkout-btn')
        .on('click', '.payment-checkout-btn', function (event) {
            event.preventDefault()

            const button = $(event.currentTarget)
            const form = button.closest('form')
            const submitInitialText = button.html()

            if (form.valid && !form.valid()) {
                return
            }

            button.prop('disabled', true)
            button.html(
                `<span class="spinner-border spinner-border-sm me-2" role="status"></span> ${button.data('processing-text')}`
            )

            if ($('input[name=payment_method]:checked').val() === 'stripe' && $('.stripe-card-wrapper').length > 0) {
                Stripe.setPublishableKey($('#payment-stripe-key').data('value'))
                Stripe.card.createToken(form, function (status, response) {
                    if (response.error) {
                        if (typeof Guestcms != 'undefined') {
                            Guestcms.showError(response.error.message, button.data('error-header'))
                        } else {
                            alert(response.error.message)
                        }
                        button.prop('disabled', false)
                        button.html(submitInitialText)
                    } else {
                        form.append($('<input type="hidden" name="stripeToken">').val(response.id))
                        form.submit()
                    }
                })
            } else {
                form.submit()
            }
        })
}

$(document).ready(function () {
    BPayment.init()

    document.addEventListener('payment-form-reloaded', function () {
        BPayment.initResources()
    })
})
