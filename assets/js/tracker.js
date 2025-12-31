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

        try {
            const response = await fetch('api/tracker.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                alert('Log saved successfully!');
                form.reset();
                document.getElementById('date').valueAsDate = new Date();
                fetchLogs();
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while saving the log.');
        }
    });

    async function fetchLogs() {
        try {
            const response = await fetch('api/tracker.php');
            const result = await response.json();

            if (result.status === 'success') {
                updateUI(result.data);
            }
        } catch (error) {
            console.error('Error fetching logs:', error);
        }
    }

    function updateUI(logs) {
        // Update Table
        tableBody.innerHTML = '';
        logs.forEach(log => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.log_date}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.systolic || '-'} / ${log.diastolic || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.blood_sugar || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.weight || '-'}</td>
            `;
            tableBody.appendChild(row);
        });

        // Update Chart
        const labels = logs.map(log => log.log_date);
        const systolicData = logs.map(log => log.systolic);
        const diastolicData = logs.map(log => log.diastolic);
        const sugarData = logs.map(log => log.blood_sugar);

        chart.data.labels = labels;
        chart.data.datasets[0].data = systolicData;
        chart.data.datasets[1].data = diastolicData;
        chart.data.datasets[2].data = sugarData;
        chart.update();
    }
});
