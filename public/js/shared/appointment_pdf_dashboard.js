/**
 * Modern A4 portrait PDF dashboard report for Counselign appointment exports.
 * Preserves existing institutional header/footer; modernizes report body only.
 */
(function (global) {
    'use strict';

    const NAVY = [0, 51, 102];
    const NAVY_TEXT = [6, 14, 87];
    const GRAY_BG = [241, 245, 249];
    const GRAY_TEXT = [100, 116, 139];
    const WHITE = [255, 255, 255];
    const SHADOW = [226, 232, 240];

    const FILTER_TABS = [
        { key: 'all', label: 'All Appointments', short: 'All' },
        { key: 'followup', label: 'Follow-up', short: 'Follow-up' },
        { key: 'approved', label: 'Approved', short: 'Approved' },
        { key: 'rescheduled', label: 'Rescheduled', short: 'Rescheduled' },
        { key: 'pending', label: 'Pending', short: 'Pending' },
        { key: 'completed', label: 'Completed', short: 'Completed' },
        { key: 'feedback-pending', label: 'InProgress', short: 'InProgress' },
    ];

    const PAGE_MARGIN = 12;
    const FOOTER_RESERVE = 30;
    const SIGNATURE_BLOCK_H = 26;

    const STATUS_BADGE = {
        completed: { fill: [0, 102, 204], label: 'Completed' },
        approved: { fill: [40, 167, 69], label: 'Approved' },
        rescheduled: { fill: [255, 193, 7], label: 'Rescheduled' },
        pending: { fill: [220, 53, 69], label: 'Pending' },
        feedback_pending: { fill: [108, 117, 125], label: 'InProgress' },
        inprogress: { fill: [108, 117, 125], label: 'InProgress' },
        followup: { fill: [255, 193, 7], label: 'Follow-up' },
        rejected: { fill: [220, 53, 69], label: 'Rejected' },
        cancelled: { fill: [108, 117, 125], label: 'Cancelled' },
    };

    function hasSubmittedFeedback(appointment) {
        const s = String(appointment?.feedback_status || '')
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');
        return s === 'submitted' || s === 'feedback_submitted';
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
        if (isFollowUp) return false;
        return (
            (status === 'COMPLETED' || status === 'FEEDBACK_PENDING') &&
            !hasSubmittedFeedback(appointment)
        );
    }

    function filterOwnCounselor(appointments, counselorIds) {
        if (!Array.isArray(counselorIds) || counselorIds.length === 0) {
            return appointments;
        }
        const ids = counselorIds.map((id) => String(id));
        return (appointments || []).filter((app) => {
            const pref = String(app.counselor_preference || app.counselor_id || '').trim();
            if (!pref) return false;
            return ids.includes(pref);
        });
    }

    function filterByTab(appointments, tabKey) {
        const source = Array.isArray(appointments) ? appointments : [];
        switch (tabKey) {
            case 'approved':
                return source.filter((a) => String(a.status || '').toUpperCase() === 'APPROVED');
            case 'rescheduled':
                return source.filter((a) => String(a.status || '').toUpperCase() === 'RESCHEDULED');
            case 'pending':
                return source.filter((a) => String(a.status || '').toUpperCase() === 'PENDING');
            case 'completed':
                return source.filter(
                    (a) =>
                        String(a.status || '').toUpperCase() === 'COMPLETED' &&
                        hasSubmittedFeedback(a)
                );
            case 'followup':
                return source.filter((a) => {
                    const isFollowUp =
                        String(a.record_kind || '').toLowerCase() === 'follow_up' ||
                        String(a.appointment_type || '').toLowerCase().includes('follow-up');
                    const st = String(a.status || '').toUpperCase();
                    return isFollowUp && (st === 'PENDING' || st === 'COMPLETED');
                });
            case 'feedback-pending':
                return source.filter((a) => isFeedbackPendingAppointment(a));
            case 'all':
            default:
                return source;
        }
    }

    function resolveActiveTabKey() {
        const activeTab = document.querySelector(
            '.rpt-tabs .nav-link.active[data-bs-target], .nav-tabs .nav-link.active[data-bs-target^="#"]'
        );
        if (!activeTab) return 'all';
        const target = activeTab.getAttribute('data-bs-target') || '#all';
        const key = target.replace('#', '').trim();
        return key || 'all';
    }

    function reportTitleForTab(tabKey) {
        const map = {
            all: 'Appointment Analytics Dashboard Report',
            followup: 'Follow-up Consultation Records',
            approved: 'Approved Consultation Records',
            rescheduled: 'Rescheduled Consultation Records',
            pending: 'Pending Consultation Records',
            completed: 'Completed Consultation Records',
            'feedback-pending': 'InProgress Consultation Records',
        };
        return map[tabKey] || map.all;
    }

    function isFollowUpRecord(app) {
        const recordKind = String(app.record_kind || '').toLowerCase();
        const appointmentType = String(app.appointment_type || '').toLowerCase();
        return recordKind === 'follow_up' || appointmentType.includes('follow-up');
    }

    /** Aligns with UI tabs / GetAllAppointments status buckets */
    function classifyAppointmentStatus(app) {
        if (isFollowUpRecord(app)) return 'followup';
        if (isFeedbackPendingAppointment(app)) return 'inProgress';
        const st = String(app.status || '').trim().toUpperCase();
        if (st === 'COMPLETED' && hasSubmittedFeedback(app)) return 'completed';
        if (st === 'APPROVED') return 'approved';
        if (st === 'RESCHEDULED') return 'rescheduled';
        if (st === 'PENDING') return 'pending';
        if (st === 'REJECTED') return 'rejected';
        if (st === 'CANCELLED') return 'cancelled';
        return 'other';
    }

    function statusDisplayKey(app) {
        const bucket = classifyAppointmentStatus(app);
        if (bucket === 'followup') return 'followup';
        if (bucket === 'inProgress') return 'feedback_pending';
        if (bucket === 'cancelled') return 'cancelled';
        if (bucket === 'other') return 'unknown';
        return bucket;
    }

    function countStatusBuckets(appointments) {
        const statusCounts = {
            completed: 0,
            approved: 0,
            rescheduled: 0,
            pending: 0,
            inProgress: 0,
            cancelled: 0,
            other: 0,
        };
        let followUpCount = 0;
        (appointments || []).forEach((app) => {
            const bucket = classifyAppointmentStatus(app);
            if (bucket === 'followup') {
                followUpCount += 1;
                return;
            }
            if (Object.prototype.hasOwnProperty.call(statusCounts, bucket)) {
                statusCounts[bucket] += 1;
            } else {
                statusCounts.other += 1;
            }
        });
        return { statusCounts, followUpCount };
    }

    function roundedRect(doc, x, y, w, h, r, style) {
        if (typeof doc.roundedRect === 'function') {
            doc.roundedRect(x, y, w, h, r, r, style);
        } else {
            doc.rect(x, y, w, h, style);
        }
    }

    function drawPageHeader(doc, logoImg) {
        const pageWidth = doc.internal.pageSize.getWidth();
        doc.addImage(logoImg, 'PNG', 12, 10, 20, 15);
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 0, 0);
        doc.text('Counselign: USTP Guidance Counseling Sanctuary', 37, 17);
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.5);
        doc.line(12, 27, pageWidth - 12, 27);
    }

    function getContentBottom(doc) {
        return doc.internal.pageSize.getHeight() - FOOTER_RESERVE;
    }

    function drawPageFooter(doc, pageNumber) {
        const pageHeight = doc.internal.pageSize.getHeight();
        const pageWidth = doc.internal.pageSize.getWidth();
        const footerLineY = pageHeight - 22;
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.3);
        doc.line(PAGE_MARGIN, footerLineY, pageWidth - PAGE_MARGIN, footerLineY);
        doc.setFontSize(7);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        const leftText = 'Confidential Document';
        const centerText = 'Prepared by the University Guidance Counseling Office';
        const currentDate = new Date();
        const dateStr = currentDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
        const timeStr = currentDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
        });
        const rightText = `Generated: ${dateStr} | ${timeStr} PST | Page ${pageNumber}`;
        const y = pageHeight - 17;
        doc.text(leftText, PAGE_MARGIN, y, { align: 'left' });
        doc.text(centerText, pageWidth / 2, y, { align: 'center' });
        doc.text(rightText, pageWidth - PAGE_MARGIN, y, { align: 'right' });
    }

    function drawFilterPills(doc, y, activeTabKey) {
        const pageWidth = doc.internal.pageSize.getWidth();
        const usable = pageWidth - PAGE_MARGIN * 2;
        const pillH = 6.5;
        const gap = 2.5;
        const cols = 3;
        const pillW = (usable - gap * (cols - 1)) / cols;
        const rows = [
            FILTER_TABS.slice(0, 3),
            FILTER_TABS.slice(3, 6),
        ];

        rows.forEach((rowPills, rowIdx) => {
            let x = PAGE_MARGIN;
            const rowY = y + rowIdx * (pillH + gap);
            rowPills.forEach((pill) => {
                const isActive = pill.key === activeTabKey;
                if (isActive) {
                    doc.setFillColor.apply(doc, NAVY);
                    doc.setTextColor.apply(doc, WHITE);
                } else {
                    doc.setFillColor.apply(doc, GRAY_BG);
                    doc.setTextColor.apply(doc, GRAY_TEXT);
                }
                roundedRect(doc, x, rowY, pillW, pillH, 2, 'F');
                doc.setFontSize(6);
                doc.setFont('helvetica', isActive ? 'bold' : 'normal');
                doc.text(pill.short, x + pillW / 2, rowY + 4.3, { align: 'center' });
                x += pillW + gap;
            });
        });
        doc.setTextColor(0, 0, 0);
        return y + 2 * (pillH + gap) + 2;
    }

    function drawFilterContext(doc, y, activeFilterLabel, recordCount, filterSummary, counselorName, scopeSubtitle) {
        doc.setFontSize(7);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor.apply(doc, GRAY_TEXT);
        let lineY = y;
        if (scopeSubtitle) {
            doc.setFont('helvetica', 'bold');
            doc.setTextColor.apply(doc, NAVY_TEXT);
            doc.text(scopeSubtitle, PAGE_MARGIN, lineY);
            lineY += 4;
            doc.setFont('helvetica', 'normal');
            doc.setTextColor.apply(doc, GRAY_TEXT);
        }
        if (counselorName) {
            doc.text(`Counselor: ${counselorName}`, PAGE_MARGIN, lineY);
            lineY += 4;
        }
        const line1 = `Active filter: ${activeFilterLabel}  |  Records in report: ${recordCount}`;
        doc.text(line1, PAGE_MARGIN, lineY);
        lineY += 4;
        if (filterSummary && filterSummary !== 'No additional filters applied') {
            doc.setFontSize(6);
            doc.text(filterSummary, PAGE_MARGIN, lineY, {
                maxWidth: doc.internal.pageSize.getWidth() - PAGE_MARGIN * 2,
            });
            return lineY + 5;
        }
        return lineY + 1;
    }

    function drawSectionTitle(doc, y, title) {
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor.apply(doc, NAVY_TEXT);
        doc.text(title, 12, y);
        doc.setDrawColor.apply(doc, SHADOW);
        doc.setLineWidth(0.2);
        doc.line(12, y + 1.5, doc.internal.pageSize.getWidth() - 12, y + 1.5);
        return y + 5;
    }

    function drawSummaryCard(doc, x, y, w, h, label, value) {
        doc.setFillColor.apply(doc, WHITE);
        doc.setDrawColor.apply(doc, SHADOW);
        doc.setLineWidth(0.15);
        roundedRect(doc, x, y, w, h, 2, 'FD');

        doc.setFontSize(5.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor.apply(doc, GRAY_TEXT);
        doc.text(label, x + 3, y + 5, { maxWidth: w - 6 });

        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor.apply(doc, NAVY_TEXT);
        const valStr = String(value);
        const displayVal =
            doc.getTextWidth(valStr) > w - 8
                ? valStr.length > 10
                    ? valStr.slice(0, 9) + '…'
                    : valStr
                : valStr;
        doc.text(displayVal, x + 3, y + h - 4);
    }

    function computeAnalytics(appointments, helpers, statusSourceAppointments) {
        const { calculateFeedbackMean, getInterpretation } = helpers;
        const studentIds = new Set();
        const ratings = [];
        const purposeCounts = {};
        const methodCounts = {};
        const ratingByMonth = {};
        const counselorMap = {};

        const statusList = Array.isArray(statusSourceAppointments)
            ? statusSourceAppointments
            : appointments;
        const { statusCounts, followUpCount } = countStatusBuckets(statusList);

        appointments.forEach((app) => {
            const sid = app.student_id || app.user_id || app.student_name;
            if (sid) studentIds.add(String(sid));
            const mean = calculateFeedbackMean(app);
            if (mean !== null && !isNaN(mean)) {
                ratings.push(mean);
                const monthKey = (app.appointed_date || '').slice(0, 7) || 'Unknown';
                if (!ratingByMonth[monthKey]) ratingByMonth[monthKey] = [];
                ratingByMonth[monthKey].push(mean);
            }

            const purpose = (app.purpose || 'General Counseling').trim();
            purposeCounts[purpose] = (purposeCounts[purpose] || 0) + 1;
            const method = (app.method_type || 'In-Person').trim();
            methodCounts[method] = (methodCounts[method] || 0) + 1;

            const counselor = app.counselor_name || 'Unassigned';
            if (!counselorMap[counselor]) counselorMap[counselor] = { count: 0, ratings: [] };
            counselorMap[counselor].count++;
            if (mean !== null) counselorMap[counselor].ratings.push(mean);
        });

        const avgRating =
            ratings.length > 0
                ? (ratings.reduce((a, b) => a + b, 0) / ratings.length).toFixed(2)
                : 'N/A';
        const highest = ratings.length ? Math.max(...ratings).toFixed(2) : 'N/A';
        const lowest = ratings.length ? Math.min(...ratings).toFixed(2) : 'N/A';

        const purposeSorted = Object.entries(purposeCounts).sort((a, b) => b[1] - a[1]);
        const methodSorted = Object.entries(methodCounts).sort((a, b) => b[1] - a[1]);
        const monthTrend = Object.keys(ratingByMonth)
            .sort()
            .slice(-6)
            .map((m) => ({
                label: m,
                value:
                    ratingByMonth[m].reduce((a, b) => a + b, 0) / ratingByMonth[m].length,
            }));

        const counselorPerf = Object.entries(counselorMap)
            .map(([name, d]) => ({
                name,
                sessions: d.count,
                avg:
                    d.ratings.length > 0
                        ? (d.ratings.reduce((a, b) => a + b, 0) / d.ratings.length).toFixed(2)
                        : 'N/A',
            }))
            .sort((a, b) => b.sessions - a.sessions)
            .slice(0, 5);

        const topPurpose = purposeSorted[0] ? purposeSorted[0][0] : 'Counseling';
        const topMethod = methodSorted[0] ? methodSorted[0][0] : 'In-Person';
        const veryGoodCount = appointments.filter((a) => {
            const m = calculateFeedbackMean(a);
            const interp = m !== null ? getInterpretation(m) : '';
            return String(interp).toLowerCase().includes('very good');
        }).length;

        return {
            totalSessions: appointments.length,
            totalStudents: studentIds.size,
            avgRating,
            highest,
            lowest,
            followUpCount,
            statusCounts,
            purposeSorted: purposeSorted.slice(0, 5),
            methodSorted: methodSorted.slice(0, 4),
            monthTrend,
            counselorPerf,
            insights: [
                `Most appointments fall under ${topPurpose}.`,
                veryGoodCount > 0
                    ? `Majority of feedback ratings are Very Good (${veryGoodCount} records).`
                    : 'Feedback ratings are distributed across satisfaction levels.',
                `${topMethod} consultations remain dominant in this report period.`,
                statusCounts.completed > 0
                    ? 'Completed appointments have the highest satisfaction rate in this dataset.'
                    : 'Complete more sessions to unlock satisfaction trend insights.',
            ],
        };
    }

    function normalizeChartColors(colors) {
        if (!colors || !colors.length) return [NAVY];
        if (typeof colors[0] === 'number') return [colors];
        return colors;
    }

    function drawMiniBarChart(doc, x, y, w, h, title, items, colors) {
        const palette = normalizeChartColors(colors);
        doc.setFillColor.apply(doc, WHITE);
        doc.setDrawColor.apply(doc, SHADOW);
        roundedRect(doc, x, y, w, h, 2, 'FD');
        doc.setFontSize(6.5);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor.apply(doc, NAVY_TEXT);
        doc.text(title, x + 3, y + 5);
        const maxVal = Math.max(...items.map((i) => i.value), 1);
        const barAreaH = h - 14;
        const barW = Math.min(12, (w - 16) / Math.max(items.length, 1) - 2);
        let bx = x + 6;
        items.forEach((item, idx) => {
            const barH = (item.value / maxVal) * barAreaH;
            const color = palette[idx % palette.length];
            doc.setFillColor.apply(doc, color);
            roundedRect(doc, bx, y + h - 4 - barH, barW, barH, 1, 'F');
            doc.setFontSize(4.5);
            doc.setTextColor.apply(doc, GRAY_TEXT);
            const short =
                item.label.length > 8 ? item.label.slice(0, 7) + '…' : item.label;
            doc.text(short, bx + barW / 2, y + h - 2, { align: 'center' });
            bx += barW + 3;
        });
    }

    function drawStatusSummaryCards(doc, y, statusCounts, followUpCount) {
        y = drawSectionTitle(doc, y, 'Status Summary');
        const margin = 12;
        const pageWidth = doc.internal.pageSize.getWidth();
        const cols = 3;
        const gap = 2;
        const cardW = (pageWidth - margin * 2 - gap * (cols - 1)) / cols;
        const cardH = 14;
        const cards = [
            { label: 'Completed', value: statusCounts.completed, color: [0, 102, 204] },
            { label: 'Approved', value: statusCounts.approved, color: [40, 167, 69] },
            { label: 'Rescheduled', value: statusCounts.rescheduled, color: [255, 193, 7] },
            { label: 'Pending', value: statusCounts.pending || 0, color: [220, 53, 69] },
            { label: 'InProgress', value: statusCounts.inProgress, color: [108, 117, 125] },
            { label: 'Follow-up', value: followUpCount, color: [255, 193, 7] },
        ];
        let rowY = y;
        cards.forEach((c, idx) => {
            const col = idx % cols;
            if (col === 0 && idx > 0) rowY += cardH + gap;
            const x = margin + col * (cardW + gap);
            doc.setFillColor.apply(doc, c.color);
            roundedRect(doc, x, rowY, cardW, cardH, 2, 'F');
            doc.setFontSize(5);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(255, 255, 255);
            doc.text(c.label, x + 2, rowY + 5);
            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.text(String(c.value), x + 2, rowY + 11);
        });
        doc.setTextColor(0, 0, 0);
        return rowY + cardH + 5;
    }

    function drawInsights(doc, y, insights) {
        y = drawSectionTitle(doc, y, 'Insights & Highlights');
        doc.setFontSize(6.5);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(50, 50, 50);
        const maxW = doc.internal.pageSize.getWidth() - PAGE_MARGIN * 2 - 4;
        insights.forEach((line) => {
            const lines = doc.splitTextToSize('• ' + line, maxW);
            doc.text(lines, PAGE_MARGIN + 2, y);
            y += lines.length * 4.2 + 1;
        });
        return y + 3;
    }

    function drawCounselorTable(doc, y, rows) {
        y = drawSectionTitle(doc, y, 'Counselor Performance Summary');
        doc.autoTable({
            startY: y,
            head: [['Counselor', 'Sessions Handled', 'Average Rating']],
            body: rows.map((r) => [r.name, String(r.sessions), r.avg]),
            margin: { left: PAGE_MARGIN, right: PAGE_MARGIN },
            tableWidth: doc.internal.pageSize.getWidth() - 24,
            styles: { fontSize: 7, cellPadding: 2 },
            headStyles: { fillColor: NAVY, textColor: WHITE, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [248, 250, 252] },
        });
        return doc.lastAutoTable ? doc.lastAutoTable.finalY + 4 : y + 20;
    }

    function getSignatureMaxStartY(doc) {
        return doc.internal.pageSize.getHeight() - FOOTER_RESERVE - SIGNATURE_BLOCK_H - 6;
    }

    function drawSignatures(doc, y) {
        const pageWidth = doc.internal.pageSize.getWidth();
        const minY = 32;
        const maxY = getSignatureMaxStartY(doc);
        const startY = Math.min(Math.max(y, minY), maxY);

        doc.setFontSize(8);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor.apply(doc, NAVY_TEXT);
        doc.text('Acknowledgment', PAGE_MARGIN, startY);

        const lineY = startY + 14;
        const leftX = PAGE_MARGIN;
        const rightX = pageWidth / 2 + 6;
        const lineW = (pageWidth - PAGE_MARGIN * 2 - 12) / 2;

        doc.setDrawColor(120, 120, 120);
        doc.setLineWidth(0.2);
        doc.line(leftX, lineY, leftX + lineW, lineY);
        doc.line(rightX, lineY, rightX + lineW, lineY);

        doc.setFontSize(7);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(50, 50, 50);
        doc.text('Guidance Counselor', leftX, lineY + 5);
        doc.text('Head, Guidance Office', rightX, lineY + 5);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(6);
        doc.setTextColor(100, 100, 100);
        doc.text('Signature over printed name', leftX, lineY + 9);
        doc.text('Signature over printed name', rightX, lineY + 9);
        return startY + SIGNATURE_BLOCK_H;
    }

    function drawDashboardPage1(doc, logoImg, opts) {
        const {
            activeTabKey,
            reportTitle,
            activeFilterLabel,
            filterSummaryText,
            analytics,
            recordCount,
            counselorName,
            scopeSubtitle,
        } = opts;
        const pageWidth = doc.internal.pageSize.getWidth();
        const contentBottom = getContentBottom(doc);

        drawPageHeader(doc, logoImg);
        let y = 30;
        y = drawFilterPills(doc, y, activeTabKey);
        y = drawFilterContext(
            doc,
            y,
            activeFilterLabel,
            recordCount,
            filterSummaryText,
            counselorName,
            scopeSubtitle
        );

        doc.setFontSize(9.5);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor.apply(doc, NAVY_TEXT);
        doc.text(reportTitle, PAGE_MARGIN, y, {
            maxWidth: pageWidth - PAGE_MARGIN * 2,
        });
        y += 7;

        y = drawSectionTitle(doc, y, 'Executive Summary');
        const cardW = (pageWidth - PAGE_MARGIN * 2 - 6) / 3;
        const cardH = 20;
        const summaryCards = [
            ['Total Sessions', analytics.totalSessions],
            ['Total Students', analytics.totalStudents],
            ['Average Rating', analytics.avgRating],
            ['Highest Rating', analytics.highest],
            ['Lowest Rating', analytics.lowest],
            [
                'Active Category',
                FILTER_TABS.find((t) => t.key === activeTabKey)?.short || activeFilterLabel,
            ],
        ];
        for (let row = 0; row < 2; row++) {
            for (let col = 0; col < 3; col++) {
                const idx = row * 3 + col;
                const [label, value] = summaryCards[idx];
                drawSummaryCard(
                    doc,
                    PAGE_MARGIN + col * (cardW + 3),
                    y + row * (cardH + 3),
                    cardW,
                    cardH,
                    label,
                    value
                );
            }
        }
        y += 2 * (cardH + 3) + 5;

        y = drawSectionTitle(doc, y, 'Analytics Overview');
        const chartW = (pageWidth - PAGE_MARGIN * 2 - 4) / 2;
        const chartH = 26;
        const statusItems = [
            { label: 'Completed', value: analytics.statusCounts.completed },
            { label: 'Approved', value: analytics.statusCounts.approved },
            { label: 'Rescheduled', value: analytics.statusCounts.rescheduled },
            { label: 'Pending', value: analytics.statusCounts.pending || 0 },
            { label: 'InProgress', value: analytics.statusCounts.inProgress },
            { label: 'Follow-up', value: analytics.followUpCount || 0 },
        ];
        const purposeItems = analytics.purposeSorted.map(([label, value]) => ({
            label: label.length > 10 ? label.slice(0, 9) + '…' : label,
            value,
        }));
        const methodItems = analytics.methodSorted.map(([label, value]) => ({
            label: label.length > 10 ? label.slice(0, 9) + '…' : label,
            value,
        }));
        const trendItems =
            analytics.monthTrend.length > 0
                ? analytics.monthTrend.map((t) => ({
                      label: (t.label || '').slice(5) || 'N/A',
                      value: parseFloat(t.value) || 0,
                  }))
                : [{ label: 'N/A', value: 0 }];

        drawMiniBarChart(doc, PAGE_MARGIN, y, chartW, chartH, 'Appointment Status', statusItems, [
            [0, 102, 204],
            [40, 167, 69],
            [255, 193, 7],
            [220, 53, 69],
            [108, 117, 125],
            [255, 193, 7],
        ]);
        drawMiniBarChart(
            doc,
            PAGE_MARGIN + chartW + 4,
            y,
            chartW,
            chartH,
            'Consultation Purpose',
            purposeItems.length ? purposeItems : [{ label: 'N/A', value: 0 }],
            [[0, 51, 102], [37, 99, 235], [22, 163, 74], [249, 115, 22], [124, 58, 237]]
        );
        y += chartH + 4;
        drawMiniBarChart(
            doc,
            PAGE_MARGIN,
            y,
            chartW,
            chartH,
            'Mean Rating Trend',
            trendItems,
            [[37, 99, 235]]
        );
        drawMiniBarChart(
            doc,
            PAGE_MARGIN + chartW + 4,
            y,
            chartW,
            chartH,
            'Method Type Usage',
            methodItems.length ? methodItems : [{ label: 'N/A', value: 0 }],
            [[6, 14, 87]]
        );
        y += chartH + 5;

        drawPageFooter(doc, 1);
        return { endY: y, analytics, deferStatusSummary: true };
    }

    function drawDashboardPage2(doc, logoImg, analytics) {
        drawPageHeader(doc, logoImg);
        let y = 32;
        const contentBottom = getContentBottom(doc);

        y = drawStatusSummaryCards(doc, y, analytics.statusCounts, analytics.followUpCount);

        y = drawInsights(doc, y, analytics.insights);

        if (analytics.counselorPerf.length && y < contentBottom - 35) {
            y = drawCounselorTable(doc, y, analytics.counselorPerf);
        }

        drawPageFooter(doc, 2);
        return y;
    }

    function drawStatusBadgeInCell(doc, cell, statusKey) {
        const badge = STATUS_BADGE[statusKey] || {
            fill: GRAY_TEXT,
            label: statusKey.replace(/_/g, ' '),
        };
        const pad = 1;
        const tw = doc.getTextWidth(badge.label) + pad * 4;
        const th = 3.5;
        const x = cell.x + 1;
        const y = cell.y + (cell.height - th) / 2;
        doc.setFillColor.apply(doc, badge.fill);
        roundedRect(doc, x, y, Math.min(tw, cell.width - 2), th, 1, 'F');
        doc.setFontSize(5);
        doc.setTextColor(255, 255, 255);
        doc.text(badge.label, x + 1.5, y + 2.4);
        doc.setTextColor(0, 0, 0);
    }

    async function ensureAutoTable(doc) {
        if (typeof doc.autoTable === 'function') return;
        await new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src =
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js';
            script.onload = resolve;
            script.onerror = () => reject(new Error('Failed to load autoTable plugin'));
            document.head.appendChild(script);
        });
        if (typeof doc.autoTable !== 'function') {
            throw new Error('AutoTable plugin could not be initialized');
        }
    }

    async function loadLogo(baseUrl) {
        const logoImg = new Image();
        logoImg.src = (baseUrl || '/') + 'Photos/ticket_logo_blue.png';
        await new Promise((resolve, reject) => {
            logoImg.onload = resolve;
            logoImg.onerror = reject;
        });
        return logoImg;
    }

    /**
     * @param {Object} config
     * @param {Array} config.appointments - full appointment list
     * @param {string} [config.activeTabKey]
     * @param {string} [config.reportTitle]
     * @param {Object} [config.filters]
     * @param {string} [config.filterSummary]
     * @param {Object} config.helpers - calculateFeedbackMean, getInterpretation, formatDate
     * @param {Function} [config.applyFilters] - (appointments, filters, title) => { appointments, reportTitle }
     * @param {string} [config.baseUrl]
     * @param {string} [config.role] - admin | counselor
     */
    async function generate(config) {
        const jspdfNs = global.jspdf || (typeof window !== 'undefined' ? window.jspdf : null);
        if (!jspdfNs || !jspdfNs.jsPDF) {
            throw new Error('jsPDF is not loaded');
        }
        const jsPDF = jspdfNs.jsPDF;
        const {
            appointments = [],
            activeTabKey = resolveActiveTabKey(),
            reportTitle: customTitle,
            filters = {},
            filterSummary = '',
            helpers,
            applyFilters,
            baseUrl = global.BASE_URL || '/',
            role = 'counselor',
            ownCounselorOnly = false,
            counselorIds = [],
            counselorName = '',
            scopeSubtitle = '',
        } = config;

        let reportTitle = customTitle || reportTitleForTab(activeTabKey);
        let scopedAppointments = appointments;

        if (ownCounselorOnly) {
            scopedAppointments = filterOwnCounselor(scopedAppointments, counselorIds);
        }

        let filteredBase = scopedAppointments;
        if (typeof applyFilters === 'function') {
            const filtered = applyFilters(scopedAppointments, filters, reportTitle);
            if (filtered && filtered.appointments) {
                filteredBase = filtered.appointments;
                reportTitle = filtered.reportTitle || reportTitle;
            } else if (Array.isArray(filtered)) {
                filteredBase = filtered;
            }
        }

        if (ownCounselorOnly) {
            filteredBase = filterOwnCounselor(filteredBase, counselorIds);
        }

        // "All Appointments" tab: status summary counts every record in scope; table may still be tab-filtered
        const statusSourceAppointments =
            activeTabKey === 'all' ? filteredBase : filterByTab(filteredBase, activeTabKey);

        let appointmentsToExport = filterByTab(filteredBase, activeTabKey);

        appointmentsToExport.sort((a, b) => {
            const dateTimeA = (a.appointed_date || '') + ' ' + (a.appointed_time || '');
            const dateTimeB = (b.appointed_date || '') + ' ' + (b.appointed_time || '');
            return dateTimeA < dateTimeB ? -1 : dateTimeA > dateTimeB ? 1 : 0;
        });

        let analytics = computeAnalytics(appointmentsToExport, helpers, statusSourceAppointments);
        if (ownCounselorOnly && counselorName) {
            analytics = Object.assign({}, analytics, {
                counselorPerf: analytics.counselorPerf.filter(
                    (row) => String(row.name).trim() === String(counselorName).trim()
                ),
            });
        }
        const activeFilterLabel =
            FILTER_TABS.find((t) => t.key === activeTabKey)?.label || 'All Appointments';

        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        await ensureAutoTable(doc);
        const logoImg = await loadLogo(baseUrl);

        const filterSummaryText = filterSummary || 'No additional filters applied';

        const tableData = appointmentsToExport.map((app) => {
            const mean = helpers.calculateFeedbackMean(app);
            const interpretation = helpers.getInterpretation(mean) || 'N/A';
            return [
                helpers.formatDate(app.appointed_date) || '',
                app.appointed_time || '',
                app.student_name || '',
                (app.purpose || 'N/A').slice(0, 40),
                app.method_type || '',
                mean !== null ? mean.toFixed(2) : 'N/A',
                interpretation || 'N/A',
                statusDisplayKey(app),
            ];
        });

        // —— Page 1: Dashboard overview (fits above footer zone) ——
        drawDashboardPage1(doc, logoImg, {
            activeTabKey,
            reportTitle,
            activeFilterLabel,
            filterSummaryText,
            analytics,
            recordCount: appointmentsToExport.length,
            counselorName: ownCounselorOnly ? counselorName : '',
            scopeSubtitle: ownCounselorOnly ? scopeSubtitle || 'List of All Your Appointments' : '',
        });

        // —— Page 2: Insights + counselor performance ——
        doc.addPage();
        drawDashboardPage2(doc, logoImg, analytics);

        // —— Page 3+: Filtered records table ——
        doc.addPage();
        drawPageHeader(doc, logoImg);
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor.apply(doc, NAVY_TEXT);
        doc.text('Filtered Appointment Records', PAGE_MARGIN, 32);

        doc.autoTable({
            startY: 36,
            head: [['Date', 'Time', 'Student Name', 'Purpose', 'Method', 'Mean', 'Interpretation', 'Status']],
            body: tableData.length
                ? tableData.map((row) => row.slice(0, 7).concat(['']))
                : [['—', '—', 'No records for selected filter', '—', '—', '—', '—', '—']],
            margin: { top: 36, bottom: FOOTER_RESERVE, left: PAGE_MARGIN, right: PAGE_MARGIN },
            styles: { fontSize: 7, cellPadding: 2, overflow: 'linebreak' },
            headStyles: { fillColor: NAVY, textColor: WHITE, fontStyle: 'bold', fontSize: 7 },
            alternateRowStyles: { fillColor: [248, 250, 252] },
            columnStyles: {
                0: { cellWidth: 18 },
                1: { cellWidth: 16 },
                2: { cellWidth: 32 },
                3: { cellWidth: 38 },
                4: { cellWidth: 18 },
                5: { cellWidth: 12 },
                6: { cellWidth: 28 },
                7: { cellWidth: 22 },
            },
            didDrawCell: function (data) {
                if (
                    data.section === 'body' &&
                    data.column.index === 7 &&
                    tableData.length &&
                    tableData[data.row.index]
                ) {
                    const statusKey = tableData[data.row.index][7];
                    drawStatusBadgeInCell(doc, data.cell, statusKey);
                }
            },
            didDrawPage: function (data) {
                if (data.pageNumber >= 3) {
                    drawPageHeader(doc, logoImg);
                }
                drawPageFooter(doc, data.pageNumber);
            },
        });

        let finalY = doc.lastAutoTable ? doc.lastAutoTable.finalY + 10 : 40;
        const sigNeedsNewPage = finalY > getSignatureMaxStartY(doc);
        if (sigNeedsNewPage) {
            doc.addPage();
            drawPageHeader(doc, logoImg);
            finalY = 36;
        }
        drawSignatures(doc, finalY);
        if (sigNeedsNewPage) {
            drawPageFooter(doc, doc.internal.getNumberOfPages());
        }

        const today = new Date().toISOString().split('T')[0];
        const rolePrefix = role === 'admin' ? 'admin' : 'counselor';
        const titleSlug = (ownCounselorOnly ? 'my_appointments' : reportTitle)
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_|_$/g, '');
        const filename = `${rolePrefix}_${titleSlug}_${today}.pdf`;
        doc.save(filename);
        return { filename, count: appointmentsToExport.length };
    }

    global.AppointmentPdfDashboard = {
        generate,
        filterByTab,
        filterOwnCounselor,
        resolveActiveTabKey,
        reportTitleForTab,
        FILTER_TABS,
    };
})(typeof window !== 'undefined' ? window : global);
