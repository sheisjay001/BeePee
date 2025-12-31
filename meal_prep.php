<?php include 'includes/header.php'; ?>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Healthy Meal Prep
        </h1>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
            Delicious recipes designed to stabilize your blood pressure and sugar.
        </p>
    </div>

    <!-- Category Tabs & Filter -->
    <div class="border-b border-gray-200 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 pb-2">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showCategory('breakfast')" id="tab-breakfast" class="border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Breakfast
                </button>
                <button onclick="showCategory('lunch')" id="tab-lunch" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Lunch
                </button>
                <button onclick="showCategory('dinner')" id="tab-dinner" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Dinner
                </button>
            </nav>

            <div class="flex items-center space-x-2">
                <label for="regionFilter" class="text-sm font-medium text-gray-700">Region:</label>
                <select id="regionFilter" onchange="updateFilters()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md border">
                    <option value="all">All Regions</option>
                    <option value="africa">Africa</option>
                    <option value="asia">Asia</option>
                    <option value="europe">Europe / Mediterranean</option>
                    <option value="americas">Americas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Recipe Container -->
    <div id="recipes-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Content will be injected by JS -->
    </div>
    
    <!-- AI Meal Generator Section -->
    <div class="mt-16 bg-green-50 rounded-xl p-8 border border-green-100">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900">Need something specific?</h2>
            <p class="mt-2 text-gray-600">Ask our AI Nutritionist to generate a custom meal plan or recipe for you based on your ingredients.</p>
            <div class="mt-6 max-w-xl mx-auto flex gap-4">
                <input type="text" id="customMealInput" placeholder="e.g. Vegetarian dinner with avocado..." class="flex-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md p-3 border">
                <button onclick="generateCustomMeal()" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Generate
                </button>
            </div>
            <div id="aiResult" class="mt-6 text-left hidden bg-white p-6 rounded-lg shadow border border-gray-200 prose max-w-none">
                <!-- AI Output -->
            </div>
        </div>
    </div>

</div>

<script src="assets/js/meal_prep.js"></script>
<?php include 'includes/footer.php'; ?>
