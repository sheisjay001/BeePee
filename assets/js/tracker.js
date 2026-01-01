document.addEventListener('DOMContentLoaded', function() {
    // Set default date to today
    document.getElementById('date').valueAsDate = new Date();

    const form = document.getElementById('trackerForm');
    const tableBody = document.getElementById('logsTableBody');
    let chart;
    let userHeight = null;

    // Initialize Chart
    const ctx = document.getElementById('healthChart').getContext('2d');
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Systolic BP',
                    borderColor: '#EF4444', // Red
                    data: [],
                    tension: 0.1
                },
                {
                    label: 'Diastolic BP',
                    borderColor: '#F87171', // Light Red
                    data: [],
                    tension: 0.1
                },
                {
                    label: 'Blood Sugar',
                    borderColor: '#10B981', // Green
                    data: [],
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Blood Pressure (mmHg)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Blood Sugar (mg/dL)' },
                    grid: {
                        drawOnChartArea: false,
                    },
                },
            }
        }
    });

    // Load Data
    init();

    async function init() {
        await fetchProfile();
        await fetchLogs();
    }

    async function fetchProfile() {
        try {
            const response = await fetch('api/profile.php');
            const result = await response.json();
            if (result.status === 'success') {
                userHeight = result.data.height;
            }
        } catch (error) {
            console.error('Error fetching profile:', error);
        }
    }

    // Handle Form Submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerText;

        // Set Loading State
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch('api/tracker.php', {
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
                    text: "Log saved successfully!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#059669",
                }).showToast();
                
                form.reset();
                document.getElementById('date').valueAsDate = new Date();
                fetchLogs();
            } else {
                Toastify({
                    text: result.message || "Error saving log",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#EF4444",
                }).showToast();
            }
        } catch (error) {
            console.error('Error:', error);
            Toastify({
                text: "An error occurred while saving the log.",
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

    async function fetchLogs() {
        try {
            const response = await fetch('api/tracker.php');
            const result = await response.json();

            if (result.status === 'success') {
                const logs = result.data;
                const streak = result.streak || 0;
                updateChart(logs);
                updateTable(logs);
                updateSummary(logs, streak);
            }
        } catch (error) {
            console.error('Error fetching logs:', error);
        }
    }

    function updateSummary(logs, streak = 0) {
        if (!logs) return;

        // Streak
        const streakEl = document.getElementById('streakDisplay');
        if (streakEl) {
            streakEl.innerText = `${streak} day${streak !== 1 ? 's' : ''}`;
        }

        if (logs.length === 0) return;

        // Averages
        const validSys = logs.filter(l => l.systolic).map(l => parseInt(l.systolic));
        const avgSys = validSys.length ? Math.round(validSys.reduce((a, b) => a + b, 0) / validSys.length) : '--';
        const avgSysEl = document.getElementById('avgSystolic');
        if(avgSysEl) avgSysEl.innerText = avgSys + (avgSys !== '--' ? ' mmHg' : '');

        const validDia = logs.filter(l => l.diastolic).map(l => parseInt(l.diastolic));
        const avgDia = validDia.length ? Math.round(validDia.reduce((a, b) => a + b, 0) / validDia.length) : '--';
        const avgDiaEl = document.getElementById('avgDiastolic');
        if(avgDiaEl) avgDiaEl.innerText = avgDia + (avgDia !== '--' ? ' mmHg' : '');

        const validSugar = logs.filter(l => l.blood_sugar).map(l => parseFloat(l.blood_sugar));
        const avgSugar = validSugar.length ? Math.round(validSugar.reduce((a, b) => a + b, 0) / validSugar.length) : '--';
        const avgSugarEl = document.getElementById('avgSugar');
        if(avgSugarEl) avgSugarEl.innerText = avgSugar + (avgSugar !== '--' ? ' mg/dL' : '');

        // Latest Weight
        const sortedByDate = [...logs].sort((a, b) => new Date(b.log_date) - new Date(a.log_date));
        const latestWeightLog = sortedByDate.find(l => l.weight);
        const latestWeight = latestWeightLog ? parseFloat(latestWeightLog.weight) : null;
        
        const latestWeightEl = document.getElementById('latestWeight');
        if(latestWeightEl) latestWeightEl.innerText = latestWeight ? latestWeight + ' kg' : '--';

        // BMI
        const bmiDisplayEl = document.getElementById('bmiDisplay');
        const bmiCategoryEl = document.getElementById('bmiCategory');
        
        if (bmiDisplayEl && bmiCategoryEl) {
            if (latestWeight && userHeight) {
                const heightM = userHeight / 100;
                const bmi = (latestWeight / (heightM * heightM)).toFixed(1);
                bmiDisplayEl.innerText = bmi;
                
                let category = '';
                let color = '';
                if (bmi < 18.5) { category = 'Underweight'; color = 'text-blue-600'; }
                else if (bmi < 25) { category = 'Normal weight'; color = 'text-green-600'; }
                else if (bmi < 30) { category = 'Overweight'; color = 'text-yellow-600'; }
                else { category = 'Obese'; color = 'text-red-600'; }
                
                bmiCategoryEl.innerText = category;
                bmiCategoryEl.className = `text-xs font-medium ${color}`;
            } else if (!userHeight) {
                bmiDisplayEl.innerText = '--';
                bmiCategoryEl.innerHTML = '<a href="/api/profile_ui.php" class="text-primary hover:underline">Set height</a>';
            } else {
                bmiDisplayEl.innerText = '--';
                bmiCategoryEl.innerText = 'Add weight log';
            }
        }
    }

    function updateTable(logs) {
        tableBody.innerHTML = '';
        
        if (logs.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="h-16 w-16 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No logs found</h3>
                            <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">It looks like you haven't tracked your health data yet. Start your journey by adding your first log above!</p>
                            <button onclick="document.getElementById('systolic').focus()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-105">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add First Log
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        // Sort logs descending for table
        const sortedLogs = [...logs].sort((a, b) => new Date(b.log_date) - new Date(a.log_date));

        sortedLogs.forEach(log => {
            const row = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.log_date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.systolic ? log.systolic + '/' + log.diastolic : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.blood_sugar || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.weight || '-'}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    function updateChart(logs) {
        // Sort logs ascending for chart
        const sortedLogs = [...logs].sort((a, b) => new Date(a.log_date) - new Date(b.log_date));

        chart.data.labels = sortedLogs.map(l => l.log_date);
        chart.data.datasets[0].data = sortedLogs.map(l => l.systolic);
        chart.data.datasets[1].data = sortedLogs.map(l => l.diastolic);
        chart.data.datasets[2].data = sortedLogs.map(l => l.blood_sugar);
        chart.update();
    }
});