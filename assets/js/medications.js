document.addEventListener('DOMContentLoaded', function() {
    fetchMedications();
    
    // Check for reminders every minute
    setInterval(checkReminders, 60000);
    
    // Request permission on load if not denied
    if (Notification.permission !== 'denied' && Notification.permission !== 'granted') {
        // Don't force it, let user click button
    }
});

let medications = [];

async function fetchMedications() {
    try {
        const response = await fetch('api/medications.php');
        const result = await response.json();
        
        if (result.status === 'success') {
            medications = result.data;
            renderMedications();
        }
    } catch (error) {
        console.error('Error fetching medications:', error);
    }
}

function renderMedications() {
    const list = document.getElementById('medicationList');
    if (!list) return;
    
    list.innerHTML = '';
    
    if (medications.length === 0) {
        list.innerHTML = `
            <li class="px-4 py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No medications</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding your daily medications.</p>
            </li>
        `;
        return;
    }
    
    medications.forEach(med => {
        const isTaken = med.taken_today > 0;
        const timeDisplay = med.schedule_time ? new Date('1970-01-01T' + med.schedule_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : 'Anytime';
        
        const item = `
            <li class="bg-white hover:bg-gray-50 transition-colors duration-150">
                <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            <button onclick="toggleTaken(${med.id})" class="h-10 w-10 rounded-full flex items-center justify-center border-2 ${isTaken ? 'bg-green-100 border-green-500 text-green-600' : 'border-gray-300 text-gray-400 hover:border-primary hover:text-primary'} transition-colors duration-200">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <div class="flex items-center">
                                <p class="text-lg font-medium text-primary truncate">${med.name}</p>
                                ${med.dosage ? `<span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${med.dosage}</span>` : ''}
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Scheduled: <span class="font-medium text-gray-900">${timeDisplay}</span></p>
                                ${isTaken ? '<span class="ml-2 text-green-600 font-medium">Taken Today</span>' : ''}
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex items-center space-x-4">
                        <button onclick="deleteMedication(${med.id})" class="text-red-400 hover:text-red-600 p-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </li>
        `;
        list.innerHTML += item;
    });
}

async function addMedication() {
    const name = document.getElementById('medName').value;
    const dosage = document.getElementById('medDosage').value;
    const time = document.getElementById('medTime').value;
    
    if (!name) {
        Toastify({ text: "Please enter a medication name", backgroundColor: "#EF4444" }).showToast();
        return;
    }
    
    try {
        const response = await fetch('api/medications.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, dosage, time })
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            document.getElementById('addMedModal').classList.add('hidden');
            document.getElementById('medName').value = '';
            document.getElementById('medDosage').value = '';
            fetchMedications();
            Toastify({ text: "Medication added!", backgroundColor: "#059669" }).showToast();
        } else {
            Toastify({ text: result.message, backgroundColor: "#EF4444" }).showToast();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function toggleTaken(id) {
    try {
        const response = await fetch('api/medications.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'toggle', id })
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            fetchMedications();
            // Confetti effect could go here
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteMedication(id) {
    if (!confirm('Are you sure you want to delete this medication?')) return;
    
    try {
        const response = await fetch('api/medications.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            fetchMedications();
            Toastify({ text: "Medication deleted", backgroundColor: "#3B82F6" }).showToast();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function requestNotificationPermission() {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            Toastify({ text: "Notifications enabled!", backgroundColor: "#059669" }).showToast();
            new Notification("BeePee Reminders", { body: "We'll remind you to take your meds!" });
        }
    });
}

function checkReminders() {
    if (Notification.permission !== 'granted') return;
    
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    
    medications.forEach(med => {
        if (med.schedule_time && !med.taken_today) {
            const [schedHour, schedMinute] = med.schedule_time.split(':').map(Number);
            
            // Check if it's time (within last minute)
            if (currentHour === schedHour && currentMinute === schedMinute) {
                new Notification(`Time to take ${med.name}`, {
                    body: `Don't forget your ${med.dosage || ''} dose!`,
                    icon: '/assets/icon.png' // Placeholder
                });
            }
        }
    });
}