document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('chartDoanhThu');
    if (!ctx) return;

    const year = new Date().getFullYear(); // lấy năm hiện tại tự động

    fetch(`http://127.0.0.1:8000/api/orders/revenue-by-month?year=${year}`)
        .then(res => res.json())
        .then(data => {
            if (!data.status) throw new Error('API error');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: `Doanh thu ${year} (VNĐ)`,
                        data: data.values,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.2)', // màu #6366f1 với alpha
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#6366f1',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (context) => context.parsed.y.toLocaleString('vi-VN') + ' ₫'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => value.toLocaleString('vi-VN') + ' ₫'
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('❌ Error loading revenue chart:', err));
});
