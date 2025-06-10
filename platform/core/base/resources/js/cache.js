class CacheManagement {
    init() {
        $(document).on('click', '.btn-clear-cache', (event) => {
            event.preventDefault()

            let _self = $(event.currentTarget)

            Guestcms.showButtonLoading(_self)

            $httpClient
                .make()
                .post(_self.data('url'), { type: _self.data('type') })
                .then(({ data }) => Guestcms.showSuccess(data.message))
                .finally(() => Guestcms.hideButtonLoading(_self))
        })
    }
}

$(() => {
    new CacheManagement().init()
})
