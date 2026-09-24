/**
 * The bar chart of the revenue, sales, commission and pay reports, fed by the controllers' chart payload
 * ({labels, datasets, stacked, legend, unit, note}); the note goes into noteSelector above the chart.
 * Returns the draw function, which replaces the previous chart; its toImage() gives the chart as a PNG data URL.
 */
function createReportChart(canvasId, noteSelector, decimals) {
    let chart = null;
    const numFormat = new Intl.NumberFormat('hu-HU', {maximumFractionDigits: decimals || 0});

    const draw = function (data) {
        const unit = data.unit || '';
        $(noteSelector).text(data.note || '').toggle(!!data.note);
        if (chart) {
            chart.destroy();
            chart = null;
        }
        if (typeof Chart === 'undefined' || !data.labels.length) {
            return;
        }
        chart = new Chart(document.getElementById(canvasId), {
            type: 'bar',
            data: {labels: data.labels, datasets: data.datasets},
            plugins: [chartValueLabels],
            options: {
                maintainAspectRatio: false,
                layout: {padding: {top: 18}},
                scales: {
                    x: {stacked: data.stacked},
                    y: {stacked: data.stacked, ticks: {callback: (value) => numFormat.format(value)}}
                },
                plugins: {
                    legend: {display: data.legend},
                    valueLabels: {format: (value) => numFormat.format(value)},
                    tooltip: {callbacks: {label: (ctx) => `${ctx.dataset.label}: ${numFormat.format(ctx.parsed.y)} ${unit}`.trim()}}
                }
            }
        });
    };

    draw.toImage = () => {
        if (!chart) {
            return '';
        }
        // the final frame, not one halfway through the animation
        chart.stop();
        chart.update('none');
        return chart.toBase64Image('image/png');
    };

    return draw;
}

/**
 * The PDF button (.js-pdfbutton) of a report: refreshes the page first, so the table and the chart in the PDF belong
 * to the current filters, then posts the filters and the chart image to url into a new window.
 * refresh returns a promise, getParams the request data of refresh.
 */
function initReportPdfButton(url, refresh, getParams, draw) {
    const target = 'reportpdf';
    $('.js-pdfbutton').on('click', (e) => {
        e.preventDefault();
        // opened within the click: a window opened after the ajax call would be blocked as a popup
        const win = window.open('', target);
        refresh().then(() => {
            const $form = $('<form method="post" style="display: none">').attr({action: url, target});
            const add = (name, value) => $('<input type="hidden">').attr('name', name).val(value).appendTo($form);
            for (const [name, value] of Object.entries({...getParams(), chart: draw.toImage()})) {
                if (Array.isArray(value)) {
                    value.forEach((v) => add(`${name}[]`, v));
                } else if (value !== undefined && value !== null) {
                    add(name, value);
                }
            }
            $form.appendTo('body').submit().remove();
        }, () => win && win.close());
    }).button();
}
