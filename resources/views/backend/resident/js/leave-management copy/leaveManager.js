/**
 * Main manager that coordinates all leave management components
 */
class LeaveManager {
    constructor() {
        this.components = {};
        this.init();
    }

    init() {
        // Check authentication
        if (!this.checkAuth()) {
            this.redirectToLogin();
            return;
        }

        // Initialize utilities
        this.initUtilities();
        
        // Initialize expandable text functionality
        this.initExpandableText();
        
        console.log('Leave Management System initialized');
    }

    checkAuth() {
        const token = localStorage.getItem(CONFIG.TOKEN_KEY);
        return !!token;
    }

    redirectToLogin() {
        // Store current URL for redirect back after login
        sessionStorage.setItem('redirect_url', window.location.href);
        window.location.href = '/login';
    }

    initUtilities() {
        // Initialize all Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: 'viewport',
                trigger: 'hover focus'
            });
        });

        // Initialize form date constraints globally
        this.initDateConstraints();
    }

    initDateConstraints() {
        const today = new Date().toISOString().split('T')[0];
        
        // Set minimum dates for all date inputs
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.min = today;
        });

        // Link start and end dates
        const startDateInputs = document.querySelectorAll('input[name="start_date"]');
        const endDateInputs = document.querySelectorAll('input[name="end_date"]');
        
        startDateInputs.forEach(startDate => {
            const form = startDate.closest('form');
            const endDate = form?.querySelector('input[name="end_date"]');
            
            if (startDate && endDate) {
                startDate.addEventListener('change', () => {
                    endDate.min = startDate.value;
                    if (endDate.value && new Date(endDate.value) < new Date(startDate.value)) {
                        endDate.value = startDate.value;
                    }
                });
            }
        });
    }

    initExpandableText() {
        // Handle expand/collapse for truncated text
        document.addEventListener('click', (e) => {
            const target = e.target;
            
            if (target.classList.contains('expand-text')) {
                const container = target.closest('.truncate-text');
                if (container) {
                    const fullText = decodeURIComponent(container.dataset.full);
                    container.innerHTML = `
                        ${fullText}
                        <span class="collapse-text text-primary cursor-pointer ms-1" 
                              style="font-size: 0.9em; font-weight: 500;">
                            less
                        </span>
                    `;
                }
            }
            
            if (target.classList.contains('collapse-text')) {
                const container = target.closest('.truncate-text');
                if (container) {
                    const fullText = decodeURIComponent(container.dataset.full);
                    container.innerHTML = `
                        ${this.truncateText(fullText, 60)}
                        <span class="expand-text text-primary cursor-pointer ms-1" 
                              style="font-size: 0.9em; font-weight: 500;">
                            more
                        </span>
                    `;
                }
            }
        });
    }

    truncateText(text, limit) {
        if (!text || text.length <= limit) return text;
        return text.substring(0, limit) + '...';
    }

    // Utility method for showing notifications
    static showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Cleanup method for SPA navigation
    cleanup() {
        // Remove all tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            const tooltip = bootstrap.Tooltip.getInstance(el);
            if (tooltip) tooltip.dispose();
        });
    }
}

// Initialize the main manager
const leaveManager = new LeaveManager();

// Make available globally for debugging
window.leaveManager = leaveManager;