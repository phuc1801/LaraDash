document.addEventListener("alpine:init", () => {
    Alpine.data("userStats", () => ({
        value: 0,
        async loadUserCount() {
            console.log("🔄 Fetching user count...");
            try {
                const res = await fetch("http://127.0.0.1:8000/api/users/count");
                const data = await res.json();
                this.value = data.total_users;
                console.log("✅ Data loaded:", this.value);
            } catch (err) {
                console.error("❌ Error loading user count:", err);
            }
        }
    }));
});


document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('latest-orders-table'); // id mới
    if (!tableBody) return;

    fetch('http://127.0.0.1:8000/api/orders/latest')
        .then(res => res.json())
        .then(data => {
            if (!data.status) return;

            tableBody.innerHTML = ''; // Xóa dữ liệu cũ nếu có

            data.orders.forEach(order => {
                const row = document.createElement('tr');

                // Format ngày
                const createdAt = new Date(order.created_at);
                const formattedDate = createdAt.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                row.innerHTML = `
                    <td>#${order.id}</td>
                    <td>${order.user?.name || 'Khách lẻ'}</td>
                    <td>${Number(order.total_price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}</td>
                    <td>
                        <span class="badge ${
                            order.status === 'pending' ? 'bg-warning' :
                            order.status === 'processing' ? 'bg-primary' :
                            order.status === 'shipping' ? 'bg-info' :
                            order.status === 'completed' ? 'bg-success' :
                            'bg-danger'
                        }">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
                    </td>
                    <td>${formattedDate}</td>
                `;

                tableBody.appendChild(row);
            });
        })
        .catch(err => console.error('❌ Lỗi load latest orders:', err));
});
