<script>
    /**
     * Handles leave requests table display and management
     */
    class LeaveTableHandler {
        constructor(service, config) {
            this.service = service;
            this.config = config;
            this.tableBody = document.querySelector('#leaveRequestList tbody');
            this.noRequestsMsg = document.getElementById('no-requests');
            this.loadingRow = document.getElementById('loading-row');
            this.refreshBtn = document.getElementById('refreshBtn');
            this.filterOptions = document.querySelectorAll('.filter-option');
            this.currentFilter = 'all';

            this.init();
        }

        init() {
            this.setupEventListeners();
            this.loadLeaves();

            // Listen for updates
            document.addEventListener('leavesUpdated', () => this.loadLeaves());
            document.addEventListener('leaveEdited', () => this.loadLeaves());
            document.addEventListener('leaveDeleted', () => this.loadLeaves());
        }

        setupEventListeners() {
            // Refresh button
            this.refreshBtn?.addEventListener('click', () => this.loadLeaves());

            // Filter options
            this.filterOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.currentFilter = e.target.dataset.status;
                    this.updateActiveFilter();
                    this.loadLeaves();
                });
            });

            // Event delegation for action buttons
            this.tableBody?.addEventListener('click', (e) => {
                const target = e.target;

                if (target.classList.contains('view-receipt-btn')) {
                    const leaveId = target.dataset.id;
                    document.dispatchEvent(new CustomEvent('viewReceipt', {
                        detail: leaveId
                    }));
                }

                if (target.classList.contains('edit-leave-btn')) {
                    const leaveId = target.dataset.id;
                    document.dispatchEvent(new CustomEvent('editLeave', {
                        detail: leaveId
                    }));
                }
            });
        }

        async loadLeaves() {
            try {
                this.showLoading(true);

                const response = await this.service.getAllLeaves();
                this.updateSummary(response.data?.summary || {});
                this.renderTable(response.data?.requests || []);

            } catch (error) {
                console.error('Error loading leaves:', error);
                this.showError();
            } finally {
                this.showLoading(false);
            }
        }

        updateSummary(summary) {
            const summaryMap = {
                'total-requests': summary.total_leaves || 0,
                'total-leaves-approved': summary.approved || 0,
                'total-leaves-pending': summary.pending || 0,
                'total-leaves-rejected': summary.rejected || 0
            };

            Object.entries(summaryMap).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value;
                    this.animateValueChange(element, value);
                }
            });
        }

        animateValueChange(element, newValue) {
            const oldValue = parseInt(element.textContent) || 0;
            const diff = newValue - oldValue;

            if (diff !== 0) {
                element.classList.add('text-success');
                setTimeout(() => {
                    element.classList.remove('text-success');
                }, 1000);
            }
        }

        renderTable(requests) {
            if (!this.tableBody) return;

            // Filter requests if needed
            const filteredRequests = this.filterRequests(requests);

            if (filteredRequests.length === 0) {
                this.showNoRequests();
                return;
            }

            this.tableBody.innerHTML = filteredRequests.map((request, index) =>
                this.createTableRow(request, index)
            ).join('');

            this.showNoRequests(false);

            // Initialize tooltips
            this.initializeTooltips();
        }

        filterRequests(requests) {
            if (this.currentFilter === 'all') return requests;

            return requests.filter(request =>
                request.hod_status?.toLowerCase() === this.currentFilter ||
                request.admin_status?.toLowerCase() === this.currentFilter
            );
        }

        updateActiveFilter() {
            this.filterOptions.forEach(option => {
                option.classList.toggle('active', option.dataset.status === this.currentFilter);
            });
        }

        createTableRow(request, index) {
            const canEdit = request.hod_status === 'pending' && request.admin_status === 'pending';

            return `
            <tr class="${this.getRowClass(request)}" data-id="${request.id}">
                <td class="text-center fw-bold">${index + 1}</td>
                <td>
                    <span class="badge ${this.getTypeBadgeClass(request.type)}">
                        ${this.formatType(request.type)}
                    </span>
                </td>
                <td>
                    <div class="fw-medium">${request.reason || 'N/A'}</div>
                    ${request.description ? 
                        `<small class="text-muted truncate-text" data-full="${encodeURIComponent(request.description)}">
                            ${this.truncateText(request.description, 60)}
                        </small>` : ''
                    }
                </td>
                <td>
                    <div class="text-nowrap">
                        <i class="fas fa-calendar-day text-primary me-1"></i>
                        ${this.formatDate(request.start_date)}
                    </div>
                    <div class="text-nowrap">
                        <i class="fas fa-calendar-day text-primary me-1"></i>
                        ${this.formatDate(request.end_date)}
                    </div>
                    <small class="text-muted">${this.calculateDuration(request)} days</small>
                </td>
                <td>
                    <div>${this.formatDateTime(request.applied_at)}</div>
                    ${request.attachment ? 
                        `<small><i class="fas fa-paperclip text-muted"></i> Attachment</small>` : ''
                    }
                </td>
                <td>${this.renderStatusCell(request.hod_status, request.hod_remarks, request.hod_action_at)}</td>
                <td>${this.renderStatusCell(request.admin_status, request.admin_remarks, request.admin_action_at)}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary view-receipt-btn" 
                                data-id="${request.id}" data-bs-toggle="tooltip" title="View Gate Pass">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${canEdit ? `
                            <button type="button" class="btn btn-outline-warning edit-leave-btn" 
                                    data-id="${request.id}" data-bs-toggle="tooltip" title="Edit Request">
                                <i class="fas fa-edit"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
        }

        renderStatusCell(status, remarks, actionAt) {
            if (!status) return '<span class="badge bg-secondary">N/A</span>';

            const badge = this.getStatusBadge(status);

            if (status !== 'pending' && remarks) {
                return `
                <div class="d-flex flex-column">
                    ${badge}
                    <small class="text-muted mt-1" data-bs-toggle="tooltip" 
                           title="${remarks}">
                        <i class="fas fa-comment me-1"></i>${this.truncateText(remarks, 30)}
                    </small>
                    ${actionAt ? 
                        `<small class="text-muted"><i class="fas fa-clock me-1"></i>${this.formatDateTime(actionAt)}</small>` : ''
                    }
                </div>
            `;
            }

            return badge;
        }

        getRowClass(request) {
            if (request.admin_status === 'approved') return 'table-success';
            if (request.admin_status === 'rejected') return 'table-danger';
            if (request.hod_status === 'approved' && request.admin_status === 'pending') return 'table-warning';
            return '';
        }

        getTypeBadgeClass(type) {
            const typeClasses = {
                'casual': 'bg-info',
                'medical': 'bg-danger',
                'emergency': 'bg-warning',
                'vacation': 'bg-success',
                'other': 'bg-secondary'
            };
            return typeClasses[type] || 'bg-secondary';
        }

        formatType(type) {
            return type ? type.charAt(0).toUpperCase() + type.slice(1) : 'N/A';
        }

        getStatusBadge(status) {
            const statusMap = {
                'pending': {
                    class: 'bg-warning text-dark',
                    label: 'Pending'
                },
                'approved': {
                    class: 'bg-success',
                    label: 'Approved'
                },
                'rejected': {
                    class: 'bg-danger',
                    label: 'Rejected'
                },
                'cancelled': {
                    class: 'bg-secondary',
                    label: 'Cancelled'
                }
            };

            const statusInfo = statusMap[status.toLowerCase()] || {
                class: 'bg-info',
                label: status
            };
            return `<span class="badge ${statusInfo.class}">${statusInfo.label}</span>`;
        }

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            return new Date(dateString).toLocaleDateString();
        }

        formatDateTime(dateTimeString) {
            if (!dateTimeString) return 'N/A';
            return new Date(dateTimeString).toLocaleString();
        }

        calculateDuration(request) {
            if (!request.start_date || !request.end_date) return 0;

            const start = new Date(request.start_date);
            const end = new Date(request.end_date);
            const diffTime = Math.abs(end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        }

        truncateText(text, limit) {
            if (!text || text.length <= limit) return text;
            return text.substring(0, limit) + '...';
        }

        initializeTooltips() {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(el => new bootstrap.Tooltip(el));
        }

        showLoading(show) {
            if (this.loadingRow) {
                this.loadingRow.classList.toggle('d-none', !show);
            }

            if (this.refreshBtn) {
                this.refreshBtn.disabled = show;
                this.refreshBtn.innerHTML = show ?
                    '<span class="spinner-border spinner-border-sm"></span>' :
                    '<i class="fas fa-sync-alt"></i>';
            }
        }

        showNoRequests(show = true) {
            if (this.noRequestsMsg) {
                this.noRequestsMsg.classList.toggle('d-none', !show);
            }

            if (this.tableBody && show) {
                this.tableBody.innerHTML = '';
            }
        }

        showError() {
            if (!this.tableBody) return;

            this.tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>Failed to load leave requests</h5>
                    <p class="text-muted">Please try again later</p>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" 
                            onclick="leaveTableHandler.loadLeaves()">
                        <i class="fas fa-redo me-1"></i> Retry
                    </button>
                </td>
            </tr>
        `;
        }
    }

    // Initialize
    const leaveTableHandler = new LeaveTableHandler(leaveService, CONFIG);
</script>
