// Data Analytics JavaScript for Admin Panel

let timeChart = null;
let statusChart = null;
let categoryChart = null;
let successFailedChart = null;
let currentAnalyticsData = [];

document.addEventListener('DOMContentLoaded', function() {
    initializeAnalytics();
    
    // Event listeners
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('refreshTimeChart').addEventListener('click', loadTimeBasedAnalytics);
    document.getElementById('categoryChartSelect').addEventListener('change', function() {
        loadCategoryAnalyticsByFilter(this.value);
    });
    document.getElementById('exportData').addEventListener('click', exportToCSV);
    
    // Sync category filter with chart select
    document.getElementById('categoryFilter').addEventListener('change', function() {
        document.getElementById('categoryChartSelect').value = this.value;
    });
});

function initializeAnalytics() {
    loadAcademicFilterOptions();
    loadTimeBasedAnalytics();
    loadCategoryAnalytics();
}

async function loadAcademicFilterOptions() {
    try {
        const response = await fetch(window.BASE_URL + '/admin/data-analytics/getAcademicFilterOptions');
        const result = await response.json();

        if (!result.success) return;

        const departmentSelect = document.getElementById('departmentFilter');
        const yearLevelSelect = document.getElementById('yearLevelFilter');

        if (departmentSelect) {
            const current = departmentSelect.value || 'all';
            departmentSelect.innerHTML = '<option value="all">All Departments</option>';
            (result.data.departments || []).forEach(dep => {
                const opt = document.createElement('option');
                opt.value = dep;
                opt.textContent = dep;
                departmentSelect.appendChild(opt);
            });
            departmentSelect.value = current;
        }

        if (yearLevelSelect) {
            const current = yearLevelSelect.value || 'all';
            yearLevelSelect.innerHTML = '<option value="all">All Year Levels</option>';
            (result.data.year_levels || []).forEach(yl => {
                const opt = document.createElement('option');
                opt.value = yl;
                opt.textContent = yl;
                yearLevelSelect.appendChild(opt);
            });
            yearLevelSelect.value = current;
        }
    } catch (error) {
        console.error('Error loading filter options:', error);
    }
}

function buildCommonQueryParams() {
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const department = document.getElementById('departmentFilter')?.value || 'all';
    const yearLevel = document.getElementById('yearLevelFilter')?.value || 'all';

    const params = new URLSearchParams();
    if (statusFilter !== 'all') params.set('status', statusFilter);
    if (department !== 'all') params.set('department', department);
    if (yearLevel !== 'all') params.set('year_level', yearLevel);

    return params;
}

async function loadTimeBasedAnalytics() {
    const timePeriod = document.getElementById('timePeriod').value;
    let endpoint = '';
    
    switch(timePeriod) {
        case 'daily':
            endpoint = window.BASE_URL + '/admin/data-analytics/getDailyAnalytics';
            break;
        case 'weekly':
            endpoint = window.BASE_URL + '/admin/data-analytics/getWeeklyAnalytics';
            break;
        case 'monthly':
            endpoint = window.BASE_URL + '/admin/data-analytics/getMonthlyAnalytics';
            break;
        case 'yearly':
            endpoint = window.BASE_URL + '/admin/data-analytics/getYearlyAnalytics';
            break;
        default:
            endpoint = window.BASE_URL + '/admin/data-analytics/getMonthlyAnalytics';
    }

    const params = buildCommonQueryParams();
    const query = params.toString();
    if (query) endpoint += '?' + query;
    
    try {
        const response = await fetch(endpoint);
        const result = await response.json();
        
        if (result.success) {
            // Keep charts/KPI driven by aggregated time-based endpoint
            renderTimeChart(result.data, timePeriod);
            renderStatusChart(result.data);
            renderSuccessFailedChart(result.data);
            updateKPICards(result.data);
            await loadDetailedAnalytics();
        } else {
            console.error('Error loading analytics:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadCategoryAnalytics() {
    const category = document.getElementById('categoryChartSelect').value;
    const params = buildCommonQueryParams();
    params.set('groupBy', category);
    const endpoint = window.BASE_URL + '/admin/data-analytics/getCategoryBreakdownAnalytics?' + params.toString();
    
    try {
        const response = await fetch(endpoint);
        const result = await response.json();
        
        if (result.success) {
            renderCategoryChart(result.data, category);
        }
    } catch (error) {
        console.error('Error loading category analytics:', error);
    }
}

async function loadSuccessFailedAnalytics() {
    try {
        const response = await fetch(window.BASE_URL + '/admin/data-analytics/getSuccessFailedAnalytics');
        const result = await response.json();
        
        if (result.success) {
            renderSuccessFailedChart(result.data);
        }
    } catch (error) {
        console.error('Error loading success/failed analytics:', error);
    }
}

function updateKPICards(data = []) {
    const totals = data.reduce((acc, item) => {
        acc.total += parseInt(item.total_appointments || 0, 10);
        acc.completed += parseInt(item.completed || 0, 10);
        acc.pending += parseInt(item.pending || 0, 10);
        acc.approved += parseInt(item.approved || 0, 10);
        acc.rescheduled += parseInt(item.rescheduled || 0, 10);
        acc.pendingFollowup += parseInt(item.pending_followup || 0, 10);
        return acc;
    }, { total: 0, completed: 0, pending: 0, approved: 0, rescheduled: 0, pendingFollowup: 0 });

    const inProgress = totals.approved + totals.rescheduled + totals.pendingFollowup;

    const successRate = totals.total > 0 ? ((totals.completed / totals.total) * 100).toFixed(1) : '0.0';
    const failedRate = totals.total > 0 ? ((inProgress / totals.total) * 100).toFixed(1) : '0.0';
    const pendingRate = totals.total > 0 ? ((totals.pending / totals.total) * 100).toFixed(1) : '0.0';

    document.getElementById('totalAppointments').textContent = totals.total;
    document.getElementById('successRate').textContent = successRate + '%';
    document.getElementById('failedRate').textContent = failedRate + '%';
    document.getElementById('pendingRate').textContent = pendingRate + '%';
}

function renderTimeChart(data, timePeriod) {
    const ctx = document.getElementById('timeChart').getContext('2d');
    
    if (timeChart) {
        timeChart.destroy();
    }
    
    const labels = data.map(item => item.month || item.year);
    const completed = data.map(item => parseInt(item.completed || 0, 10));
    const pending = data.map(item => parseInt(item.pending || 0, 10));
    const inProgress = data.map(item =>
        parseInt(item.approved || 0, 10)
        + parseInt(item.rescheduled || 0, 10)
        + parseInt(item.pending_followup || 0, 10)
    );
    
    timeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Completed',
                    data: completed,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'In Progress',
                    data: inProgress,
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Pending',
                    data: pending,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: timePeriod === 'monthly' ? 'Monthly Appointment Trends' : 'Yearly Appointment Trends'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function renderStatusChart(data) {
    const ctx = document.getElementById('statusChart').getContext('2d');
    
    if (statusChart) {
        statusChart.destroy();
    }
    
    // Aggregate data across all periods
    const totals = {
        completed: 0,
        pending: 0,
        in_progress: 0
    };
    
    data.forEach(item => {
        totals.completed += parseInt(item.completed || 0, 10);
        totals.pending += parseInt(item.pending || 0, 10);
        totals.in_progress +=
            parseInt(item.approved || 0, 10)
            + parseInt(item.rescheduled || 0, 10)
            + parseInt(item.pending_followup || 0, 10);
    });
    
    statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Success', 'In Progress', 'Pending'],
            datasets: [{
                data: [totals.completed, totals.in_progress, totals.pending],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function renderCategoryChart(data, category) {
    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    if (categoryChart) {
        categoryChart.destroy();
    }
    
    const labels = data.map(item => item.group_label || item.month || 'N/A');
    const completed = data.map(item => parseInt(item.completed || 0, 10));
    const inProgress = data.map(item => parseInt(item.in_progress || 0, 10));
    const pending = data.map(item => parseInt(item.pending || 0, 10));
    
    categoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Success',
                    data: completed,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                },
                {
                    label: 'In Progress',
                    data: inProgress,
                    backgroundColor: 'rgba(23, 162, 184, 0.8)',
                },
                {
                    label: 'Pending',
                    data: pending,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: category === 'course_year_level'
                        ? 'By Course/Year Level'
                        : 'By Department'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                },
                x: {
                    stacked: true,
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}

function renderSuccessFailedChart(data) {
    const ctx = document.getElementById('successFailedChart').getContext('2d');
    
    if (successFailedChart) {
        successFailedChart.destroy();
    }
    
    const labels = data.map(item => item.month || item.year);
    const completed = data.map(item => parseInt(item.completed || 0, 10));
    const inProgress = data.map(item =>
        item.in_progress !== undefined
            ? parseInt(item.in_progress || 0, 10)
            : parseInt(item.approved || 0, 10) + parseInt(item.rescheduled || 0, 10) + parseInt(item.pending_followup || 0, 10)
    );
    const pending = data.map(item => parseInt(item.pending || 0, 10));
    
    successFailedChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Success',
                    data: completed,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    stack: 'Stack 0'
                },
                {
                    label: 'In Progress',
                    data: inProgress,
                    backgroundColor: 'rgba(23, 162, 184, 0.8)',
                    stack: 'Stack 0'
                },
                {
                    label: 'Pending',
                    data: pending,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    stack: 'Stack 0'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });
}

function updateAnalyticsTable(data, timePeriod) {
    const tbody = document.getElementById('analyticsTableBody');
    tbody.innerHTML = '';
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center">No data available</td></tr>';
        return;
    }
    
    data.forEach(item => {
        const total = parseInt(item.total_appointments, 10);
        const successRate = total > 0 ? ((item.completed / total) * 100).toFixed(1) : 0;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.period || item.month || item.year}</td>
            <td>${item.department || item.departments || '-'}</td>
            <td>${item.year_level || item.year_levels || '-'}</td>
            <td>${item.total_appointments}</td>
            <td>${item.completed}</td>
            <td>${item.pending}</td>
            <td>${item.approved}</td>
            <td>${item.rescheduled}</td>
            <td>${item.pending_followup || 0}</td>
            <td>${successRate}%</td>
        `;
        tbody.appendChild(row);
    });
}

async function loadDetailedAnalytics() {
    const timePeriod = document.getElementById('timePeriod').value;
    const params = buildCommonQueryParams();
    params.set('timePeriod', timePeriod);

    const endpoint = window.BASE_URL + '/admin/data-analytics/getDetailedAnalytics?' + params.toString();

    try {
        const response = await fetch(endpoint);
        const result = await response.json();

        if (result.success) {
            currentAnalyticsData = result.data || [];
            updateAnalyticsTable(currentAnalyticsData, timePeriod);
        } else {
            console.error('Error loading detailed analytics:', result.message);
        }
    } catch (error) {
        console.error('Error loading detailed analytics:', error);
    }
}

async function applyFilters() {
    const applyBtn = document.getElementById('applyFilters');
    const originalText = applyBtn.innerHTML;
    
    // Add loading state
    applyBtn.classList.add('loading');
    applyBtn.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
    
    try {
        await loadTimeBasedAnalytics();
        
        const categoryFilter = document.getElementById('categoryFilter').value;
        await loadCategoryAnalyticsByFilter(categoryFilter);
    } catch (error) {
        console.error('Error applying filters:', error);
    } finally {
        // Remove loading state
        applyBtn.classList.remove('loading');
        applyBtn.innerHTML = originalText;
    }
}

async function loadCategoryAnalyticsByFilter(category) {
    const params = buildCommonQueryParams();
    params.set('groupBy', category);
    const endpoint = window.BASE_URL + '/admin/data-analytics/getCategoryBreakdownAnalytics?' + params.toString();
    
    try {
        const response = await fetch(endpoint);
        const result = await response.json();
        
        if (result.success) {
            renderCategoryChart(result.data, category);
        }
    } catch (error) {
        console.error('Error loading category analytics:', error);
    }
}

function exportToCSV() {
    if (currentAnalyticsData.length === 0) {
        alert('No data to export');
        return;
    }
    
    const headers = ['Period', 'Department', 'Year Level', 'Total Appointments', 'Completed', 'Pending', 'Approved', 'Reschedule', 'Pending Followup', 'Success Rate'];
    const rows = currentAnalyticsData.map(item => [
        item.period || item.month || item.year,
        item.department || item.departments || '-',
        item.year_level || item.year_levels || '-',
        item.total_appointments,
        item.completed,
        item.pending,
        item.approved,
        item.rescheduled,
        item.pending_followup || 0,
        (() => {
            const total = parseInt(item.total_appointments || 0, 10);
            return total > 0 ? ((parseInt(item.completed || 0, 10) / total) * 100).toFixed(1) + '%' : '0.0%';
        })()
    ]);
    
    let csvContent = headers.join(',') + '\n';
    rows.forEach(row => {
        csvContent += row.join(',') + '\n';
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'counselign_analytics_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
