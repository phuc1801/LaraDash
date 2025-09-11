document.addEventListener('DOMContentLoaded', () => {
    const feedContainer = document.querySelector('.activity-feed');
    if (!feedContainer) return;

    // Fetch 5 thông báo mới nhất từ API
    fetch('http://127.0.0.1:8000/api/notifications/latest')
        .then(res => res.json())
        .then(data => {
            if (!data.status || !data.notifications) return;

            // Xóa nội dung cũ
            feedContainer.innerHTML = '';

            // Tạo HTML cho mỗi notification
            data.notifications.slice(0, 5).forEach(notif => {
                const iconClass = getIconClass(notif.type);
                const timeAgo = timeSince(new Date(notif.created_at));

                const item = document.createElement('div');
                item.classList.add('activity-item');

                item.innerHTML = `
                    <div class="activity-icon ${iconClass.bg} ${iconClass.text}">
                        <i class="${iconClass.icon}"></i>
                    </div>
                    <div class="activity-content">
                        <p class="mb-1"><a href="${notif.link}" class="text-decoration-none">${notif.message}</a></p>
                        <small class="text-muted">${timeAgo}</small>
                    </div>
                `;

                feedContainer.appendChild(item);
            });
        })
        .catch(err => console.error('❌ Error loading notifications:', err));

    // Helper: map type -> icon & color
    function getIconClass(type) {
        switch(type) {
            case 'order': return { icon: 'bi bi-bag-check', bg: 'bg-success bg-opacity-10', text: 'text-success' };
            case 'promotion': return { icon: 'bi bi-megaphone', bg: 'bg-warning bg-opacity-10', text: 'text-warning' };
            case 'account': return { icon: 'bi bi-person-check', bg: 'bg-primary bg-opacity-10', text: 'text-primary' };
            case 'product': return { icon: 'bi bi-box', bg: 'bg-info bg-opacity-10', text: 'text-info' };
            case 'cart': return { icon: 'bi bi-cart', bg: 'bg-danger bg-opacity-10', text: 'text-danger' };
            default: return { icon: 'bi bi-info-circle', bg: 'bg-secondary bg-opacity-10', text: 'text-secondary' };
        }
    }

    // Helper: hiển thị "x phút trước", "x giờ trước"
    function timeSince(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " giờ trước";
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " phút trước";
        return Math.floor(seconds) + " giây trước";
    }
});
