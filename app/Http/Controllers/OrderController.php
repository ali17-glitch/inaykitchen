<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_whatsapp' => 'required|string|max:20',
            'address' => 'required|string',
            'order_details' => 'required|string', // JSON string from frontend
            'total_price' => 'required|integer',
            'payment_method' => 'required|string'
        ]);

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_whatsapp' => $request->customer_whatsapp,
            'address' => $request->address,
            'order_details' => json_decode($request->order_details, true),
            'total_price' => $request->total_price,
            'payment_method' => $request->payment_method,
            'status' => 'Pending'
        ]);

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }

    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        
        // Calculate Statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->where('status', 'Selesai')->sum('total_price');
        $pendingOrders = $orders->where('status', 'Pending')->count();

        return view('admin.dashboard', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders'));
    }

    public function print()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.print', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Proses,Selesai,Batal'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
