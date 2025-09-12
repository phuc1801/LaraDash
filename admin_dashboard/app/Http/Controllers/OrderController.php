<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;

class OrderController extends Controller
{
    // Lấy danh sách orders
    public function index()
    {
        $orders = Order::with(['user', 'shippingAddress'])->get();

         // Search theo id, mã đơn, tên, email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                ->orWhere('order_number', 'like', "%$search%")
                ->orWhereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $dateFilter = $request->date;
            if ($dateFilter === 'today') {
                $query->whereDate('created_at', now());
            } elseif ($dateFilter === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateFilter === 'month') {
                $query->whereMonth('created_at', now()->month);
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'orders' => $orders
        ]);
    }

    // Xem chi tiết order
    public function show($id)
    {
        $order = Order::with(['user', 'shippingAddress'])->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'order' => $order
        ]);
    }


    // đếm tất cả đơn hàng
    public function count()
    {
        $totalOrders = Order::count();

        return response()->json([
            'status' => true,
            'total_orders' => $totalOrders
        ]);
    }

    // đêm số đơn hàng đã hoàn thành và chưa
    public function countByStatus()
    {
        $pending = Order::where('status', 'pending')->count();
        $completed = Order::where('status', 'completed')->count();

        return response()->json([
            'status' => true,
            'pending' => $pending,
            'completed' => $completed
        ]);
    }


    // tinh doanh thu sum, pending, completed
    public function revenue()
    {
        // Tổng doanh thu tất cả đơn hàng
        $totalRevenue = Order::sum('total_price');

        // Doanh thu theo trạng thái
        $completedRevenue = Order::where('status', 'completed')->sum('total_price');
        $pendingRevenue = Order::where('status', 'pending')->sum('total_price');

        return response()->json([
            'status' => true,
            'data' => [
                'total' => $totalRevenue,
                'completed' => $completedRevenue,
                'pending' => $pendingRevenue,
            ]
        ]);
    }


    // Doanh thu theo tháng trong năm
    public function revenueByMonth(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $revenues = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereYear('created_at', $year)
            ->where('status', 'completed') // chỉ tính đơn hoàn thành
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Chuẩn hóa dữ liệu cho chart.js
        $labels = [];
        $values = [];

        foreach ($revenues as $row) {
            $labels[] = "Tháng " . $row->month;
            $values[] = (int) $row->revenue;
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'values' => $values
        ]);
    }


    // Tăng trưởng bán hàng 7 ngày gần nhất
    public function weeklyGrowth()
    {
        $today = \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->startOfDay();
        $startDate = $today->copy()->subDays(6); // 7 ngày: hôm nay + 6 ngày trước

        $orders = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->whereBetween('created_at', [$startDate, $today])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Khởi tạo mảng 7 ngày
        $labels = [];
        $values = [];
        $datesMap = []; // map để tìm index nhanh

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d'); // để map
            $labels[] = $startDate->copy()->addDays($i)->format('d/m'); // hiển thị
            $values[] = 0;
            $datesMap[$date] = $i;
        }

        foreach ($orders as $order) {
            if (isset($datesMap[$order->date])) {
                $values[$datesMap[$order->date]] = (int) $order->total;
            }
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'values' => $values
        ]);
    }

    // Thêm vào OrderController
    public function statusFrequency()
    {
        // Lấy danh sách các trạng thái và số lượng tương ứng
        $statuses = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $labels = [];
        $values = [];

        foreach ($statuses as $status) {
            $labels[] = ucfirst($status->status); // Ví dụ: Pending, Completed
            $values[] = (int) $status->count;
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'values' => $values
        ]);
    }


    // Lấy 5 đơn hàng gần nhất
    public function latestOrders()
    {
        $orders = Order::with(['user', 'shippingAddress'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders
        ]);
    }


    public function warehouseStatus()
    {
        // Tổng dung lượng kho thực tế: sum tất cả stock của sản phẩm
        $totalCapacity = Product::sum('quantity');

        // Tổng số lượng hàng đã dùng: sum quantity từ order items của các order chưa hủy
        $used = OrderItem::whereHas('order', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->sum('quantity');

        $free = max($totalCapacity - $used, 0);
        $percentUsed = $totalCapacity > 0 ? round(($used / $totalCapacity) * 100, 2) : 0;

        return response()->json([
            'status' => true,
            'data' => [
                'total' => $totalCapacity,
                'used' => $used,
                'free' => $free,
                'percentUsed' => $percentUsed
            ]
        ]);
    }

    // Thống kê đơn hàng theo khu vực dựa vào địa chỉ user
    public function ordersByUserRegion()
    {
        $orders = Order::selectRaw('users.address as region, COUNT(*) as total_orders, SUM(total_price) as total_revenue')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->groupBy('users.address')
            ->orderBy('total_orders', 'desc')
            ->get();

        $labels = [];
        $orderCounts = [];
        $revenues = [];

        foreach ($orders as $order) {
            $labels[] = $order->region ?: 'Chưa xác định'; // nếu address null
            $orderCounts[] = (int) $order->total_orders;
            $revenues[] = (float) $order->total_revenue;
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,          // tên khu vực
            'order_counts' => $orderCounts, // số lượng đơn
            'revenues' => $revenues        // tổng doanh thu
        ]);
    }





}
