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

// Legacy functions for backward compatibility (if needed)
function toggleSkills(button) {
    const card = button.closest('.skills-highlight-card');
    if (!card) return;
    const hiddenSkills = card.querySelector('.skills-grid-hidden');
    const isExpanded = hiddenSkills.classList.contains('show');
    const skillCount = hiddenSkills.querySelectorAll('.skill-pill').length;
    
    if (isExpanded) {
        hiddenSkills.classList.remove('show');
        button.innerHTML = `<span style="font-weight: 600;">+${skillCount} kỹ năng</span>`;
        button.style.background = 'rgba(255, 255, 255, 0.2)';
    } else {
        hiddenSkills.style.display = 'flex';
        setTimeout(() => {
            hiddenSkills.classList.add('show');
        }, 10);
        button.innerHTML = '<span style="font-weight: 600;">⬆ Thu gọn</span>';
        button.style.background = 'rgba(255, 255, 255, 0.25)';
    }
}

function toggleBenefits(button) {
    const card = button.closest('.benefits-highlight-card');
    if (!card) return;
    const hiddenBenefits = card.querySelector('.benefits-grid-hidden');
    const isExpanded = hiddenBenefits.classList.contains('show');
    const benefitCount = hiddenBenefits.querySelectorAll('.benefit-item-compact').length;
    
    if (isExpanded) {
        hiddenBenefits.classList.remove('show');
        button.innerHTML = '<span style="font-weight: 600;">Xem thêm ' + benefitCount + ' phúc lợi →</span>';
        button.style.background = 'rgba(255, 255, 255, 0.2)';
    } else {
        hiddenBenefits.style.display = 'flex';
        setTimeout(() => {
            hiddenBenefits.classList.add('show');
        }, 10);
        button.innerHTML = '<span style="font-weight: 600;">⬆ Thu gọn</span>';
        button.style.background = 'rgba(255, 255, 255, 0.25)';
    }
}
