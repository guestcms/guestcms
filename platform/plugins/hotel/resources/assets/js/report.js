import SalesReportsChart from './components/SalesReportsChart'
import RevenueChart from './components/RevenueChart'

if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('sales-reports-chart', SalesReportsChart)
        vue.component('revenue-chart', RevenueChart)
    })
}

$(() => {
    if (!window.moment || !jQuery().daterangepicker) {
        return
    }

    moment.locale($('html').attr('lang'))

    let $dateRange = $(document).find('.date-range-picker')
    let dateFormat = $dateRange.data('format') || 'YYYY-MM-DD'
    let startDate = $dateRange.data('start-date') || moment().subtract(29, 'days')

    let today = moment()
    let endDate = moment().endOf('month')
    if (endDate > today) {
        endDate = today
    }
    let rangesTrans = GuestcmsVariables.languages.reports
    let ranges = {
        [rangesTrans.today]: [today, today],
        [rangesTrans.this_week]: [moment().startOf('week'), today],
        [rangesTrans.last_7_days]: [moment().subtract(6, 'days'), today],
        [rangesTrans.last_30_days]: [moment().subtract(29, 'days'), today],
        [rangesTrans.this_month]: [moment().startOf('month'), endDate],
        [rangesTrans.this_year]: [moment().startOf('year'), moment().endOf('year')],
    }

    $dateRange.daterangepicker(
        {
            ranges: ranges,
            alwaysShowCalendars: true,
            startDate: startDate,
            endDate: endDate,
            maxDate: endDate,
            opens: 'left',
            drops: 'auto',
            locale: {
                format: dateFormat,
            },
            autoUpdateInput: false,
        },
        function (start, end, label) {
            $.ajax({
                url: $dateRange.data('href'),
                data: {
                    date_from: start.format('YYYY-MM-DD'),
                    date_to: end.format('YYYY-MM-DD'),
                    predefined_range: label,
                },
                type: 'GET',
                success: (data) => {
                    if (data.error) {
                        Guestcms.showError(data.message)
                    } else {
                        if (!$('#report-stats-content').length) {
                            const newUrl = new URL(window.location.href)

                            newUrl.searchParams.set('date_from', start.format('YYYY-MM-DD'))
                            newUrl.searchParams.set('date_to', end.format('YYYY-MM-DD'))

                            history.pushState({ urlPath: newUrl.href }, '', newUrl.href)

                            window.location.reload()
                        } else {
                            $('.widget-item').each((key, widget) => {
                                const widgetEl = $(widget).prop('id')

                                if (! widgetEl) {
                                    return
                                }

                                $(`#${widgetEl}`).replaceWith($(data.data).find(`#${widgetEl}`))
                            })
                        }

                        if (window.LaravelDataTables) {
                            Object.keys(window.LaravelDataTables).map((key) => {
                                let table = window.LaravelDataTables[key]
                                let url = new URL(table.ajax.url())
                                url.searchParams.set('date_from', start.format('YYYY-MM-DD'))
                                url.searchParams.set('date_to', end.format('YYYY-MM-DD'))
                                table.ajax.url(url.href).load()
                            })
                        }
                    }
                },
                error: (data) => {
                    Guestcms.handleError(data)
                },
            })
        }
    )

    $dateRange.on('apply.daterangepicker', function (ev, picker) {
        let $this = $(this)
        let formatValue = $this.data('format-value')
        if (!formatValue) {
            formatValue = '__from__ - __to__'
        }
        let value = formatValue
            .replace('__from__', picker.startDate.format(dateFormat))
            .replace('__to__', picker.endDate.format(dateFormat))
        $this.find('span').text(value)
    })
})
