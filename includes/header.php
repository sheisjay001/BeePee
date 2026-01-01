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
    
    <script>
        tailwind.config = {
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
                        accent: '#34D399', 
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
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    <nav class="bg-primary-900 shadow-lg border-b border-primary-800 sticky top-0 z-50">
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
                        <?php endif; ?>
                        <a href="dashboard" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Dashboard</a>
                        <a href="meal_prep.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Meal Prep</a>
                        <a href="chat.php" class="border-transparent text-gray-300 hover:text-white hover:border-accent inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Ask AI</a>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-gray-300 text-sm mr-4">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="#" onclick="logout()" class="text-gray-300 hover:text-white text-sm font-medium">Logout</a>
                    <?php else: ?>
                        <a href="login" class="text-gray-300 hover:text-white text-sm font-medium mr-4">Login</a>
                        <a href="register" class="bg-accent text-primary-900 hover:bg-white px-3 py-2 rounded-md text-sm font-medium">Sign Up</a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center sm:hidden">
                    <!-- Mobile menu button -->
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-accent" aria-controls="mobile-menu" aria-expanded="false" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="sm:hidden hidden bg-primary-800" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="index.php" class="bg-primary-900 border-accent text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</a>
                <?php endif; ?>
                <a href="dashboard" class="border-transparent text-gray-300 hover:bg-primary-700 hover:border-gray-300 hover:text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Dashboard</a>
                <a href="meal_prep.php" class="border-transparent text-gray-300 hover:bg-primary-700 hover:border-gray-300 hover:text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Meal Prep</a>
                <a href="chat.php" class="border-transparent text-gray-300 hover:bg-primary-700 hover:border-gray-300 hover:text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Ask AI</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="#" onclick="logout()" class="border-transparent text-gray-300 hover:bg-primary-700 hover:border-gray-300 hover:text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Logout</a>
                <?php else: ?>
                    <a href="login" class="border-transparent text-gray-300 hover:bg-primary-700 hover:border-gray-300 hover:text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Login</a>
                    <a href="register" class="border-transparent text-gray-300 hover:bg-primary-700 hover:border-gray-300 hover:text-white block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="flex-grow">
