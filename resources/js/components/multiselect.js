import TomSelect from 'tom-select';

/**
 * Initialize Tom Select multiselect component
 * 
 * @param {string} selector - CSS selector for the select element
 * @param {Object} options - Additional configuration options
 * @returns {TomSelect} Tom Select instance
 */
export function initMultiSelect(selector, options = {}) {
    const defaultConfig = {
        plugins: ['remove_button', 'checkbox_options'],
        maxItems: null,
        closeAfterSelect: false,
        hideSelected: false,
        controlInput: null,
        
        // Tailwind-styled render functions
        render: {
            option: function(data, escape) {
                return `
                    <div class="flex items-center gap-2 py-2 px-3">
                        <span class="flex-1 text-sm text-gray-900">${escape(data.text)}</span>
                    </div>
                `;
            },
            item: function(data, escape) {
                return `
                    <div data-value="${escape(data.value)}">
                        <span>${escape(data.text)}</span>
                    </div>
                `;
            },
            no_results: function() {
                return '<div class="px-3 py-3 text-sm text-gray-500 text-center">Không tìm thấy kết quả</div>';
            }
        },
        
        // Callbacks
        onChange: function(values) {
            if (options.onChangeCallback) {
                options.onChangeCallback(values);
            }
        }
    };
    
    const config = { ...defaultConfig, ...options };
    return new TomSelect(selector, config);
}

/**
 * Update counter display for selected items
 * 
 * @param {TomSelect} selectInstance - Tom Select instance
 * @param {string} counterId - ID of the counter element
 */
export function updateCounter(selectInstance, counterId) {
    const count = selectInstance.items.length;
    const counterEl = document.getElementById(counterId);
    
    if (counterEl) {
        const emptyText = counterEl.dataset.emptyText || 'Chưa chọn';
        const suffix = counterEl.dataset.suffix || 'đã chọn';
        
        if (count === 0) {
            counterEl.textContent = emptyText;
            counterEl.classList.add('text-gray-500');
            counterEl.classList.remove('text-primary-600', 'font-medium');
        } else {
            counterEl.textContent = `${count} ${suffix}`;
            counterEl.classList.remove('text-gray-500');
            counterEl.classList.add('text-primary-600', 'font-medium');
        }
    }
}

/**
 * Populate Tom Select with options from API data
 * 
 * @param {TomSelect} selectInstance - Tom Select instance
 * @param {Array} options - Array of option objects {id, value} or {value}
 */
export function populateOptions(selectInstance, options) {
    options.forEach(opt => {
        selectInstance.addOption({ 
            value: opt.id || opt.value, 
            text: opt.value 
        });
    });
    selectInstance.refreshOptions(false);
}
