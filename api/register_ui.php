<?php 
include __DIR__ . '/../includes/header.php'; 

if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'tracker_ui.php';</script>";
    exit;
}
?>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="login" class="font-medium text-primary hover:text-secondary">
                sign in to your existing account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="registerForm" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        Username
                    </label>
                    <div class="mt-1">
                        <input id="username" name="username" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <!-- Password Strength Meter -->
                        <div class="mt-2">
                            <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full bg-red-500 w-0 transition-all duration-300"></div>
                            </div>
                            <p id="strength-text" class="text-xs text-gray-500 mt-1">Password strength: Weak</p>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                        Sign up
                    </button>
                </div>
            </form>
            
            <div id="message" class="mt-4 text-center text-sm text-red-600 hidden"></div>
        </div>
    </div>
</div>

<script>
// Password Strength Logic
const passwordInput = document.getElementById('password');
const strengthBar = document.getElementById('strength-bar');
const strengthText = document.getElementById('strength-text');

passwordInput.addEventListener('input', function() {
    const val = this.value;
    let strength = 0;
    
    if (val.length >= 8) strength += 1;
    if (val.match(/[A-Z]/)) strength += 1;
    if (val.match(/[0-9]/)) strength += 1;
    if (val.match(/[^A-Za-z0-9]/)) strength += 1;

    switch(strength) {
        case 0:
        case 1:
            strengthBar.style.width = '25%';
            strengthBar.className = 'h-full bg-red-500 w-0 transition-all duration-300';
            strengthText.innerText = 'Password strength: Weak';
            strengthText.className = 'text-xs text-red-500 mt-1';
            break;
        case 2:
            strengthBar.style.width = '50%';
            strengthBar.className = 'h-full bg-yellow-500 w-0 transition-all duration-300';
            strengthText.innerText = 'Password strength: Medium';
            strengthText.className = 'text-xs text-yellow-500 mt-1';
            break;
        case 3:
            strengthBar.style.width = '75%';
            strengthBar.className = 'h-full bg-blue-500 w-0 transition-all duration-300';
            strengthText.innerText = 'Password strength: Strong';
            strengthText.className = 'text-xs text-blue-500 mt-1';
            break;
        case 4:
            strengthBar.style.width = '100%';
            strengthBar.className = 'h-full bg-green-500 w-0 transition-all duration-300';
            strengthText.innerText = 'Password strength: Very Strong';
            strengthText.className = 'text-xs text-green-500 mt-1';
            break;
    }
});

document.getElementById('email').addEventListener('input', function(e) {
    const email = e.target.value;
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    if (email && !isValid) {
        e.target.classList.add('border-red-500', 'text-red-900');
        e.target.classList.remove('border-gray-300');
    } else {
        e.target.classList.remove('border-red-500', 'text-red-900');
        e.target.classList.add('border-gray-300');
    }
});

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const originalText = btn.innerText;
    
    // Set Loading State
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Signing up...';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const messageEl = document.getElementById('message');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            Toastify({
                text: "Registration successful! Redirecting...",
                duration: 2000,
                gravity: "top",
                position: "right",
                backgroundColor: "#059669",
            }).showToast();
            setTimeout(() => window.location.href = '/dashboard', 2000);
        } else {
            Toastify({
                text: result.message || 'Registration failed',
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#EF4444",
            }).showToast();
            btn.disabled = false;
            btn.innerText = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        Toastify({
            text: "An error occurred. Please try again.",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#EF4444",
        }).showToast();
        btn.disabled = false;
        btn.innerText = originalText;
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
