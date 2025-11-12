/**
 * Demo Script for Admin Jobs Page
 * Use this to test all new features
 */

class JobsDemo {
    constructor() {
        this.manager = window.jobsManager;
        this.demoRunning = false;
    }

    async runFullDemo() {
        if (this.demoRunning) {
            console.log('Demo already running...');
            return;
        }

        this.demoRunning = true;
        console.log('🎬 Starting Admin Jobs Demo...\n');

        try {
            await this.demoSearch();
            await this.wait(2000);
            
            await this.demoFilter();
            await this.wait(2000);
            
            await this.demoSort();
            await this.wait(2000);
            
            await this.demoModal();
            await this.wait(2000);
            
            await this.demoToast();
            await this.wait(2000);
            
            await this.demoStats();
            
            console.log('\n✅ Demo completed!');
        } catch (error) {
            console.error('Demo error:', error);
        } finally {
            this.demoRunning = false;
        }
    }

    async demoSearch() {
        console.log('🔍 Testing Search Feature...');
        const searchInput = document.getElementById('searchJobs');
        
        if (searchInput) {
            // Simulate typing
            searchInput.value = 'developer';
            searchInput.dispatchEvent(new Event('input'));
            console.log('  ✓ Search input: "developer"');
            
            await this.wait(500);
            
            // Clear search
            const clearBtn = document.getElementById('clearSearch');
            if (clearBtn && clearBtn.style.display !== 'none') {
                clearBtn.click();
                console.log('  ✓ Clear button clicked');
            }
        }
    }

    async demoFilter() {
        console.log('🎯 Testing Filter Feature...');
        const filterBtns = document.querySelectorAll('[data-filter]');
        
        for (const btn of filterBtns) {
            const filter = btn.dataset.filter;
            btn.click();
            console.log(`  ✓ Filter: ${filter}`);
            await this.wait(500);
        }
    }

    async demoSort() {
        console.log('📊 Testing Sort Feature...');
        const sortableHeaders = document.querySelectorAll('.sortable');
        
        if (sortableHeaders.length > 0) {
            const firstHeader = sortableHeaders[0];
            const column = firstHeader.dataset.sort;
            
            // Sort ascending
            firstHeader.click();
            console.log(`  ✓ Sort by ${column} (asc)`);
            await this.wait(500);
            
            // Sort descending
            firstHeader.click();
            console.log(`  ✓ Sort by ${column} (desc)`);
        }
    }

    async demoModal() {
        console.log('🎭 Testing Modal Feature...');
        
        if (this.manager) {
            const result = await this.manager.confirmAction({
                title: 'Demo Modal',
                message: 'This is a demo of the custom confirmation modal. Click Cancel to continue the demo.',
                confirmText: 'Confirm',
                type: 'info'
            });
            
            console.log(`  ✓ Modal result: ${result ? 'Confirmed' : 'Cancelled'}`);
        }
    }

    async demoToast() {
        console.log('🍞 Testing Toast Notifications...');
        
        if (this.manager) {
            const types = ['success', 'error', 'info', 'warning'];
            
            for (const type of types) {
                this.manager.showToast(`This is a ${type} toast`, type);
                console.log(`  ✓ Toast: ${type}`);
                await this.wait(1000);
            }
        }
    }

    async demoStats() {
        console.log('📈 Testing Statistics Animation...');
        
        if (this.manager) {
            // Simulate stat changes
            this.manager.updateStats({
                total: 5,
                published: 3,
                unpublished: 2
            });
            console.log('  ✓ Stats updated (+5 total, +3 published, +2 unpublished)');
            
            await this.wait(1000);
            
            // Revert
            this.manager.updateStats({
                total: -5,
                published: -3,
                unpublished: -2
            });
            console.log('  ✓ Stats reverted');
        }
    }

    wait(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Individual test methods
    testKeyboardShortcuts() {
        console.log('⌨️ Testing Keyboard Shortcuts...');
        console.log('  Try these shortcuts:');
        console.log('  - Ctrl/Cmd + K: Focus search');
        console.log('  - Ctrl/Cmd + A: Select all');
        console.log('  - ESC: Clear selection / Close modal');
    }

    testAccessibility() {
        console.log('♿ Testing Accessibility Features...');
        
        // Check ARIA labels
        const elementsWithAria = document.querySelectorAll('[aria-label]');
        console.log(`  ✓ Found ${elementsWithAria.length} elements with aria-label`);
        
        // Check screen reader region
        const srRegion = document.getElementById('sr-announcements');
        console.log(`  ✓ Screen reader region: ${srRegion ? 'Present' : 'Missing'}`);
        
        // Check focus visible
        const focusableElements = document.querySelectorAll('button, a, input, [tabindex]');
        console.log(`  ✓ Found ${focusableElements.length} focusable elements`);
    }

    testResponsive() {
        console.log('📱 Testing Responsive Design...');
        const width = window.innerWidth;
        
        if (width < 768) {
            console.log('  ✓ Mobile view detected');
        } else if (width < 1024) {
            console.log('  ✓ Tablet view detected');
        } else {
            console.log('  ✓ Desktop view detected');
        }
        
        console.log(`  Current viewport: ${width}px`);
    }

    testPerformance() {
        console.log('⚡ Testing Performance...');
        
        // Measure search performance
        const searchInput = document.getElementById('searchJobs');
        if (searchInput) {
            const start = performance.now();
            searchInput.value = 'test';
            searchInput.dispatchEvent(new Event('input'));
            const end = performance.now();
            
            console.log(`  ✓ Search execution time: ${(end - start).toFixed(2)}ms`);
        }
        
        // Check animation performance
        const rows = document.querySelectorAll('.job-row');
        console.log(`  ✓ Total rows: ${rows.length}`);
        
        // Memory usage (if available)
        if (performance.memory) {
            const used = (performance.memory.usedJSHeapSize / 1048576).toFixed(2);
            const total = (performance.memory.totalJSHeapSize / 1048576).toFixed(2);
            console.log(`  ✓ Memory usage: ${used}MB / ${total}MB`);
        }
    }

    generateReport() {
        console.log('\n📋 Feature Report\n');
        console.log('='.repeat(50));
        
        const features = {
            'Custom Modal': !!document.getElementById('confirmModal'),
            'Loading Overlay': !!document.getElementById('loadingOverlay'),
            'Search Box': !!document.getElementById('searchJobs'),
            'Clear Button': !!document.getElementById('clearSearch'),
            'Results Counter': !!document.getElementById('resultsCount'),
            'Refresh Button': !!document.getElementById('refreshData'),
            'Sortable Columns': document.querySelectorAll('.sortable').length > 0,
            'Filter Buttons': document.querySelectorAll('[data-filter]').length > 0,
            'Bulk Actions': !!document.getElementById('bulkActionsBar'),
            'Screen Reader Region': !!document.getElementById('sr-announcements'),
            'Statistics Grid': !!document.getElementById('statsGrid'),
            'Data Table': !!document.getElementById('dataTable')
        };
        
        Object.entries(features).forEach(([feature, present]) => {
            const status = present ? '✅' : '❌';
            console.log(`${status} ${feature}`);
        });
        
        console.log('='.repeat(50));
        
        const total = Object.keys(features).length;
        const present = Object.values(features).filter(Boolean).length;
        const percentage = ((present / total) * 100).toFixed(1);
        
        console.log(`\nFeature Coverage: ${present}/${total} (${percentage}%)`);
    }
}

// Initialize demo
window.jobsDemo = new JobsDemo();

// Console helpers
console.log('%c🎬 Admin Jobs Demo Ready!', 'color: #6a4c93; font-size: 16px; font-weight: bold;');
console.log('\nAvailable commands:');
console.log('  jobsDemo.runFullDemo()          - Run complete demo');
console.log('  jobsDemo.testKeyboardShortcuts() - Test keyboard shortcuts');
console.log('  jobsDemo.testAccessibility()     - Test accessibility features');
console.log('  jobsDemo.testResponsive()        - Test responsive design');
console.log('  jobsDemo.testPerformance()       - Test performance');
console.log('  jobsDemo.generateReport()        - Generate feature report');
console.log('\nExample: jobsDemo.runFullDemo()\n');
