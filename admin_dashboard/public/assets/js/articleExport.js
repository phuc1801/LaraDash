document.addEventListener('alpine:init', () => {
    Alpine.data('exportPostsComponent', () => ({
        posts: [],

        init() {
            // Lấy dữ liệu từ API
            axios.get('http://127.0.0.1:8000/api/articles')
                .then(res => { this.posts = res.data; })
                .catch(err => console.error(err));
        },

        exportPosts() {
            if (!this.posts.length) {
                alert('Không có dữ liệu để xuất Excel!');
                return;
            }

            // Chuẩn bị dữ liệu
            const data = this.posts.map(p => ({
                ID: p.id,
                Tiêu_đề: p.title,
                Tác_giả: p.author_name,
                Trạng_thái: p.type === 1 ? 'Đã xuất bản' : 'Nháp',
                Ngày_tạo: new Date(p.created_at).toLocaleString(),
                Số_ảnh: p.images.length
            }));

            // Tạo workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, 'Bài viết');

            // Xuất file Excel
            XLSX.writeFile(wb, 'articles.xlsx');
        }
    }));
});
