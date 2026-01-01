<?php 
include __DIR__ . '/../includes/header.php'; 

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if (!$token || !$email) {
    echo "<div class='min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 text-center'><p class='text-red-600'>Invalid link.</p></div>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}
?>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
            Set new password
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form id="resetPasswordForm" class="space-y-6">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        New Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirm Password
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" id="submitBtn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;

    if (password !== confirm) {
        Toastify({
            text: "Passwords do not match",
            backgroundColor: "#EF4444",
        }).showToast();
        return;
    }

    if (password.length < 8) {
        Toastify({
            text: "Password must be at least 8 characters",
            backgroundColor: "#EF4444",
        }).showToast();
        return;
    }

    btn.disabled = true;
    btn.innerText = "Resetting...";

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch('reset_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            Toastify({
                text: "Password reset successfully! Redirecting...",
                duration: 2000,
                backgroundColor: "#059669",
            }).showToast();
            setTimeout(() => {
                window.location.href = 'login_ui.php';
            }, 2000);
        } else {
            Toastify({
                text: result.message,
                backgroundColor: "#EF4444",
            }).showToast();
            btn.disabled = false;
            btn.innerText = "Reset Password";
        }
    } catch (error) {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerText = "Reset Password";
        Toastify({
            text: "An error occurred. Please try again.",
            backgroundColor: "#EF4444",
        }).showToast();
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>