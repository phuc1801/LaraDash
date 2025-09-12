// export-pdf.js
document.addEventListener('DOMContentLoaded', () => {
    const exportPdfBtn = document.getElementById('exportPdf');

    if (!exportPdfBtn) return;

    exportPdfBtn.addEventListener('click', () => {
        const element = document.getElementById('admin-wrapper'); // hoặc 'main-content' nếu chỉ muốn phần chính

        if (!element) {
            alert('Không tìm thấy nội dung để xuất PDF!');
            return;
        }

        // Sử dụng html2pdf.js
        html2pdf()
            .set({
                margin:       0.5,
                filename:     'dashboard.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
            })
            .from(element)
            .save()
            .catch(err => console.error('❌ Lỗi xuất PDF:', err));
    });
});
