document.addEventListener("alpine:init", () => {
    Alpine.data("productStats", () => ({
        // --- Stats ---
        stats: {
            total: 0,
            inStock: 0,
            outOfStock: 0,
            lowStock: 0,
            totalValue: 0,
        },

        // --- Products ---
        products: [],
        filteredProducts: [],
        paginatedProducts: [],
        selectedProducts: [],

        // --- Pagination ---
        currentPage: 1,
        itemsPerPage: 10,

        // --- Filters ---
        searchQuery: "",
        categoryFilter: "",
        stockFilter: "",

        // --- Fetch Stats ---
        async fetchStats() {
            try {
                const res = await fetch("http://127.0.0.1:8000/api/products/stats");
                const data = await res.json();
                if (data.status && data.data) {
                    this.stats.total = data.data.total_products;
                    this.stats.inStock = data.data.in_stock;
                    this.stats.outOfStock = data.data.out_of_stock;
                    this.stats.lowStock = data.data.low_stock ?? 0;
                    this.stats.totalValue = Number(data.data.inventory_value);
                }
            } catch (e) {
                console.error("Lỗi khi gọi API stats:", e);
            }
        },

        // --- Fetch Products ---
        async fetchProducts() {
            try {
                const res = await fetch("http://127.0.0.1:8000/api/products");
                const data = await res.json();
                if (data.status && Array.isArray(data.data)) {
                    this.products = data.data.map(p => ({
                        id: p.id,
                        name: p.name,
                        sku: p.slug ?? `SKU-${p.id}`,
                        category: p.category?.name ?? this.mapCategory(p.category_id),
                        price: p.price,
                        stock: p.quantity,
                        status: p.status, // in_stock, out_of_stock, low_stock
                        created: new Date(p.created_at).toLocaleDateString("vi-VN"),
                        image: "https://via.placeholder.com/60" // ảnh tạm
                    }));

                    this.filteredProducts = this.products;
                    this.updatePagination();
                }
            } catch (e) {
                console.error("Lỗi khi gọi API products:", e);
            }
        },

        // --- Map Category ID to name ---
        mapCategory(catId) {
            const categories = {
                1: "Electronics",
                2: "Clothing",
                3: "Books",
                4: "Home & Garden"
            };
            return categories[catId] ?? "Uncategorized";
        },

        // --- Pagination Helpers ---
        updatePagination() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            this.paginatedProducts = this.filteredProducts.slice(start, end);
        },
        goToPage(page) {
            if (page < 1 || page > this.totalPages) return;
            this.currentPage = page;
            this.updatePagination();
        },
        get totalPages() {
            return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
        },
        get visiblePages() {
            let pages = [];
            for (let i = 1; i <= this.totalPages; i++) pages.push(i);
            return pages;
        },

        // --- Filters ---
        filterProducts() {
            this.filteredProducts = this.products.filter(p => {
                let matchSearch = p.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                let matchCategory = this.categoryFilter ? p.category === this.categoryFilter : true;
                let matchStock = true;
                if (this.stockFilter === "in-stock") matchStock = p.stock > 20;
                if (this.stockFilter === "low-stock") matchStock = p.stock > 0 && p.stock <= 20;
                if (this.stockFilter === "out-of-stock") matchStock = p.stock === 0;
                return matchSearch && matchCategory && matchStock;
            });
            this.currentPage = 1;
            this.updatePagination();
        },

        // --- Select All ---
        toggleAll(checked) {
            this.selectedProducts = checked ? this.filteredProducts.map(p => p.id) : [];
        },

        // --- Actions ---
        editProduct(product) {
            alert(`Chỉnh sửa sản phẩm: ${product.name}`);
        },
        viewProduct(product) {
            alert(`Xem chi tiết sản phẩm: ${product.name}`);
        },
        duplicateProduct(product) {
            const newProduct = { ...product, id: Date.now(), name: product.name + " (Copy)" };
            this.products.push(newProduct);
            this.filterProducts();
            alert(`Đã nhân bản sản phẩm: ${product.name}`);
        },
        deleteProduct(product) {
            if (confirm(`Bạn có chắc muốn xóa sản phẩm: ${product.name}?`)) {
                this.products = this.products.filter(p => p.id !== product.id);
                this.filterProducts();
                alert(`Đã xóa sản phẩm: ${product.name}`);
            }
        },

        // --- Export Excel ---
        exportProducts() {
            if (!this.products.length) {
                alert("Không có dữ liệu để xuất!");
                return;
            }

            const wb = XLSX.utils.book_new();
            const ws_data = this.products.map(p => ({
                ID: p.id,
                Name: p.name,
                SKU: p.sku,
                Category: p.category,
                Price: p.price,
                Stock: p.stock,
                Status: p.status,
                Created: p.created
            }));

            const ws = XLSX.utils.json_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "Products");
            XLSX.writeFile(wb, "products.xlsx");
        },



        // --- Init ---
        init() {
            this.fetchStats();
            this.fetchProducts();
        }

    }));
});
