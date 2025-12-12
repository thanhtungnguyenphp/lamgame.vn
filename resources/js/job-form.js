import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';
import '../css/tom-select-custom.css';
import { initMultiSelect, updateCounter, populateOptions } from './components/multiselect.js';

// Make functions available globally for inline scripts or export for module usage
window.JobForm = {
    initMultiSelect,
    updateCounter,
    populateOptions,
    TomSelect
};

// Auto-initialize if on job create/edit page
document.addEventListener('DOMContentLoaded', function() {
    // Check if elements exist
    if (!document.getElementById('required_skills') || !document.getElementById('job_benefits')) {
        console.log('Tom Select: Skills/Benefits fields not found on this page');
        return;
    }
    
    console.log('Tom Select: Initializing...');
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const isEditMode = window.existingJobData !== undefined;
        
        // Initialize Tom Select instances (will be populated after API call)
        let skillsSelect, benefitsSelect;
        
        // Fetch form data from API
        fetch('/api/jobs/options/form-data', {
            method: 'GET',
            headers: { 
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const { attributes, popular_skills, common_benefits } = data.data;
            
            // Populate standard selects
            populateSelect('job_type', attributes.job_type?.options || [], isEditMode ? window.existingJobData.attributes[40] : null);
            populateSelect('experience_level', attributes.experience_level?.options || [], isEditMode ? window.existingJobData.attributes[41] : null);
            populateSelect('job_location', attributes.job_location?.options || [], isEditMode ? window.existingJobData.attributes[43] : null);
            populateSelect('salary_range', attributes.salary_range?.options || [], isEditMode ? window.existingJobData.attributes[42] : null);
            populateSelect('application_method', attributes.application_method?.options || [], isEditMode ? window.existingJobData.attributes[46] : null);
            populateSelect('education_level', attributes.education_level?.options || [], isEditMode ? window.existingJobData.attributes[44] : null);
            populateSelect('english_level', attributes.english_level?.options || [], isEditMode ? window.existingJobData.attributes[47] : null);
            populateSelect('company_size', attributes.company_size?.options || [], isEditMode ? window.existingJobData.attributes[49] : null);
            
            // Initialize Tom Select with data
            skillsSelect = initMultiSelect('#required_skills', {
                placeholder: '🔍 Tìm và chọn kỹ năng...',
                onChangeCallback: () => updateCounter(skillsSelect, 'skills_count')
            });
            
            benefitsSelect = initMultiSelect('#job_benefits', {
                placeholder: '🔍 Tìm và chọn phúc lợi...',
                onChangeCallback: () => updateCounter(benefitsSelect, 'benefits_count')
            });
            
            // Populate Tom Select multiselects
            populateOptions(skillsSelect, popular_skills || []);
            populateOptions(benefitsSelect, common_benefits || []);
            
            // Pre-select existing values in edit mode
            if (isEditMode) {
                console.log('Edit mode detected:', window.existingJobData);
                
                if (window.existingJobData.skills && window.existingJobData.skills.length > 0) {
                    console.log('Setting skills:', window.existingJobData.skills);
                    skillsSelect.setValue(window.existingJobData.skills);
                    updateCounter(skillsSelect, 'skills_count');
                }
                if (window.existingJobData.benefits && window.existingJobData.benefits.length > 0) {
                    console.log('Setting benefits:', window.existingJobData.benefits);
                    benefitsSelect.setValue(window.existingJobData.benefits);
                    updateCounter(benefitsSelect, 'benefits_count');
                }
            }
            
            // Enable form after loading
            disableForm(false);
        })
        .catch(error => {
            console.error('Error loading form data:', error);
            showError('Không thể tải dữ liệu form. Vui lòng tải lại trang.');
            disableForm(false);
        });
        
        function populateSelect(selectId, options, selectedValue = null) {
            const select = document.getElementById(selectId);
            if (select) {
                const placeholder = select.querySelector('option[value=""]');
                select.innerHTML = '';
                if (placeholder) select.appendChild(placeholder);
                
                options.forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.id;
                    opt.textContent = option.value;
                    if (selectedValue && option.id == selectedValue) {
                        opt.selected = true;
                    }
                    select.appendChild(opt);
                });
            }
        }
        
        function disableForm(disabled) {
            submitBtn.disabled = disabled;
            
            if (disabled) {
                submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Đang tải...';
            } else {
                submitBtn.textContent = 'Đăng Job';
            }
        }
        
        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 5000);
        }
});
