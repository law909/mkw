/**
 * The bar chart of the revenue, sales and pay reports, fed by the controllers' chart payload
 * ({labels, datasets, stacked, legend, unit, note}); the note goes into noteSelector above the chart.
 * Returns the draw function, which replaces the previous chart.
 */
function createReportChart(canvasId, noteSelector, decimals) {
    let chart = null;
    const numFormat = new Intl.NumberFormat('hu-HU', {maximumFractionDigits: decimals || 0});

    return function (data) {
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
}
