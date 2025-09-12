import { fetchAllChartData } from './fetch-chart-data.js';

async function exportChartsToExcel() {
    const data = await fetchAllChartData();
    const wb = XLSX.utils.book_new();

    // Sheet 1: Revenue
    const revenueSheet = data.revenue.data ? Object.entries(data.revenue.data).map(([key, val]) => ({ Metric: key, Value: val })) : [];
    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(revenueSheet), "Doanh thu");

    // Sheet 2: Weekly Growth
    const weeklySheet = data.weekly.labels.map((label, i) => ({ Date: label, Revenue: data.weekly.values[i] }));
    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(weeklySheet), "7 Ngày");

    // Sheet 3: Status Frequency
    const statusSheet = data.status.labels.map((label, i) => ({ Status: label, Count: data.status.values[i] }));
    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(statusSheet), "Trạng thái đơn");

    // Sheet 4: Warehouse
    const warehouseSheet = [
        { Metric: "Tổng", Value: data.warehouse.data.total },
        { Metric: "Đã dùng", Value: data.warehouse.data.used },
        { Metric: "Còn trống", Value: data.warehouse.data.free },
        { Metric: "% Sử dụng", Value: data.warehouse.data.percentUsed }
    ];
    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(warehouseSheet), "Kho hàng");

    // Sheet 5: Sales by Region
    const regionSheet = data.region.labels.map((label, i) => ({
        Location: label,
        Orders: data.region.order_counts[i],
        Revenue: data.region.revenues[i]
    }));
    XLSX.utils.book_append_sheet(wb, XLSX.utils.json_to_sheet(regionSheet), "Theo khu vực");

    // Xuất file
    XLSX.writeFile(wb, "dashboard-charts.xlsx");
}

document.getElementById('exportExcel').addEventListener('click', exportChartsToExcel);
