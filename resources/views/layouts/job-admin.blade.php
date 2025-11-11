<!DOCTYPE html>
<html lang="vi" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Job Management') - LamGame</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/2.0.18/24/outline/heroicons.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
    
    <!-- Custom Admin Styles -->
    <style>
        /* Custom input padding for job admin system */
        input[type="text"],
        input[type="email"],
        input[type="url"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="search"],
        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        select,
        textarea {
            padding: 10px !important;
        }
        
        /* Adjust for file inputs */
        input[type="file"] {
            padding: 8px !important;
        }
        
        /* Ensure proper line height */
        input:not([type="checkbox"]):not([type="radio"]) {
            line-height: 1.5;
        }
    </style>
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-full">
        <!-- Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 shadow-xl">
                <!-- Logo -->
                <div class="flex h-16 shrink-0 items-center border-b border-gray-200">
                    <img class="h-8 w-auto" src="{{ asset('logo/lamgame-logo.svg') }}" alt="LamGame">
                    <span class="ml-3 text-xl font-semibold text-gray-900">Job Admin</span>
                </div>
                
                <!-- Navigation -->
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.jobs.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.jobs.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }}">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.09-.89 2.25-2.25 2.25H5.25A2.25 2.25 0 013 18.75V8.25a2.25 2.25 0 012.25-2.25h4.5m6.75 0V5.25A2.25 2.25 0 0114.25 3h-4.5A2.25 2.25 0 008.25 5.25v.75m6.75 0h2.25A2.25 2.25 0 0119.5 8.25v5.25" />
                                        </svg>
                                        Quản lý Jobs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.applications.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.applications.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }}">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        Ứng viên
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.companies.index') }}" 
                                       class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.companies.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:text-primary-600 hover:bg-gray-50' }}">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18m2.25-18v18M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-.75 3h.75M9 21h6" />
                                        </svg>
                                        Công ty
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Quick Actions -->
                        <li class="mt-auto">
                            <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg p-4 text-white">
                                <h3 class="text-sm font-medium">Đăng job mới</h3>
                                <p class="text-xs text-primary-100 mt-1">Tuyển dụng nhân tài ngay hôm nay</p>
                                <a href="{{ route('admin.jobs.create') }}" 
                                   class="mt-3 inline-flex items-center text-xs font-medium text-white bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-md transition-colors">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Tạo job
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Mobile menu button -->
        <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" id="mobile-menu-button">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">Job Management</div>
        </div>

        <!-- Main content -->
        <main class="lg:pl-72">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center">
                        <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications -->
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <div class="relative">
                            <button type="button" class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900" id="user-menu-button">
                                <img class="h-8 w-8 rounded-full bg-gray-50" src="https://ui-avatars.com/api/?name={{ Auth::guard('admin')->user()->name ?? 'Admin' }}&background=3b82f6&color=fff" alt="">
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                                    <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.53a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                @foreach($errors->all() as $error)
                                    <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            // Add mobile menu functionality here
        });
    </script>
</body>
</html>
