let trendChart;
let statusChart;

document.addEventListener('DOMContentLoaded', function () {
    initializeCharts();

    const today = new Date();
    const defaultMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
    document.getElementById('monthFilter').value = defaultMonth;

    loadHistoricalReport();

    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (trendChart) trendChart.resize();
            if (statusChart) statusChart.resize();
        }, 150);
    });
});

function initializeCharts() {
    const theme = window.ReportChartTheme;
    if (!theme) {
        console.error('ReportChartTheme not loaded');
        return;
    }
    trendChart = theme.createTrendChart(
        document.getElementById('appointmentTrendChart').getContext('2d')
    );
    statusChart = theme.createStatusChart(
        document.getElementById('statusPieChart').getContext('2d')
    );
    theme.setTrendHint('Stacked bars show how many appointments fall in each status per period. Hover for details.');
    theme.renderStatusBreakdown([0, 0, 0, 0, 0]);
}

function loadHistoricalReport() {
    const selectedMonth = document.getElementById('monthFilter').value;
    const reportType = document.getElementById('reportTypeFilter').value;

    if (!selectedMonth) {
        alert('Please select a month');
        return;
    }

    document.querySelectorAll('.stat-card h3').forEach((el) => (el.textContent = 'Loading...'));

    fetch(
        (window.BASE_URL || '/') +
            `counselor/history-reports/historical-data?month=${selectedMonth}&type=${reportType}`
    )
        .then((response) => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then((data) => {
            if (data.error) throw new Error(data.error);
            updateCharts(data);
            updateStatistics(data);
        })
        .catch((error) => {
            console.error('Error fetching report data:', error);
            alert('Error loading report data: ' + error.message);
            resetStatistics();
        });
}

function normalizeChartSeries(value, length) {
    const out = new Array(length).fill(0);
    if (value == null) return out;
    if (Array.isArray(value)) {
        for (let i = 0; i < length; i++) out[i] = Number(value[i]) || 0;
        return out;
    }
    if (typeof value === 'object') {
        Object.keys(value).forEach((key) => {
            const i = parseInt(key, 10);
            if (!Number.isNaN(i) && i >= 0 && i < length) out[i] = Number(value[key]) || 0;
        });
    }
    return out;
}

function normalizeReportChartPayload(data) {
    const rawLabels = Array.isArray(data.labels) ? data.labels : [];
    const labels = rawLabels.map((l) => String(l));
    const len = labels.length;
    return {
        labels,
        completed: normalizeChartSeries(data.completed, len),
        approved: normalizeChartSeries(data.approved, len),
        rescheduled: normalizeChartSeries(data.rescheduled, len),
        pending: normalizeChartSeries(data.pending, len),
        feedback_pending: normalizeChartSeries(data.feedback_pending, len),
        totalCompleted: Number(data.totalCompleted) || 0,
        totalApproved: Number(data.totalApproved) || 0,
        totalRescheduled: Number(data.totalRescheduled) || 0,
        totalPending: Number(data.totalPending) || 0,
        totalFeedbackPending: Number(data.totalFeedbackPending) || 0,
        appointments: data.appointments,
        followUps: data.followUps,
    };
}

function normalizeFeedbackStatus(appointment) {
    const normalizedFeedbackStatus = String(
        (appointment && (appointment.feedback_status || appointment.student_feedback_status)) || ''
    )
        .trim()
        .toLowerCase()
        .replace(/[\s-]+/g, '_');

    if (normalizedFeedbackStatus === 'submitted' || normalizedFeedbackStatus === 'feedback_submitted') {
        return 'submitted';
    }

    return 'pending';
}

function getExactFeedbackPendingTotal(data) {
    const appointments = Array.isArray(data && data.appointments) ? data.appointments : [];
    const followUps = Array.isArray(data && data.followUps) ? data.followUps : [];
    const source = appointments.concat(followUps);

    if (!source.length) return null;

    return source.filter((appointment) => {
        if (!appointment) return false;
        if (Number(appointment.is_feedback_pending) === 1) return true;

        const status = String(appointment.status || '').trim().toUpperCase();
        const feedbackStatus = normalizeFeedbackStatus(appointment);
        const recordKind = String(appointment.record_kind || '').trim().toLowerCase();
        const appointmentType = String(appointment.appointment_type || '').trim().toLowerCase();
        const isFollowUp = recordKind === 'follow_up' || appointmentType.includes('follow-up');

        return (
            !isFollowUp &&
            (status === 'FEEDBACK_PENDING' || status === 'COMPLETED') &&
            feedbackStatus !== 'submitted'
        );
    }).length;
}

function updateCharts(data) {
    if (!trendChart || !statusChart || !window.ReportChartTheme) return;

    const theme = window.ReportChartTheme;
    const chartData = normalizeReportChartPayload(data);
    const reportType = document.getElementById('reportTypeFilter').value;

    const monthName = new Date(document.getElementById('monthFilter').value + '-01').toLocaleString(
        'default',
        { month: 'long', year: 'numeric' }
    );
    const typeLabel = reportType.charAt(0).toUpperCase() + reportType.slice(1);
    theme.setTrendSubtitle(monthName + ' · ' + typeLabel + ' view');

    theme.updateTrendChart(trendChart, chartData, reportType, { isPercentage: false });

    const exactFeedbackPending = getExactFeedbackPendingTotal(data);

    theme.updateStatusChart(statusChart, [
        chartData.totalCompleted,
        chartData.totalApproved,
        chartData.totalRescheduled,
        chartData.totalPending,
        exactFeedbackPending ?? chartData.totalFeedbackPending,
    ]);
}

function updateStatistics(data) {
    const exactFeedbackPending = getExactFeedbackPendingTotal(data);

    document.getElementById('completedCount').textContent = data.totalCompleted || 0;
    document.getElementById('approvedCount').textContent = data.totalApproved || 0;
    document.getElementById('rescheduledCount').textContent = data.totalRescheduled || 0;
    document.getElementById('pendingCount').textContent = data.totalPending || 0;
    document.getElementById('feedbackPendingCount').textContent =
        exactFeedbackPending ?? (data.totalFeedbackPending || 0);
}

function resetStatistics() {
    document.getElementById('completedCount').textContent = '0';
    document.getElementById('approvedCount').textContent = '0';
    document.getElementById('rescheduledCount').textContent = '0';
    document.getElementById('pendingCount').textContent = '0';
    document.getElementById('feedbackPendingCount').textContent = '0';

    if (trendChart && statusChart && window.ReportChartTheme) {
        const empty = {
            labels: [],
            completed: [],
            approved: [],
            rescheduled: [],
            pending: [],
            feedback_pending: [],
        };
        window.ReportChartTheme.updateTrendChart(trendChart, empty, 'daily');
        window.ReportChartTheme.updateStatusChart(statusChart, [0, 0, 0, 0, 0]);
    }
}
