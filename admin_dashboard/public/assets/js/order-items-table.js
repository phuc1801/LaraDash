function ordersTable() {
    return {
        orders: [],
        selectedOrders: [],
        filteredOrders: [],
        paginatedOrders: [],

        currentPage: 1,
        itemsPerPage: 10,
        totalPages: 1,
        visiblePages: [],

        async fetchOrders() {
            try {
                const res = await fetch("http://127.0.0.1:8000/api/order-items");
                const json = await res.json();

                if (json.status) {
                    const grouped = {};
                    json.data.forEach(item => {
                        const orderId = item.order.id;
                        if (!grouped[orderId]) {
                            grouped[orderId] = {
                                id: item.order.id,
                                orderNumber: "ORD-" + item.order.id,
                                customer: {
                                    name: item.order.user.name,
                                    email: item.order.user.email,
                                    avatar: item.order.user.avatar
                                },
                                items: [],
                                itemCount: 0,
                                total: item.order.total_price,
                                status: item.order.status,
                                orderDate: new Date(item.order.created_at).toLocaleDateString()
                            };
                        }
                        grouped[orderId].items.push({
                            id: item.product.id,
                            name: item.product.name,
                            quantity: item.quantity
                        });
                        grouped[orderId].itemCount++;
                    });

                    this.orders = Object.values(grouped);
                    this.filteredOrders = this.orders;

                    this.totalPages = Math.ceil(this.filteredOrders.length / this.itemsPerPage);
                    this.goToPage(1);
                }
            } catch (error) {
                console.error("Fetch error:", error);
            }
        },

        goToPage(page) {
            if (page < 1 || page > this.totalPages) return;
            this.currentPage = page;

            const start = (page - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            this.paginatedOrders = this.filteredOrders.slice(start, end);

            this.updateVisiblePages();
        },

        updateVisiblePages() {
            // Luôn hiển thị cặp 2 trang liên tiếp
            const startPage = this.currentPage % 2 === 0 ? this.currentPage - 1 : this.currentPage;
            const pages = [];
            if (startPage <= this.totalPages) pages.push(startPage);
            if (startPage + 1 <= this.totalPages) pages.push(startPage + 1);
            this.visiblePages = pages;
        },

        toggleAll(checked) {
            this.selectedOrders = checked ? this.filteredOrders.map(o => o.id) : [];
        },

        sortBy(field) {
            this.filteredOrders.sort((a, b) => a[field] > b[field] ? 1 : -1);
            this.goToPage(1);
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(value);
        },

        statusClass(status) {
            return {
                'bg-warning text-dark': status === 'pending',
                'bg-info text-dark': status === 'processing',
                'bg-primary': status === 'shipped',
                'bg-success': status === 'delivered' || status === 'completed',
                'bg-danger': status === 'cancelled'
            };
        },

        capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },

        // Mock action
        viewOrder(order) {
            // Nếu modal đã có thì xóa đi trước
            const old = document.getElementById("customModal");
            if (old) old.remove();

            // Tạo overlay
            const modal = document.createElement("div");
            modal.id = "customModal";
            modal.style.position = "fixed";
            modal.style.top = "0";
            modal.style.left = "0";
            modal.style.width = "100%";
            modal.style.height = "100%";
            modal.style.background = "rgba(0,0,0,0.5)";
            modal.style.display = "flex";
            modal.style.alignItems = "center";
            modal.style.justifyContent = "center";
            modal.style.zIndex = "9999";

            // Nội dung modal
            modal.innerHTML = `
                <div style="background:#fff; padding:20px; border-radius:10px; width:500px; max-width:90%;">
                <h3>Chi tiết đơn hàng</h3>
                <p><b>Mã đơn hàng:</b> ${order.orderNumber}</p>
                <p><b>Khách hàng:</b> ${order.customer.name} (${order.customer.email})</p>
                <p><b>Ngày mua:</b> ${order.orderDate}</p>
                <p><b>Trạng thái:</b> ${this.capitalize(order.status)}</p>
                <p><b>Tổng giá:</b> ${this.formatCurrency(order.total)}</p>

                <button id="closeModalBtn" style="margin-top:10px; padding:6px 12px; background:#dc3545; color:#fff; border:none; border-radius:5px; cursor:pointer;">
                    Đóng
                </button>
                </div>
            `;

            // Đóng khi bấm nút
            modal.querySelector("#closeModalBtn").addEventListener("click", () => modal.remove());
            // Đóng khi bấm nền ngoài
            modal.addEventListener("click", e => {
                if (e.target.id === "customModal") modal.remove();
            });

            document.body.appendChild(modal);
            },

        trackOrder(order) { alert("Theo dõi đơn: " + order.orderNumber); },
        printInvoice(order) {
        // Tạo nội dung HTML cho hóa đơn
        const invoice = `
                <div style="font-family: Arial, sans-serif; padding: 20px; width: 700px;">
                    <h2 style="text-align:center;">HÓA ĐƠN</h2>
                    <p><b>Mã đơn hàng:</b> ${order.orderNumber}</p>
                    <p><b>Khách hàng:</b> ${order.customer.name} (${order.customer.email})</p>
                    <p><b>Ngày:</b> ${order.orderDate}</p>

                    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
                        <thead>
                            <tr>
                                <th style="border:1px solid #ccc; padding:8px; text-align:left;">Sản phẩm</th>
                                <th style="border:1px solid #ccc; padding:8px; text-align:center;">Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${order.items.map(item => `
                                <tr>
                                    <td style="border:1px solid #ccc; padding:8px;">${item.name}</td>
                                    <td style="border:1px solid #ccc; padding:8px; text-align:center;">${item.quantity}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>

                    <h3 style="text-align:right; margin-top:20px;">
                        Tổng cộng: ${this.formatCurrency(order.total)}
                    </h3>
                </div>
            `;

            // Chuyển thành element DOM để html2pdf xử lý
            const element = document.createElement("div");
            element.innerHTML = invoice;

            // Xuất PDF
            html2pdf().from(element).set({
                margin: 10,
                filename: `Invoice-${order.orderNumber}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            }).save();
        },

        cancelOrder(order) {
            // Nếu modal đã có thì xóa đi trước
            const old = document.getElementById("customModal");
            if (old) old.remove();

            // Tạo overlay
            const modal = document.createElement("div");
            modal.id = "customModal";
            modal.style.position = "fixed";
            modal.style.top = "0";
            modal.style.left = "0";
            modal.style.width = "100%";
            modal.style.height = "100%";
            modal.style.background = "rgba(0,0,0,0.5)";
            modal.style.display = "flex";
            modal.style.alignItems = "center";
            modal.style.justifyContent = "center";
            modal.style.zIndex = "9999";

            // Nội dung modal
            modal.innerHTML = `
                <div style="background:#fff; padding:20px; border-radius:10px; width:400px; max-width:90%;">
                <h3>Xác nhận hủy đơn</h3>
                <p>Bạn có chắc chắn muốn hủy <b>đơn hàng #${order.orderNumber}</b> không?</p>

                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:15px;">
                    <button id="cancelBtn" style="padding:6px 12px; background:#6c757d; color:#fff; border:none; border-radius:5px; cursor:pointer;">
                    Thoát
                    </button>
                    <button id="confirmCancelBtn" style="padding:6px 12px; background:#dc3545; color:#fff; border:none; border-radius:5px; cursor:pointer;">
                    Xác nhận
                    </button>
                </div>
                </div>
            `;

            // Thoát
            modal.querySelector("#cancelBtn").addEventListener("click", () => modal.remove());
            // Xác nhận
            modal.querySelector("#confirmCancelBtn").addEventListener("click", () => {
                alert("Đơn hàng #" + order.orderNumber + " đã bị hủy!");
                modal.remove();

                // 👉 Ở đây bạn có thể gọi API để hủy đơn thật
                fetch(`/api/orders/${order.id}/cancel`, { method: "POST" })
            });

            // Đóng khi bấm nền ngoài
            modal.addEventListener("click", e => {
                if (e.target.id === "customModal") modal.remove();
            });

            document.body.appendChild(modal);
            }

    }
}


