document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('orderStatusBarChart');
    if (!ctx) return;

    fetch('http://127.0.0.1:8000/api/orders/status-frequency')
        .then(res => res.json())
        .then(data => {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Số lượng đơn hàng',
                        data: data.values,
                        backgroundColor: [
                            '#f59e0b', // Pending
                            '#3b82f6', // Processing
                            '#6366f1', // Shipping
                            '#16a34a', // Completed
                            '#ef4444'  // Cancelled
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                font: { size: 14, weight: 'bold' },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const label = context.label;
                                    const total = context.chart._metasets[0].total;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${percentage}%`;
                            },
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels] // Đảm bảo bạn đã include plugin ChartDataLabels
            });
        })
        .catch(err => console.error('❌ Lỗi load status chart:', err));
});
