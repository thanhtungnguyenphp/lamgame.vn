// Job Form API Integration
class JobFormIntegrator {
    constructor() {
        this.apiUrl = 'https://lamgame.localhost/api/jobs/options/form-data';
        this.formData = null;
    }

    async loadFormData() {
        try {
            const response = await fetch(this.apiUrl, {
                headers: {
                    'Authorization': 'Bearer null'
                }
            });
            const result = await response.json();
            this.formData = result.data;
            this.populateSelects();
        } catch (error) {
            console.error('Error loading form data:', error);
        }
    }

    populateSelects() {
        const attributes = this.formData.attributes;
        
        // 1. Application Method
        this.populateSelect('application_method', attributes.application_method.options);
        
        // 2. Company Size
        this.populateSelect('company_size', attributes.company_size.options);
        
        // 3. Education Level
        this.populateSelect('education_level', attributes.education_level.options);
        
        // 4. English Level
        this.populateSelect('english_level', attributes.english_level.options);
        
        // 5. Experience Level
        this.populateSelect('experience_level', attributes.experience_level.options);
        
        // 6. Job Benefits (multiselect)
        this.populateMultiSelect('job_benefits', attributes.job_benefits.options);
        
        // 7. Job Location
        this.populateSelect('job_location', attributes.job_location.options);
        
        // 8. Job Type
        this.populateSelect('job_type', attributes.job_type.options);
        
        // 9. Required Skills (multiselect)
        this.populateMultiSelect('required_skills', attributes.required_skills.options);
        
        // 10. Salary Range
        this.populateSelect('salary_range', attributes.salary_range.options);
    }

    populateSelect(fieldName, options) {
        const select = document.querySelector(`select[name="${fieldName}"]`) || 
                      document.getElementById(fieldName);
        
        if (!select) return;
        
        // Clear existing options except first (placeholder)
        const firstOption = select.firstElementChild;
        select.innerHTML = '';
        if (firstOption) select.appendChild(firstOption);
        
        // Add options sorted by sort_order
        options.sort((a, b) => a.sort_order - b.sort_order)
               .forEach(option => {
                   const optionElement = document.createElement('option');
                   optionElement.value = option.id;
                   optionElement.textContent = option.value;
                   select.appendChild(optionElement);
               });
    }

    populateMultiSelect(fieldName, options) {
        const container = document.querySelector(`[data-field="${fieldName}"]`) ||
                         document.getElementById(`${fieldName}_container`);
        
        if (!container) return;
        
        // Clear existing checkboxes
        container.innerHTML = '';
        
        // Add checkboxes sorted by sort_order
        options.sort((a, b) => a.sort_order - b.sort_order)
               .forEach(option => {
                   const wrapper = document.createElement('div');
                   wrapper.className = 'form-check';
                   
                   wrapper.innerHTML = `
                       <input class="form-check-input" type="checkbox" 
                              name="${fieldName}[]" value="${option.id}" 
                              id="${fieldName}_${option.id}">
                       <label class="form-check-label" for="${fieldName}_${option.id}">
                           ${option.value}
                       </label>
                   `;
                   
                   container.appendChild(wrapper);
               });
    }

    // Initialize on page load
    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.loadFormData());
        } else {
            this.loadFormData();
        }
    }
}

// Auto-initialize
const jobFormIntegrator = new JobFormIntegrator();
jobFormIntegrator.init();

// Export for manual use
window.JobFormIntegrator = JobFormIntegrator;
