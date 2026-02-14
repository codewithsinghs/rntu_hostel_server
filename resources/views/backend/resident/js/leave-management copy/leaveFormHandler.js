/**
 * Handles leave form submission and validation
 */
class LeaveFormHandler {
    constructor(service, config) {
        this.service = service;
        this.config = config;
        this.form = document.getElementById('leaveRequestForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.messageContainer = document.getElementById('form-message');
        
        this.init();
    }

    init() {
        if (!this.form) return;

        this.setupEventListeners();
        this.setupDateConstraints();
        this.setupFormValidation();
    }

    setupEventListeners() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time validation
        this.form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
        });
    }

    setupDateConstraints() {
        const today = new Date().toISOString().split('T')[0];
        const startDate = this.form.querySelector('#start_date');
        const endDate = this.form.querySelector('#end_date');

        if (startDate) startDate.min = today;
        if (endDate) endDate.min = today;

        startDate?.addEventListener('change', () => {
            if (endDate) endDate.min = startDate.value;
        });
    }

    setupFormValidation() {
        // Add Bootstrap validation classes
        this.form.addEventListener('submit', (e) => {
            if (!this.form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.form.classList.add('was-validated');
        }, false);
    }

    async handleSubmit(event) {
        event.preventDefault();
        
        if (!this.validateForm()) {
            return;
        }

        try {
            this.setLoading(true);
            
            const formData = new FormData(this.form);
            const fileInput = this.form.querySelector('#attachment');
            
            // Validate file if present
            if (fileInput.files.length > 0) {
                this.service.validateFile(fileInput.files[0]);
            }
            
            const response = await this.service.createLeave(formData);
            
            this.showMessage('Leave request submitted successfully!', 'success');
            this.form.reset();
            this.form.classList.remove('was-validated');
            
            // Trigger refresh
            document.dispatchEvent(new CustomEvent('leavesUpdated'));
            
        } catch (error) {
            console.error('Submission error:', error);
            this.showMessage(error.message || 'Error submitting request. Please try again.', 'danger');
            
            if (error.data?.messages) {
                this.displayFieldErrors(error.data.messages);
            }
        } finally {
            this.setLoading(false);
        }
    }

    validateForm() {
        let isValid = true;
        
        // Clear previous errors
        this.clearFieldErrors();
        
        // Validate required fields
        this.form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                this.markFieldError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Validate dates
        const startDate = this.form.querySelector('#start_date').value;
        const endDate = this.form.querySelector('#end_date').value;
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            this.markFieldError(
                this.form.querySelector('#end_date'),
                'End date must be after start date'
            );
            isValid = false;
        }
        
        // Validate description length
        const description = this.form.querySelector('#description');
        if (description.value.length > 500) {
            this.markFieldError(description, 'Description must be less than 500 characters');
            isValid = false;
        }
        
        return isValid;
    }

    validateField(field) {
        if (!field.hasAttribute('required')) return true;
        
        const isValid = field.value.trim() !== '';
        
        if (!isValid) {
            this.markFieldError(field, 'This field is required');
        } else {
            this.clearFieldError(field);
        }
        
        return isValid;
    }

    markFieldError(field, message) {
        field.classList.add('is-invalid');
        
        let feedback = field.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        
        feedback.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }

    clearFieldErrors() {
        this.form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        this.form.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });
    }

    displayFieldErrors(messages) {
        for (const [fieldName, errors] of Object.entries(messages)) {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                this.markFieldError(field, errors.join(', '));
            }
        }
    }

    showMessage(message, type) {
        if (!this.messageContainer) return;
        
        this.messageContainer.className = `alert alert-${type} alert-dismissible fade show`;
        this.messageContainer.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        this.messageContainer.classList.remove('d-none');
    }

    setLoading(isLoading) {
        if (!this.submitBtn) return;
        
        this.submitBtn.disabled = isLoading;
        this.submitBtn.innerHTML = isLoading ? 
            '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...' : 
            '<i class="fas fa-paper-plane me-1"></i> Submit Request';
    }
}

// Initialize
const leaveFormHandler = new LeaveFormHandler(leaveService, CONFIG);