<?php include 'includes/header.php'; ?>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Health Log</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Record your daily measurements to track your progress over time.
                </p>
            </div>
            
            <form id="trackerForm" class="mt-5 md:mt-0">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-6">
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="date" id="date" required class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="systolic" class="block text-sm font-medium text-gray-700">Systolic (mmHg)</label>
                                <input type="number" name="systolic" id="systolic" placeholder="120" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="diastolic" class="block text-sm font-medium text-gray-700">Diastolic (mmHg)</label>
                                <input type="number" name="diastolic" id="diastolic" placeholder="80" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="blood_sugar" class="block text-sm font-medium text-gray-700">Blood Sugar (mg/dL)</label>
                                <input type="number" step="0.1" name="blood_sugar" id="blood_sugar" placeholder="90" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                <input type="number" step="0.1" name="weight" id="weight" placeholder="70" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                            </div>

                            <div class="col-span-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <div class="mt-1">
                                    <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-primary focus:border-primary mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2" placeholder="What did you eat today?"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Save Log
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Your Progress</h3>
                <div class="relative h-96 w-full">
                    <canvas id="healthChart"></canvas>
                </div>
            </div>
            
            <div class="mt-6 bg-white shadow sm:rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Logs</h3>
                </div>
                <div class="border-t border-gray-200">
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
    </div>
</div>

<script src="assets/js/tracker.js"></script>
<?php include 'includes/footer.php'; ?>
