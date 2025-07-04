$(() => {
    const $treeWrapper = $('.file-tree-wrapper')
    const $formLoading = $('.tree-form-container')

    function initNestable() {
        const $dd = $(document).find('.dd')

        if (typeof $dd.nestable === 'function') {
            $dd.nestable({
                group: 1,
                maxDepth: $dd.data('depth') || 5,
                listClass: 'list-group dd-list',
                emptyClass: `dd-empty">${$dd.data('empty-text')}</div><div class="`,
                callback: function (l, e) {
                    $httpClient
                        .make()
                        .put($treeWrapper.data('update-url'), {
                            data: $dd.nestable('serialize'),
                        })
                        .then(({ data }) => {
                            Guestcms.showSuccess(data.message)
                        })
                },
            })
        }
    }

    initNestable()

    function reloadForm(data) {
        $('.tree-form-body').html(data)
        initNestable()
        Guestcms.initResources()
        Guestcms.handleCounterUp()
        if (window.EditorManagement) {
            window.EDITOR = new EditorManagement().init()
        }
        Guestcms.initMediaIntegrate()
    }

    function fetchData(url, $el) {
        Guestcms.showLoading($formLoading)
        $treeWrapper.find('.dd3-content.active').removeClass('active')

        if ($el) {
            $el.addClass('active')
        }

        $httpClient
            .make()
            .get(url)
            .then(({ data }) => reloadForm(data.data))
            .finally(() => Guestcms.hideLoading($formLoading))
    }

    $treeWrapper.on('click', '.fetch-data', (event) => {
        event.preventDefault()
        const currentTarget = $(event.currentTarget)

        if (currentTarget.data('href')) {
            fetchData(currentTarget.data('href'), currentTarget.closest('.dd3-content'))
        } else {
            $treeWrapper.find('.dd3-content.active').removeClass('active')
            currentTarget.closest('.dd3-content').addClass('active')
        }
    })

    $(document).on('click', '.tree-categories-create', (event) => {
        event.preventDefault()

        const $this = $(event.currentTarget)

        loadCreateForm($this.attr('href'))
    })

    let searchParams = new URLSearchParams(window.location.search)

    function loadCreateForm(url) {
        let data = {}
        if (searchParams.get('ref_lang')) {
            data.ref_lang = searchParams.get('ref_lang')
        }

        Guestcms.showLoading($formLoading)

        $httpClient
            .make()
            .get(url, data)
            .then(({ data }) => reloadForm(data.data))
            .finally(() => Guestcms.hideLoading($formLoading))
    }

    function reloadTree(activeId, callback) {
        $httpClient
            .make()
            .get($treeWrapper.data('url') || window.location.href)
            .then(({ data }) => {
                $treeWrapper.html(data.data)

                handleOpenCollapseState()

                if (jQuery().tooltip) {
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        placement: 'top',
                        boundary: 'window',
                    })
                }

                if (callback) {
                    callback()
                }
            })
    }

    $(document).on('click', '#list-others-language a', (event) => {
        event.preventDefault()

        fetchData($(event.currentTarget).prop('href'))
    })

    $(document).on('submit', '.tree-form-container form', (event) => {
        event.preventDefault()
        const $form = $(event.currentTarget)
        const formData = new FormData(event.currentTarget)
        const submitter = event.originalEvent?.submitter
        let saveAndEdit = false

        if (submitter && submitter.name) {
            saveAndEdit = submitter.value === 'apply'
            formData.append(submitter.name, submitter.value)
        }

        $form.find('select').each(function() {
            if ($(this).val() == null) {
                formData.append($(this).attr('name'), '')
            }
        })

        const method = $form.attr('method').toLowerCase() || 'post'

        $httpClient
            .make()
            .withLoading($formLoading)
            [method]($form.attr('action'), formData)
            .then(({ data }) => {
                Guestcms.showSuccess(data.message)

                let $createButton = $('.tree-categories-create')

                const activeId = saveAndEdit && data.data && data.data.model ? data.data.model.id : null

                reloadTree(activeId, function () {
                    if (activeId) {
                        const fetchDataButton = $(`.dd-item[data-id="${activeId}"] > .dd3-content .fetch-data`)
                        if (fetchDataButton.length) {
                            fetchDataButton.trigger('click')
                        } else {
                            location.reload()
                        }
                    } else if ($createButton.length) {
                        $createButton.trigger('click')
                    } else {
                        reloadForm(data.data?.form)
                    }
                })
            })
            .finally(() => $form.find('button[type=submit]').prop('disabled', false).removeClass('disabled'))
    })

    $(document)
        .on('show.bs.modal', '.modal-confirm-delete', (event) => {
            $(event.currentTarget)
                .find('[data-bb-toggle="modal-confirm-delete"]')
                .attr('data-url', $(event.relatedTarget).data('url'))
        })
        .on('click', '[data-bb-toggle="modal-confirm-delete"]', (event) => {
            event.preventDefault()

            const button = $(event.currentTarget)

            $httpClient
                .make()
                .withButtonLoading(button)
                .delete($(button).get(0).dataset.url)
                .then(({ data }) => {
                    Guestcms.showSuccess(data.message)

                    reloadTree()

                    let $createButton = $('.tree-categories-create')
                    if ($createButton.length) {
                        $createButton.trigger('click')
                    } else {
                        reloadForm('')
                    }
                })
                .finally(() => button.closest('.modal').modal('hide'))
        })

    function handleOpenCollapseState() {
        // Restore state from localStorage
        $treeWrapper.find('.dd-item').each(function () {
            const categoryId = $(this).data('id')
            const isCollapsed = localStorage.getItem('category_' + categoryId) === 'collapsed'

            if (isCollapsed) {
                $(this).addClass('dd-collapsed')
                $(this).find('.dd-list').hide()
            } else {
                $(this).removeClass('dd-collapsed')
                $(this).find('.dd-list').show()
            }
        })

        // Handle collapse button click
        $treeWrapper.on('click', '.dd-collapse', function () {
            const categoryItem = $(this).closest('.dd-item')
            const categoryId = categoryItem.data('id')

            categoryItem.addClass('dd-collapsed')
            categoryItem.find('.dd-list').slideUp()
            localStorage.setItem('category_' + categoryId, 'collapsed')
        })

        // Handle expand button click
        $treeWrapper.on('click', '.dd-expand', function () {
            const categoryItem = $(this).closest('.dd-item')
            const categoryId = categoryItem.data('id')

            categoryItem.removeClass('dd-collapsed')
            categoryItem.find('.dd-list').slideDown()
            localStorage.setItem('category_' + categoryId, 'expanded')
        })
    }

    $(document).ready(function () {
        handleOpenCollapseState()
    })
})
