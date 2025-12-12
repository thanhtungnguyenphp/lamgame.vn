// Toggle Skills
function toggleSkills(button) {
    const card = button.closest('.skills-highlight-card');
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

// Toggle Benefits
function toggleBenefits(button) {
    const card = button.closest('.benefits-highlight-card');
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
