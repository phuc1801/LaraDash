<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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





}
