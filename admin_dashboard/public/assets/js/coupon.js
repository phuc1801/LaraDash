function couponCategory() {
    return {
        visibleTypes: [],
        counts: {},

        // Hàm khởi tạo: gọi API khi component load
        init() {
            fetch('http://127.0.0.1:8000/api/coupons/count')
                .then(res => res.json())
                .then(data => {
                    this.counts['event'] = data.total_coupons || 0;
                })
                .catch(err => {
                    console.error('Lỗi khi lấy số lượng coupon:', err);
                    this.counts['event'] = 0;
                });
        },

        getCount(type) {
            return this.counts[type] || 0;
        }
    }
}


function couponSidebar() {
    return {
        upcomingCoupons: [],
        loadCoupons() {
            fetch('http://127.0.0.1:8000/api/coupons')
                .then(res => res.json())
                .then(data => {
                    this.upcomingCoupons = data.data;
                })
                .catch(err => console.error(err));
        },
        formatPrice(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        },
        viewCoupon(coupon) {
            alert(`Coupon: ${coupon.name}\nMã: ${coupon.code}\nGiảm: ${this.formatPrice(coupon.value)}\nSản phẩm: ${coupon.product.name}`);
        }
    }
}
