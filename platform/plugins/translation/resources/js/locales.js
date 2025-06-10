$(() => {
    let languageTable = $('.table-language')

    languageTable.on('click', '.delete-locale-button', (event) => {
        event.preventDefault()

        $('.delete-crud-entry').data('url', $(event.currentTarget).data('url'))
        $('.modal-confirm-delete').modal('show')
    })

    $(document).on('click', '.delete-crud-entry', (event) => {
        event.preventDefault()
        $('.modal-confirm-delete').modal('hide')

        let deleteURL = $(event.currentTarget).data('url')
        Guestcms.showButtonLoading($(this))

        $httpClient
            .make()
            .delete(deleteURL)
            .then(({ data }) => {
                if (data.data) {
                    languageTable.find(`i[data-locale=${data.data}]`).unwrap()
                    $('.tooltip').remove()
                }

                languageTable.find(`.delete-locale-button[data-url="${deleteURL}"]`).closest('tr').remove()

                Guestcms.showSuccess(data.message)
            })
            .finally(() => {
                Guestcms.hideButtonLoading($(this))
            })
    })

    $(document).on('submit', '.add-locale-form', function (event) {
        event.preventDefault()
        event.stopPropagation()

        const form = $(this)
        const button = form.find('button[type="submit"]')

        Guestcms.showButtonLoading(button)

        $httpClient
            .make()
            .postForm(form.prop('action'), new FormData(form[0]))
            .then(({ data }) => {
                Guestcms.showSuccess(data.message)
                languageTable.load(`${window.location.href} .table-language > *`)
            })
            .finally(() => {
                Guestcms.hideButtonLoading(button)
            })
    })
})
