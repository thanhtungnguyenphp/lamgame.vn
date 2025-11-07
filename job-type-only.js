// Simple job_type mapping only
fetch('https://lamgame.localhost/api/jobs/options/form-data', {
    headers: { 'Authorization': 'Bearer null' }
})
.then(response => response.json())
.then(data => {
    const jobTypeSelect = document.querySelector('select[name="job_type"]') || 
                         document.getElementById('job_type');
    
    if (!jobTypeSelect) {
        console.error('job_type select not found');
        return;
    }
    
    const options = data.data.attributes.job_type.options;
    
    // Clear existing options except placeholder
    const placeholder = jobTypeSelect.querySelector('option[value=""]');
    jobTypeSelect.innerHTML = '';
    if (placeholder) jobTypeSelect.appendChild(placeholder);
    
    // Add job type options
    options.forEach(option => {
        const opt = document.createElement('option');
        opt.value = option.id;
        opt.textContent = option.value;
        jobTypeSelect.appendChild(opt);
    });
    
    console.log(`Added ${options.length} job types:`, options.map(o => o.value));
})
.catch(error => console.error('Error:', error));
