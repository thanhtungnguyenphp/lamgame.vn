<x-admin::layouts>

    <!-- Title of the page -->
    <x-slot:title>
        Danh sách Menu
    </x-slot>
@pushOnce('styles')
    <style>
        .menu-modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
        .menu-card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endPushOnce
    <!-- Page Content -->
    <div class="mt-4 flex">
        <div class="w-full">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold">Menu Management</h1>
                <a href="{{ route('admin.menu.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">Create Menu</a>
            </div>
            <div class="mb-4 flex items-center px-6 pt-4">
            <div class="relative mr-3 w-64">
                <form action="{{ route('admin.menu.index') }}" method="GET">
                        <input
                            type="text"
                            name="search"
                            class="block w-full rounded-lg border bg-white py-2 leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400 pl-3 pr-10"
                            placeholder="Search"
                            autocomplete="off"
                            value="{{ request('search') }}"
                        />
                    </form>

                <script>
                    const searchInput = document.querySelector('input[name="search"]');

                    searchInput.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter' && searchInput.value.trim() !== '') {
                            event.preventDefault();
                            searchInput.form.submit();
                        }
                    });
                </script>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="icon-search text-2xl text-gray-400"></span>
                    </div>
                </div>
                <span class="text-gray-500 ml-2 px-2 py-1">{{ $total }} Results</span>
                <!-- <button class="ml-4 flex items-center px-3 py-2 border rounded text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6h18M3 14h18M3 20h18" /></svg>
                    Filter
                </button> -->
                <form id="perPageForm" action="{{ route('admin.menu.index') }}" method="GET">
                    <select class="ml-4 border rounded px-3 py-2" name="perPage" id="perPage" onchange="this.form.submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                <span class="ml-2 px-2 py-1 text-gray-500">Per Page</span>
                <span class="ml-4 px-2 py-1 text-gray-500">{{ $menus->currentPage() }} of {{ $menus->lastPage() }}</span>

                <div class="inline-flex items-center ml-4">
                    <label for="page" class="mr-2 text-gray-500">Go to page:</label>
                    <input type="number" id="page" name="page" min="1" max="{{ $menus->lastPage() }}" value="{{ $menus->currentPage() }}" class="w-20 px-2 py-1 text-gray-700 border rounded" />
                    <button onclick="goToPage()" class="ml-2 px-4 py-1 bg-blue-500 hover:bg-blue-700 text-white rounded">Go</button>

                    <script>
                        function goToPage() {
                            var page = document.getElementById('page').value;
                            if (page >= 1 && page <= {{ $menus->lastPage() }}) {
                                window.location.href = '?page=' + page;
                            }
                        }

                        document.getElementById('page').addEventListener('keyup', function(event) {
                            if (event.key === 'Enter') {
                                goToPage();
                            }
                        });
                    </script>
                </div>

                <!-- Pagination Buttons -->
                <div class="flex items-center ml-4 gap-1">
                    @if ($menus->onFirstPage())
                        <div class="inline-flex w-full max-w-max cursor-not-allowed appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-2 text-center text-gray-600 transition-all marker:shadow disabled:opacity-50">
                            <span class="icon-sort-left rtl:icon-sort-right text-2xl"></span>
                        </div>
                    @else
                        <a href="{{ $menus->previousPageUrl() }}">
                            <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-2 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                                <span class="icon-sort-left rtl:icon-sort-right text-2xl"></span>
                            </div>
                        </a>
                    @endif

                    @if ($menus->hasMorePages())
                        <a href="{{ $menus->nextPageUrl() }}">
                            <div class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-2 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                                <span class="icon-sort-right rtl:icon-sort-left text-2xl"></span>
                            </div>
                        </a>
                    @else
                        <div class="inline-flex w-full max-w-max cursor-not-allowed appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-2 text-center text-gray-600 transition-all marker:shadow disabled:opacity-50">
                            <span class="icon-sort-right rtl:icon-sort-left text-2xl"></span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Channel</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Visible In Menu</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($menus as $menu)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $menu->id }}</td>
                                <td class="px-4 py-2">{{ $menu->name }}</td>
                                <td class="px-4 py-2">{{ $menu->channel->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">
                                    <span class="text-green-600 font-medium">{{ $menu->menuItems->count() }} items</span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center space-x-2">
                                        <!-- Manage Items Button -->
                                        <a href="{{ route('admin.menu.items.index', $menu->id) }}" 
                                           class="icon-settings text-gray-600 hover:text-green-600"
                                           title="Manage Menu Items"></a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.menu.edit', ['id' => $menu->id]) }}" 
                                           class="icon-edit text-gray-600 hover:text-blue-600"
                                           title="Edit Menu"></a>
                                        
                                        <!-- Delete Button -->
                                        <a href="#" class="icon-delete text-gray-600 hover:text-red-600" 
                                           onclick="openConfirmationModal(event, '{{ route('admin.menu.destroy', ['id' => $menu->id]) }}')"
                                           title="Delete Menu"></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed left-0 top-0 z-[999] flex h-full w-full items-center justify-center menu-modal-overlay hidden">
        <div class="menu-card-shadow relative w-full max-w-[400px] rounded-lg bg-white dark:bg-gray-900 max-md:w-[90%]">
            <div class="flex items-center justify-between gap-2.5 border-b px-4 py-3 text-lg font-bold text-gray-800 dark:border-gray-800 dark:text-white">
                Confirm Delete
            </div>

            <div class="px-4 py-3 text-left text-gray-600 dark:text-gray-300">
                Are you sure you want to delete this menu? This action cannot be undone.
            </div>

            <div class="flex justify-end gap-2.5 px-4 py-2.5">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50" onclick="closeConfirmationModal()">
                    Cancel
                </button>

                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="confirmAction()">
                    Delete
                </button>
            </div>
        </div>
    </div>

</x-admin::layouts>

@pushOnce('scripts')
<script>
    let deleteUrl = '';

    function openConfirmationModal(event, url) {
        event.preventDefault();
        deleteUrl = url;
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
    }

    function confirmAction() {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        // Add method spoofing for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        
        form.submit();
        
        closeConfirmationModal();
    }
</script>
@endPushOnce
