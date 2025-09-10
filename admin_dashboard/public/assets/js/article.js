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