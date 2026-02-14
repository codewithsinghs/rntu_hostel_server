<script>
    /**
     * Handles editing and deleting leave requests
     */
    class EditLeaveHandler {
        constructor(service, config) {
            this.service = service;
            this.config = config;
            this.modal = new bootstrap.Modal(document.getElementById('editLeaveModal'));
            this.editForm = document.getElementById('editLeaveForm');
            this.updateBtn = document.getElementById('updateLeaveBtn');
            this.deleteBtn = document.getElementById('deleteLeaveBtn');

            this.currentLeaveId = null;

            this.init();
        }

        init() {
            // Listen for edit events
            document.addEventListener('editLeave', (e) => this.loadLeaveForEdit(e.detail));

            // Setup form submission
            this.updateBtn?.addEventListener('click', () => this.updateLeave());

            // Setup delete button
            this.deleteBtn?.addEventListener('click', () => this.deleteLeave());

            // Setup form validation
            this.setupFormValidation();
        }

        async loadLeaveForEdit(leaveId) {
            this.currentLeaveId = leaveId;

            try {
                const response = await this.service.getLeave(leaveId);
                this.populateForm(response.data);
                this.modal.show();

            } catch (error) {
                console.error('Error loading leave for edit:', error);
                this.showMessage('Unable to load leave details', 'danger');
            }
        }

        populateForm(data) {
            // Check if leave can be edited
            if (data.hod_status !== 'pending' || data.admin_status !== 'pending') {
                this.disableForm('This request cannot be edited as it has been processed.');
                return;
            }

            // Populate form fields
            this.editForm.querySelector('#edit_leave_id').value = data.id;
            this.editForm.querySelector('#edit_type').value = data.type || '';
            this.editForm.querySelector('#edit_reason').value = data.reason || '';
            this.editForm.querySelector('#edit_description').value = data.description || '';
            this.editForm.querySelector('#edit_start_date').value = data.start_date ?
                data.start_date.split('T')[0] : '';
            this.editForm.querySelector('#edit_end_date').value = data.end_date ?
                data.end_date.split('T')[0] : '';

            // Show current attachment info
            const attachmentInfo = document.getElementById('current-attachment');
            if (data.attachment_url) {
                attachmentInfo.innerHTML = `
                Current: <a href="${data.attachment_url}" target="_blank" class="text-decoration-none">
                    <i class="fas fa-paperclip me-1"></i>View Attachment
                </a>
            `;
            } else {
                attachmentInfo.textContent = 'No attachment uploaded';
            }

            this.enableForm();
        }

        async updateLeave() {
            if (!this.validateForm()) {
                return;
            }

            try {
                this.setLoading(true);

                const formData = new FormData(this.editForm);
                const fileInput = this.editForm.querySelector('#edit_attachment');

                // Validate file if new one is uploaded
                if (fileInput.files.length > 0) {
                    this.service.validateFile(fileInput.files[0]);
                }

                await this.service.updateLeave(this.currentLeaveId, formData);

                this.showMessage('Leave request updated successfully!', 'success');

                // Close modal after delay
                setTimeout(() => {
                    this.modal.hide();
                    document.dispatchEvent(new CustomEvent('leaveEdited'));
                }, 1500);

            } catch (error) {
                console.error('Update error:', error);
                this.showMessage(error.message || 'Error updating request', 'danger');
            } finally {
                this.setLoading(false);
            }
        }

        async deleteLeave() {
            if (!confirm('Are you sure you want to delete this leave request? This action cannot be undone.')) {
                return;
            }

            try {
                this.setLoading(true);

                await this.service.deleteLeave(this.currentLeaveId);

                this.showMessage('Leave request deleted successfully!', 'success');

                // Close modal after delay
                setTimeout(() => {
                    this.modal.hide();
                    document.dispatchEvent(new CustomEvent('leaveDeleted'));
                }, 1500);

            } catch (error) {
                console.error('Delete error:', error);
                this.showMessage(error.message || 'Error deleting request', 'danger');
            } finally {
                this.setLoading(false);
            }
        }

        validateForm() {
            let isValid = true;

            // Clear previous errors
            this.clearFieldErrors();

            // Validate required fields
            this.editForm.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    this.markFieldError(field, 'This field is required');
                    isValid = false;
                }
            });

            // Validate dates
            const startDate = this.editForm.querySelector('#edit_start_date').value;
            const endDate = this.editForm.querySelector('#edit_end_date').value;

            if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                this.markFieldError(
                    this.editForm.querySelector('#edit_end_date'),
                    'End date must be after start date'
                );
                isValid = false;
            }

            return isValid;
        }

        setupFormValidation() {
            this.editForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!this.editForm.checkValidity()) {
                    e.stopPropagation();
                }
                this.editForm.classList.add('was-validated');
            }, false);
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

        clearFieldErrors() {
            this.editForm.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });

            this.editForm.querySelectorAll('.invalid-feedback').forEach(feedback => {
                feedback.textContent = '';
            });
        }

        showMessage(message, type) {
            const messageContainer = document.getElementById('edit-form-message');
            if (!messageContainer) return;

            messageContainer.className = `alert alert-${type} alert-dismissible fade show`;
            messageContainer.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
            messageContainer.classList.remove('d-none');
        }

        setLoading(isLoading) {
            if (this.updateBtn) {
                this.updateBtn.disabled = isLoading;
                this.updateBtn.innerHTML = isLoading ?
                    '<span class="spinner-border spinner-border-sm me-1"></span> Updating...' :
                    '<i class="fas fa-save me-1"></i> Update Request';
            }

            if (this.deleteBtn) {
                this.deleteBtn.disabled = isLoading;
            }
        }

        disableForm(message) {
            this.editForm.querySelectorAll('input, select, textarea, button').forEach(element => {
                element.disabled = true;
            });

            this.showMessage(message, 'warning');
        }

        enableForm() {
            this.editForm.querySelectorAll('input, select, textarea, button').forEach(element => {
                element.disabled = false;
            });

            const messageContainer = document.getElementById('edit-form-message');
            if (messageContainer) {
                messageContainer.classList.add('d-none');
            }
        }
    }

    // Initialize
    const editLeaveHandler = new EditLeaveHandler(leaveService, CONFIG);
</script>
