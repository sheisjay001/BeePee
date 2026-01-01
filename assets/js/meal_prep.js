// Recipe Data
const recipes = {
    breakfast: [
        {
            title: "Oatmeal with Berries & Walnuts",
            region: "americas",
            image: "https://images.unsplash.com/photo-1517673132405-a56a62b18caf?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Low GI", "High Fiber", "Western"],
            description: "A heart-healthy start to your day packed with fiber and omega-3s.",
            ingredients: [
                "1/2 cup Rolled oats",
                "1 cup Water or almond milk",
                "1/4 cup Blueberries (fresh or frozen)",
                "1 tbsp Walnuts, chopped",
                "1/2 tsp Cinnamon"
            ],
            instructions: [
                "Boil water or milk in a small pot.",
                "Add oats and reduce heat to low.",
                "Simmer for 5-7 minutes until creamy.",
                "Top with berries, walnuts, and cinnamon."
            ],
            nutrition: "Calories: 250 | Carbs: 35g | Protein: 8g"
        },
        {
            title: "Spinach & Mushroom Egg White Omelet",
            region: "europe",
            image: "https://images.unsplash.com/photo-1525351484163-7529414395d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["High Protein", "Low Carb", "Mediterranean"],
            description: "A fluffy, protein-packed breakfast that keeps blood sugar stable.",
            ingredients: [
                "3 Egg whites",
                "1/2 cup Spinach, fresh",
                "1/4 cup Mushrooms, sliced",
                "1 tsp Olive oil",
                "Salt & pepper (pinch)"
            ],
            instructions: [
                "Heat olive oil in a non-stick pan.",
                "Sauté mushrooms until soft.",
                "Add spinach until wilted.",
                "Pour in egg whites and cook until set."
            ],
            nutrition: "Calories: 150 | Carbs: 3g | Protein: 20g"
        },
         {
            title: "Ful Medames (Egyptian Fava Beans)",
            region: "africa",
            image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["High Fiber", "Plant Protein", "North African"],
            description: "Traditional hearty breakfast rich in fiber and flavor.",
            ingredients: [
                "1 can Fava beans (rinsed)",
                "1 clove Garlic, minced",
                "1 tbsp Olive oil",
                "1/2 Lemon, juiced",
                "1/2 tsp Cumin",
                "Fresh parsley for garnish"
            ],
            instructions: [
                "Warm the beans in a saucepan with a splash of water.",
                "Mash slightly with a fork.",
                "Stir in garlic, lemon juice, cumin, and olive oil.",
                "Serve warm garnished with parsley."
            ],
            nutrition: "Calories: 280 | Carbs: 40g | Protein: 14g"
        },
        {
            title: "Moi Moi (Steamed Bean Pudding)",
            region: "africa",
            image: "https://images.unsplash.com/photo-1628833989397-5c5f47053075?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80", 
            tags: ["High Protein", "Steamed", "Nigerian"],
            description: "A nutrient-dense steamed bean pudding, rich in protein and heart-healthy.",
            ingredients: [
                "2 cups Black-eyed peas (peeled)",
                "1 Red bell pepper (Tatashe)",
                "1 Onion",
                "1 tbsp Ground crayfish (optional)",
                "1 tbsp Olive oil",
                "Smoked fish or boiled egg"
            ],
            instructions: [
                "Blend peeled beans with peppers and onions until smooth.",
                "Stir in oil, crayfish, and seasoning.",
                "Pour into molds or leaves (add egg/fish if using).",
                "Steam for 40-50 minutes until set."
            ],
            nutrition: "Calories: 300 | Carbs: 20g | Protein: 15g"
        },
        {
            title: "Congee with Lean Chicken",
            region: "asia",
            image: "https://images.unsplash.com/photo-1626809804153-29a3a9106093?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Comfort Food", "Low Fat", "Asian"],
            description: "A soothing rice porridge, easy on the stomach and warm.",
            ingredients: [
                "1/2 cup Jasmine rice",
                "4 cups Water or low-sodium broth",
                "100g Chicken breast, shredded",
                "1 inch Ginger, sliced",
                "Green onions for topping"
            ],
            instructions: [
                "Rinse rice and boil with water/broth and ginger.",
                "Simmer for 45-60 mins until rice breaks down.",
                "Add chicken and cook for 5 more mins.",
                "Garnish with green onions."
            ],
            nutrition: "Calories: 300 | Carbs: 50g | Protein: 20g"
        }
    ],
    lunch: [
        {
            title: "Jollof Rice with Grilled Chicken",
            region: "africa",
            image: "https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Nigerian", "Moderate Carb", "Spicy"],
            description: "A healthier take on the classic Nigerian Jollof, using less oil and lean protein.",
            ingredients: [
                "1 cup Parboiled rice",
                "2 cups Tomato stew base (Tomato, pepper, onion)",
                "1 tsp Thyme & Curry powder",
                "1 tbsp Vegetable oil",
                "4 oz Grilled Chicken Breast",
                "Steamed vegetables on the side"
            ],
            instructions: [
                "Sauté stew base in minimal oil with spices.",
                "Add washed rice and water/stock.",
                "Cover and steam on low heat until fluffy.",
                "Serve with grilled chicken and veggies."
            ],
            nutrition: "Calories: 450 | Carbs: 60g | Protein: 30g"
        },
        {
            title: "Grilled Chicken Salad",
            region: "americas",
            image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["High Protein", "Low Carb", "Western"],
            description: "Classic, reliable, and perfectly balanced for lunch.",
            ingredients: [
                "4 oz Chicken breast, grilled",
                "2 cups Mixed greens",
                "1/4 Avocado, sliced",
                "5 Cherry tomatoes",
                "1 tbsp Balsamic vinaigrette"
            ],
            instructions: [
                "Grill chicken seasoned with herbs.",
                "Toss greens, tomatoes, and dressing.",
                "Top with sliced chicken and avocado."
            ],
            nutrition: "Calories: 350 | Carbs: 10g | Protein: 35g"
        },
        {
            title: "Quinoa Tabbouleh",
            region: "europe",
            image: "https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Vegan", "Gluten Free", "Mediterranean"],
            description: "A gluten-free twist on the Levantine classic, high in protein.",
            ingredients: [
                "1 cup Cooked quinoa",
                "1 bunch Parsley, chopped",
                "1/2 Cucumber, diced",
                "1 Tomato, diced",
                "Lemon juice & Olive oil"
            ],
            instructions: [
                "Combine all chopped vegetables with cooled quinoa.",
                "Dress with lemon juice, olive oil, salt, and pepper.",
                "Chill before serving."
            ],
            nutrition: "Calories: 220 | Carbs: 30g | Protein: 6g"
        },
         {
            title: "Jollof Quinoa",
            region: "africa",
            image: "https://images.unsplash.com/photo-1604329760661-e71dc83f8f1a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Spicy", "Whole Grain", "West African"],
            description: "The famous West African flavor profile applied to a lower GI grain.",
            ingredients: [
                "1 cup Quinoa",
                "1 can Tomato paste (small)",
                "1 Onion, chopped",
                "1 tsp Thyme & Curry powder",
                "Scotch bonnet pepper (optional)"
            ],
            instructions: [
                "Sauté onions and spices in a little oil.",
                "Add tomato paste and fry for 5 mins.",
                "Add washed quinoa and water/broth.",
                "Cover and simmer until fluffy."
            ],
            nutrition: "Calories: 300 | Carbs: 55g | Protein: 9g"
        },
        {
            title: "Vietnamese Summer Rolls",
            region: "asia",
            image: "https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Fresh", "Low Calorie", "SE Asian"],
            description: "Fresh herbs and shrimp wrapped in rice paper. Very light.",
            ingredients: [
                "4 Rice paper wrappers",
                "8 Shrimp, boiled and halved",
                "1 cup Vermicelli noodles (cooked)",
                "Mint & Basil leaves",
                "Cucumber strips"
            ],
            instructions: [
                "Dip rice paper in warm water to soften.",
                "Layer herbs, shrimp, noodles, and cucumber.",
                "Roll tightly and serve with peanut dip."
            ],
            nutrition: "Calories: 200 | Carbs: 35g | Protein: 12g"
        }
    ],
    dinner: [
        {
            title: "Baked Salmon with Asparagus",
            region: "europe",
            image: "https://images.unsplash.com/photo-1467003909585-2f8a7270028d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Omega-3", "Keto Friendly", "Mediterranean"],
            description: "Rich in healthy fats perfect for heart health.",
            ingredients: [
                "6 oz Salmon fillet",
                "1/2 bunch Asparagus",
                "1 Lemon, sliced",
                "1 tbsp Olive oil",
                "Dill & Garlic"
            ],
            instructions: [
                "Place salmon and asparagus on a baking sheet.",
                "Drizzle with oil, lemon juice, and herbs.",
                "Bake at 400°F (200°C) for 12-15 mins."
            ],
            nutrition: "Calories: 450 | Carbs: 5g | Protein: 40g"
        },
        {
            title: "Lentil Soup",
            region: "americas",
            image: "https://images.unsplash.com/photo-1547592166-23acbe346499?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["High Fiber", "Vegan", "Global"],
            description: "Warm, filling, and incredible for blood sugar management.",
            ingredients: [
                "1 cup Dry lentils",
                "1 Carrot, chopped",
                "1 Celery stalk, chopped",
                "4 cups Vegetable broth",
                "1 tsp Cumin"
            ],
            instructions: [
                "Sauté carrots and celery.",
                "Add lentils, broth, and spices.",
                "Simmer for 25-30 mins until soft."
            ],
            nutrition: "Calories: 280 | Carbs: 45g | Protein: 18g"
        },
        {
            title: "Nigerian Vegetable Soup (Efo Riro style)",
            region: "africa",
            image: "https://images.unsplash.com/photo-1628833989397-5c5f47053075?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Nutrient Dense", "Low Carb", "West African"],
            description: "A rich spinach stew with locust beans (iru) and fish.",
            ingredients: [
                "4 cups Spinach or Kale (blanched)",
                "1 Red bell pepper base (blended)",
                "Smoked fish or lean meat",
                "1 tbsp Palm oil (use moderately)",
                "Locust beans (Iru)"
            ],
            instructions: [
                "Cook the pepper blend until reduced.",
                "Add smoked fish and locust beans.",
                "Stir in the vegetables and simmer for 5 mins.",
                "Serve with small portion of oats or plantain flour."
            ],
            nutrition: "Calories: 320 | Carbs: 10g | Protein: 25g"
        },
        {
            title: "Stir-Fried Tofu with Broccoli",
            region: "asia",
            image: "https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Vegan", "Quick", "East Asian"],
            description: "Simple, crunchy, and savory dinner without the salt spike.",
            ingredients: [
                "1 block Firm tofu, cubed",
                "1 head Broccoli, florets",
                "1 tbsp Soy sauce (low sodium)",
                "1 tsp Sesame oil",
                "Garlic & Ginger"
            ],
            instructions: [
                "Pan-fry tofu until golden.",
                "Stir-fry broccoli with garlic and ginger.",
                "Toss everything with soy sauce and sesame oil."
            ],
            nutrition: "Calories: 260 | Carbs: 15g | Protein: 22g"
        }
    ]
};

// Functions to Render Recipes
let currentCategory = 'breakfast';
let currentRegion = 'all';

function showCategory(category) {
    currentCategory = category;
    
    // Update Tabs UI
    document.querySelectorAll('nav button').forEach(btn => {
        btn.classList.remove('border-primary', 'text-primary');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeBtn = document.getElementById(`tab-${category}`);
    if(activeBtn) {
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
        activeBtn.classList.add('border-primary', 'text-primary');
    }

    renderRecipes();
}

function updateFilters() {
    const regionSelect = document.getElementById('regionFilter');
    currentRegion = regionSelect.value;
    renderRecipes();
}

function renderRecipes() {
    const container = document.getElementById('recipes-container');
    container.innerHTML = '';

    const categoryRecipes = recipes[currentCategory] || [];
    
    // Filter by Region
    const filteredRecipes = categoryRecipes.filter(recipe => {
        if (currentRegion === 'all') return true;
        return recipe.region === currentRegion;
    });

    if (filteredRecipes.length === 0) {
        container.innerHTML = `<div class="col-span-3 text-center py-10 text-gray-500">No recipes found for this region in ${currentCategory}.</div>`;
        return;
    }

    filteredRecipes.forEach(recipe => {
        const card = `
            <div class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex-shrink-0">
                    <img class="h-48 w-full object-cover" src="${recipe.image}" alt="${recipe.title}">
                </div>
                <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                             <span class="text-sm font-medium text-primary bg-green-50 px-2 py-1 rounded-full">
                                ${recipe.tags[0]}
                            </span>
                             <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                                ${recipe.region}
                            </span>
                        </div>
                       
                        <h3 class="text-xl font-semibold text-gray-900">
                            ${recipe.title}
                        </h3>
                        <p class="mt-3 text-base text-gray-500">
                            ${recipe.description}
                        </p>
                        
                        <div class="mt-4">
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ingredients</h4>
                            <ul class="mt-2 list-disc list-inside text-sm text-gray-600">
                                ${recipe.ingredients.slice(0, 3).map(i => `<li>${i}</li>`).join('')}
                                ${recipe.ingredients.length > 3 ? `<li class="italic text-gray-400">+ ${recipe.ingredients.length - 3} more</li>` : ''}
                            </ul>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            ${recipe.nutrition.split('|')[0]}
                        </div>
                         <button onclick='showRecipeDetails(${JSON.stringify(recipe).replace(/'/g, "&#39;")})' class="text-primary hover:text-green-700 font-medium text-sm">
                            View Full Recipe &rarr;
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}

function showRecipeDetails(recipe) {
    // Simple alert for now, could be a modal in V2
    const ingredientsList = recipe.ingredients.map(i => `• ${i}`).join('\n');
    const instructionsList = recipe.instructions.map((step, index) => `${index + 1}. ${step}`).join('\n');
    
    alert(`${recipe.title}\n\nINGREDIENTS:\n${ingredientsList}\n\nINSTRUCTIONS:\n${instructionsList}\n\nNUTRITION:\n${recipe.nutrition}`);
}

// AI Recipe Generator Logic
async function generateRecipe() {
    const input = document.getElementById('ingredients-input');
    const btn = document.getElementById('generate-btn');
    const container = document.getElementById('generated-recipe-container');
    const ingredients = input.value.trim();

    if (!ingredients) {
        alert('Please enter some ingredients first!');
        return;
    }

    // UI Loading State
    const originalBtnText = btn.innerHTML;
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg> Generating...`;
    btn.disabled = true;
    container.classList.add('hidden');

    // Simulate AI delay
    setTimeout(() => {
        const generatedRecipe = {
            title: "Nigerian Vegetable Stew (Efo Riro)",
            tags: ["AI Generated", "Nigerian", "Low Carb"],
            image: "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            description: `A heart-healthy spin on the Nigerian classic using ${ingredients}. Packed with vitamins and low in oil.`,
            ingredients: [
                ...ingredients.split(',').map(i => i.trim()),
                "Spinach or Shoko leaves",
                "Locust beans (Iru)",
                "Red Bell Pepper base",
                "1 tbsp Palm oil (minimal)",
                "Smoked fish (optional)"
            ],
            instructions: [
                "Blanch the vegetables in hot water.",
                "Sauté the pepper blend in minimal oil.",
                "Add locust beans and smoked fish for flavor.",
                "Stir in the blanched vegetables and cook for 2 mins.",
                "Serve with a small portion of swallow or rice."
            ],
            nutrition: "Calories: 320 | Carbs: 10g | Protein: 25g"
        };

        renderGeneratedRecipe(generatedRecipe);
        container.classList.remove('hidden');
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        btn.innerHTML = originalBtnText;
        btn.disabled = false;
    }, 1500);
}

function renderGeneratedRecipe(data) {
    const container = document.getElementById('generated-recipe-container');
    
    // Handle case where AI might return raw text instead of JSON (safety check)
    let recipe = data;
    if (typeof data === 'string') {
        try {
            recipe = JSON.parse(data);
        } catch (e) {
            // Fallback for raw text
            container.innerHTML = `<div class="p-8 text-gray-700 whitespace-pre-line">${data}</div>`;
            return;
        }
    }

    const html = `
        <div class="md:flex">
            <div class="md:flex-shrink-0 bg-green-600 md:w-48 flex items-center justify-center">
                 <svg class="h-24 w-24 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div class="p-8 w-full">
                <div class="uppercase tracking-wide text-sm text-primary font-semibold">AI Chef Recommendation</div>
                <h2 class="block mt-1 text-2xl leading-tight font-bold text-black">${recipe.title}</h2>
                <p class="mt-2 text-gray-500">${recipe.description}</p>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-bold text-gray-900 border-b pb-2 mb-2">Ingredients</h3>
                        <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                            ${recipe.ingredients.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 border-b pb-2 mb-2">Instructions</h3>
                        <ol class="list-decimal list-inside text-gray-600 text-sm space-y-2">
                            ${recipe.instructions.map(step => `<li>${step}</li>`).join('')}
                        </ol>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-4 text-sm font-medium text-gray-500">
                    <div class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Prep: ${recipe.prep_time}</div>
                    <div class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg> Calories: ${recipe.macros?.calories || 'N/A'}</div>
                    <div class="bg-green-100 text-green-800 px-2 py-0.5 rounded">Protein: ${recipe.macros?.protein || 'N/A'}</div>
                </div>
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

// Initial Render
document.addEventListener('DOMContentLoaded', () => {
    renderRecipes();
});
