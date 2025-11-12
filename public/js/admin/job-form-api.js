// Job Form API Integration
class JobFormAPI {
    constructor(options = {}) {
        this.apiUrl = options.apiUrl || '/api/jobs/options/form-data';
        this.currentValues = options.currentValues || {};
        this.formData = null;
        console.log('JobFormAPI initialized with URL:', this.apiUrl);
    }

    async loadFormData() {
        console.log('Loading form data...');
        this.showLoading();
        
        try {
            const response = await fetch(this.apiUrl, {
                method: 'GET',
                headers: { 
                    'Authorization': 'Bearer null',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const result = await response.json();
            console.log('API Response:', result);
            
            this.formData = result.data;
            this.populateAllFields();
            this.hideLoading();
            console.log('Form populated successfully');
        } catch (error) {
            console.error('Error loading form data:', error);
            this.showError('Không thể tải dữ liệu form. Vui lòng thử lại.');
        }
    }

    showLoading() {
        const containers = ['required_skills_container', 'job_benefits_container'];
        containers.forEach(id => {
            const container = document.getElementById(id);
            if (container) {
                container.innerHTML = '<div class="loading-options">Đang tải...</div>';
            }
        });
        console.log('Loading state shown');
    }

    hideLoading() {
        console.log('Loading state hidden');
    }

    showError(message) {
        const containers = ['required_skills_container', 'job_benefits_container'];
        containers.forEach(id => {
            const container = document.getElementById(id);
            if (container) {
                container.innerHTML = `<div class="error-loading">${message}</div>`;
            }
        });
        console.log('Error shown:', message);
    }

    populateAllFields() {
        console.log('Populating all fields...');
        const attributes = this.formData.attributes;
        
        // Single select fields
        this.populateSelect('job_type', attributes.job_type.options);
        this.populateSelect('experience_level', attributes.experience_level.options);
        this.populateSelect('job_location', attributes.job_location.options);
        this.populateSelect('application_method', attributes.application_method.options);
        this.populateSelect('education_level', attributes.education_level.options);
        this.populateSelect('english_level', attributes.english_level.options);
        this.populateSelect('company_size', attributes.company_size.options);
        this.populateSelect('salary_range', attributes.salary_range.options);
        
        // Multi-select fields
        this.populateMultiSelect('required_skills', attributes.required_skills.options);
        this.populateMultiSelect('job_benefits', attributes.job_benefits.options);
    }

    populateSelect(fieldName, options) {
        const select = document.getElementById(fieldName);
        if (!select) {
            console.warn(`Select element not found: ${fieldName}`);
            return;
        }
        
        console.log(`Populating select: ${fieldName} with ${options.length} options`);
        
        const placeholder = select.querySelector('option[value=""]');
        select.innerHTML = '';
        if (placeholder) select.appendChild(placeholder);
        
        options.sort((a, b) => a.sort_order - b.sort_order)
               .forEach(option => {
                   const optionElement = document.createElement('option');
                   optionElement.value = option.id;
                   optionElement.textContent = option.value;
                   
                   // Set selected if matches current value
                   if (this.currentValues[fieldName] == option.id) {
                       optionElement.selected = true;
                   }
                   
                   select.appendChild(optionElement);
               });
    }

    populateMultiSelect(fieldName, options) {
        const container = document.getElementById(`${fieldName}_container`);
        if (!container) {
            console.warn(`Container not found: ${fieldName}_container`);
            return;
        }
        
        console.log(`Populating multiselect: ${fieldName} with ${options.length} options`);
        
        container.innerHTML = '';
        const selectedIds = this.currentValues[fieldName] || [];
        
        options.sort((a, b) => a.sort_order - b.sort_order)
               .forEach(option => {
                   const wrapper = document.createElement('div');
                   wrapper.className = 'flex items-center mb-2';
                   
                   const isChecked = selectedIds.includes(option.id);
                   
                   wrapper.innerHTML = `
                       <input type="checkbox" name="${fieldName}[]" value="${option.id}" 
                              id="${fieldName}_${option.id}"
                              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                              ${isChecked ? 'checked' : ''}>
                       <label for="${fieldName}_${option.id}" class="ml-2 text-sm text-gray-900">
                           ${option.value}
                       </label>
                   `;
                   
                   container.appendChild(wrapper);
               });
    }

    init() {
        console.log('Initializing JobFormAPI...');
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                console.log('DOM loaded, starting API call');
                this.loadFormData();
            });
        } else {
            console.log('DOM already loaded, starting API call');
            this.loadFormData();
        }
    }
}

// Export for use
window.JobFormAPI = JobFormAPI;
