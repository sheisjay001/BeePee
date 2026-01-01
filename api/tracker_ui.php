<?php 
include __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '/login';</script>";
    exit;
}
?>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>. Here's your health overview.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg Systolic BP</dt>
                            <dd class="text-lg font-medium text-gray-900" id="avgSystolic">--</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg Diastolic BP</dt>
                            <dd class="text-lg font-medium text-gray-900" id="avgDiastolic">--</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg Blood Sugar</dt>
                            <dd class="text-lg font-medium text-gray-900" id="avgSugar">--</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Latest Weight</dt>
                            <dd class="text-lg font-medium text-gray-900" id="latestWeight">--</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <!-- Chart Section (Takes up 2 cols on large screens) -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Your Progress</h3>
                <div class="relative h-96 w-full">
                    <canvas id="healthChart"></canvas>
                </div>
            </div>

            <!-- Recent Logs Table -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Logs</h3>
                            <div class="flex space-x-2">
                                <a href="/api/export_logs.php" target="_blank" class="text-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-md font-medium transition-colors">
                                    Export CSV
                                </a>
                                <button onclick="document.getElementById('trackerFormContainer').scrollIntoView({behavior: 'smooth'})" class="text-sm text-primary hover:text-secondary font-medium md:hidden">
                                    + Add New Log
                                </button>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BP</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sugar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Logs will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Section (Takes up 1 col) -->
        <div class="md:col-span-1 mt-5 md:mt-0" id="trackerFormContainer">
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Add New Log</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Record today's measurements.
                    </p>
                </div>
                <div class="p-4 sm:p-6">
                    <form id="trackerForm">
                        <div class="space-y-6">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="date" id="date" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="systolic" class="block text-sm font-medium text-gray-700">Systolic</label>
                                    <input type="number" name="systolic" id="systolic" placeholder="120" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                                <div>
                                    <label for="diastolic" class="block text-sm font-medium text-gray-700">Diastolic</label>
                                    <input type="number" name="diastolic" id="diastolic" placeholder="80" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>

                            <div>
                                <label for="blood_sugar" class="block text-sm font-medium text-gray-700">Blood Sugar (mg/dL)</label>
                                <input type="number" step="0.1" name="blood_sugar" id="blood_sugar" placeholder="90" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                <input type="number" step="0.1" name="weight" id="weight" placeholder="70" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <div class="mt-1">
                                    <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-primary focus:border-primary mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2" placeholder="What did you eat today?"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Save Log
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Links / Tips -->
            <div class="mt-6 bg-green-50 rounded-lg p-4 border border-green-100">
                <h4 class="text-sm font-bold text-green-800 mb-2">Did you know?</h4>
                <p class="text-sm text-green-700">
                    Reducing sodium intake can significantly lower blood pressure. Try flavoring with herbs instead of salt today!
                </p>
                <div class="mt-3">
                    <a href="meal_prep.php" class="text-sm font-medium text-green-600 hover:text-green-500">
                        Get a healthy meal plan &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/tracker.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
