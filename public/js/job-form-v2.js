/**
 * Job Form v2 - populate selects from server-rendered window.jobFormOptions
 */
document.addEventListener('DOMContentLoaded', function () {
    const opts = window.jobFormOptions || {};
    const existing = window.existingJobData;

    const fieldMap = {
        'job_type': opts.job_types,
        'experience_level': opts.experience_levels,
        'job_location': opts.locations,
        'location': opts.locations,
        'salary_range': opts.salary_ranges,
        'application_method': opts.application_methods,
        'education_level': opts.education_levels,
        'english_level': opts.english_levels,
        'company_size': opts.company_sizes,
    };

    // Populate single selects
    Object.entries(fieldMap).forEach(([id, options]) => {
        const el = document.getElementById(id);
        if (!el || !options) return;
        const selected = existing ? existing[id] : null;
        options.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt;
            o.textContent = opt;
            if (selected === opt) o.selected = true;
            el.appendChild(o);
        });
    });

    // Populate multi-selects (skills, benefits)
    const multiMap = {
        'required_skills': { options: opts.skills || [], existing: existing?.skills_list || [] },
        'job_benefits': { options: opts.benefits || [], existing: existing?.benefits_list || [] },
    };

    Object.entries(multiMap).forEach(([id, { options, existing: sel }]) => {
        const el = document.getElementById(id);
        if (!el) return;
        options.forEach(opt => {
            const name = typeof opt === 'string' ? opt : opt.skill_name || opt.benefit_name || opt;
            const o = document.createElement('option');
            o.value = name;
            o.textContent = name;
            if (sel.includes(name)) o.selected = true;
            el.appendChild(o);
        });
    });

    // Field name mapping: job_location → location for form submission
    const locationSelect = document.getElementById('job_location');
    if (locationSelect && !document.getElementById('location')) {
        locationSelect.name = 'location';
    }

    // Skills/benefits: rename for form submission
    const skillsEl = document.getElementById('required_skills');
    if (skillsEl) skillsEl.name = 'skills[]';
    const benefitsEl = document.getElementById('job_benefits');
    if (benefitsEl) benefitsEl.name = 'benefits[]';
});
