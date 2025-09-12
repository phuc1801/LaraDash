document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('warehouseStatusChart').getContext('2d');
    if (!ctx) return;

    // Tạo gradient cho màu sắc vòng tròn
    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, '#055a04ff'); // xanh lá
    gradient.addColorStop(1, '#00cfff'); // xanh dương

    // Fetch dữ liệu từ API warehouse-status
    fetch('/api/warehouse-status')
        .then(res => res.json())
        .then(res => {
            if (!res.status) return;

            const dataValue = parseFloat(res.data.percentUsed);
            const remaining = 100 - dataValue;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Used Space', 'Free Space'],
                    datasets: [{
                        data: [dataValue, remaining],
                        backgroundColor: [gradient, '#e0e0e0'],
                        borderWidth: 0,
                        cutout: '80%' // tạo vòng tròn mỏng
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },
                        datalabels: {
                            display: true,
                            formatter: (value, context) => {
                                if(context.dataIndex === 0) return `${dataValue}%`;
                                return '';
                            },
                            color: '#ffffff',
                            font: { size: 20, weight: 'bold' },
                            anchor: 'center',
                            align: 'center'
                        }
                    }
                },
                plugins: [ChartDataLabels] // cần include chartjs-plugin-datalabels
            });
        })
        .catch(err => console.error('Error fetching warehouse status:', err));
});
