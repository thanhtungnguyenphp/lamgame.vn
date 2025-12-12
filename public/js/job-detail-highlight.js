// Toggle Skills with smooth animation
function toggleSkills(button) {
    const card = button.closest('.skills-highlight-card');
    const hiddenSkills = card.querySelector('.skills-grid-hidden');
    const isExpanded = hiddenSkills.classList.contains('show');
    const skillCount = hiddenSkills.querySelectorAll('.skill-pill').length;
    
    if (isExpanded) {
        // Collapse
        hiddenSkills.classList.remove('show');
        button.innerHTML = `<span style="font-weight: 600;">+${skillCount} kỹ năng</span>`;
        button.style.background = 'rgba(255, 255, 255, 0.2)';
    } else {
        // Expand
        hiddenSkills.style.display = 'flex';
        setTimeout(() => {
            hiddenSkills.classList.add('show');
        }, 10);
        button.innerHTML = '<span style="font-weight: 600;">⬆ Thu gọn</span>';
        button.style.background = 'rgba(255, 255, 255, 0.25)';
    }
}

// Toggle Benefits with smooth animation
function toggleBenefits(button) {
    const card = button.closest('.benefits-highlight-card');
    const hiddenBenefits = card.querySelector('.benefits-grid-hidden');
    const isExpanded = hiddenBenefits.classList.contains('show');
    const benefitCount = hiddenBenefits.querySelectorAll('.benefit-item-compact').length;
    
    if (isExpanded) {
        // Collapse
        hiddenBenefits.classList.remove('show');
        button.innerHTML = '<span style="font-weight: 600;">Xem thêm ' + benefitCount + ' phúc lợi →</span>';
        button.style.background = 'rgba(255, 255, 255, 0.2)';
    } else {
        // Expand
        hiddenBenefits.style.display = 'flex';
        setTimeout(() => {
            hiddenBenefits.classList.add('show');
        }, 10);
        button.innerHTML = '<span style="font-weight: 600;">⬆ Thu gọn</span>';
        button.style.background = 'rgba(255, 255, 255, 0.25)';
    }
}
