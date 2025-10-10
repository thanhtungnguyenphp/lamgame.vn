<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Banner List - LamGame Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Banner Management</h1>
                <div class="flex space-x-3">
                    <a href="/admin/banners/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Create Banner
                    </a>
                </div>
            </div>

            @if(isset($banners) && $banners->count() > 0)
                <!-- Banner Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded">
                    <div class="text-center">
                        <div class="text-xl font-bold text-gray-900">{{ $banners->count() }}</div>
                        <div class="text-sm text-gray-500">Total Banners</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-600">{{ $banners->where('status', true)->count() }}</div>
                        <div class="text-sm text-gray-500">Active</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-red-600">{{ $banners->where('status', false)->count() }}</div>
                        <div class="text-sm text-gray-500">Inactive</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-blue-600">{{ $banners->sum('clicks_count') }}</div>
                        <div class="text-sm text-gray-500">Total Clicks</div>
                    </div>
                </div>

                <!-- Banner Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Banner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Analytics</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($banners as $banner)
                                <tr id="banner-row-{{ $banner->id }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-gray-300 rounded flex items-center justify-center">
                                                <span class="text-xs">IMG</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $banner->name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $banner->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $banner->position }}</span><br>
                                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">{{ $banner->device_type }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded {{ $banner->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $banner->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div>Views: {{ $banner->impressions_count ?? 0 }}</div>
                                        <div>Clicks: {{ $banner->clicks_count ?? 0 }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="/admin/banners/{{ $banner->id }}/edit" 
                                               class="text-blue-600 hover:text-blue-900">
                                                Edit
                                            </a>
                                            <button type="button" 
                                                    onclick="deleteBanner({{ $banner->id }}, '{{ $banner->name }}')"
                                                    class="text-red-600 hover:text-red-900">
                                                Delete
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No banners found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first banner.</p>
                    <a href="/admin/banners/create" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Create Your First Banner
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
            <div class="bg-white rounded-lg p-6 z-10 max-w-md w-full mx-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Banner</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Are you sure you want to delete the banner "<span id="bannerName" class="font-medium"></span>"? 
                    This action cannot be undone.
                </p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="button" id="confirmDelete" 
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

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
            deleteButton.disabled = true;
            deleteButton.textContent = 'Deleting...';

            // Make AJAX request to delete banner
            fetch(`/api/banners/${bannerToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                        bannerRow.remove();
                    }
                    
                    // Show success message
                    alert('Banner deleted successfully!');
                    
                    // Close modal
                    closeDeleteModal();
                    
                    // Refresh page to update stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to delete banner');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete banner. Please try again.');
            })
            .finally(() => {
                // Reset button state
                deleteButton.disabled = false;
                deleteButton.textContent = 'Delete';
            });
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>