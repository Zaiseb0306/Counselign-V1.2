/**
 * Shared appointment status bucketing for report stat cards and charts.
 * Matches consultation tab logic (initial appointments only; follow-ups excluded).
 */
(function (global) {
    'use strict';

    function normalizeFeedbackStatus(appointment) {
        const normalized = String(
            (appointment && (appointment.feedback_status || appointment.student_feedback_status)) || ''
        )
            .trim()
            .toLowerCase()
            .replace(/[\s-]+/g, '_');

        if (normalized === 'submitted' || normalized === 'feedback_submitted') {
            return 'submitted';
        }
        return 'pending';
    }

    function isFollowUpRecord(appointment) {
        const recordKind = String(appointment?.record_kind || '').trim().toLowerCase();
        const appointmentType = String(appointment?.appointment_type || '').trim().toLowerCase();
        return recordKind === 'follow_up' || appointmentType.includes('follow-up');
    }

    function isFeedbackPendingAppointment(appointment) {
        if (!appointment || isFollowUpRecord(appointment)) return false;
        if (Number(appointment.is_feedback_pending) === 1) return true;

        const status = String(appointment.status || '').trim().toUpperCase();
        const feedbackStatus = normalizeFeedbackStatus(appointment);

        return (
            (status === 'FEEDBACK_PENDING' || status === 'COMPLETED') &&
            feedbackStatus !== 'submitted'
        );
    }

    function isCompletedWithSubmittedFeedback(appointment) {
        if (!appointment || isFollowUpRecord(appointment)) return false;

        const status = String(appointment.status || '').trim().toUpperCase();
        return status === 'COMPLETED' && normalizeFeedbackStatus(appointment) === 'submitted';
    }

    function classifyBucket(appointment) {
        if (!appointment || isFollowUpRecord(appointment)) return null;
        if (isCompletedWithSubmittedFeedback(appointment)) return 'completed';
        if (isFeedbackPendingAppointment(appointment)) return 'feedback_pending';

        const status = String(appointment.status || '').trim().toLowerCase();
        if (status === 'approved') return 'approved';
        if (status === 'rescheduled') return 'rescheduled';
        if (status === 'pending') return 'pending';
        return null;
    }

    function countStatusTotals(appointments) {
        const totals = {
            completed: 0,
            approved: 0,
            rescheduled: 0,
            pending: 0,
            feedback_pending: 0,
        };

        (appointments || []).forEach((appointment) => {
            const bucket = classifyBucket(appointment);
            if (bucket && Object.prototype.hasOwnProperty.call(totals, bucket)) {
                totals[bucket] += 1;
            }
        });

        return totals;
    }

    global.ReportStatusUtils = {
        normalizeFeedbackStatus,
        isFollowUpRecord,
        isFeedbackPendingAppointment,
        isCompletedWithSubmittedFeedback,
        classifyBucket,
        countStatusTotals,
    };
})(typeof window !== 'undefined' ? window : global);
