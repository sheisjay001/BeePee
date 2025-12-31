<?php 
include __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '/login';</script>";
    exit;
}
?>

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
    <div class="mt-16 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border border-green-200 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-primary opacity-10 rounded-full blur-2xl"></div>
        
        <div class="text-center relative z-10">
            <h2 class="text-3xl font-extrabold text-green-900">AI Chef: What's in your fridge?</h2>
            <p class="mt-3 text-lg text-green-700 max-w-2xl mx-auto">
                Don't know what to cook? Enter your available ingredients, and our AI will generate a custom, heart-healthy recipe just for you.
            </p>
            
            <div class="mt-8 max-w-xl mx-auto">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" id="ingredients-input" 
                        placeholder="e.g. Chicken breast, spinach, garlic, lemon" 
                        class="flex-1 appearance-none border border-green-300 rounded-lg px-4 py-3 bg-white text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    <button onclick="generateRecipe()" id="generate-btn"
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-md transition-all transform hover:scale-105">
                        <span>Generate Recipe</span>
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </button>
                </div>
                <p class="mt-2 text-sm text-green-600 text-left italic">
                    * AI will optimize for low sodium and glycemic index.
                </p>
            </div>
        </div>

        <!-- Generated Recipe Display -->
        <div id="generated-recipe-container" class="hidden mt-10 bg-white rounded-xl shadow-lg border border-green-100 overflow-hidden transition-all duration-500">
            <!-- Content injected by JS -->
        </div>
    </div>

</div>

<script src="../assets/js/meal_prep.js"></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
