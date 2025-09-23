<x-admin::layouts>
    <x-slot:title>
        Create Menu
    </x-slot>

    <!-- Page Content -->
    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            Create New Menu
        </p>

        <div class="flex items-center gap-x-2.5">
            <!-- Back Button -->
            <a href="{{ route('admin.menu.index') }}" 
               class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800">
                Back
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <x-admin::form action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <!-- Menu Details -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Menu Details
                    </p>

                    <!-- Menu Name -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Menu Name
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="name"
                            rules="required"
                            :value="old('name')"
                            :label="trans('Menu Name')"
                            placeholder="Enter menu name (e.g., Main Navigation)"
                        />

                        <x-admin::form.control-group.error control-name="name" />
                    </x-admin::form.control-group>

                    <!-- Channel -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            Channel
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="select"
                            name="channel_id"
                            rules="required"
                            :value="old('channel_id')"
                            :label="trans('Channel')"
                        >
                            <option value="">Select Channel</option>
                            @foreach(core()->getAllChannels() as $channel)
                                <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                    {{ $channel->name }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>

                        <x-admin::form.control-group.error control-name="channel_id" />
                    </x-admin::form.control-group>
                </div>

                <!-- Menu Items -->
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Menu Items
                    </p>

                    <v-menu-items></v-menu-items>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[360px] max-w-full flex-col gap-2">
                <!-- Actions -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            Actions
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <button type="submit" class="primary-button w-full">
                            Create Menu
                        </button>
                    </x-slot>
                </x-admin::accordion>

                <!-- Quick Links -->
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
                            Quick Add Links
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="grid gap-2">
                            <button type="button" class="secondary-button" onclick="addQuickLink('home')">
                                Add Home Link
                            </button>
                            <button type="button" class="secondary-button" onclick="addQuickLink('categories')">
                                Add Categories
                            </button>
                            <button type="button" class="secondary-button" onclick="addQuickLink('about')">
                                Add About Page
                            </button>
                            <button type="button" class="secondary-button" onclick="addQuickLink('contact')">
                                Add Contact Page
                            </button>
                        </div>
                    </x-slot>
                </x-admin::accordion>
            </div>
        </div>
    </x-admin::form>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-menu-items-template">
            <div>
                <div class="mb-4">
                    <button type="button" @click="addMenuItem" class="secondary-button">
                        Add Menu Item
                    </button>
                </div>

                <div v-if="menuItems.length === 0" class="text-center py-8 text-gray-500">
                    No menu items added yet. Click "Add Menu Item" to get started.
                </div>

                <div v-else class="space-y-3">
                    <div v-for="(item, index) in menuItems" :key="index" 
                         class="border rounded p-4 bg-gray-50">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" 
                                       v-model="item.title" 
                                       :name="`menu_items[${index}][title]`"
                                       placeholder="Menu Title"
                                       class="form-input w-full" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                <input type="text" 
                                       v-model="item.url" 
                                       :name="`menu_items[${index}][url]`"
                                       placeholder="/path or https://..."
                                       class="form-input w-full" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" 
                                       v-model="item.sort_order" 
                                       :name="`menu_items[${index}][sort_order]`"
                                       class="form-input w-full" 
                                       min="0" value="0">
                            </div>
                            
                            <div class="flex items-end">
                                <button type="button" 
                                        @click="removeMenuItem(index)"
                                        class="text-red-600 hover:text-red-800 p-2">
                                    <span class="icon-delete text-lg"></span>
                                    Remove
                                </button>
                            </div>
                        </div>

                        <!-- Target & Icon Options -->
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                                <select v-model="item.target" 
                                        :name="`menu_items[${index}][target]`"
                                        class="form-input w-full">
                                    <option value="_self">Same Window</option>
                                    <option value="_blank">New Window</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Optional)</label>
                                <input type="text" 
                                       v-model="item.icon" 
                                       :name="`menu_items[${index}][icon]`"
                                       placeholder="icon-home"
                                       class="form-input w-full">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-menu-items', {
                template: '#v-menu-items-template',

                data() {
                    return {
                        menuItems: []
                    }
                },

                methods: {
                    addMenuItem() {
                        this.menuItems.push({
                            title: '',
                            url: '',
                            sort_order: this.menuItems.length,
                            target: '_self',
                            icon: ''
                        });
                    },

                    removeMenuItem(index) {
                        this.menuItems.splice(index, 1);
                    },

                    addQuickLink(type) {
                        const quickLinks = {
                            home: { title: 'Home', url: '/' },
                            categories: { title: 'Categories', url: '/categories' },
                            about: { title: 'About Us', url: '/about' },
                            contact: { title: 'Contact', url: '/contact' }
                        };

                        if (quickLinks[type]) {
                            this.menuItems.push({
                                ...quickLinks[type],
                                sort_order: this.menuItems.length,
                                target: '_self',
                                icon: ''
                            });
                        }
                    }
                }
            });
            
            // Global function to add quick links
            window.addQuickLink = function(type) {
                const vueApp = document.querySelector('v-menu-items').__vue__;
                if (vueApp && vueApp.addQuickLink) {
                    vueApp.addQuickLink(type);
                }
            }
        </script>
    @endPushOnce

    <style>
        .form-input {
            @apply flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400;
        }
        
        .primary-button {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition;
        }
        
        .secondary-button {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition;
        }
        
        .transparent-button {
            @apply bg-transparent border border-gray-300 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded transition;
        }
        
        .box-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</x-admin::layouts>
