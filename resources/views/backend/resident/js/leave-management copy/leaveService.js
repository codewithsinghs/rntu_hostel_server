/**
 * API Service for Leave Management
 */
class LeaveService {
    constructor(config) {
        this.config = config;
    }

    getHeaders(isFormData = false) {
        const headers = {
            'Authorization': `Bearer ${localStorage.getItem(this.config.TOKEN_KEY)}`,
            'Accept': 'application/json'
        };
        
        if (!isFormData) {
            headers['Content-Type'] = 'application/json';
        }
        
        return headers;
    }

    async handleResponse(response) {
        if (!response.ok) {
            const error = await response.json().catch(() => ({}));
            throw {
                status: response.status,
                message: error.error || `HTTP ${response.status}`,
                data: error
            };
        }
        
        return response.json();
    }

    async request(url, options = {}) {
        try {
            const response = await fetch(url, options);
            return await this.handleResponse(response);
        } catch (error) {
            console.error('API Request Error:', error);
            throw error;
        }
    }

    // CRUD Operations
    async getAllLeaves() {
        return this.request(this.config.API_BASE, {
            method: 'GET',
            headers: this.getHeaders()
        });
    }

    async getLeave(id) {
        return this.request(`${this.config.API_BASE}/${id}`, {
            method: 'GET',
            headers: this.getHeaders()
        });
    }

    async createLeave(formData) {
        return this.request(this.config.API_BASE, {
            method: 'POST',
            headers: this.getHeaders(true),
            body: formData
        });
    }

    async updateLeave(id, formData) {
        return this.request(`${this.config.API_BASE}/${id}`, {
            method: 'PUT',
            headers: this.getHeaders(true),
            body: formData
        });
    }

    async deleteLeave(id) {
        return this.request(`${this.config.API_BASE}/${id}`, {
            method: 'DELETE',
            headers: this.getHeaders()
        });
    }

    // File validation
    validateFile(file) {
        if (file.size > this.config.MAX_FILE_SIZE) {
            throw new Error(`File size exceeds ${this.config.MAX_FILE_SIZE / 1024 / 1024}MB limit`);
        }
        
        const extension = file.name.split('.').pop().toLowerCase();
        if (!this.config.ALLOWED_FILE_TYPES.includes(extension)) {
            throw new Error(`File type not allowed. Allowed types: ${this.config.ALLOWED_FILE_TYPES.join(', ')}`);
        }
        
        return true;
    }
}

// Export singleton instance
const leaveService = new LeaveService(CONFIG);