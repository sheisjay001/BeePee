<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo generate_csrf_token(); ?>">
    <title>BeePee - Blood Pressure & Sugar Tracker</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>
    <script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#059669',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        secondary: '#10B981', 
                        accent: '#F472B6', // Soft Pink/Coral for contrast
                        cta: '#F59E0B', // Amber for Call to Actions
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-pattern {
            background-color: #f0fdf4;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23166534' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        /* Micro-interactions */
        .btn-hover { transition: all 0.2s ease-in-out; }
        .btn-hover:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    </style>
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 flex flex-col min-h-screen transition-colors duration-200">
    <nav class="bg-primary-900 dark:bg-gray-800 shadow-lg border-b border-primary-800 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                            <svg class="h-8 w-8 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            BeePee
                        </span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="index.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Home</a>
                        <?php else: ?>
                            <a href="tracker_ui.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Dashboard</a>
                            <a href="meal_prep.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Meal Prep</a>
                            <a href="medications_ui.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Medications</a>
                            <a href="chat_ui.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">AI Health Coach</a>
                            <a href="profile_ui.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Profile</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button type="button" class="theme-toggle-btn text-gray-300 hover:text-white focus:outline-none rounded-lg text-sm p-2.5">
                        <svg class="theme-toggle-dark-icon hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg class="theme-toggle-light-icon hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="ml-3 relative flex items-center gap-4">
                            <span class="text-gray-300 text-sm">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Sign out</a>
                        </div>
                    <?php else: ?>
                        <a href="login_ui.php" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Sign in</a>
                        <a href="register_ui.php" class="bg-accent hover:bg-yellow-400 text-primary-900 px-3 py-2 rounded-md text-sm font-bold transition-colors duration-200">Get Started</a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" id="mobile-menu-button" class="bg-primary-900 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!-- Heroicon name: outline/menu -->
                        <svg id="menu-icon-open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Heroicon name: outline/x -->
                        <svg id="menu-icon-close" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu, show/hide based on menu state. -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="index.php" class="bg-primary-900 text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>
                    <a href="login_ui.php" class="text-gray-300 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Sign in</a>
                    <a href="register_ui.php" class="text-gray-300 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Get Started</a>
                <?php else: ?>
                    <a href="tracker_ui.php" class="bg-primary-900 text-white block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                    <a href="meal_prep.php" class="text-gray-300 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Meal Prep</a>
                    <a href="medications_ui.php" class="text-gray-300 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Medications</a>
                    <a href="chat_ui.php" class="text-gray-300 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">AI Health Coach</a>
                    <a href="profile_ui.php" class="text-gray-300 hover:bg-primary-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Profile</a>
                    
                    <div class="border-t border-primary-800 pt-4 pb-3">
                        <div class="flex items-center px-5">
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-white"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></div>
                            </div>
                            <button type="button" class="theme-toggle-btn ml-auto bg-primary-800 flex-shrink-0 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-800 focus:ring-white">
                                <span class="sr-only">Toggle Theme</span>
                                <svg class="theme-toggle-dark-icon hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                                <svg class="theme-toggle-light-icon hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                            <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-primary-700">Sign out</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        // Check for saved theme preference or use system preference
        // Mobile (sm < 640px) defaults to light mode unless explicitly set
        const isMobile = window.innerWidth < 640;
        
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && !isMobile && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIconOpen = document.getElementById('menu-icon-open');
        const menuIconClose = document.getElementById('menu-icon-close');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                menuIconOpen.classList.toggle('hidden');
                menuIconClose.classList.toggle('hidden');
            });
        }

        // Theme Toggle (Handles both Desktop and Mobile buttons)
        const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');
        
        themeToggleBtns.forEach(btn => {
            const darkIcon = btn.querySelector('.theme-toggle-dark-icon');
            const lightIcon = btn.querySelector('.theme-toggle-light-icon');

            // Initialize icons based on current theme
            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
            }

            btn.addEventListener('click', function() {
                // Toggle icons for THIS button (and others will update on reload, or we can sync them)
                // Better to sync all buttons
                themeToggleBtns.forEach(b => {
                    b.querySelector('.theme-toggle-dark-icon').classList.toggle('hidden');
                    b.querySelector('.theme-toggle-light-icon').classList.toggle('hidden');
                });

                // Toggle theme
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        });
    </script>
    <main class="flex-grow">
