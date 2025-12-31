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
                { item: "Rolled Oats", qty: "1/2 cup" },
                { item: "Almond Milk (Unsweetened)", qty: "1 cup" },
                { item: "Chia Seeds", qty: "1 tbsp" },
                { item: "Blueberries", qty: "1/2 cup" },
                { item: "Walnuts (chopped)", qty: "1 tbsp" }
            ]
        },
        {
            title: "Spinach & Mushroom Egg White Frittata",
            region: "europe",
            image: "https://images.unsplash.com/photo-1590786968030-9b62f741c094?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["High Protein", "Low Carb", "European"],
            description: "Protein-packed breakfast that keeps blood sugar stable.",
            ingredients: [
                { item: "Egg Whites", qty: "3 large" },
                { item: "Spinach (fresh)", qty: "1 cup" },
                { item: "Mushrooms (sliced)", qty: "1/2 cup" },
                { item: "Olive Oil", qty: "1 tsp" }
            ]
        },
        {
            title: "Akara (Bean Cakes) with Pap",
            region: "africa",
            image: "https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Plant Protein", "Gluten Free", "African"],
            description: "Traditional West African breakfast made from peeled beans, deep fried or baked.",
            ingredients: [
                { item: "Black-eyed Peas (peeled/blended)", qty: "1 cup" },
                { item: "Onion (chopped)", qty: "1 small" },
                { item: "Habanero Pepper", qty: "to taste" },
                { item: "Vegetable Oil (for frying)", qty: "minimal" }
            ]
        },
        {
            title: "Miso Soup with Tofu",
            region: "asia",
            image: "https://images.unsplash.com/photo-1547592166-23acbe346499?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Probiotic", "Light", "Asian"],
            description: "A traditional Japanese breakfast that is light on the stomach and rich in probiotics.",
            ingredients: [
                { item: "Dashi Stock", qty: "2 cups" },
                { item: "Miso Paste", qty: "1 tbsp" },
                { item: "Silken Tofu (cubed)", qty: "1/2 cup" },
                { item: "Green Onions", qty: "1 stalk" },
                { item: "Wakame Seaweed", qty: "1 tsp" }
            ]
        },
        {
            title: "Shakshuka",
            region: "europe",
            image: "https://images.unsplash.com/photo-1590412200988-a436970781fa?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Vegetarian", "Spicy", "Mediterranean"],
            description: "Eggs poached in a sauce of tomatoes, olive oil, peppers, onion and garlic.",
            ingredients: [
                { item: "Eggs", qty: "2" },
                { item: "Tomatoes (canned/diced)", qty: "1 cup" },
                { item: "Bell Pepper", qty: "1/2" },
                { item: "Onion", qty: "1/2" },
                { item: "Cumin & Paprika", qty: "1 tsp each" }
            ]
        }
    ],
    lunch: [
        {
            title: "Grilled Chicken Quinoa Salad",
            region: "americas",
            image: "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["DASH Friendly", "Balanced", "Western"],
            description: "A perfect balance of lean protein and complex carbohydrates.",
            ingredients: [
                { item: "Chicken Breast", qty: "4 oz" },
                { item: "Quinoa (cooked)", qty: "1/2 cup" },
                { item: "Cucumber", qty: "1/2 cup" },
                { item: "Feta Cheese", qty: "1 tbsp" }
            ]
        },
        {
            title: "Jollof Rice with Grilled Fish",
            region: "africa",
            image: "https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Spicy", "Favorite", "African"],
            description: "A healthier take on the classic West African dish using parboiled rice and less oil.",
            ingredients: [
                { item: "Parboiled/Brown Rice", qty: "1 cup" },
                { item: "Tomato Stew Base", qty: "1/2 cup" },
                { item: "Grilled Tilapia/Mackerel", qty: "1 fillet" },
                { item: "Steamed Vegetables", qty: "1 cup" }
            ]
        },
        {
            title: "Vietnamese Fresh Spring Rolls",
            region: "asia",
            image: "https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Fresh", "Low Calorie", "Asian"],
            description: "Rice paper rolls filled with shrimp, herbs, vermicelli, and vegetables.",
            ingredients: [
                { item: "Rice Paper Wrappers", qty: "3" },
                { item: "Shrimp (boiled)", qty: "6" },
                { item: "Rice Vermicelli", qty: "1/2 cup" },
                { item: "Mint & Cilantro", qty: "1 bunch" },
                { item: "Lettuce", qty: "3 leaves" }
            ]
        },
        {
            title: "Greek Salad with Hummus",
            region: "europe",
            image: "https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Heart Healthy", "Vegetarian", "Mediterranean"],
            description: "Crisp vegetables, olives, and feta cheese with a side of creamy hummus.",
            ingredients: [
                { item: "Cucumber & Tomato", qty: "1 cup" },
                { item: "Kalamata Olives", qty: "5" },
                { item: "Feta Cheese", qty: "1 oz" },
                { item: "Hummus", qty: "2 tbsp" },
                { item: "Olive Oil", qty: "1 tbsp" }
            ]
        }
    ],
    dinner: [
        {
            title: "Baked Salmon with Asparagus",
            region: "americas",
            image: "https://images.unsplash.com/photo-1467003909585-2f8a7270028d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Omega-3", "Keto Friendly", "Western"],
            description: "Rich in healthy fats to support heart health.",
            ingredients: [
                { item: "Salmon Fillet", qty: "5 oz" },
                { item: "Asparagus Spears", qty: "10-12" },
                { item: "Lemon", qty: "1/2" },
                { item: "Dill", qty: "1 sprig" }
            ]
        },
        {
            title: "Egusi Soup with Oat Fufu",
            region: "africa",
            image: "https://images.unsplash.com/photo-1590577976322-3d2d6e8f307c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Rich", "Traditional", "African"],
            description: "Melon seed soup cooked with spinach and fish, served with a fiber-rich oat swallow.",
            ingredients: [
                { item: "Ground Egusi (Melon)", qty: "1/2 cup" },
                { item: "Spinach/Bitter Leaf", qty: "2 cups" },
                { item: "Fish/Chicken", qty: "4 oz" },
                { item: "Oat Flour (for swallow)", qty: "1/2 cup" }
            ]
        },
        {
            title: "Thai Green Curry (Light)",
            region: "asia",
            image: "https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Spicy", "Coconut", "Asian"],
            description: "A fragrant curry with chicken, bamboo shoots, and basil. Use light coconut milk.",
            ingredients: [
                { item: "Green Curry Paste", qty: "1 tbsp" },
                { item: "Light Coconut Milk", qty: "1/2 cup" },
                { item: "Chicken Breast", qty: "4 oz" },
                { item: "Bamboo Shoots", qty: "1/4 cup" },
                { item: "Thai Basil", qty: "handful" }
            ]
        },
        {
            title: "Ratatouille",
            region: "europe",
            image: "https://images.unsplash.com/photo-1572453800999-e8d2d1589b7c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80",
            tags: ["Vegan", "Vegetable Heavy", "French"],
            description: "A classic French Provençal stewed vegetable dish.",
            ingredients: [
                { item: "Eggplant", qty: "1/2" },
                { item: "Zucchini", qty: "1" },
                { item: "Bell Pepper", qty: "1" },
                { item: "Tomato Sauce", qty: "1/2 cup" },
                { item: "Herbes de Provence", qty: "1 tsp" }
            ]
        }
    ]
};

let currentCategory = 'breakfast';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    showCategory('breakfast');
});

function updateFilters() {
    showCategory(currentCategory);
}

function showCategory(category) {
    currentCategory = category;
    const regionFilter = document.getElementById('regionFilter').value;

    // Update Tabs UI
    ['breakfast', 'lunch', 'dinner'].forEach(cat => {
        const btn = document.getElementById(`tab-${cat}`);
        if (cat === category) {
            btn.className = "border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm";
        } else {
            btn.className = "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm";
        }
    });

    // Filter Recipes
    const container = document.getElementById('recipes-container');
    container.innerHTML = '';

    const filteredRecipes = recipes[category].filter(recipe => {
        if (regionFilter === 'all') return true;
        return recipe.region === regionFilter;
    });

    if (filteredRecipes.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500 text-lg">No recipes found for this region in ${category}.</p>
                <p class="text-sm text-gray-400 mt-2">Try selecting "All Regions" or another meal type.</p>
            </div>
        `;
        return;
    }

    filteredRecipes.forEach(recipe => {
        const card = document.createElement('div');
        card.className = "bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-300 flex flex-col";
        
        let tagsHtml = recipe.tags.map(tag => 
            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2 mb-1">${tag}</span>`
        ).join('');

        let ingredientsHtml = recipe.ingredients.map(ing => 
            `<li class="flex justify-between py-1 border-b border-gray-100 last:border-0">
                <span class="text-gray-600">${ing.item}</span>
                <span class="font-medium text-gray-900">${ing.qty}</span>
             </li>`
        ).join('');

        card.innerHTML = `
            <div class="h-48 w-full bg-gray-200 relative group">
                <img src="${recipe.image}" alt="${recipe.title}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute top-0 right-0 bg-black bg-opacity-50 text-white text-xs px-2 py-1 m-2 rounded">
                    ${recipe.region.toUpperCase()}
                </div>
            </div>
            <div class="px-6 py-4 flex-grow">
                <div class="mb-2 flex flex-wrap">
                    ${tagsHtml}
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">${recipe.title}</h3>
                <p class="text-gray-500 text-sm mb-4">${recipe.description}</p>
                
                <div class="mt-4">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Ingredients</h4>
                    <ul class="text-sm space-y-1">
                        ${ingredientsHtml}
                    </ul>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

// Keep the AI functions
async function generateCustomMeal() {
    const input = document.getElementById('customMealInput');
    const resultDiv = document.getElementById('aiResult');
    const query = input.value.trim();

    if (!query) {
        alert("Please enter a meal preference.");
        return;
    }

    const btn = document.querySelector('button[onclick="generateCustomMeal()"]');
    const originalText = btn.innerText;
    btn.innerText = "Generating...";
    btn.disabled = true;

    // Show loading
    resultDiv.innerHTML = '<div class="animate-pulse flex space-x-4"><div class="flex-1 space-y-4 py-1"><div class="h-4 bg-gray-200 rounded w-3/4"></div><div class="space-y-2"><div class="h-4 bg-gray-200 rounded"></div><div class="h-4 bg-gray-200 rounded w-5/6"></div></div></div></div>';
    resultDiv.classList.remove('hidden');

    try {
        const response = await fetch('api/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                message: `Create a healthy recipe for someone with blood pressure/sugar concerns. The request is: "${query}". Format the response nicely with a title, description, and a bulleted list of ingredients with quantities.` 
            })
        });

        const data = await response.json();

        if (response.ok) {
            const aiResponse = data.choices[0].message.content;
            resultDiv.innerHTML = formatText(aiResponse);
        } else {
            resultDiv.innerHTML = `<p class="text-red-500">Error: ${data.message || 'Something went wrong'}</p>`;
        }
    } catch (error) {
        console.error('Error:', error);
        resultDiv.innerHTML = `<p class="text-red-500">Network error. Please try again.</p>`;
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}

function formatText(text) {
    let formatted = text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>');
    return formatted;
}
