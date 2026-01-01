document.addEventListener('DOMContentLoaded', function() {
    // Set default date to today
    document.getElementById('date').valueAsDate = new Date();

    const form = document.getElementById('trackerForm');
    const tableBody = document.getElementById('logsTableBody');
    let chart;

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
    fetchLogs();

    // Handle Form Submit
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
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
        }
    });

    async function fetchLogs() {
        try {
            const response = await fetch('api/tracker.php');
            const result = await response.json();

            if (result.status === 'success') {
                const logs = result.data;
                
                // Update Chart
                chart.data.labels = logs.map(log => log.log_date);
                chart.data.datasets[0].data = logs.map(log => log.systolic);
                chart.data.datasets[1].data = logs.map(log => log.diastolic);
                chart.data.datasets[2].data = logs.map(log => log.blood_sugar);
                chart.update();

                // Update Table (Reverse order for table: newest first)
                tableBody.innerHTML = '';
                [...logs].reverse().forEach(log => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.log_date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.systolic ? log.systolic + '/' + log.diastolic : '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.blood_sugar || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.weight || '-'}</td>
                    `;
                    tableBody.appendChild(row);
                });

                // Calculate Summary Stats
                calculateStats(logs);

            } else {
                console.error('Failed to fetch logs:', result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function calculateStats(logs) {
        if (!logs || logs.length === 0) {
            document.getElementById('avgSystolic').textContent = '--';
            document.getElementById('avgDiastolic').textContent = '--';
            document.getElementById('avgSugar').textContent = '--';
            document.getElementById('latestWeight').textContent = '--';
            return;
        }

        const validSystolic = logs.filter(l => l.systolic).map(l => parseFloat(l.systolic));
        const validDiastolic = logs.filter(l => l.diastolic).map(l => parseFloat(l.diastolic));
        const validSugar = logs.filter(l => l.blood_sugar).map(l => parseFloat(l.blood_sugar));
        const validWeight = logs.filter(l => l.weight).map(l => parseFloat(l.weight));

        const avgSys = validSystolic.length ? Math.round(validSystolic.reduce((a, b) => a + b, 0) / validSystolic.length) : '--';
        const avgDia = validDiastolic.length ? Math.round(validDiastolic.reduce((a, b) => a + b, 0) / validDiastolic.length) : '--';
        const avgSug = validSugar.length ? Math.round(validSugar.reduce((a, b) => a + b, 0) / validSugar.length) : '--';
        
        // Latest weight is the last entry in the sorted (oldest -> newest) logs array that has a weight
        let latestW = '--';
        for (let i = logs.length - 1; i >= 0; i--) {
            if (logs[i].weight) {
                latestW = logs[i].weight;
                break;
            }
        }

        document.getElementById('avgSystolic').textContent = avgSys;
        document.getElementById('avgDiastolic').textContent = avgDia;
        document.getElementById('avgSugar').textContent = avgSug;
        document.getElementById('latestWeight').textContent = latestW;
    }
});
