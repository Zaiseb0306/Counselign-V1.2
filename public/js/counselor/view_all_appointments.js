let trendChart;



let pieChart;







// Initialize charts on page load



document.addEventListener('DOMContentLoaded', function () {



    initializeCharts();



    updateReports();







    // Add event listeners for filter changes



    document.getElementById('timeRange').addEventListener('change', updateReports);



    



    // Add window resize event listener for chart responsiveness



    window.addEventListener('resize', function() {



        if (trendChart) {



            trendChart.resize();



        }



        if (pieChart) {



            pieChart.resize();



        }



    });



});







function initializeCharts() {
    const theme = window.ReportChartTheme;
    const trendCtx = document.getElementById('appointmentTrendChart').getContext('2d');
    const pieCtx = document.getElementById('statusPieChart').getContext('2d');
    trendChart = theme.createTrendChart(trendCtx);
    pieChart = theme.createPieChart(pieCtx);
    theme.setTrendHint('Stacked bars show how many appointments fall in each status per period. Hover for details.');
}




function updateReports() {



    const timeRange = document.getElementById('timeRange').value;







    // Show loading state



    document.querySelectorAll('.stat-card h3').forEach(el => el.textContent = 'Loading...');



    



    // Fetch data from the server based on the selected time range



    fetch((window.BASE_URL || '/') + `counselor/appointments/get_all_appointments?timeRange=${encodeURIComponent(timeRange)}&_=${Date.now()}`, {
        credentials: 'include',
        cache: 'no-store',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Cache-Control': 'no-cache' },
    })



        .then(response => {



            if (!response.ok) {



                throw new Error('Network response was not ok');



            }



            return response.json();



        })



        .then(data => {



            if (data.error) {



                throw new Error(data.error);



            }



            updateCharts(data);



            updateStatistics(data);



            updateCounselorName(data);



            saveToHistory(data);



        })



        .catch(error => {



            console.error('Error fetching report data:', error);



            alert('Error loading report data: ' + error.message);



            // Reset statistics to 0 on error



            resetStatistics();



        });



}







// Updated updateCharts function with correct counting logic



function updateCharts(data) {



    // Validate data



    if (!data || !Array.isArray(data.labels)) {



        console.error('Invalid data format received');



        return;



    }







    const timeRange = document.getElementById('timeRange').value;



    let labels = data.labels;







    // Format dates based on time range



    if (timeRange === 'monthly') {



        labels = [



            'January', 'February', 'March', 'April', 



            'May', 'June', 'July', 'August',



            'September', 'October', 'November', 'December'



        ];



    } else if (timeRange === 'daily') {



        if (data.weekInfo && Array.isArray(data.weekInfo.weekDays)) {



            labels = data.weekInfo.weekDays.map(day =>



                `${day.shortDayName}, ${day.dayMonth}`



            );



        } else if (status === 'FEEDBACK_PENDING') {

            filteredAppointments = allAppointments.filter(app => isFeedbackPendingAppointment(app));

        } else {



            labels = labels.map(date => {



                const d = new Date(date);



                return d.toLocaleDateString('en-US', {



                    weekday: 'short',



                    month: 'short',



                    day: '2-digit'



                });



            });



        }



    } else if (timeRange === 'weekly') {



        if (data.weekRanges) {



            labels = data.weekRanges.map(week => {



                const start = new Date(week.start);



                const end = new Date(week.end);



                return `${start.toLocaleDateString('en-US', {



                    month: 'short',



                    day: '2-digit'



                })} - ${end.toLocaleDateString('en-US', {



                    month: 'short',



                    day: '2-digit'



                })}`;



            });



        } else {



            labels = labels.map(date => {



                const start = new Date(date);



                const end = new Date(date);



                end.setDate(end.getDate() + 6);



                return `${start.toLocaleDateString('en-US', {



                    month: 'short',



                    day: '2-digit'



                })} - ${end.toLocaleDateString('en-US', {



                    month: 'short',



                    day: '2-digit'



                })}`;



            });



        }



    }







    // Update trend chart - FIXED: Use correct data based on timeRange



    trendChart.data.labels = labels;



    



    // For monthly, use monthlyXXX arrays, otherwise use the regular arrays



    trendChart.data.datasets[0].data = timeRange === 'monthly' ? 



        (data.monthlyCompleted || Array(12).fill(0)) : 



        (data.completed || Array(labels.length).fill(0));



    trendChart.data.datasets[1].data = timeRange === 'monthly' ? 



        (data.monthlyApproved || Array(12).fill(0)) : 



        (data.approved || Array(labels.length).fill(0));



    trendChart.data.datasets[2].data = timeRange === 'monthly' ?



        (data.monthlyRescheduled || Array(12).fill(0)) :



        (data.rescheduled || Array(labels.length).fill(0));



    trendChart.data.datasets[3].data = timeRange === 'monthly' ?



        (data.monthlyPending || Array(12).fill(0)) :



        (data.pending || Array(labels.length).fill(0));



    trendChart.data.datasets[4].data = timeRange === 'monthly' ?



        (data.monthlyFeedbackPending || Array(12).fill(0)) :



        (data.feedback_pending || Array(labels.length).fill(0));







    // Update chart title



    let titleText = `Appointment Trends - ${timeRange.charAt(0).toUpperCase() + timeRange.slice(1)} Report`;



    if (timeRange === 'daily' && data.weekInfo) {



        const startDate = new Date(data.weekInfo.startDate);



        const monthYear = startDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });



        titleText += ` (${monthYear})`;



    } else if (timeRange === 'weekly' && data.startDate && data.endDate) {



        const monthDate = new Date(data.startDate);



        const monthName = monthDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });



        titleText += ` (${monthName})`;



    }



    if (window.ReportChartTheme) window.ReportChartTheme.setTrendSubtitle(titleText.replace(/^Appointment Trends\s*/i, ''));







    if (window.ReportChartTheme) {
        const vaChartData = {
            labels: trendChart.data.labels,
            completed: trendChart.data.datasets[0].data,
            approved: trendChart.data.datasets[1].data,
            rescheduled: trendChart.data.datasets[2].data,
            pending: trendChart.data.datasets[3].data,
            feedback_pending: trendChart.data.datasets[4].data,
        };
        window.ReportChartTheme.updateTrendChart(trendChart, vaChartData, timeRange, {
            isPercentage: timeRange === 'monthly',
            skipLabelFormat: true,
        });
    } else {
        trendChart.update();
    }

    const dashboardTotals = computeDashboardStatTotals(data);
    const hasAppointmentList =
        Array.isArray(data.appointments) && data.appointments.length > 0;

    const pieData = hasAppointmentList
        ? [
            dashboardTotals.completed,
            dashboardTotals.approved,
            dashboardTotals.rescheduled,
            dashboardTotals.pending,
            dashboardTotals.feedback_pending,
          ]
        : [
            parseInt(data.totalCompleted) || 0,
            parseInt(data.totalApproved) || 0,
            parseInt(data.totalRescheduled) || 0,
            parseInt(data.totalPending) || 0,
            parseInt(data.totalFeedbackPending) || 0,
          ];



    if (window.ReportChartTheme) {
        window.ReportChartTheme.updateStatusChart(pieChart, pieData);
    } else {
        pieChart.data.datasets[0].data = pieData;
        pieChart.update();
    }



}







// Updated updateStatistics function - ensure proper integer conversion



function normalizeFeedbackStatusForReport(appointment) {
    return String((appointment && appointment.feedback_status) || '')
        .trim()
        .toLowerCase()
        .replace(/[\s-]+/g, '_');
}

function hasSubmittedFeedbackForReport(appointment) {
    const s = normalizeFeedbackStatusForReport(appointment);
    return s === 'submitted' || s === 'feedback_submitted';
}

function isFeedbackPendingForReport(appointment) {
    if (!appointment) return false;
    if (Number(appointment.is_feedback_pending) === 1) return true;

    const status = String(appointment.status || '').trim().toUpperCase();
    const feedbackStatus = normalizeFeedbackStatusForReport(appointment);
    const recordKind = String(appointment.record_kind || '').trim().toLowerCase();
    const appointmentType = String(appointment.appointment_type || '').trim().toLowerCase();
    const isFollowUp = recordKind === 'follow_up' || appointmentType.includes('follow-up');

    return (
        !isFollowUp &&
        (status === 'FEEDBACK_PENDING' || status === 'COMPLETED') &&
        feedbackStatus !== 'submitted' &&
        feedbackStatus !== 'feedback_submitted'
    );
}

function isCompletedWithSubmittedFeedbackForReport(appointment) {
    if (!appointment) return false;
    const recordKind = String(appointment.record_kind || '').trim().toLowerCase();
    const appointmentType = String(appointment.appointment_type || '').trim().toLowerCase();
    const isFollowUp = recordKind === 'follow_up' || appointmentType.includes('follow-up');
    return (
        !isFollowUp &&
        String(appointment.status || '').trim().toUpperCase() === 'COMPLETED' &&
        hasSubmittedFeedbackForReport(appointment)
    );
}

/** Status totals for stat cards — all initial appointments (not limited to chart period). */
function computeDashboardStatTotals(data) {
    const appointments = Array.isArray(data && data.appointments) ? data.appointments : [];
    if (window.ReportStatusUtils) {
        return window.ReportStatusUtils.countStatusTotals(appointments);
    }

    const totals = {
        completed: 0,
        approved: 0,
        rescheduled: 0,
        pending: 0,
        feedback_pending: 0,
    };

    appointments.forEach((app) => {
        if (isCompletedWithSubmittedFeedbackForReport(app)) {
            totals.completed++;
        } else if (isFeedbackPendingForReport(app)) {
            totals.feedback_pending++;
        } else {
            const status = String(app.status || '').trim().toLowerCase();
            if (status === 'approved') totals.approved++;
            else if (status === 'rescheduled') totals.rescheduled++;
            else if (status === 'pending') totals.pending++;
        }
    });

    return totals;
}

function updateStatistics(data) {
    const dashboardTotals = computeDashboardStatTotals(data);
    const hasAppointmentList =
        Array.isArray(data.appointments) && data.appointments.length > 0;

    document.getElementById('completedCount').textContent = hasAppointmentList
        ? dashboardTotals.completed
        : parseInt(data.totalCompleted) || 0;
    document.getElementById('approvedCount').textContent = hasAppointmentList
        ? dashboardTotals.approved
        : parseInt(data.totalApproved) || 0;
    document.getElementById('rescheduledCount').textContent = hasAppointmentList
        ? dashboardTotals.rescheduled
        : parseInt(data.totalRescheduled) || 0;
    document.getElementById('pendingCount').textContent = hasAppointmentList
        ? dashboardTotals.pending
        : parseInt(data.totalPending) || 0;
    document.getElementById('feedbackPendingCount').textContent = hasAppointmentList
        ? dashboardTotals.feedback_pending
        : parseInt(data.totalFeedbackPending) || 0;
}

function getExactFeedbackPendingTotal(data) {
    return computeDashboardStatTotals(data).feedback_pending;
}







function updateCounselorName(data) {



    const counselorNameElement = document.getElementById('counselorName');



    if (counselorNameElement && data.counselorName) {



        counselorNameElement.textContent = data.counselorName;



    }



}







function resetStatistics() {



    document.getElementById('completedCount').textContent = '0';



    document.getElementById('approvedCount').textContent = '0';



    document.getElementById('rescheduledCount').textContent = '0';



    document.getElementById('pendingCount').textContent = '0';



    document.getElementById('feedbackPendingCount').textContent = '0';







    // Reset charts



    if (trendChart && pieChart) {



        trendChart.data.labels = [];



        trendChart.data.datasets.forEach(dataset => dataset.data = []);

        if (window.ReportChartTheme) {
            window.ReportChartTheme.updateTrendChart(trendChart, {
                labels: [], completed: [], approved: [], rescheduled: [], pending: [], feedback_pending: [],
            }, 'daily', { skipLabelFormat: true });
            window.ReportChartTheme.updateStatusChart(pieChart, [0, 0, 0, 0, 0]);
        } else {
            trendChart.update();
        }







        pieChart.data.datasets[0].data = [0, 0, 0, 0, 0];



        pieChart.update();



    }



} 







// Function to view report history



function viewHistory() {



    // Show the history modal



    const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));



    



    // Get the history data from localStorage



    const reportHistory = JSON.parse(localStorage.getItem('reportHistory') || '[]');



    



    // Get the table body



    const historyTableBody = document.getElementById('historyTableBody');



    historyTableBody.innerHTML = '';



    



    if (reportHistory.length === 0) {



        historyTableBody.innerHTML = `



            <tr>



                <td colspan="4" class="text-center">No history available</td>



            </tr>



        `;



    } else {



        // Sort history by date (newest first)



        reportHistory.sort((a, b) => new Date(b.dateGenerated) - new Date(a.dateGenerated));



        



        // Populate the table



        reportHistory.forEach(record => {



            const row = document.createElement('tr');



            row.innerHTML = `



                <td>${new Date(record.dateGenerated).toLocaleString()}</td>



                <td>${record.reportType}</td>



                <td>${record.totalAppointments}</td>



                <td>



                    <button class="btn btn-sm btn-primary me-2" onclick="viewReport('${record.id}')">



                        <i class="fas fa-eye"></i> View



                    </button>



                    <button class="btn btn-sm btn-danger" onclick="deleteReport('${record.id}')">



                        <i class="fas fa-trash"></i> Delete



                    </button>



                </td>



            `;



            historyTableBody.appendChild(row);



        });



    }



    



    historyModal.show();



}







// Function to save report to history



function saveToHistory(reportData) {



    // Get existing history



    const reportHistory = JSON.parse(localStorage.getItem('reportHistory') || '[]');



    



    // Create new report record



    const newReport = {



        id: Date.now().toString(), // Unique ID



        dateGenerated: new Date().toISOString(),



        reportType: document.getElementById('timeRange').value,



        totalAppointments: (parseInt(reportData.totalCompleted) || 0) + (parseInt(reportData.totalApproved) || 0) +



                         (parseInt(reportData.totalRescheduled) || 0) + (parseInt(reportData.totalPending) || 0) + (getExactFeedbackPendingTotal(reportData) ?? (parseInt(reportData.totalFeedbackPending) || 0)),



        data: { ...reportData, totalFeedbackPending: (getExactFeedbackPendingTotal(reportData) ?? (parseInt(reportData.totalFeedbackPending) || 0)) }



    };



    



    // Add to history (limit to last 50 reports)



    reportHistory.unshift(newReport);



    if (reportHistory.length > 50) {



        reportHistory.pop();



    }



    



    // Save back to localStorage



    localStorage.setItem('reportHistory', JSON.stringify(reportHistory));



}







// Function to view a specific report



function viewReport(reportId) {



    const reportHistory = JSON.parse(localStorage.getItem('reportHistory') || '[]');



    const report = reportHistory.find(r => r.id === reportId);



    



    if (report) {



        // Update the charts and statistics with the historical data



        updateCharts(report.data);



        updateStatistics(report.data);



        



        // Close the history modal



        const historyModal = bootstrap.Modal.getInstance(document.getElementById('historyModal'));



        historyModal.hide();



    }



}







// Function to delete a report



function deleteReport(reportId) {



    if (confirm('Are you sure you want to delete this report?')) {



        const reportHistory = JSON.parse(localStorage.getItem('reportHistory') || '[]');



        const updatedHistory = reportHistory.filter(r => r.id !== reportId);



        localStorage.setItem('reportHistory', JSON.stringify(updatedHistory));



        



        // Refresh the history view



        viewHistory();



    }



}







// Main appointments management code (only one DOMContentLoaded)



document.addEventListener('DOMContentLoaded', function () {



    // Initialize variables



    let allAppointments = [];
    /** Logged-in counselor scope for exports (own appointments only). */
    let myCounselorExportScope = { ids: [], name: '' };

    function belongsToMyCounselor(app) {
        if (!myCounselorExportScope.ids.length) return true;
        const pref = String(
            app.counselor_preference || app.counselor_id || ''
        ).trim();
        if (!pref) return false;
        return myCounselorExportScope.ids.some((id) => String(id) === pref);
    }

    function filterToMyAppointments(appointments) {
        return (appointments || []).filter((app) => belongsToMyCounselor(app));
    }

    function reportTitleForMyAppointments(activeTabKey) {
        const tabLabels = {
            all: 'All Appointments',
            followup: 'Follow-up',
            approved: 'Approved',
            rescheduled: 'Rescheduled',
            completed: 'Completed',
            'feedback-pending': 'InProgress',
        };
        const tabLabel = tabLabels[activeTabKey] || tabLabels.all;
        if (activeTabKey === 'all') {
            return 'List of All Your Appointments';
        }
        return `List of All Your Appointments — ${tabLabel}`;
    }



    const searchInput = document.getElementById('searchInput');



    const dateFilter = document.getElementById('dateFilter');



    const loadingSpinner = document.querySelector('.loading-spinner');



    const emptyState = document.querySelector('.empty-state');



    



    // Get modal elements



    const exportFiltersModalEl = document.getElementById('exportFiltersModal');



    const exportFiltersModal = exportFiltersModalEl ? new bootstrap.Modal(exportFiltersModalEl) : null;







    // Utility functions



    function getStatusClass(status) {



        if (!status) return 'pending';



        switch (status.toUpperCase()) {



            case 'APPROVED':



                return 'approved';



            case 'REJECTED':



                return 'rejected';



            case 'COMPLETED':



                return 'completed';



            case 'FEEDBACK_PENDING':



                return 'feedback-pending';



            case 'PENDING':



            default:



                return 'pending';



        }



    }







    function formatReason(reason) {



        if (!reason) return '';



        const idx = reason.indexOf(':');



        if (idx === -1) return reason;



        // Split at the first colon and insert a <br>



        return reason.slice(0, idx + 1) + '<br>' + reason.slice(idx + 1).trim();



    }







    function getFeedbackStatus(appointment) {

        const status = String(appointment.status || '').trim().toUpperCase();
        const normalizedFeedbackStatus = String(appointment.feedback_status || '')
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');
        const isSubmitted = normalizedFeedbackStatus === 'submitted' || normalizedFeedbackStatus === 'feedback_submitted';
        if (status === 'COMPLETED' || status === 'FEEDBACK_PENDING') {



            if (isSubmitted) {



                return '<span class="badge bg-success">Feedback Submitted</span>';



            } else {



                return '<span class="badge bg-warning">Pending Feedback</span>';



            }



        }



        return '';



    }







    function calculateFeedbackMean(appointment) {



        if (appointment.feedback_status !== 'submitted') {



            return null;



        }







        const feedbackQuestions = [



            'q1_ease_of_use', 'q2_satisfaction', 'q3_timeliness',



            'q4_information_clarity', 'q5_staff_helpfulness', 'q6_technology_reliability',



            'q7_privacy_confidence', 'q8_recommendation', 'q9_overall_experience',



            'q10_future_use'



        ];







        let sum = 0;



        let count = 0;







        feedbackQuestions.forEach(question => {



            const value = appointment[question];



            if (value !== null && value !== undefined && value !== '') {



                sum += parseFloat(value);



                count++;



            }



        });







        return count > 0 ? sum / count : null;



    }







    function getInterpretation(mean) {



        if (mean === null) {



            return '';



        }







        if (mean >= 4.50 && mean <= 5.00) {



            return 'Excellent';



        } else if (mean >= 3.50 && mean < 4.50) {



            return 'Very Good';



        } else if (mean >= 2.50 && mean < 3.50) {



            return 'Good';



        } else if (mean >= 1.50 && mean < 2.50) {



            return 'Fair';



        } else if (mean >= 1.00 && mean < 1.50) {



            return 'Poor';



        } else {



            return '';



        }



    }







    function getFeedbackStatusText(appointment) {



        const status = String(appointment.status || '').trim().toUpperCase();
        const normalizedFeedbackStatus = String(appointment.feedback_status || '')
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');
        const isSubmitted = normalizedFeedbackStatus === 'submitted' || normalizedFeedbackStatus === 'feedback_submitted';
        if (status === 'COMPLETED' || status === 'FEEDBACK_PENDING') {



            if (isSubmitted) {



                return 'Feedback Submitted';



            } else {



                return 'InProgress';



            }



        }



        return 'N/A';



    }

    function getFeedbackStatusBadge(appointment) {

        const status = String(appointment.status || '').trim().toUpperCase();
        const normalizedFeedbackStatus = String(appointment.feedback_status || '')
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');
        const isSubmitted = normalizedFeedbackStatus === 'submitted' || normalizedFeedbackStatus === 'feedback_submitted';

        if (status === 'COMPLETED' || status === 'FEEDBACK_PENDING') {

            if (isSubmitted) {

                return '<span class="badge bg-success">Feedback Submitted</span>';

            } else {

                return '<span class="badge bg-warning text-dark">Pending Feedback</span>';

            }

        }

        return '';

    }

    function isFeedbackPendingAppointment(appointment) {
        if (!appointment) return false;
        if (Number(appointment.is_feedback_pending) === 1) return true;

        const status = String(appointment.status || '').trim().toUpperCase();
        const feedbackStatus = String(appointment.feedback_status || '')
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');
        const recordKind = String(appointment.record_kind || '').trim().toLowerCase();
        const appointmentType = String(appointment.appointment_type || '').trim().toLowerCase();
        const isFollowUp = recordKind === 'follow_up' || appointmentType.includes('follow-up');

        // Feedback pending tab should show regular appointments only.
        // A row is considered pending when status is completed/feedback_pending
        // and student feedback has not been submitted yet.
        return !isFollowUp
            && (status === 'FEEDBACK_PENDING' || status === 'COMPLETED')
            && feedbackStatus !== 'submitted'
            && feedbackStatus !== 'feedback_submitted';
    }

    function hasSubmittedFeedback(appointment) {
        const normalizedFeedbackStatus = String(appointment?.feedback_status || '')
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');
        return normalizedFeedbackStatus === 'submitted' || normalizedFeedbackStatus === 'feedback_submitted';
    }

    function getFilteredAppointmentsByTab(tabKey, appointments) {
        const source = Array.isArray(appointments) ? appointments : [];

        switch (tabKey) {
            case 'approved':
                return source.filter(app => app.status && String(app.status).toUpperCase() === 'APPROVED');
            case 'rejected':
                return source.filter(app => app.status && String(app.status).toUpperCase() === 'REJECTED');
            case 'completed':
                return source.filter(app =>
                    app.status
                    && String(app.status).toUpperCase() === 'COMPLETED'
                    && hasSubmittedFeedback(app)
                );
            case 'rescheduled':
                return source.filter(app => app.status && String(app.status).toUpperCase() === 'RESCHEDULED');
            case 'pending':
                return source.filter(app => app.status && String(app.status).toUpperCase() === 'PENDING');
            case 'followup':
                return source.filter(app => {
                    const isFollowUp = String(app.record_kind || '').toLowerCase() === 'follow_up'
                        || String(app.appointment_type || '').toLowerCase().includes('follow-up');
                    const status = String(app.status || '').toUpperCase();
                    return isFollowUp && (status === 'PENDING' || status === 'COMPLETED');
                });
            case 'feedback-pending':
                return source.filter(app => isFeedbackPendingAppointment(app));
            case 'all':
            default:
                return source;
        }
    }







    // Fetch all appointments when the page loads



    fetchAppointments();







    // Add event listeners



    if (searchInput) searchInput.addEventListener("input", filterAppointments);



    if (dateFilter) dateFilter.addEventListener("change", filterAppointments);







    // Add event listeners for tab changes



    document.querySelectorAll('[data-bs-toggle="tab"]').forEach((tab) => {



        tab.addEventListener("shown.bs.tab", handleTabChange);



    });







    // Export buttons



    const exportPDFBtn = document.getElementById('exportPDF');



    const exportExcelBtn = document.getElementById('exportExcel');



    if (exportPDFBtn) exportPDFBtn.addEventListener('click', function(e){



        if (exportFiltersModalEl) exportFiltersModalEl.setAttribute('data-export-type', 'PDF');



        if (exportFiltersModal) exportFiltersModal.show();



        e.stopPropagation();



    });



    if (exportExcelBtn) exportExcelBtn.addEventListener('click', function(e){



        if (exportFiltersModalEl) exportFiltersModalEl.setAttribute('data-export-type', 'Excel');



        if (exportFiltersModal) exportFiltersModal.show();



        e.stopPropagation();



    });







    // Enhanced filter elements



    const exportStartDate = document.getElementById('exportStartDate');



    const exportEndDate = document.getElementById('exportEndDate');



    const exportStudentFilter = document.getElementById('exportStudentFilter');



    const exportCourseFilter = document.getElementById('exportCourseFilter');



    const exportYearLevelFilter = document.getElementById('exportYearLevelFilter');



    const clearAllFiltersBtn = document.getElementById('clearAllFilters');



    const clearDateRangeBtn = document.getElementById('clearDateRange');



    const applyFiltersBtn = document.getElementById('applyFilters');







    // Enhanced filter event listeners



    if (clearAllFiltersBtn) clearAllFiltersBtn.addEventListener('click', clearAllFilters);



    if (clearDateRangeBtn) clearDateRangeBtn.addEventListener('click', clearDateRange);



    if (applyFiltersBtn) applyFiltersBtn.addEventListener('click', applyFilters);







    // Load filter data on page load



    loadFilterData();







    const RPT_ROW_LABELS = [
        'User ID',
        'Full Name',
        'Date',
        'Time',
        'Method Type',
        'Consultation Type',
        'Session Type',
        'Purpose',
        'Student Concern',
        'Counselor Remarks',
        'Counselor',
        'Student Feedbacks',
        'Mean',
        'Interpretation',
        'Status',
        'Reason for Status'
    ];

    function applyAppointmentRowLabels(row, showReason) {
        const cells = row.querySelectorAll('td');
        cells.forEach((cell, index) => {
            const label = RPT_ROW_LABELS[index];
            if (label) {
                cell.setAttribute('data-label', label);
            }
        });
    }

    function displayAppointments(appointments, targetTableId = 'allAppointmentsTable') {



        // Determine if this table should show the reason column



        const showReason = [



            'allAppointmentsTable',



            'rejectedAppointmentsTable'



        ].includes(targetTableId);







        const tableBody = document.getElementById(targetTableId);



        if (!tableBody) {



            console.error(`Table body with ID ${targetTableId} not found`);



            return;



        }







        tableBody.innerHTML = '';







        if (!appointments || appointments.length === 0) {



            const colspan = showReason ? 16 : 15;



            tableBody.innerHTML = `<tr class="rpt-empty-row"><td colspan="${colspan}">No appointments found</td></tr>`;



            return;



        }



    



        // Sort appointments from oldest to newest



        const sortedAppointments = [...appointments].sort((a, b) => {



            const dateTimeA = a.appointed_date + ' ' + a.appointed_time;



            const dateTimeB = b.appointed_date + ' ' + b.appointed_time;



            



            if (dateTimeA < dateTimeB) return -1;



            if (dateTimeA > dateTimeB) return 1;



            return 0;



        });







        sortedAppointments.forEach(appointment => {



            const row = document.createElement('tr');
            row.className = 'rpt-data-row';



            const feedbackMean = calculateFeedbackMean(appointment);



            const interpretation = getInterpretation(feedbackMean);



            row.innerHTML = `



                <td>${appointment.user_id || ''}</td>



                <td>${appointment.student_name || ''}</td>



                <td>${appointment.appointed_date ? new Date(appointment.appointed_date).toLocaleDateString() : ''}</td>



                <td>${appointment.appointed_time || ''}</td>



                <td>${appointment.method_type || ''}</td>



                <td>${appointment.consultation_type || 'Individual Consultation'}</td>



                <td>${appointment.appointment_type || (appointment.record_kind === 'follow_up' ? 'Follow-up Session' : 'First Session') || ''}</td>



                <td>${appointment.purpose || ''}</td>



                <td>${appointment.description ? '<button type="button" class="rpt-action-btn" onclick="viewStudentConcern(\'' + encodeURIComponent(JSON.stringify(appointment)) + '\')" title="View concern"><i class="fas fa-eye"></i></button>' : '<span class="rpt-cell-muted">—</span>'}</td>



                <td>${appointment.counselor_remarks ? '<button type="button" class="rpt-action-btn" onclick="viewCounselorRemarks(\'' + encodeURIComponent(JSON.stringify(appointment)) + '\')" title="View remarks"><i class="fas fa-eye"></i></button>' : '<span class="rpt-cell-muted">—</span>'}</td>



                <td>${appointment.counselor_name || ''}</td>



                <td>${getFeedbackStatus(appointment)}</td>



                <td>${feedbackMean !== null ? feedbackMean.toFixed(2) : ''}</td>



                <td>${interpretation || ''}</td>



                <td><span class="rpt-status-badge rpt-status-badge--${getStatusClass(appointment.status)}">${appointment.status === 'feedback_pending' ? 'InProgress' : (appointment.status || 'PENDING')}</span></td>



                ${showReason ? `<td class="rpt-col-reason-cell">${appointment.reason ? '<button type="button" class="rpt-action-btn" onclick="viewReasonForStatus(\'' + encodeURIComponent(JSON.stringify(appointment)) + '\')" title="View reason"><i class="fas fa-eye"></i></button>' : '<span class="rpt-cell-muted">—</span>'}</td>` : ''}



            `;

            applyAppointmentRowLabels(row, showReason);

            tableBody.appendChild(row);



        });







        // Add total summary row at the bottom



        if (sortedAppointments.length > 0) {



            const totalRow = document.createElement('tr');



            totalRow.className = 'rpt-table-summary-row';



            totalRow.innerHTML = `



                <td colspan="${showReason ? 14 : 14}" class="text-end rpt-summary-label">Total Appointments:</td>



                <td>${sortedAppointments.length}</td>



                ${showReason ? '<td class="rpt-col-reason-cell"></td>' : ''}



            `;



            tableBody.appendChild(totalRow);



        }



    }







    // Handle tab changes



    function handleTabChange(event) {



        const targetTabId = event.target.getAttribute('data-bs-target').replace('#', '');







        let targetTableId;







        switch (targetTabId) {



            case 'approved':



                targetTableId = 'approvedAppointmentsTable';



                break;



            case 'rejected':



                targetTableId = 'rejectedAppointmentsTable';



                break;



            case 'completed':



                targetTableId = 'completedAppointmentsTable';



                break;



            case 'followup':



                targetTableId = 'followUpAppointmentsTable';



                break;



            case 'rescheduled':



                targetTableId = 'rescheduledAppointmentsTable';



                break;

            case 'pending':



                targetTableId = 'pendingAppointmentsTable';



                break;



            case 'feedback-pending':



                targetTableId = 'feedbackPendingAppointmentsTable';



                break;



            case 'all':



            default:



                targetTableId = 'allAppointmentsTable';



        }







        const filteredAppointments = getFilteredAppointmentsByTab(targetTabId, allAppointments);







        console.log(`Tab changed to ${targetTabId}. Found: ${filteredAppointments.length}`);



        displayAppointments(filteredAppointments, targetTableId);



    }







    // Update initial display after fetch



    function updateInitialDisplay(followUps = []) {



        console.log('Updating initial display for all tabs');







        // Display all appointments first



        displayAppointments(allAppointments, 'allAppointmentsTable');







        // Pre-filter and display appointments for each status tab



        const approvedAppointments = getFilteredAppointmentsByTab('approved', allAppointments);



        console.log(`Found ${approvedAppointments.length} approved appointments`);



        displayAppointments(approvedAppointments, 'approvedAppointmentsTable');







        const rejectedAppointments = getFilteredAppointmentsByTab('rejected', allAppointments);



        console.log(`Found ${rejectedAppointments.length} rejected appointments`);



        displayAppointments(rejectedAppointments, 'rejectedAppointmentsTable');







        const completedAppointments = getFilteredAppointmentsByTab('completed', allAppointments);



        console.log(`Found ${completedAppointments.length} completed appointments`);



        displayAppointments(completedAppointments, 'completedAppointmentsTable');







        const rescheduledAppointments = getFilteredAppointmentsByTab('rescheduled', allAppointments);



        console.log(`Found ${rescheduledAppointments.length} rescheduled appointments`);



        displayAppointments(rescheduledAppointments, 'rescheduledAppointmentsTable');

        const pendingAppointments = getFilteredAppointmentsByTab('pending', allAppointments);
        console.log(`Found ${pendingAppointments.length} pending appointments`);
        displayAppointments(pendingAppointments, 'pendingAppointmentsTable');

        const feedbackPendingAppointments = getFilteredAppointmentsByTab('feedback-pending', allAppointments);



        console.log(`Found ${feedbackPendingAppointments.length} feedback pending appointments`);



        displayAppointments(feedbackPendingAppointments, 'feedbackPendingAppointmentsTable');







        const followUpAppointments = getFilteredAppointmentsByTab('followup', followUps);



        console.log(`Found ${followUpAppointments.length} follow-up appointments`);



        displayAppointments(followUpAppointments, 'followUpAppointmentsTable');



    }







    // Update fetchAppointments to call updateInitialDisplay



    async function fetchAppointments() {



        try {



            showLoading();



            const response = await fetch((window.BASE_URL || '/') + 'counselor/appointments/get_all_appointments', {



                method: 'GET',



                credentials: 'include',



                headers: {



                    'Cache-Control': 'no-cache',



                    'Pragma': 'no-cache'



                }



            });



            



            if (!response.ok) {



                const errorText = await response.text();



                console.error('Server response:', errorText);



                throw new Error(`HTTP error! status: ${response.status}`);



            }



            



            const data = await response.json();



            



            console.log('Full API Response:', data);



            



            if (data.success) {



                myCounselorExportScope = {
                    ids: Array.isArray(data.counselorIds)
                        ? data.counselorIds.map((id) => String(id))
                        : [],
                    name: data.counselorName || '',
                };

                allAppointments = filterToMyAppointments(data.appointments || []);

                const followUps = filterToMyAppointments(data.followUps || []);



                



                // Merge follow-ups into allAppointments for filtering purposes



                allAppointments = [...allAppointments, ...followUps];



                



                console.log('Appointments received:', data.appointments);



                console.log('Follow-ups received:', followUps);



                console.log('Merged appointments for filtering:', allAppointments);



                console.log('Number of appointments:', allAppointments.length);



                



                // Log first appointment to see structure



                if (allAppointments.length > 0) {



                    console.log('First appointment structure:', allAppointments[0]);



                }



                



                // Check if we have appointments with these statuses



                console.log('APPROVED appointments:', allAppointments.filter(app => app.status && app.status.toUpperCase() === 'APPROVED').length);



                console.log('REJECTED appointments:', allAppointments.filter(app => app.status && app.status.toUpperCase() === 'REJECTED').length);



                console.log('COMPLETED appointments:', allAppointments.filter(app => app.status && app.status.toUpperCase() === 'COMPLETED').length);



                



                updateInitialDisplay(followUps); // Update all tables initially



                



                if (allAppointments.length === 0) {



                    showEmptyState();



                } else {



                    hideEmptyState();



                }



            } else {



                console.error('Server error:', data.message);



                showError(data.message || 'Failed to fetch appointments');



            }



        } catch (error) {



            console.error('Error fetching appointments:', error);



            showError('An error occurred while fetching appointments');



        } finally {



            hideLoading();



        }



    }







    // Filter appointments based on search and date



    function filterAppointments() {



        const searchTerm = searchInput.value.toLowerCase();



        const dateValue = dateFilter.value;



        



        let filtered = allAppointments;







        if (searchTerm) {



            filtered = filtered.filter(appointment =>



                Object.values(appointment).some(value =>



                String(value).toLowerCase().includes(searchTerm)



                )



            );



        }







        if (dateValue) {



            filtered = filtered.filter(appointment =>



                appointment.appointed_date.startsWith(dateValue)



            );



        }







        const activeTab = document.querySelector('.nav-link.active');



        if (activeTab) {



            const tabId = activeTab.id;



            let targetTableId;



            



            switch (tabId) {



                case 'approved-tab':



                    targetTableId = 'approvedAppointmentsTable';



                    break;

                case 'pending-tab':



                    targetTableId = 'pendingAppointmentsTable';



                    break;



                case 'rejected-tab':



                    targetTableId = 'rejectedAppointmentsTable';



                    break;



                case 'completed-tab':



                    targetTableId = 'completedAppointmentsTable';



                    break;



                case 'followup-tab':



                    targetTableId = 'followUpAppointmentsTable';



                    break;

                case 'feedback-pending-tab':

                    targetTableId = 'feedbackPendingAppointmentsTable';

                    break;



                case 'all-tab':



                default:



                    targetTableId = 'allAppointmentsTable';



            }



            



            const tabKey = tabId.replace('-tab', '');
            filtered = getFilteredAppointmentsByTab(tabKey, filtered);



            



            displayAppointments(filtered, targetTableId);



        } else {



            displayAppointments(filtered, 'allAppointmentsTable');



        }



    }







    // Enhanced filter functions



    function clearDateRange() {



        if (exportStartDate) exportStartDate.value = '';



        if (exportEndDate) exportEndDate.value = '';



    }







    function clearAllFilters() {



        if (exportStartDate) exportStartDate.value = '';



        if (exportEndDate) exportEndDate.value = '';



        if (exportStudentFilter) exportStudentFilter.value = '';



        if (exportCourseFilter) exportCourseFilter.value = '';



        if (exportYearLevelFilter) exportYearLevelFilter.value = '';



    }







    function loadFilterData() {



        // Load students



        fetch((window.BASE_URL || '/') + 'counselor/filter-data/students')



            .then(response => response.json())



            .then(data => {



                if (data.success && exportStudentFilter) {



                    exportStudentFilter.innerHTML = '<option value="">All Students</option>';



                    data.data.forEach(student => {



                        const option = document.createElement('option');



                        option.value = student.student_id;



                        option.textContent = student.full_name;



                        exportStudentFilter.appendChild(option);



                    });



                }



            })



            .catch(error => console.error('Error loading students:', error));







        // Load academic map for course/year filtering in exports (by student_id)



        fetch((window.BASE_URL || '/') + 'counselor/filter-data/student-academic-map')



            .then(response => response.json())



            .then(data => {



                if (data.success) {



                    window.__studentAcademicMap = data.data || {};



                } else {



                    window.__studentAcademicMap = {};



                }



            })



            .catch(error => {



                console.error('Error loading academic map:', error);



                window.__studentAcademicMap = {};



            });



    }







    async function applyFilters() {



        const startDate = exportStartDate ? exportStartDate.value : '';



        const endDate = exportEndDate ? exportEndDate.value : '';



        const studentId = exportStudentFilter ? exportStudentFilter.value : '';



        const course = exportCourseFilter ? exportCourseFilter.value : '';



        const yearLevel = exportYearLevelFilter ? exportYearLevelFilter.value : '';



        const exportType = exportFiltersModalEl ? exportFiltersModalEl.getAttribute('data-export-type') : '';



        



        // Validate date range



        if (startDate && endDate && startDate > endDate) {



            alert('Start date cannot be later than end date.');



            return;



        }







        // Ensure academic map is loaded if needed



        if ((course || yearLevel) && (!window.__studentAcademicMap || Object.keys(window.__studentAcademicMap).length === 0)) {



            await ensureAcademicMapLoaded();



        }



        



        // Hide modal



        if (exportFiltersModal) exportFiltersModal.hide();



        



        // Prepare filter object



        const filters = {



            startDate,



            endDate,



            studentId,



            course,



            yearLevel



        };



        



        // Call the appropriate export function



        if (exportType === 'PDF') {



            exportToPDF(filters);



        } else if (exportType === 'Excel') {



            exportToExcel(filters);



        }



    }







    async function ensureAcademicMapLoaded() {



        try {



            const resp = await fetch((window.BASE_URL || '/') + 'counselor/filter-data/student-academic-map');



            const data = await resp.json();



            if (data && data.success) {



                window.__studentAcademicMap = data.data || {};



            }



        } catch (e) {



            console.warn('Failed to ensure academic map:', e);



        }



    }







    // Enhanced filter application function



    function applyEnhancedFilters(appointments, filters, reportTitle) {



        let filteredAppointments = [...appointments];



        let title = reportTitle;



        



        // Apply date range filter



        if (filters.startDate || filters.endDate) {



            filteredAppointments = filteredAppointments.filter(app => {



                const appointmentDate = new Date(app.appointed_date);



                const start = filters.startDate ? new Date(filters.startDate) : null;



                const end = filters.endDate ? new Date(filters.endDate) : null;



                



                if (start && end) {



                    return appointmentDate >= start && appointmentDate <= end;



                } else if (start) {



                    return appointmentDate >= start;



                } else if (end) {



                    return appointmentDate <= end;



                }



                return true;



            });



            



            // Date range is now shown in footer, not in title



        }



        



        // Apply student filter (by student_id)



        if (filters.studentId) {



            filteredAppointments = filteredAppointments.filter(app => String(app.student_id || app.user_id) === String(filters.studentId));



        }



        



        // Apply course filter using academic map



        if (filters.course) {



            const academicMap = window.__studentAcademicMap || {};



            filteredAppointments = filteredAppointments.filter(app => {



                const academic = academicMap[String(app.student_id || app.user_id)] || {};



                return academic.course === filters.course;



            });



        }



        



        // Apply year level filter using academic map



        if (filters.yearLevel) {



            const academicMap = window.__studentAcademicMap || {};



            filteredAppointments = filteredAppointments.filter(app => {



                const academic = academicMap[String(app.student_id || app.user_id)] || {};



                return academic.year_level === filters.yearLevel;



            });



        }



        



        return {



            appointments: filteredAppointments,



            reportTitle: title



        };



    }







    // Build human-readable filter summary for export footers



    function buildFilterSummary(filters) {



        const parts = [];



        // Status from active tab



        const activeTab = document.querySelector('.nav-link.active');



        if (activeTab) {



            const tabId = activeTab.getAttribute('data-bs-target').replace('#', '');



            const statusMap = { all: 'All', approved: 'Approved', rejected: 'Rejected', completed: 'Completed' };



            parts.push(`Status: ${statusMap[tabId] || 'All'}`);



        }



        if (filters.startDate) parts.push(`Start: ${formatDateForTitle(filters.startDate)}`);



        if (filters.endDate) parts.push(`End: ${formatDateForTitle(filters.endDate)}`);



        if (filters.studentId) {



            const opt = exportStudentFilter ? exportStudentFilter.querySelector(`option[value="${filters.studentId}"]`) : null;



            const label = opt ? opt.textContent : filters.studentId;



            parts.push(`Student: ${label}`);



        }



        if (filters.course) parts.push(`Course: ${filters.course}`);



        if (filters.yearLevel) parts.push(`Year: ${filters.yearLevel}`);



        return parts.join(' | ');



    }







    // Export to PDF — modern A4 dashboard layout (shared module)
    async function exportToPDF(filters = {}) {
        try {
            if (!window.AppointmentPdfDashboard) {
                throw new Error('PDF dashboard module not loaded. Please refresh the page.');
            }
            const activeTabKey = window.AppointmentPdfDashboard.resolveActiveTabKey();
            const myAppointments = filterToMyAppointments(allAppointments);
            await window.AppointmentPdfDashboard.generate({
                appointments: myAppointments,
                activeTabKey,
                reportTitle: reportTitleForMyAppointments(activeTabKey),
                filters,
                filterSummary: buildFilterSummary(filters),
                helpers: {
                    calculateFeedbackMean,
                    getInterpretation,
                    formatDate,
                },
                applyFilters: applyEnhancedFilters,
                baseUrl: window.BASE_URL || '/',
                role: 'counselor',
                ownCounselorOnly: true,
                counselorIds: myCounselorExportScope.ids,
                counselorName: myCounselorExportScope.name,
                scopeSubtitle: 'List of All Your Appointments',
            });
        } catch (error) {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF. Please try again. Error: ' + error.message);
        }
    }

    // Export to Excel



    function exportToExcel(filters = {}) {



        // Get current active tab



        const activeTab = document.querySelector('.nav-link.active');



        



        // Get appointments based on active tab



        let appointmentsToExport = [...allAppointments];



        let reportTitle = 'All Consultation Records';



        



        if (activeTab) {



            const tabId = activeTab.getAttribute('data-bs-target').replace('#', '');



            switch (tabId) {



                case 'approved':



                    appointmentsToExport = getFilteredAppointmentsByTab('approved', allAppointments);



                    reportTitle = 'Approved Consultation Records';



                    break;



                case 'rejected':



                    appointmentsToExport = getFilteredAppointmentsByTab('rejected', allAppointments);



                    reportTitle = 'Rejected Consultation Records';



                    break;



                case 'completed':



                    appointmentsToExport = getFilteredAppointmentsByTab('completed', allAppointments);



                    reportTitle = 'Completed Consultation Records';



                    break;



                case 'rescheduled':



                    appointmentsToExport = getFilteredAppointmentsByTab('rescheduled', allAppointments);



                    reportTitle = 'Rescheduled Consultation Records';



                    break;



                case 'followup':



                    appointmentsToExport = getFilteredAppointmentsByTab('followup', allAppointments);



                    reportTitle = 'Follow-up Consultation Records';



                    break;



                case 'feedback-pending':



                    appointmentsToExport = getFilteredAppointmentsByTab('feedback-pending', allAppointments);



                    reportTitle = 'InProgress Consultation Records';



                    break;



            }



        }







        // Apply enhanced filters



        const filteredResult = applyEnhancedFilters(appointmentsToExport, filters, reportTitle);



        reportTitle = filteredResult.reportTitle || reportTitle;



        appointmentsToExport = filteredResult.appointments || appointmentsToExport;







        // Sort appointments from oldest to newest



        appointmentsToExport.sort((a, b) => {



            const dateTimeA = a.appointed_date + ' ' + a.appointed_time;



            const dateTimeB = b.appointed_date + ' ' + b.appointed_time;



            return dateTimeA < dateTimeB ? -1 : dateTimeA > dateTimeB ? 1 : 0;



        });







        // Determine if we need to show "Reason for Status" column



        const showReason = reportTitle.includes('Rejected') || reportTitle.includes('All');







        // Prepare the data with headers



        const headerRow = showReason 



            ? ['User ID', 'Full Name', 'Date', 'Time', 'Method Type', 'Consultation Type', 'Session', 'Purpose', 'Counselor', 'Status', 'Reason for Status']



            : ['User ID', 'Full Name', 'Date', 'Time', 'Method Type', 'Consultation Type', 'Session', 'Purpose', 'Counselor', 'Status'];



        



        const filterSummary = buildFilterSummary(filters) || 'No additional filters applied';



        const excelData = [



            [reportTitle],              // Title row



            [filterSummary],            // Filters summary row



            [],                         // Empty row for spacing



            headerRow                   // Headers



        ];







        // Add the appointment data



        appointmentsToExport.forEach(app => {



            const appointmentType = app.appointment_type || (app.record_kind === 'follow_up' ? 'Follow-up Session' : 'First Session');



            const baseData = [



                (app.student_id || app.user_id || ''),



                app.student_name || '',



                formatDate(app.appointed_date),



                app.appointed_time,



                app.method_type,



                app.consultation_type || 'Individual Consultation',



                appointmentType,



                app.purpose || 'N/A',



                app.counselor_name,



                (app.status ? String(app.status).toLowerCase() : '')



            ];



            



            if (showReason) {



                baseData.push(app.reason || '');



            }



            



            excelData.push(baseData);



        });







        // Create a new workbook and worksheet



        const workbook = XLSX.utils.book_new();



        const worksheet = XLSX.utils.aoa_to_sheet(excelData);







        // Set column widths



        const cols = [



            { wch: 12 },    // User ID



            { wch: 25 },    // Full Name



            { wch: 12 },    // Date



            { wch: 15 },    // Time



            { wch: 15 },    // Method Type



            { wch: 22 },    // Consultation Type



            { wch: 18 },    // Session



            { wch: 30 },    // Purpose



            { wch: 25 },    // Counselor



            { wch: 12 },    // Status



            ...(showReason ? [{ wch: 40 }] : []) // Reason (if shown)



        ];



        worksheet['!cols'] = cols;







        // Set title merge



        const mergeEnd = showReason ? 10 : 9;



        worksheet['!merges'] = [



            { s: { r: 0, c: 0 }, e: { r: 0, c: mergeEnd } }



        ];







        // Apply styles: title (row 1), headers (row 4), and Status column



        (function applyExcelStyles(){



            // Helper to get A1 reference



            function addr(r, c) {



                return XLSX.utils.encode_cell({ r: r - 1, c }); // convert to 0-based



            }



            // Title: row 1, col 1



            const titleCellRef = addr(1, 0);



            if (!worksheet[titleCellRef]) worksheet[titleCellRef] = { t: 's', v: reportTitle };



            worksheet[titleCellRef].s = {



                font: { bold: true, sz: 14 },



                alignment: { horizontal: 'center' }



            };







            // Header row is row 4 (after title, filter summary, and empty row)



            const headerRow = 4;



            const totalCols = mergeEnd + 1;



            for (let c = 0; c < totalCols; c++) {



                const ref = addr(headerRow, c);



                if (worksheet[ref]) {



                    worksheet[ref].s = Object.assign({}, worksheet[ref].s || {}, {



                        font: { bold: true },



                        alignment: { horizontal: 'center' }



                    });



                }



            }







            // Make Status column bold and centered



            const headerLabels = showReason 



                ? ['User ID', 'Full Name', 'Date', 'Time', 'Method Type', 'Session', 'Purpose', 'Counselor', 'Status', 'Reason for Status']



                : ['User ID', 'Full Name', 'Date', 'Time', 'Method Type', 'Session', 'Purpose', 'Counselor', 'Status'];



            const statusColIdx = headerLabels.indexOf('Status');



            if (statusColIdx >= 0) {



                for (let r = headerRow + 1; r < excelData.length + 1; r++) { // data rows start after header



                    const ref = addr(r, statusColIdx);



                    if (worksheet[ref]) {



                        worksheet[ref].s = Object.assign({}, worksheet[ref].s || {}, {



                            font: { bold: true },



                            alignment: { horizontal: 'center' }



                        });



                    }



                }



            }



        })();







        // Add the worksheet to the workbook



        XLSX.utils.book_append_sheet(workbook, worksheet, 'Appointments');







        // Generate filename and save



        const today = new Date().toISOString().split('T')[0];



        const filename = `${reportTitle.toLowerCase().replace(/\s+/g, '_')}_${today}.xlsx`;



        XLSX.writeFile(workbook, filename);



    }







    // View appointment details



    window.viewDetails = function (appointmentId) {



        const appointment = allAppointments.find(app => app.id === appointmentId);



        if (!appointment) return;







        const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));



        document.getElementById('modalTitle').textContent = `Appointment Details - ${appointment.student_id}`;



        



        const modalBody = document.getElementById('modalBody');



        modalBody.innerHTML = `



            <div class="row">



                <div class="col-md-6">



                    <p><strong>Student ID:</strong> ${appointment.student_id}</p>



                    <p><strong>Date:</strong> ${formatDate(appointment.appointed_date)}</p>



                    <p><strong>Time:</strong> ${formatTime(appointment.appointed_time)}</p>



                </div>



                <div class="col-md-6">



                    <p><strong>Consultation Type:</strong> ${appointment.method_type}</p>



                    <p><strong>Counselor:</strong> ${appointment.counselor_name}</p>



                    <p><strong>Status:</strong> <span class="badge badge-${getStatusClass(appointment.status)}">${appointment.status === 'feedback_pending' ? 'InProgress' : appointment.status}</span></p>



                </div>



            </div>



            <div class="mt-3">



                <p><strong>Notes:</strong></p>



                <p>${appointment.notes || 'No notes available'}</p>



            </div>



        `;







        modal.show();



    };







    // Utility functions



    function formatDate(dateString) {



        return new Date(dateString).toLocaleDateString();



    }







    function formatTime(timeString) {



        return new Date(`2000-01-01T${timeString}`).toLocaleTimeString([], { 



            hour: '2-digit', 



            minute: '2-digit' 



        });



    }















    // Show loading state



    function showLoading() {



        if (loadingSpinner) loadingSpinner.style.display = 'flex';



        document.querySelectorAll('.table-responsive').forEach(el => el.style.display = 'none');



    }







    // Hide loading state



    function hideLoading() {



        if (loadingSpinner) loadingSpinner.style.display = 'none';



        document.querySelectorAll('.table-responsive').forEach(el => el.style.display = 'block');



    }







    // Show empty state



    function showEmptyState() {



        if (emptyState) emptyState.style.display = 'block';



        document.querySelectorAll('.table-responsive').forEach(el => el.style.display = 'none');



    }







    // Hide empty state



    function hideEmptyState() {



        if (emptyState) emptyState.style.display = 'none';



        document.querySelectorAll('.table-responsive').forEach(el => el.style.display = 'block');



    }







    function showError(message) {



        // You can implement a toast or alert system here



        alert(message);



    }















    function formatDateForTitle(dateString) {



        const date = new Date(dateString);



        return date.toLocaleDateString('en-US', {



            year: 'numeric',



            month: 'short',



            day: 'numeric'



        });



    }



});







// Global functions for viewing details



function viewStudentConcern(encodedAppointment) {



    const appointment = JSON.parse(decodeURIComponent(encodedAppointment));



    document.getElementById('modalStudentName').textContent = appointment.student_name || 'N/A';



    document.getElementById('modalDateTime').textContent = `${appointment.appointed_date ? new Date(appointment.appointed_date).toLocaleDateString() : 'N/A'} at ${appointment.appointed_time || 'N/A'}`;



    document.getElementById('modalDetails').textContent = appointment.description || 'No student concern available';



    document.getElementById('viewDetailsModalLabel').innerHTML = '<i class="fas fa-user me-2"></i>Student Concern';



    const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));



    modal.show();



}







function viewCounselorRemarks(encodedAppointment) {



    const appointment = JSON.parse(decodeURIComponent(encodedAppointment));



    document.getElementById('modalStudentName').textContent = appointment.student_name || 'N/A';



    document.getElementById('modalDateTime').textContent = `${appointment.appointed_date ? new Date(appointment.appointed_date).toLocaleDateString() : 'N/A'} at ${appointment.appointed_time || 'N/A'}`;



    document.getElementById('modalDetails').textContent = appointment.counselor_remarks || 'No counselor remarks available';



    document.getElementById('viewDetailsModalLabel').innerHTML = '<i class="fas fa-comment-dots me-2"></i>Counselor Remarks';



    const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));



    modal.show();



}







function viewReasonForStatus(encodedAppointment) {



    const appointment = JSON.parse(decodeURIComponent(encodedAppointment));



    document.getElementById('modalStudentName').textContent = appointment.student_name || 'N/A';



    document.getElementById('modalDateTime').textContent = `${appointment.appointed_date ? new Date(appointment.appointed_date).toLocaleDateString() : 'N/A'} at ${appointment.appointed_time || 'N/A'}`;



    document.getElementById('modalDetails').textContent = appointment.reason || 'No reason for status available';



    document.getElementById('viewDetailsModalLabel').innerHTML = '<i class="fas fa-info-circle me-2"></i>Reason for Status';



    const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));



    modal.show();



}















