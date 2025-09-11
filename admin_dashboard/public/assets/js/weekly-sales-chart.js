document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('weeklySalesChart').getContext('2d');
    if (!ctx) return;

    // Lấy dữ liệu thật từ API
    fetch('http://127.0.0.1:8000/api/orders/weekly-growth')
        .then(response => response.json())
        .then(data => {
            if (!data.status) return;

            new Chart(ctx, {
                type: 'bar', // biểu đồ cột
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data.values,
                        backgroundColor: '#6366f1',
                        borderColor: '#4f46e5',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
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
        .catch(err => console.error('Lỗi khi lấy dữ liệu biểu đồ:', err));
});
