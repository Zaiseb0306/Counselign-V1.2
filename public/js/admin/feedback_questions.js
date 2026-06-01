document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = (window.BASE_URL || '/').replace(/\/?$/, '/');
    const authUrl = window.AUTH_URL || (baseUrl + 'auth');

    const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));

    const loadingIndicator = document.getElementById('loadingIndicator');
    const noQuestionsMessage = document.getElementById('noQuestionsMessage');
    const questionsList = document.getElementById('questionsList');
    const addQuestionBtn = document.getElementById('addQuestionBtn');
    const addFirstQuestionBtn = document.getElementById('addFirstQuestionBtn');
    const seedDefaultsBtn = document.getElementById('seedDefaultsBtn');
    const saveQuestionBtn = document.getElementById('saveQuestionBtn');
    const confirmActionBtn = document.getElementById('confirmActionBtn');

    let questions = Array.isArray(window.INITIAL_QUESTIONS) ? window.INITIAL_QUESTIONS : [];
    let currentQuestionId = null;
    let actionToConfirm = null;

    function redirectToAuth() {
        window.location.href = authUrl;
    }

    function loadQuestions(skipInitialRender) {
        if (!skipInitialRender && questions.length > 0) {
            displayQuestions(questions);
            if (loadingIndicator) {
                loadingIndicator.classList.add('d-none');
            }
        } else {
            if (loadingIndicator) {
                loadingIndicator.classList.remove('d-none');
            }
            if (questionsList) {
                questionsList.classList.add('d-none');
            }
            if (noQuestionsMessage) {
                noQuestionsMessage.classList.add('d-none');
            }
        }

        const timestamp = new Date().getTime();

        fetch(baseUrl + `admin/feedback-questions/getAll?_=${timestamp}`, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache'
            }
        })
        .then(response => {
            if (response.status === 401) {
                redirectToAuth();
                throw new Error('Session expired - Please log in again');
            }
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'error') {
                throw new Error(data.message || 'Failed to load questions');
            }

            questions = Array.isArray(data.questions) ? data.questions : [];
            displayQuestions(questions);
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessage = error.message || 'Failed to load questions';

            if (!questions.length) {
                showToast('Error', errorMessage);
                if (noQuestionsMessage) {
                    const p = noQuestionsMessage.querySelector('p');
                    if (p) {
                        p.textContent = errorMessage;
                    }
                    noQuestionsMessage.classList.remove('d-none');
                }
                if (questionsList) {
                    questionsList.classList.add('d-none');
                }
            }
        })
        .finally(() => {
            if (loadingIndicator) {
                loadingIndicator.classList.add('d-none');
            }
        });
    }

    function displayQuestions(questionsData) {
        if (!questionsList) {
            return;
        }

        if (questionsData.length === 0) {
            showNoQuestionsMessage();
            return;
        }

        questionsList.innerHTML = '';

        questionsData.forEach(question => {
            questionsList.appendChild(createQuestionCard(question));
        });

        initSortable();
        questionsList.classList.remove('d-none');
        if (noQuestionsMessage) {
            noQuestionsMessage.classList.add('d-none');
        }
    }

    function createQuestionCard(question) {
        const card = document.createElement('div');
        card.className = 'question-card';
        card.dataset.id = question.id;
        card.dataset.sortOrder = question.sort_order;

        const activeStatus = question.is_active == 1 ? 'Active' : 'Inactive';
        const activeClass = question.is_active == 1 ? 'bg-success' : 'bg-secondary';

        card.innerHTML = `
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-grip-vertical drag-handle me-2"></i>
                        <span class="badge ${activeClass}">${activeStatus}</span>
                        <span class="ms-2 text-muted">Q${question.question_number}</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${question.id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${question.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="question-text mb-0">${escapeHtml(question.question_text)}</p>
            </div>
        `;

        card.querySelector('.edit-btn').addEventListener('click', () => openEditQuestionModal(question));
        card.querySelector('.delete-btn').addEventListener('click', () => confirmDeleteQuestion(question.id));

        return card;
    }

    function showNoQuestionsMessage() {
        if (questionsList) {
            questionsList.classList.add('d-none');
        }
        if (noQuestionsMessage) {
            noQuestionsMessage.classList.remove('d-none');
        }
    }

    function initSortable() {
        if (typeof Sortable !== 'undefined' && questionsList) {
            new Sortable(questionsList, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function() {
                    const cards = questionsList.querySelectorAll('.question-card');
                    const newOrder = [];
                    cards.forEach((card, index) => {
                        newOrder.push({
                            id: parseInt(card.dataset.id, 10),
                            sort_order: index + 1
                        });
                    });
                    saveNewOrder(newOrder);
                }
            });
        }
    }

    function saveNewOrder(newOrder) {
        fetch(baseUrl + 'admin/feedback-questions/reorder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include',
            body: JSON.stringify(newOrder)
        })
        .then(response => {
            if (response.status === 401) {
                redirectToAuth();
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showToast('Success', 'Questions reordered successfully');
                loadQuestions(true);
            } else {
                showToast('Error', data.message || 'Failed to reorder questions');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Failed to reorder questions');
        });
    }

    function openAddQuestionModal() {
        currentQuestionId = null;
        document.getElementById('modalTitleText').textContent = 'Add Question';
        document.getElementById('questionId').value = '';
        document.getElementById('questionText').value = '';
        document.getElementById('fieldName').value = '';
        document.getElementById('sortOrder').value = '';
        document.getElementById('isActive').checked = true;
        questionModal.show();
    }

    function openEditQuestionModal(question) {
        currentQuestionId = question.id;
        document.getElementById('modalTitleText').textContent = 'Edit Question';
        document.getElementById('questionId').value = question.id;
        document.getElementById('questionText').value = question.question_text;
        document.getElementById('fieldName').value = question.field_name;
        document.getElementById('sortOrder').value = question.sort_order;
        document.getElementById('isActive').checked = question.is_active == 1;
        questionModal.show();
    }

    function saveQuestion() {
        const questionId = document.getElementById('questionId').value;
        const questionText = document.getElementById('questionText').value.trim();
        const sortOrder = document.getElementById('sortOrder').value;
        const isActive = document.getElementById('isActive').checked ? 1 : 0;

        if (!questionText) {
            showToast('Error', 'Question text is required');
            return;
        }

        saveQuestionBtn.disabled = true;
        saveQuestionBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

        const formData = new FormData();
        formData.append('question_text', questionText);
        formData.append('sort_order', sortOrder);
        formData.append('is_active', isActive);

        const url = questionId
            ? baseUrl + `admin/feedback-questions/update/${questionId}`
            : baseUrl + 'admin/feedback-questions/create';

        fetch(url, {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 401) {
                redirectToAuth();
                throw new Error('Your session has expired. Please log in again.');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showToast('Success', questionId ? 'Question updated successfully' : 'Question added successfully');
                questionModal.hide();
                loadQuestions(true);
            } else {
                showToast('Error', data.message || 'Failed to save question');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', error.message || 'An error occurred. Please try again.');
        })
        .finally(() => {
            saveQuestionBtn.disabled = false;
            saveQuestionBtn.innerHTML = '<i class="fas fa-save me-1"></i>Save';
        });
    }

    function confirmDeleteQuestion(questionId) {
        currentQuestionId = questionId;
        actionToConfirm = 'delete';

        document.getElementById('confirmationModalTitle').textContent = 'Delete Question';
        document.getElementById('confirmationModalBody').innerHTML = `
            <p>Are you sure you want to delete this question?</p>
            <p class="text-muted">This action cannot be undone.</p>
        `;

        confirmationModal.show();
    }

    function executeConfirmedAction() {
        if (actionToConfirm === 'delete' && currentQuestionId) {
            deleteQuestion(currentQuestionId);
        }

        confirmationModal.hide();
        actionToConfirm = null;
        currentQuestionId = null;
    }

    function deleteQuestion(questionId) {
        fetch(baseUrl + `admin/feedback-questions/delete/${questionId}`, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 401) {
                redirectToAuth();
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showToast('Success', 'Question deleted successfully');
                loadQuestions(true);
            } else {
                showToast('Error', data.message || 'Failed to delete question');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', error.message || 'An error occurred. Please try again.');
        });
    }

    function seedDefaultQuestions() {
        if (seedDefaultsBtn) {
            seedDefaultsBtn.disabled = true;
        }

        fetch(baseUrl + 'admin/feedback-questions/seed-defaults', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showToast('Success', data.message || 'Default questions loaded');
                questions = data.questions || [];
                displayQuestions(questions);
                loadQuestions(true);
            } else {
                showToast('Error', data.message || 'Failed to load defaults');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Failed to load default questions');
        })
        .finally(() => {
            if (seedDefaultsBtn) {
                seedDefaultsBtn.disabled = false;
            }
        });
    }

    function showToast(title, message) {
        const toast = document.getElementById('statusToast');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');
        const toastTime = document.getElementById('toastTime');

        if (toastTitle) toastTitle.textContent = title;
        if (toastMessage) toastMessage.textContent = message;
        if (toastTime) toastTime.textContent = 'Just now';

        const toastInstance = new bootstrap.Toast(toast);
        toastInstance.show();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', openAddQuestionModal);
    }
    if (addFirstQuestionBtn) {
        addFirstQuestionBtn.addEventListener('click', openAddQuestionModal);
    }
    if (seedDefaultsBtn) {
        seedDefaultsBtn.addEventListener('click', seedDefaultQuestions);
    }
    if (saveQuestionBtn) {
        saveQuestionBtn.addEventListener('click', saveQuestion);
    }
    if (confirmActionBtn) {
        confirmActionBtn.addEventListener('click', executeConfirmedAction);
    }

    loadQuestions();
});
