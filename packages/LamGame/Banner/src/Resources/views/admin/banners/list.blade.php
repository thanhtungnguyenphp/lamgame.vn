<x-admin::layouts>
    <x-slot:title>
        {{ __('banner::app.admin.banners.title') }}
    </x-slot:title>

    <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            {{ __('banner::app.admin.banners.index-title') }}
        </p>

        <div class="flex items-center gap-x-2.5">
            <a href="{{ route('admin.banners.analytics') }}" class="secondary-button">
                📊 Analytics
            </a>
            <a href="{{ route('admin.banners.create') }}" class="primary-button">
                {{ __('banner::app.admin.banners.create-title') }}
            </a>
        </div>
    </div>

    <div class="mt-8">
        <x-admin::datagrid>
            <x-slot:body>
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow">
                    @if(isset($banners) && $banners->count() > 0)
                        <!-- Banner Stats -->
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $banners->count() }}</div>
                                    <div class="text-sm text-gray-500">Total Banners</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $banners->where('status', true)->count() }}</div>
                                    <div class="text-sm text-gray-500">Active</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600">{{ $banners->where('status', false)->count() }}</div>
                                    <div class="text-sm text-gray-500">Inactive</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $banners->sum('clicks_count') }}</div>
                                    <div class="text-sm text-gray-500">Total Clicks</div>
                                </div>
                            </div>
                        </div>

                        <!-- Banner Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Banner
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Position & Device
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Analytics
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Created
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($banners as $banner)
                                        <tr id="banner-row-{{ $banner->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($banner->image)
                                                        <div class="flex-shrink-0 h-12 w-12">
                                                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ $banner->image_url }}" alt="{{ $banner->name }}">
                                                        </div>
                                                    @else
                                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $banner->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $banner->type }} • ID: {{ $banner->id }}
                                                        </div>
                                                        @if($banner->title)
                                                            <div class="text-xs text-gray-400 mt-1">
                                                                "{{ Str::limit($banner->title, 50) }}"
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mb-1">
                                                    {{ $banner->position }}
                                                </span>
                                                <br>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                                    {{ ucfirst($banner->device_type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $banner->status ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                    {{ $banner->status ? '🟢 Active' : '🔴 Inactive' }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Order: {{ $banner->sort_order }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <div class="space-y-1">
                                                    <div>👁️ {{ $banner->impressions_count ?? 0 }}</div>
                                                    <div>👆 {{ $banner->clicks_count ?? 0 }}</div>
                                                    @if(($banner->impressions_count ?? 0) > 0)
                                                        <div class="text-xs">
                                                            CTR: {{ round((($banner->clicks_count ?? 0) / ($banner->impressions_count ?? 1)) * 100, 2) }}%
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <div>{{ $banner->created_at->format('M j, Y') }}</div>
                                                <div class="text-xs">{{ $banner->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" 
                                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" 
                                                       title="Edit Banner">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <button type="button" 
                                                            onclick="deleteBanner({{ $banner->id }}, '{{ $banner->name }}')"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                                            title="Delete Banner">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🎨</div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No banners found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first banner.</p>
                            <a href="{{ route('admin.banners.create') }}" class="primary-button">
                                Create Your First Banner
                            </a>
                        </div>
                    @endif
                </div>
            </x-slot:body>
        </x-admin::datagrid>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Delete Banner
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete the banner "<span id="bannerName" class="font-medium"></span>"? 
                                    This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDelete" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span class="delete-text">Delete</span>
                        <span class="delete-loading hidden">Deleting...</span>
                    </button>
                    <button type="button" onclick="closeDeleteModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let bannerToDelete = null;

        function deleteBanner(bannerId, bannerName) {
            bannerToDelete = bannerId;
            document.getElementById('bannerName').textContent = bannerName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            bannerToDelete = null;
        }

        // Confirm delete
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!bannerToDelete) return;

            const deleteButton = this;
            const deleteText = deleteButton.querySelector('.delete-text');
            const deleteLoading = deleteButton.querySelector('.delete-loading');
            
            // Show loading state
            deleteText.classList.add('hidden');
            deleteLoading.classList.remove('hidden');
            deleteButton.disabled = true;

            // Make AJAX request to delete banner
            fetch(`/api/banners/${bannerToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the banner row from the table
                    const bannerRow = document.getElementById(`banner-row-${bannerToDelete}`);
                    if (bannerRow) {
                        bannerRow.style.transition = 'all 0.3s ease';
                        bannerRow.style.opacity = '0';
                        bannerRow.style.transform = 'translateX(-100%)';
                        setTimeout(() => bannerRow.remove(), 300);
                    }
                    
                    // Show success message
                    showNotification('success', 'Banner deleted successfully!');
                    
                    // Close modal
                    closeDeleteModal();
                    
                    // Refresh page after a short delay to update stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to delete banner');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', error.message || 'Failed to delete banner. Please try again.');
            })
            .finally(() => {
                // Reset button state
                deleteText.classList.remove('hidden');
                deleteLoading.classList.add('hidden');
                deleteButton.disabled = false;
            });
        });

        function showNotification(type, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? '✓' : '✗'}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.transition = 'all 0.3s ease';
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    </script>
    @endpush
</x-admin::layouts>