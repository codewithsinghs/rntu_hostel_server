/**
 * Handles receipt modal and printing functionality
 */
class ReceiptHandler {
    constructor(service, config) {
        this.service = service;
        this.config = config;
        this.modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        this.receiptContainer = document.getElementById('receiptDetails');
        this.printBtn = document.getElementById('printReceiptBtn');
        this.downloadBtn = document.getElementById('downloadReceiptBtn');
        
        this.init();
    }

    init() {
        // Listen for view receipt events
        document.addEventListener('viewReceipt', (e) => this.showReceipt(e.detail));
        
        // Setup print button
        this.printBtn?.addEventListener('click', () => this.printReceipt());
        
        // Setup download button
        this.downloadBtn?.addEventListener('click', () => this.downloadAsPDF());
    }

    async showReceipt(leaveId) {
        try {
            this.showLoading(true);
            
            const response = await this.service.getLeave(leaveId);
            this.renderReceipt(response.data);
            this.modal.show();
            
        } catch (error) {
            console.error('Error loading receipt:', error);
            this.showError('Unable to load receipt details');
        } finally {
            this.showLoading(false);
        }
    }

    renderReceipt(data) {
        const receiptHtml = this.generateReceiptHtml(data);
        this.receiptContainer.innerHTML = receiptHtml;
        
        // Inject QR code if available
        if (data.qr_code) {
            const qrContainer = this.receiptContainer.querySelector('#qrCodeContainer');
            if (qrContainer) {
                qrContainer.innerHTML = this.generateQRCodeHtml(data);
            }
        }
        
        // Setup expandable text
        this.setupExpandableText();
    }

    generateReceiptHtml(data) {
        return `
            <div class="receipt-header text-center mb-4">
                <div class="institute-logo mb-3">
                    <!-- <img src="/images/logo.png" alt="Institute Logo" class="img-fluid" style="max-height: 80px;"> -->
                </div>
                <h2 class="text-primary fw-bold mb-2">STUDENT GATE PASS</h2>
                <div class="border-top border-bottom py-2">
                    <div class="row">
                        <div class="col">
                            <small class="text-muted">Pass ID: <strong>${data.id || 'N/A'}</strong></small>
                        </div>
                        <div class="col">
                            <small class="text-muted">Generated: ${new Date().toLocaleString()}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="receipt-body">
                <div class="row g-4">
                    <!-- Student Information -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Student Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    ${this.generateInfoRow('Student Name', data.name)}
                                    ${this.generateInfoRow('Registration No.', data.registration_number)}
                                    ${this.generateInfoRow('Room No.', data.room_number)}
                                    ${this.generateInfoRow('Course', data.course)}
                                    ${this.generateInfoRow('Department', data.department)}
                                    ${this.generateInfoRow('Email', data.email)}
                                    ${this.generateInfoRow('Mobile', data.mobile)}
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Leave Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    ${this.generateInfoRow('Leave Type', data.type, true)}
                                    ${this.generateInfoRow('Reason', data.reason)}
                                    ${this.generateInfoRow('Description', data.description, false, true)}
                                    ${this.generateInfoRow('Start Date', this.formatDate(data.start_date))}
                                    ${this.generateInfoRow('End Date', this.formatDate(data.end_date))}
                                    ${this.generateInfoRow('Duration', `${this.calculateDuration(data)} days`)}
                                    ${this.generateInfoRow('Applied On', this.formatDateTime(data.applied_at))}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Section -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Verification</h5>
                            </div>
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <div id="qrCodeContainer" class="mb-3">
                                    ${data.qr_code ? this.generateQRCodeHtml(data) : 'No QR Code Available'}
                                </div>
                                
                                ${data.token ? `
                                    <div class="verification-token text-center mb-3">
                                        <div class="alert alert-light border">
                                            <small class="text-muted d-block mb-1">Verification Token</small>
                                            <code class="fw-bold">${data.token}</code>
                                        </div>
                                    </div>
                                ` : ''}
                                
                                <div class="status-badges mt-3">
                                    ${this.generateStatusBadges(data)}
                                </div>
                                
                                <div class="hostel-timing mt-4">
                                    <h6 class="text-muted mb-2"><i class="fas fa-clock me-1"></i>Hostel Timing</h6>
                                    <div class="small">
                                        <div>IN: ${data.hostel_in_time || 'N/A'}</div>
                                        <div>OUT: ${data.hostel_out_time || 'N/A'}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approval Timeline -->
                ${this.generateApprovalTimeline(data)}
            </div>
        `;
    }

    generateInfoRow(label, value, capitalize = false, expandable = false) {
        if (!value) value = 'N/A';
        
        if (capitalize && value !== 'N/A') {
            value = value.charAt(0).toUpperCase() + value.slice(1);
        }
        
        const valueHtml = expandable ? 
            `<span class="expandable-text" data-full="${encodeURIComponent(value)}">${this.truncateText(value, 100)}</span>` :
            value;
        
        return `
            <div class="col-md-6">
                <div class="info-item">
                    <label class="form-label text-muted mb-1">${label}</label>
                    <div class="fw-medium">${valueHtml}</div>
                </div>
            </div>
        `;
    }

    generateQRCodeHtml(data) {
        return `
            <div class="text-center">
                <img src="data:image/png;base64,${data.qr_code}" 
                     alt="QR Code" 
                     class="img-fluid rounded border" 
                     style="max-width: 200px;">
                <div class="mt-2">
                    <small class="text-muted d-block">Scan to verify authenticity</small>
                </div>
            </div>
        `;
    }

    generateStatusBadges(data) {
        return `
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <span class="badge ${this.getStatusClass(data.hod_status)}">
                    HOD: ${data.hod_status || 'Pending'}
                </span>
                <span class="badge ${this.getStatusClass(data.admin_status)}">
                    Admin: ${data.admin_status || 'Pending'}
                </span>
            </div>
        `;
    }

    generateApprovalTimeline(data) {
        const steps = [];
        
        if (data.applied_at) {
            steps.push({
                title: 'Applied',
                date: data.applied_at,
                status: 'completed'
            });
        }
        
        if (data.hod_action_at) {
            steps.push({
                title: 'HOD Action',
                date: data.hod_action_at,
                status: data.hod_status === 'approved' ? 'approved' : 'rejected',
                remarks: data.hod_remarks
            });
        }
        
        if (data.admin_action_at) {
            steps.push({
                title: 'Admin Action',
                date: data.admin_action_at,
                status: data.admin_status === 'approved' ? 'approved' : 'rejected',
                remarks: data.admin_remarks
            });
        }
        
        if (steps.length === 0) return '';
        
        return `
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Approval Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        ${steps.map((step, index) => this.generateTimelineStep(step, index, steps.length)).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    generateTimelineStep(step, index, total) {
        const statusIcon = step.status === 'approved' ? 'check-circle text-success' :
                         step.status === 'rejected' ? 'times-circle text-danger' :
                         'clock text-warning';
        
        return `
            <div class="timeline-step ${index === total - 1 ? 'last' : ''}">
                <div class="timeline-marker">
                    <i class="fas fa-${statusIcon}"></i>
                </div>
                <div class="timeline-content">
                    <h6 class="mb-1">${step.title}</h6>
                    <div class="text-muted small mb-1">${this.formatDateTime(step.date)}</div>
                    ${step.remarks ? `<div class="small">${step.remarks}</div>` : ''}
                </div>
            </div>
        `;
    }

    getStatusClass(status) {
        const statusMap = {
            'pending': 'bg-warning text-dark',
            'approved': 'bg-success',
            'rejected': 'bg-danger'
        };
        return statusMap[status?.toLowerCase()] || 'bg-secondary';
    }

    formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-IN', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    formatDateTime(dateTimeString) {
        if (!dateTimeString) return 'N/A';
        return new Date(dateTimeString).toLocaleString('en-IN', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    calculateDuration(data) {
        if (!data.start_date || !data.end_date) return 0;
        const start = new Date(data.start_date);
        const end = new Date(data.end_date);
        return Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
    }

    truncateText(text, limit) {
        if (!text || text.length <= limit) return text;
        return text.substring(0, limit) + '...';
    }

    setupExpandableText() {
        this.receiptContainer.querySelectorAll('.expandable-text').forEach(element => {
            element.style.cursor = 'pointer';
            element.addEventListener('click', () => {
                const fullText = decodeURIComponent(element.dataset.full);
                if (element.textContent.includes('...')) {
                    element.textContent = fullText;
                    element.title = 'Click to collapse';
                } else {
                    element.textContent = this.truncateText(fullText, 100);
                    element.title = 'Click to expand';
                }
            });
        });
    }

    printReceipt() {
        const printContent = this.receiptContainer.innerHTML;
        const originalContent = document.body.innerHTML;
        
        document.body.innerHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Gate Pass - ${new Date().toISOString().slice(0,10)}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <style>
                    @media print {
                        body { margin: 0; padding: 20px; }
                        .no-print { display: none !important; }
                        .card { border: none !important; box-shadow: none !important; }
                        .timeline-step::before { border-left: 2px dashed #ddd; }
                    }
                    body { font-family: Arial, sans-serif; }
                    .timeline { position: relative; padding-left: 30px; }
                    .timeline-step { position: relative; margin-bottom: 20px; }
                    .timeline-step::before {
                        content: '';
                        position: absolute;
                        left: -20px;
                        top: 0;
                        bottom: -20px;
                        border-left: 2px solid #dee2e6;
                    }
                    .timeline-step.last::before { display: none; }
                    .timeline-marker {
                        position: absolute;
                        left: -30px;
                        top: 0;
                        background: white;
                        padding: 5px;
                    }
                </style>
            </head>
            <body>
                ${printContent}
                <div class="text-center mt-4 no-print">
                    <hr>
                    <small class="text-muted">
                        Generated by Leave Management System • ${new Date().toLocaleString()}
                    </small>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.history.back();
                        }, 100);
                    };
                </script>
            </body>
            </html>
        `;
        
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();
    }

    async downloadAsPDF() {
        // This would require a PDF generation library or API endpoint
        alert('PDF download functionality requires backend implementation');
        // Implement using jsPDF or call an API endpoint that returns PDF
    }

    showLoading(show) {
        if (this.printBtn) {
            this.printBtn.disabled = show;
        }
        if (this.downloadBtn) {
            this.downloadBtn.disabled = show;
        }
    }

    showError(message) {
        this.receiptContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h4 class="text-danger">Error Loading Receipt</h4>
                <p class="text-muted">${message}</p>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" 
                        onclick="receiptHandler.modal.hide()">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        `;
    }
}

// Initialize
const receiptHandler = new ReceiptHandler(leaveService, CONFIG);