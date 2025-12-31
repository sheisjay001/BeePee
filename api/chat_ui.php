<?php include '../includes/header.php'; ?>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 h-[calc(100vh-140px)] flex flex-col">
    <div class="text-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Ask BeePee AI
        </h1>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
            Get instant answers about your diet, blood pressure, and blood sugar.
        </p>
    </div>

    <div class="flex-grow flex flex-col bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
        <div id="chatContainer" class="flex-grow p-6 overflow-y-auto space-y-4 bg-gray-50">
            <!-- Welcome Message -->
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                    AI
                </div>
                <div class="ml-3 bg-white p-3 rounded-lg shadow-sm border border-gray-200 max-w-[80%]">
                    <p class="text-sm text-gray-800">Hello! I'm BeePee AI. I can help you understand which foods are good for stabilizing your blood pressure and sugar levels. What would you like to know?</p>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-gray-200">
            <form id="chatForm" class="flex space-x-4">
                <input type="text" id="userInput" class="flex-grow focus:ring-primary focus:border-primary block w-full rounded-md sm:text-sm border-gray-300 p-3 border" placeholder="Type your question here..." autocomplete="off">
                <button type="submit" id="sendBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Send
                </button>
            </form>
        </div>
    </div>
</div>

<script src="../assets/js/chat.js"></script>
<?php include '../includes/footer.php'; ?>
