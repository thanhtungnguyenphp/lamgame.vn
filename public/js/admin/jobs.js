/**
 * Admin Jobs Manager
 * Handles all interactions for the admin jobs page
 */

class JobsManager {
    constructor() {
        this.selectedJobs = new Set();
        this.currentFilter = 'all';
        this.searchTimeout = null;
        this.sortColumn = null;
        this.sortDirection = 'asc';
        this.modal = null;
        this.modalResolve = null;
        this.stats = {
            total: 0,
            published: 0,
            unpublished: 0,
            thisWeek: 0
        };
        this.init();
    }

    init() {
        this.initModal();
        this.bindEvents();
        this.updateResultsCount();
        this.loadInitialStats();
        this.addEnterAnimations();
    }

    bindEvents() {
        // Search functionality
        const searchInput = document.getElementById('searchJobs');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.handleSearch(e);
                }, 300);
                
                // Show/hide clear button
                const clearBtn = document.getElementById('clearSearch');
                if (clearBtn) {
                    clearBtn.style.display = e.target.value ? 'flex' : 'none';
                }
            });
        }

        // Clear search button
        const clearSearch = document.getElementById('clearSearch');
        if (clearSearch) {
            clearSearch.addEventListener('click', () => {
                searchInput.value = '';
                clearSearch.style.display = 'none';
                this.handleSearch({ target: searchInput });
                searchInput.focus();
            });
        }

        // Refresh button
        const refreshBtn = document.getElementById('refreshData');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.refreshData());
        }

        // Select all checkbox
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', (e) => this.handleSelectAll(e));
        }

        // Individual checkboxes
        document.querySelectorAll('.job-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => this.handleCheckboxChange(e));
        });

        // Filter buttons
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleFilter(e));
        });

        // Delete buttons
        document.querySelectorAll('[data-delete-job]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleDelete(e));
        });

        // Bulk action buttons
        document.querySelectorAll('[data-bulk-action]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleBulkAction(e));
        });

        // Sortable columns
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', (e) => this.handleSort(e));
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
    }

    handleSearch(event) {
        const searchTerm = event.target.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.job-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const jobName = row.querySelector('.job-info__title')?.textContent.toLowerCase() || '';
            const company = row.querySelector('.badge--light')?.textContent.toLowerCase() || '';
            const jobId = row.querySelector('.job-info__meta')?.textContent.toLowerCase() || '';

            const isVisible = jobName.includes(searchTerm) || 
                            company.includes(searchTerm) || 
                            jobId.includes(searchTerm);

            if (isVisible) {
                row.style.display = '';
                row.classList.add('fade-in');
                visibleCount++;
                
                // Highlight search terms
                if (searchTerm) {
                    this.highlightText(row, searchTerm);
                } else {
                    this.removeHighlight(row);
                }
            } else {
                row.style.display = 'none';
                row.classList.remove('fade-in');
            }
        });

        this.updateResultsCount(visibleCount);
        this.announce(`Tìm thấy ${visibleCount} kết quả`);
    }

    highlightText(row, searchTerm) {
        const elements = row.querySelectorAll('.job-info__title, .badge--light, .job-info__meta');
        elements.forEach(el => {
            const text = el.textContent;
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            if (regex.test(text)) {
                el.innerHTML = text.replace(regex, '<mark class="highlight">$1</mark>');
            }
        });
    }

    removeHighlight(row) {
        const marks = row.querySelectorAll('mark.highlight');
        marks.forEach(mark => {
            mark.replaceWith(mark.textContent);
        });
    }

    handleSelectAll(event) {
        const isChecked = event.target.checked;
        const visibleCheckboxes = Array.from(document.querySelectorAll('.job-checkbox'))
            .filter(cb => cb.closest('.job-row').style.display !== 'none');

        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
            if (isChecked) {
                this.selectedJobs.add(checkbox.value);
            } else {
                this.selectedJobs.delete(checkbox.value);
            }
        });

        this.updateBulkActions();
        this.announce(`${isChecked ? 'Đã chọn' : 'Đã bỏ chọn'} tất cả`);
    }

    handleCheckboxChange(event) {
        const jobId = event.target.value;
        
        if (event.target.checked) {
            this.selectedJobs.add(jobId);
        } else {
            this.selectedJobs.delete(jobId);
            document.getElementById('selectAll').checked = false;
        }

        this.updateBulkActions();
    }

    handleFilter(event) {
        const filter = event.target.dataset.filter;
        this.currentFilter = filter;

        // Update active button
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Filter rows
        const rows = document.querySelectorAll('.job-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.dataset.status;
            const isVisible = filter === 'all' || status === filter;
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        this.updateResultsCount(visibleCount);
        this.announce(`Đang hiển thị ${visibleCount} jobs ${filter !== 'all' ? filter : ''}`);
    }

    async handleDelete(event) {
        event.preventDefault();
        const button = event.currentTarget;
        const jobId = button.dataset.deleteJob;
        const jobName = button.dataset.jobName;

        const confirmed = await this.confirmAction({
            title: 'Xác nhận xóa',
            message: `Bạn có chắc chắn muốn xóa job "${jobName}"?`,
            confirmText: 'Xóa',
            type: 'danger'
        });

        if (confirmed) {
            await this.deleteJob(jobId);
        }
    }

    async deleteJob(jobId) {
        try {
            this.showLoading('Đang xóa job...');

            const response = await fetch(`/admin/jobs/${jobId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const row = document.querySelector(`[data-job-id="${jobId}"]`);
                if (row) {
                    const status = row.dataset.status;
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        row.remove();
                        // Update stats
                        this.updateStats({
                            total: -1,
                            [status]: -1
                        });
                    }, 300);
                }
                this.showToast('Job đã được xóa thành công', 'success');
                this.selectedJobs.delete(jobId);
                this.updateBulkActions();
                this.updateResultsCount();
            } else {
                throw new Error('Delete failed');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showToast('Có lỗi xảy ra khi xóa job', 'error');
        } finally {
            this.hideLoading();
        }
    }

    async handleBulkAction(event) {
        const action = event.target.dataset.bulkAction;
        const jobIds = Array.from(this.selectedJobs);

        if (jobIds.length === 0) {
            this.showToast('Vui lòng chọn ít nhất một job', 'warning');
            return;
        }

        const actionMessages = {
            publish: 'xuất bản',
            unpublish: 'ẩn',
            delete: 'xóa'
        };

        const confirmed = await this.confirmAction({
            title: `Xác nhận ${actionMessages[action]}`,
            message: `Bạn có chắc chắn muốn ${actionMessages[action]} ${jobIds.length} jobs đã chọn?`,
            confirmText: actionMessages[action].charAt(0).toUpperCase() + actionMessages[action].slice(1),
            type: action === 'delete' ? 'danger' : 'warning'
        });

        if (confirmed) {
            await this.performBulkAction(action, jobIds);
        }
    }

    async performBulkAction(action, jobIds) {
        try {
            const actionText = action === 'publish' ? 'xuất bản' : action === 'unpublish' ? 'ẩn' : 'xóa';
            this.showLoading(`Đang ${actionText} ${jobIds.length} jobs...`);

            const response = await fetch(`/admin/jobs/bulk-${action}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ job_ids: jobIds })
            });

            if (response.ok) {
                this.showToast(`Đã ${actionText} ${jobIds.length} jobs`, 'success');
                
                if (action === 'delete') {
                    let deletedPublished = 0;
                    let deletedUnpublished = 0;
                    
                    jobIds.forEach(id => {
                        const row = document.querySelector(`[data-job-id="${id}"]`);
                        if (row) {
                            if (row.dataset.status === 'published') deletedPublished++;
                            else deletedUnpublished++;
                            
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                    
                    // Update stats
                    setTimeout(() => {
                        this.updateStats({
                            total: -jobIds.length,
                            published: -deletedPublished,
                            unpublished: -deletedUnpublished
                        });
                    }, 300);
                } else {
                    // Update status badges without reload
                    jobIds.forEach(id => {
                        const row = document.querySelector(`[data-job-id="${id}"]`);
                        if (row) {
                            const badge = row.querySelector('.badge');
                            const newStatus = action === 'publish' ? 'published' : 'unpublished';
                            row.dataset.status = newStatus;
                            
                            if (badge) {
                                if (newStatus === 'published') {
                                    badge.className = 'badge badge--success';
                                    badge.innerHTML = '<i class="fas fa-check-circle"></i> Đã xuất bản';
                                } else {
                                    badge.className = 'badge badge--warning';
                                    badge.innerHTML = '<i class="fas fa-pause-circle"></i> Chưa xuất bản';
                                }
                            }
                            
                            // Update action button
                            const form = row.querySelector('.inline-form');
                            if (form) {
                                const actionUrl = newStatus === 'published' 
                                    ? `/admin/jobs/${id}/unpublish`
                                    : `/admin/jobs/${id}/publish`;
                                form.action = actionUrl;
                                
                                const button = form.querySelector('button');
                                if (button) {
                                    if (newStatus === 'published') {
                                        button.innerHTML = '<i class="fas fa-pause"></i>';
                                        button.title = 'Ẩn';
                                    } else {
                                        button.innerHTML = '<i class="fas fa-play"></i>';
                                        button.title = 'Xuất bản';
                                    }
                                }
                            }
                        }
                    });
                    
                    // Update stats
                    const changeCount = jobIds.length;
                    if (action === 'publish') {
                        this.updateStats({
                            published: changeCount,
                            unpublished: -changeCount
                        });
                    } else {
                        this.updateStats({
                            published: -changeCount,
                            unpublished: changeCount
                        });
                    }
                }

                this.selectedJobs.clear();
                document.getElementById('selectAll').checked = false;
                document.querySelectorAll('.job-checkbox').forEach(cb => cb.checked = false);
                this.updateBulkActions();
                this.updateResultsCount();
            } else {
                throw new Error('Bulk action failed');
            }
        } catch (error) {
            console.error('Bulk action error:', error);
            this.showToast('Có lỗi xảy ra', 'error');
        } finally {
            this.hideLoading();
        }
    }

    updateBulkActions() {
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCount = document.getElementById('selectedCount');

        if (bulkActionsBar && selectedCount) {
            if (this.selectedJobs.size > 0) {
                bulkActionsBar.classList.add('visible');
                selectedCount.textContent = this.selectedJobs.size;
            } else {
                bulkActionsBar.classList.remove('visible');
            }
        }
    }

    updateResultsCount(count = null) {
        if (count === null) {
            const visibleRows = document.querySelectorAll('.job-row:not([style*="display: none"])');
            count = visibleRows.length;
        }

        const countElement = document.getElementById('resultsCount');
        if (countElement) {
            countElement.textContent = `Hiển thị ${count} kết quả`;
        }
    }

    initModal() {
        this.modal = document.getElementById('confirmModal');
        if (!this.modal) return;

        const closeBtn = document.getElementById('modalClose');
        const cancelBtn = document.getElementById('modalCancel');
        const confirmBtn = document.getElementById('modalConfirm');
        const overlay = this.modal.querySelector('.modal__overlay');

        const closeModal = () => {
            this.modal.classList.remove('active');
            if (this.modalResolve) {
                this.modalResolve(false);
                this.modalResolve = null;
            }
        };

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);
        overlay?.addEventListener('click', closeModal);

        confirmBtn?.addEventListener('click', () => {
            this.modal.classList.remove('active');
            if (this.modalResolve) {
                this.modalResolve(true);
                this.modalResolve = null;
            }
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                closeModal();
            }
        });
    }

    confirmAction({ title, message, confirmText, type = 'warning' }) {
        return new Promise((resolve) => {
            this.modalResolve = resolve;
            
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');
            const modalConfirm = document.getElementById('modalConfirm');

            if (modalTitle) modalTitle.textContent = title;
            if (modalMessage) modalMessage.textContent = message;
            if (modalConfirm) {
                modalConfirm.textContent = confirmText;
                modalConfirm.className = `btn btn--${type}`;
            }

            // Update icon based on type
            if (modalIcon) {
                const iconClass = {
                    danger: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle',
                    success: 'fa-check-circle'
                }[type] || 'fa-exclamation-triangle';
                
                modalIcon.innerHTML = `<i class="fas ${iconClass}"></i>`;
                modalIcon.className = `modal__icon modal__icon--${type}`;
            }

            this.modal.classList.add('active');
        });
    }

    showToast(message, type = 'info') {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#11998e' : type === 'error' ? '#dc3545' : '#6a4c93'};
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    showLoading(message = 'Đang xử lý...') {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            const text = overlay.querySelector('p');
            if (text) text.textContent = message;
            overlay.classList.add('active');
        }
    }

    hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('active');
        }
    }

    refreshData() {
        const refreshBtn = document.getElementById('refreshData');
        if (refreshBtn) {
            const icon = refreshBtn.querySelector('i');
            icon.classList.add('fa-spin');
            
            setTimeout(() => {
                icon.classList.remove('fa-spin');
                this.showToast('Dữ liệu đã được làm mới', 'success');
                this.announce('Dữ liệu đã được làm mới');
            }, 1000);
        }
    }

    loadInitialStats() {
        const statsGrid = document.getElementById('statsGrid');
        if (statsGrid) {
            const cards = statsGrid.querySelectorAll('[data-stat]');
            cards.forEach(card => {
                const stat = card.dataset.stat;
                const value = card.querySelector('.stat-card__value')?.textContent || '0';
                this.stats[stat] = parseInt(value);
            });
        }
    }

    updateStats(changes) {
        Object.keys(changes).forEach(key => {
            if (this.stats.hasOwnProperty(key)) {
                this.stats[key] += changes[key];
                const card = document.querySelector(`[data-stat="${key}"] .stat-card__value`);
                if (card) {
                    this.animateNumber(card, parseInt(card.textContent), this.stats[key]);
                }
            }
        });
    }

    animateNumber(element, from, to) {
        const duration = 500;
        const steps = 20;
        const stepValue = (to - from) / steps;
        let current = from;
        let step = 0;

        const timer = setInterval(() => {
            step++;
            current += stepValue;
            element.textContent = Math.round(current);

            if (step >= steps) {
                element.textContent = to;
                clearInterval(timer);
            }
        }, duration / steps);
    }

    handleSort(event) {
        const th = event.currentTarget;
        const column = th.dataset.sort;

        // Update sort direction
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
        }

        // Update UI
        document.querySelectorAll('.sortable').forEach(header => {
            const icon = header.querySelector('.sort-icon');
            if (icon) {
                icon.className = 'fas fa-sort sort-icon';
            }
        });

        const icon = th.querySelector('.sort-icon');
        if (icon) {
            icon.className = `fas fa-sort-${this.sortDirection === 'asc' ? 'up' : 'down'} sort-icon active`;
        }

        // Sort rows
        this.sortTable(column, this.sortDirection);
        this.announce(`Đã sắp xếp theo ${column} ${this.sortDirection === 'asc' ? 'tăng dần' : 'giảm dần'}`);
    }

    sortTable(column, direction) {
        const tbody = document.querySelector('.data-table__table tbody');
        const rows = Array.from(tbody.querySelectorAll('.job-row'));

        rows.sort((a, b) => {
            let aVal, bVal;

            switch(column) {
                case 'name':
                    aVal = a.querySelector('.job-info__title')?.textContent || '';
                    bVal = b.querySelector('.job-info__title')?.textContent || '';
                    break;
                case 'company':
                    aVal = a.querySelector('.badge--light')?.textContent || '';
                    bVal = b.querySelector('.badge--light')?.textContent || '';
                    break;
                case 'status':
                    aVal = a.dataset.status || '';
                    bVal = b.dataset.status || '';
                    break;
                case 'date':
                    aVal = a.querySelector('.date-info div')?.textContent || '';
                    bVal = b.querySelector('.date-info div')?.textContent || '';
                    break;
                default:
                    return 0;
            }

            const comparison = aVal.localeCompare(bVal);
            return direction === 'asc' ? comparison : -comparison;
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    handleKeyboardShortcuts(event) {
        // Ctrl/Cmd + K: Focus search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            document.getElementById('searchJobs')?.focus();
        }

        // Ctrl/Cmd + A: Select all (when not in input)
        if ((event.ctrlKey || event.metaKey) && event.key === 'a' && 
            !['INPUT', 'TEXTAREA'].includes(event.target.tagName)) {
            event.preventDefault();
            document.getElementById('selectAll')?.click();
        }

        // ESC: Clear selection
        if (event.key === 'Escape') {
            this.selectedJobs.clear();
            document.querySelectorAll('.job-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            this.updateBulkActions();
        }
    }

    addEnterAnimations() {
        const rows = document.querySelectorAll('.job-row');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            row.classList.add('fade-in-up');
        });
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    announce(message) {
        const liveRegion = document.getElementById('sr-announcements');
        if (liveRegion) {
            liveRegion.textContent = message;
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.jobsManager = new JobsManager();
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
