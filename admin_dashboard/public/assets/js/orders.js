function statsCard() {
    return {
        total: 0,
        completed: 0,
        pending: 0,
        async init() {
            try {
                const response = await fetch('/api/orders/revenue');
                const data = await response.json();
                
                if (data.status) {
                    // Chuyển sang định dạng VNĐ
                    this.total = Number(data.data.total).toLocaleString('vi-VN') + 'VNĐ';
                    this.completed = Number(data.data.completed).toLocaleString('vi-VN') + 'VNĐ';
                    this.pending = Number(data.data.pending).toLocaleString('vi-VN') + 'VNĐ';
                }
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        }
    }
}
