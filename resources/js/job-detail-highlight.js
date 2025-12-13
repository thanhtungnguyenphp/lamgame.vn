// Toggle Quick Info Skills
function toggleQuickSkills(button) {
    const quickInfo = button.closest('.job-quick-info');
    const hiddenSkills = quickInfo.querySelector('.hidden-skills');
    const count = button.getAttribute('data-count');
    
    if (hiddenSkills.style.display === 'none' || !hiddenSkills.style.display) {
        hiddenSkills.style.display = 'flex';
        button.textContent = '↑ Thu gọn';
    } else {
        hiddenSkills.style.display = 'none';
        button.textContent = `+${count} kỹ năng`;
    }
}

// Toggle Quick Info Benefits
function toggleQuickBenefits(button) {
    const quickInfo = button.closest('.job-quick-info');
    const hiddenBenefits = quickInfo.querySelector('.hidden-benefits');
    const count = button.getAttribute('data-count');
    
    if (hiddenBenefits.style.display === 'none' || !hiddenBenefits.style.display) {
        hiddenBenefits.style.display = 'flex';
        button.textContent = '↑ Thu gọn';
    } else {
        hiddenBenefits.style.display = 'none';
        button.textContent = `+${count} phúc lợi`;
    }
}

// Legacy functions for backward compatibility
function toggleSkills(button) {
    const card = button.closest('.skills-highlight-card');
    if (!card) return;
    const hiddenSkills = card.querySelector('.skills-grid-hidden');
    
    if (hiddenSkills.classList.contains('show')) {
        hiddenSkills.classList.remove('show');
        hiddenSkills.style.display = 'none';
        button.textContent = `+${hiddenSkills.querySelectorAll('.skill-pill').length} more`;
    } else {
        hiddenSkills.classList.add('show');
        hiddenSkills.style.display = 'flex';
        button.textContent = 'Show less';
    }
}

function toggleBenefits(button) {
    const card = button.closest('.benefits-highlight-card');
    if (!card) return;
    const hiddenBenefits = card.querySelector('.benefits-grid-hidden');
    
    if (hiddenBenefits.classList.contains('show')) {
        hiddenBenefits.classList.remove('show');
        hiddenBenefits.style.display = 'none';
        button.textContent = 'Xem tất cả phúc lợi →';
    } else {
        hiddenBenefits.classList.add('show');
        hiddenBenefits.style.display = 'grid';
        button.textContent = 'Thu gọn ←';
    }
}
