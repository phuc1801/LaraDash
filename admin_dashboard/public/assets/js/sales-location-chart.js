document.addEventListener('DOMContentLoaded', () => { 
    const chartDom = document.getElementById('salesLocationChart');
    const salesLocationChart = echarts.init(chartDom);

    // Gọi API để lấy dữ liệu
    fetch('http://localhost:8000/api/orders/region')
        .then(response => response.json())
        .then(data => {
            if (!data.status) {
                console.error('API trả về lỗi');
                return;
            }

            const labels = data.labels;
            const orderCounts = data.order_counts;
            const revenues = data.revenues;

            const colors = ['#8c9db5', '#c1c9b5', '#d3d9b8', '#bcc9b0', '#97a3b5'];

            // Tạo data cho treemap
            const treemapData = labels.map((label, i) => ({
                name: label,
                value: revenues[i], // dùng doanh thu làm kích thước ô
                itemStyle: { color: colors[i % colors.length] }
            }));

            const option = {
                animation: false,
                title: {
                    text: 'Bán hàng theo khu vực',
                    left: 'center',
                    textStyle: { 
                        fontSize: 18, 
                        fontWeight: 'bold', 
                        fontFamily: "Arial, 'Segoe UI', sans-serif" 
                    }
                },
                tooltip: {
                    formatter: function(info) {
                        const idx = labels.indexOf(info.name);
                        const orders = orderCounts[idx];
                        const revenue = revenues[idx];
                        return `${info.name}<br/>Đơn hàng: ${orders}<br/>Doanh thu: ${revenue.toLocaleString()}₫`;
                    }
                },
                series: [
                    {
                        type: 'treemap',
                        nodeClick: false,
                        roam: false,
                        breadcrumb: { show: false },
                        label: {
                            show: true,
                            formatter: '{b}\n{c}',
                            color: '#fff',
                            fontSize: 14,
                            fontWeight: 'bold',
                            fontFamily: "Arial, 'Segoe UI', sans-serif",
                            align: 'center',
                            verticalAlign: 'middle'
                        },
                        itemStyle: {
                            borderColor: '#fff',
                            borderWidth: 2
                        },
                        data: treemapData
                    }
                ]
            };

            salesLocationChart.setOption(option);
        })
        .catch(err => {
            console.error('Lỗi khi gọi API:', err);
        });
});
