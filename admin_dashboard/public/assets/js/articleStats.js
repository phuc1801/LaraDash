document.addEventListener('alpine:init', () => {
    Alpine.data('productStats', () => ({
        stats: {
            total: 0,
            inStock: 0,
            outOfStock: 0,
            totalValue: 0 // giả sử là tổng lượt xem
        },

        init() {
            fetch('/api/articles-statistics') // đổi đường dẫn API nếu bạn cần
                .then(response => response.json())
                .then(data => {
                    this.stats.total = data.total || 0;
                    this.stats.inStock = data.published || 0;
                    this.stats.outOfStock = data.unpublished || 0;
                    this.stats.totalValue = data.totalValue || 0; // nếu API trả lượt xem
                })
                .catch(err => console.error('Lỗi khi lấy dữ liệu thống kê:', err));
        }
    }));
});
