<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Recovery;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $from = now()->startOfMonth()->format('Y-m-d');
        $to   = now()->format('Y-m-d');

        return view('dashboard.index', [
            'today_sales'       => Sale::whereDate('sale_date', today())->sum('net_amount'),
            'today_sales_count' => Sale::whereDate('sale_date', today())->count(),
            'monthly_sales'     => Sale::whereBetween('sale_date', [$from, $to])->sum('net_amount'),
            'total_outstanding' => Sale::sum('due_amount'),
            'today_recovery'    => Recovery::whereDate('payment_date', today())->sum('amount'),
            'monthly_recovery'  => Recovery::whereBetween('payment_date', [$from, $to])->sum('amount'),
            'total_customers'   => Customer::count(),
            'low_stock'         => Product::whereColumn('stock_qty', '<=', 'low_stock_alert')->count(),
            'monthly_expenses'  => Expense::whereBetween('expense_date', [$from, $to])->sum('amount'),
            'recent_sales'      => Sale::with('customer')->latest()->take(8)->get(),
            'recent_recoveries' => Recovery::with('customer', 'sale')->latest()->take(5)->get(),
            'low_stock_items'   => Product::whereColumn('stock_qty', '<=', 'low_stock_alert')->take(5)->get(),
        ]);
    }
}