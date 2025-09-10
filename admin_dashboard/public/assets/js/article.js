function articlesManager() {
  return {
    articles: [],
    selectedArticles: [],
    currentPage: 1,
    itemsPerPage: 5,
    modalTitle: '',
    modalContent: '',
    modalImage: '',
    editArticleData: {},

    fetchArticles() {
      fetch('http://127.0.0.1:8000/api/articles')
        .then(res => res.json())
        .then(data => this.articles = data)
        .catch(err => console.error(err));
    },

    get filteredArticles() {
      return this.articles;
    },

    get paginatedArticles() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredArticles.slice(start, start + this.itemsPerPage);
    },

    get totalPages() {
      return Math.ceil(this.filteredArticles.length / this.itemsPerPage);
    },

    goToPage(page) {
      if (page < 1 || page > this.totalPages) return;
      this.currentPage = page;
    },

    toggleAll(checked) {
      if (checked) {
        this.selectedArticles = this.paginatedArticles.map(a => a.id);
      } else {
        this.selectedArticles = [];
      }
    },

    viewArticle(article) {
        fetch(`http://127.0.0.1:8000/api/articles/${article.id}`)
        .then(res => res.json())
        .then(data => {
            this.modalTitle = data.title || '';
            this.modalContent = data.content || '';
            this.modalImage = (data.images && data.images.length > 0) 
                ? 'http://127.0.0.1:8000/assets/img/articles/' + data.images[0].image 
                : '';

            // Alpine bind xong mới show modal
            setTimeout(() => {
                const modalEl = document.getElementById('viewArticleModal');
                if (modalEl) new bootstrap.Modal(modalEl).show();
            }, 0);
        })
        .catch(err => console.error(err));
    },






    editArticle(article) {
      this.editArticleData = {...article}; // copy dữ liệu
      new bootstrap.Modal(document.getElementById('editArticleModal')).show();
    },

    saveEdit() {
      const index = this.articles.findIndex(a => a.id === this.editArticleData.id);
      if (index !== -1) this.articles[index] = {...this.editArticleData};
      bootstrap.Modal.getInstance(document.getElementById('editArticleModal')).hide();
    },

    deleteArticle(article) {
        if (!confirm(`Bạn có chắc muốn xóa bài "${article.title}" không?`)) return;

        fetch(`http://127.0.0.1:8000/api/articles/${article.id}`, {
            method: 'DELETE',
            headers: {
            'Content-Type': 'application/json',
            // Nếu API cần auth token, thêm ở đây
            // 'Authorization': 'Bearer YOUR_TOKEN'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Xóa thất bại');
            // Xóa khỏi mảng local
            this.articles = this.articles.filter(a => a.id !== article.id);
            alert('Xóa bài viết thành công!');
        })
        .catch(err => {
            console.error(err);
            alert('Đã xảy ra lỗi khi xóa bài viết.');
        });
    }

  }
}

document.addEventListener('DOMContentLoaded', () => {
    const postForm = document.getElementById('createPostForm');
    const imagesInput = document.getElementById('images');
    const previewContainer = document.getElementById('previewImages');

    // Preview ảnh khi chọn file
    imagesInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                img.classList.add('border', 'rounded', 'me-2', 'mb-2');
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // Submit form tạo bài viết
    postForm.addEventListener('submit', async e => {
        e.preventDefault();

        const formData = new FormData(postForm);

        // Append file thật
        Array.from(imagesInput.files).forEach(file => {
            formData.append('images[]', file);
        });

        try {
            const res = await axios.post('/api/articles', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });

            alert('Tạo bài viết thành công!');
            postForm.reset();
            previewContainer.innerHTML = '';

            // Ẩn modal
            const modalEl = document.getElementById('postModal');
            bootstrap.Modal.getInstance(modalEl)?.hide();

            // Tự động fetch lại danh sách bài viết từ Alpine component
            const articlesComponent = document.querySelector('[x-data="articlesManager()"]');
            if (articlesComponent && articlesComponent.__x) {
                articlesComponent.__x.getUnobservedData().fetchArticles();
            }
            window.location.href = '/article';

        } catch (err) {
            console.error(err.response?.data || err);
            alert('Tạo bài viết thất bại! Xem console để biết chi tiết.');
        }
    });
});
