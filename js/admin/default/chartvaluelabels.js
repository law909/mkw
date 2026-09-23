/**
 * Chart.js plugin: writes the value on each bar. Stacked charts get the segment values inside
 * the segments (where they fit) and the column total above the column.
 * Usage: new Chart(el, {..., plugins: [chartValueLabels], options: {plugins: {valueLabels: {format: fn}}}})
 */
const chartValueLabels = (function () {

    const compact = new Intl.NumberFormat('hu-HU', {notation: 'compact', maximumFractionDigits: 1});

    // falls back to the compact form ("12,3 M") when the full number is wider than the bar
    function fitText(ctx, value, format, width) {
        const full = format(value);
        if (ctx.measureText(full).width <= width + 4) {
            return full;
        }
        const short = compact.format(value);
        return ctx.measureText(short).width <= width + 4 ? short : null;
    }

    return {
        id: 'valueLabels',
        afterDatasetsDraw(chart, args, opts) {
            const format = (opts && opts.format) || ((v) => String(v));
            const stacked = !!chart.options.scales?.y?.stacked;
            const {ctx} = chart;
            const totals = [];

            ctx.save();
            ctx.font = '11px sans-serif';
            ctx.fillStyle = '#333';
            ctx.textAlign = 'center';

            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                if (!chart.isDatasetVisible(i)) {
                    return;
                }
                meta.data.forEach((bar, j) => {
                    const value = Number(dataset.data[j]) || 0;
                    if (!value) {
                        return;
                    }
                    const top = Math.min(bar.y, bar.base);
                    if (!stacked) {
                        const text = fitText(ctx, value, format, bar.width);
                        if (text) {
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(text, bar.x, top - 2);
                        }
                        return;
                    }
                    const total = totals[j] || (totals[j] = {value: 0, top: Infinity, x: bar.x, width: bar.width});
                    total.value += value;
                    total.top = Math.min(total.top, top);
                    if (chart.data.datasets.length > 1 && Math.abs(bar.base - bar.y) >= 14) {
                        const text = fitText(ctx, value, format, bar.width);
                        if (text) {
                            ctx.textBaseline = 'middle';
                            ctx.fillText(text, bar.x, (bar.y + bar.base) / 2);
                        }
                    }
                });
            });

            ctx.font = 'bold 11px sans-serif';
            ctx.textBaseline = 'bottom';
            totals.forEach((total) => {
                const text = total && fitText(ctx, total.value, format, total.width);
                if (text) {
                    ctx.fillText(text, total.x, total.top - 2);
                }
            });
            ctx.restore();
        }
    };
})();
