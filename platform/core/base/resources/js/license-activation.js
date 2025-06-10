'use strict'

$(() => {
    $(document).on('submit', '[data-bb-toggle="activate-license"]', function (e) {
        e.preventDefault()

        const $form = $(this)
        const formData = new FormData(e.currentTarget)

        Guestcms.showLoading($form[0])

        $httpClient
            .make()
            .postForm($form.prop('action'), formData)
            .then(({ data }) => {
                Guestcms.showSuccess(data.message)

                if ($form.data('reload')) {
                    setTimeout(() => {
                        window.location.reload()
                    }, 1000)

                    return
                }

                let redirect = $form.data('redirect')

                if (redirect) {
                    window.location.assign(redirect)
                }
            })
            .finally(() => {
                Guestcms.hideLoading($form[0])
            })
    })
})
