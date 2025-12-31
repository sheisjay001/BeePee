document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const userInput = document.getElementById('userInput');
    const chatContainer = document.getElementById('chatContainer');
    const sendBtn = document.getElementById('sendBtn');

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = userInput.value.trim();
        if (!message) return;

        // Add User Message
        appendMessage('user', message);
        userInput.value = '';
        userInput.disabled = true;
        sendBtn.disabled = true;

        // Add Loading Indicator
        const loadingId = appendLoading();

        try {
            const response = await fetch('api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            // Remove Loading
            removeMessage(loadingId);

            if (response.ok) {
                const aiResponse = data.choices[0].message.content;
                appendMessage('ai', aiResponse);
            } else {
                appendMessage('error', 'Sorry, something went wrong. Please check your API key or try again later.');
            }

        } catch (error) {
            console.error('Error:', error);
            removeMessage(loadingId);
            appendMessage('error', 'Network error. Please try again.');
        } finally {
            userInput.disabled = false;
            sendBtn.disabled = false;
            userInput.focus();
        }
    });

    function appendMessage(role, text) {
        const div = document.createElement('div');
        div.className = 'flex items-start ' + (role === 'user' ? 'justify-end' : '');
        
        let content = '';
        if (role === 'user') {
            content = `
                <div class="mr-3 bg-primary text-white p-3 rounded-lg shadow-sm max-w-[80%]">
                    <p class="text-sm">${escapeHtml(text)}</p>
                </div>
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    You
                </div>
            `;
        } else if (role === 'ai') {
            content = `
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                    AI
                </div>
                <div class="ml-3 bg-white p-3 rounded-lg shadow-sm border border-gray-200 max-w-[80%]">
                    <div class="text-sm text-gray-800 prose prose-sm max-w-none">${formatText(text)}</div>
                </div>
            `;
        } else { // Error
            content = `
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-500 flex items-center justify-center text-white font-bold">
                    !
                </div>
                <div class="ml-3 bg-red-50 p-3 rounded-lg shadow-sm border border-red-200 max-w-[80%]">
                    <p class="text-sm text-red-800">${escapeHtml(text)}</p>
                </div>
            `;
        }

        div.innerHTML = content;
        chatContainer.appendChild(div);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        return div.id = 'msg-' + Date.now();
    }

    function appendLoading() {
        const id = 'loading-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex items-start';
        div.innerHTML = `
            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                AI
            </div>
            <div class="ml-3 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        `;
        chatContainer.appendChild(div);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        return id;
    }

    function removeMessage(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatText(text) {
        // Simple formatter for basic markdown-like syntax
        // Convert **bold** to <strong>
        let formatted = escapeHtml(text);
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Convert *italics* to <em>
        formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Convert newlines to <br>
        formatted = formatted.replace(/\n/g, '<br>');
        return formatted;
    }
});
