<?php 
include __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login_ui.php';</script>";
    exit;
}
?>

<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Profile Settings</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update your personal information and manage your account security.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="profileForm">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white dark:bg-gray-800 space-y-6 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                                <input type="text" name="username" id="username" autocomplete="username" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border">
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                                <input type="email" name="email" id="email" autocomplete="email" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border">
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="height" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Height (cm)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="height" id="height" placeholder="175" class="focus:ring-primary focus:border-primary block w-full pr-12 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">cm</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Used for BMI calculation.</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Change Password</h4>
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border">
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200 disabled:opacity-50">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="hidden sm:block" aria-hidden="true">
    <div class="py-5">
        <div class="border-t border-gray-200 dark:border-gray-700"></div>
    </div>
</div>

<div class="md:grid md:grid-cols-3 md:gap-6">
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Two-Factor Authentication</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Add an extra layer of security to your account.
            </p>
        </div>
    </div>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6">
                <div id="2faStatusSection">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Loading 2FA status...</p>
                </div>
                
                <!-- Setup Area (Hidden by default) -->
                <div id="2faSetupArea" class="hidden mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        1. Scan this QR code with your authenticator app (Google Authenticator, Authy, etc).
                    </p>
                    <div class="flex justify-center mb-4">
                        <img id="qrCodeImg" src="" alt="QR Code" class="border p-2 bg-white">
                    </div>
                    <p class="text-xs text-center text-gray-500 mb-4">Secret: <span id="secretKeyDisplay" class="font-mono font-bold"></span></p>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                        2. Enter the 6-digit code from the app.
                    </p>
                    <div class="flex gap-2">
                        <input type="text" id="verifyCodeInput" placeholder="123456" class="flex-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border">
                        <button id="verify2faBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Verify & Enable
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden sm:block" aria-hidden="true">
    <div class="py-5">
        <div class="border-t border-gray-200 dark:border-gray-700"></div>
    </div>
</div>

<div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Audit Logs</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Recent activity on your account.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow overflow-hidden sm:rounded-md">
                 <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:p-6">
                     <div class="flow-root">
                         <ul id="auditLogsList" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                             <li class="py-4 text-center text-gray-500">Loading...</li>
                         </ul>
                     </div>
                 </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Fetch Audit Logs
    try {
        const response = await fetch('audit_logs.php');
        const result = await response.json();
        const list = document.getElementById('auditLogsList');
        
        if (result.status === 'success' && result.data.length > 0) {
            list.innerHTML = result.data.map(log => `
                <li class="py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                ${log.action.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                ${log.details}
                            </p>
                        </div>
                        <div class="inline-flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400">
                             <div>
                                <div>${new Date(log.created_at).toLocaleDateString()}</div>
                                <div class="text-right">${new Date(log.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                             </div>
                        </div>
                    </div>
                </li>
            `).join('');
        } else {
            list.innerHTML = '<li class="py-4 text-center text-gray-500">No activity logs found.</li>';
        }
    } catch (e) {
        console.error("Error fetching logs", e);
    }

    // Fetch user data
    try {
        const response = await fetch('profile.php');
        const result = await response.json();
        
        if (result.status === 'success') {
            document.getElementById('username').value = result.data.username;
            document.getElementById('email').value = result.data.email;
            document.getElementById('height').value = result.data.height || '';
        }
    } catch (error) {
        console.error('Error fetching profile:', error);
    }

    // 2FA Logic
    const statusSection = document.getElementById('2faStatusSection');
    const setupArea = document.getElementById('2faSetupArea');
    const verifyBtn = document.getElementById('verify2faBtn');

    async function check2FAStatus() {
        try {
            const res = await fetch('2fa_setup.php?action=status');
            const data = await res.json();
            
            if (data.status === 'success') {
                if (data.enabled) {
                    statusSection.innerHTML = `
                        <div class="flex items-center text-green-600 mb-4">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Two-Factor Authentication is ENABLED.
                        </div>
                        <button id="disable2faBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Disable 2FA
                        </button>
                    `;
                    setupArea.classList.add('hidden');
                    
                    document.getElementById('disable2faBtn').addEventListener('click', async () => {
                        const password = prompt("Enter your password to disable 2FA:");
                        if (!password) return;
                        
                        const res = await fetch('2fa_setup.php?action=disable', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({password})
                        });
                        const result = await res.json();
                        if (result.status === 'success') {
                            Toastify({ text: "2FA Disabled", backgroundColor: "#059669" }).showToast();
                            check2FAStatus();
                        } else {
                            Toastify({ text: result.message, backgroundColor: "#EF4444" }).showToast();
                        }
                    });
                } else {
                    statusSection.innerHTML = `
                        <p class="text-sm text-gray-500 mb-4">2FA is currently DISABLED.</p>
                        <button id="enable2faBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Enable 2FA
                        </button>
                    `;
                    
                    document.getElementById('enable2faBtn').addEventListener('click', async () => {
                        const res = await fetch('2fa_setup.php?action=generate');
                        const data = await res.json();
                        
                        if (data.status === 'success') {
                            document.getElementById('qrCodeImg').src = data.qr_code_url;
                            document.getElementById('secretKeyDisplay').innerText = data.secret;
                            setupArea.classList.remove('hidden');
                            statusSection.innerHTML = '<p class="text-sm text-gray-500">Scan the QR code below.</p>';
                        }
                    });
                }
            }
        } catch (e) {
            console.error(e);
        }
    }
    
    check2FAStatus();
    
    verifyBtn.addEventListener('click', async () => {
        const code = document.getElementById('verifyCodeInput').value;
        if (code.length < 6) return;
        
        verifyBtn.disabled = true;
        verifyBtn.innerText = 'Verifying...';
        
        try {
            const res = await fetch('2fa_setup.php?action=verify', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({code})
            });
            const result = await res.json();
            
            if (result.status === 'success') {
                Toastify({ text: "2FA Enabled Successfully!", backgroundColor: "#059669" }).showToast();
                check2FAStatus();
            } else {
                Toastify({ text: result.message, backgroundColor: "#EF4444" }).showToast();
                verifyBtn.disabled = false;
                verifyBtn.innerText = 'Verify & Enable';
            }
        } catch (e) {
            verifyBtn.disabled = false;
            verifyBtn.innerText = 'Verify & Enable';
        }
    });

    // Handle Form Submit
    document.getElementById('profileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerText;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Remove empty password fields if not changing
        if (!data.new_password) {
            delete data.current_password;
            delete data.new_password;
        }

        try {
            const response = await fetch('api/profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                Toastify({
                    text: result.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#059669",
                }).showToast();
                
                // Clear password fields
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
            } else {
                Toastify({
                    text: result.message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#EF4444",
                }).showToast();
            }
        } catch (error) {
            console.error('Error:', error);
            Toastify({
                text: "An error occurred.",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#EF4444",
            }).showToast();
        } finally {
            btn.disabled = false;
            btn.innerText = originalText;
        }
    });
});
</script>
</body>
</html>