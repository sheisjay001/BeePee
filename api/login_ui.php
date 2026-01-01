<?php 
include __DIR__ . '/../includes/header.php'; 

if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '/dashboard';</script>";
    exit;
}
?>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Sign in to your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="register" class="font-medium text-primary hover:text-secondary">
                create a new account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="loginForm" class="space-y-6">
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
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-secondary">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" id="loginBtn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                        Sign in
                    </button>
                </div>
            </form>
            
            <div id="message" class="mt-4 text-center text-sm text-red-600 hidden"></div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('loginBtn');
    const originalText = btn.innerText;

    // Set Loading State
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Signing in...';

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const messageEl = document.getElementById('message');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            window.location.href = 'tracker_ui.php';
        } else {
            Toastify({
                text: result.message || 'Login failed',
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
        }
    } catch (error) {
        console.error('Error:', error);
        Toastify({
            text: 'An error occurred. Please try again.',
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#EF4444",
        }).showToast();
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
