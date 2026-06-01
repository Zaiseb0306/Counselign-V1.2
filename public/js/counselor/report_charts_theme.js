/**
 * Shared Chart.js theme — readable stacked trend + horizontal status bars.
 * Chart.js 3.9.x
 */
(function (global) {
    const C = {
        navy: '#060E57',
        navySoft: '#0a1a7a',
        completed: '#2563eb',
        approved: '#16a34a',
        rescheduled: '#f59e0b',
        pending: '#dc2626',
        inProgress: '#64748b',
        grid: 'rgba(6, 14, 87, 0.07)',
        tick: '#64748b',
        font: 'DM Sans',
        mono: 'JetBrains Mono',
    };

    const STATUS_KEYS = ['completed', 'approved', 'rescheduled', 'pending', 'inProgress'];
    const STATUS_LABELS = ['Completed', 'Approved', 'Rescheduled', 'Pending', 'In Progress'];
    const STATUS_COLORS = [C.completed, C.approved, C.rescheduled, C.pending, C.inProgress];

    const axisTitleStyle = {
        display: true,
        color: C.navy,
        font: { family: C.font, size: 12, weight: 'bold' },
        padding: { top: 8, bottom: 4 },
    };

    function tooltipBase() {
        return {
            backgroundColor: C.navy,
            titleColor: '#ffffff',
            bodyColor: 'rgba(255,255,255,0.92)',
            titleFont: { family: C.font, size: 13, weight: 'bold' },
            bodyFont: { family: C.font, size: 12 },
            padding: 12,
            cornerRadius: 10,
            boxPadding: 6,
        };
    }

    function legendLabels() {
        return {
            usePointStyle: true,
            pointStyle: 'rectRounded',
            padding: 14,
            font: { family: C.font, size: 11, weight: 'bold' },
            color: C.navy,
        };
    }

    function getXAxisTitle(reportType) {
        switch (reportType) {
            case 'daily': return 'Day of month';
            case 'weekly': return 'Week in period';
            case 'monthly': return 'Month';
            case 'yearly': return 'Year';
            default: return 'Time period';
        }
    }

    /** Friendlier x-axis labels (e.g. day "5" → "Day 5"). */
    function formatTrendLabels(rawLabels, reportType) {
        return (rawLabels || []).map(function (label) {
            const s = String(label);
            if (reportType === 'daily') {
                return 'Day ' + s;
            }
            if (reportType === 'weekly' && /^Week/i.test(s)) {
                return s;
            }
            if (reportType === 'monthly') {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const n = parseInt(s, 10);
                if (n >= 1 && n <= 12) return months[n - 1];
            }
            return s;
        });
    }

    function makeTrendDataset(label, color) {
        return {
            label: label,
            data: [],
            backgroundColor: color,
            borderColor: color,
            borderWidth: 0,
            borderRadius: 4,
            stack: 'appointments',
            maxBarThickness: 42,
        };
    }

    function getTrendDatasets() {
        return [
            makeTrendDataset('Completed', C.completed),
            makeTrendDataset('Approved', C.approved),
            makeTrendDataset('Rescheduled', C.rescheduled),
            makeTrendDataset('Pending', C.pending),
            makeTrendDataset('In Progress', C.inProgress),
        ];
    }

    function stackedBarTooltipFooter(items) {
        if (!items || !items.length) return '';
        const sum = items.reduce(function (acc, item) {
            return acc + (Number(item.parsed.y) || 0);
        }, 0);
        return 'Total this period: ' + sum;
    }

    function baseTrendOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 400 },
            plugins: {
                title: { display: false },
                legend: {
                    position: 'bottom',
                    labels: legendLabels(),
                },
                tooltip: Object.assign({}, tooltipBase(), {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        footer: stackedBarTooltipFooter,
                    },
                }),
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: {
                        color: C.tick,
                        font: { family: C.font, size: 11 },
                        maxRotation: 45,
                        minRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 14,
                    },
                    title: Object.assign({}, axisTitleStyle, { text: 'Time period' }),
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: C.grid, drawBorder: false },
                    ticks: {
                        color: C.tick,
                        font: { family: C.font, size: 11 },
                        precision: 0,
                        stepSize: 1,
                    },
                    title: Object.assign({}, axisTitleStyle, { text: 'Appointments' }),
                },
            },
        };
    }

    function applyTrendYAxis(chart, reportType, opts) {
        if (!chart || !chart.options.scales) return;
        opts = opts || {};
        const y = chart.options.scales.y;
        const x = chart.options.scales.x;

        y.beginAtZero = true;
        y.stacked = true;
        y.title = Object.assign({}, axisTitleStyle, {
            text: opts.isPercentage ? 'Share of appointments (%)' : 'Number of appointments',
        });

        if (opts.isPercentage) {
            y.max = 100;
            y.ticks = Object.assign({}, y.ticks || {}, {
                stepSize: 20,
                callback: function (v) { return v + '%'; },
            });
        } else {
            delete y.max;
            y.ticks = Object.assign({}, y.ticks || {}, {
                stepSize: undefined,
                callback: function (v) { return Number(v).toFixed(0); },
            });
            const suggested = opts.suggestedMax;
            if (suggested > 0) {
                y.suggestedMax = Math.ceil(suggested * 1.15);
            } else {
                delete y.suggestedMax;
            }
        }

        if (x) {
            x.stacked = true;
            x.title = Object.assign({}, axisTitleStyle, { text: getXAxisTitle(reportType) });
        }
    }

    function sumStackedTotals(chartData) {
        const len = chartData.labels.length;
        let max = 0;
        for (let i = 0; i < len; i++) {
            const t =
                chartData.completed[i] +
                chartData.approved[i] +
                chartData.rescheduled[i] +
                chartData.pending[i] +
                chartData.feedback_pending[i];
            if (t > max) max = t;
        }
        return max;
    }

    function statusTooltipLabel(context) {
        const value = Number(context.raw) || 0;
        const data = context.dataset.data || [];
        const total = data.reduce(function (a, b) { return a + (Number(b) || 0); }, 0);
        const pct = total > 0 ? ((value / total) * 100).toFixed(1) : '0';
        return value + ' appointments (' + pct + '%)';
    }

    function getStatusChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 400 },
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: Object.assign({}, tooltipBase(), {
                    callbacks: { label: statusTooltipLabel },
                }),
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: C.grid, drawBorder: false },
                    ticks: {
                        color: C.tick,
                        font: { family: C.font, size: 11 },
                        precision: 0,
                        stepSize: 1,
                    },
                    title: Object.assign({}, axisTitleStyle, { text: 'Number of appointments' }),
                },
                y: {
                    grid: { display: false },
                    ticks: {
                        color: C.navy,
                        font: { family: C.font, size: 12, weight: 'bold' },
                    },
                },
            },
        };
    }

    function createTrendChart(ctx) {
        return new Chart(ctx, {
            type: 'bar',
            data: { labels: [], datasets: getTrendDatasets() },
            options: baseTrendOptions(),
        });
    }

    function createStatusChart(ctx) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: STATUS_LABELS.slice(),
                datasets: [{
                    label: 'Appointments',
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: STATUS_COLORS.slice(),
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 26,
                }],
            },
            options: getStatusChartOptions(),
        });
    }

    /** HTML breakdown list — easier to read than a doughnut. */
    function renderStatusBreakdown(values) {
        const el = document.getElementById('statusChartBreakdown');
        if (!el) return;

        const nums = (values || []).map(function (v) { return Number(v) || 0; });
        const total = nums.reduce(function (a, b) { return a + b; }, 0);

        if (total === 0) {
            el.innerHTML =
                '<p class="rpt-chart-empty"><i class="fas fa-info-circle me-2"></i>No appointments in this period.</p>';
            return;
        }

        let html = '<ul class="rpt-status-list">';
        STATUS_LABELS.forEach(function (label, i) {
            const count = nums[i] || 0;
            const pct = total > 0 ? ((count / total) * 100).toFixed(1) : '0';
            const zeroClass = count === 0 ? ' rpt-status-item--zero' : '';
            html +=
                '<li class="rpt-status-item' + zeroClass + '">' +
                '<span class="rpt-status-swatch" style="background:' + STATUS_COLORS[i] + '"></span>' +
                '<span class="rpt-status-name">' + label + '</span>' +
                '<span class="rpt-status-count">' + count + '</span>' +
                '<span class="rpt-status-pct">' + pct + '%</span>' +
                '</li>';
        });
        html += '</ul>';
        html += '<p class="rpt-status-total"><strong>' + total + '</strong> appointments total</p>';
        el.innerHTML = html;
    }

    function updateStatusChart(chart, values) {
        if (!chart) return;
        const nums = (values || []).map(function (v) { return Number(v) || 0; });
        chart.data.datasets[0].data = nums;
        const maxVal = Math.max.apply(null, nums.concat([1]));
        if (chart.options.scales && chart.options.scales.x) {
            chart.options.scales.x.suggestedMax = Math.ceil(maxVal * 1.2);
        }
        renderStatusBreakdown(nums);
        chart.update();
    }

    function setTrendSubtitle(text) {
        const el = document.getElementById('trendChartSubtitle');
        if (el) el.textContent = text || '';
    }

    function setTrendHint(text) {
        const el = document.getElementById('trendChartHint');
        if (el) el.textContent = text || '';
    }

    function updateTrendChart(trendChart, chartData, reportType, opts) {
        if (!trendChart || !chartData) return;
        opts = opts || {};

        applyTrendYAxis(trendChart, reportType, {
            isPercentage: !!opts.isPercentage,
            suggestedMax: opts.isPercentage ? 100 : sumStackedTotals(chartData),
        });

        trendChart.data.labels = opts.skipLabelFormat
            ? chartData.labels
            : formatTrendLabels(chartData.labels, reportType);
        trendChart.data.datasets[0].data = chartData.completed;
        trendChart.data.datasets[1].data = chartData.approved;
        trendChart.data.datasets[2].data = chartData.rescheduled;
        trendChart.data.datasets[3].data = chartData.pending;
        trendChart.data.datasets[4].data = chartData.feedback_pending;

        const empty = trendChart.data.labels.length === 0 || sumStackedTotals(chartData) === 0;
        const hintEl = document.getElementById('trendChartEmpty');
        if (hintEl) hintEl.style.display = empty ? 'block' : 'none';

        trendChart.update();
    }

    global.ReportChartTheme = {
        COLORS: C,
        STATUS_LABELS: STATUS_LABELS,
        STATUS_COLORS: STATUS_COLORS,
        createTrendChart: createTrendChart,
        createPieChart: createStatusChart,
        createStatusChart: createStatusChart,
        applyTrendYAxis: applyTrendYAxis,
        formatTrendLabels: formatTrendLabels,
        updateTrendChart: updateTrendChart,
        updateStatusChart: updateStatusChart,
        renderStatusBreakdown: renderStatusBreakdown,
        setTrendSubtitle: setTrendSubtitle,
        setTrendHint: setTrendHint,
        applyPieTooltip: function () {},
        refreshScaleStyle: function () {},
        pieTooltipLabel: statusTooltipLabel,
    };
})(typeof window !== 'undefined' ? window : this);
