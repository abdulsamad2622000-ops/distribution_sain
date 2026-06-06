<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Recovery;
use App\Models\Expense;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to ?? now()->format('Y-m-d');

        $sales          = Sale::with('customer')->whereBetween('sale_date', [$from, $to])->latest()->get();
        $total_sales    = $sales->sum('net_amount');
        $total_recovered = $sales->sum('paid_amount');
        $total_due      = $sales->sum('due_amount');

        return view('reports.index', compact('sales', 'total_sales', 'total_recovered', 'total_due', 'from', 'to'));
    }

    public function recovery(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to ?? now()->format('Y-m-d');

        $recoveries      = Recovery::with('customer', 'sale')->whereBetween('payment_date', [$from, $to])->latest()->get();
        $total_recovered = $recoveries->sum('amount');

        return view('reports.index', compact('recoveries', 'total_recovered', 'from', 'to'));
    }

    public function stock()
    {
        $products = Product::with('supplier')->latest()->get();
        return view('reports.index', compact('products'));
    }

    public function profitLoss(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to ?? now()->format('Y-m-d');

        $total_sales    = Sale::whereBetween('sale_date', [$from, $to])->sum('net_amount');
        $total_cost     = SaleItem::whereHas('sale', fn($q) => $q->whereBetween('sale_date', [$from, $to]))->sum(\Illuminate\Support\Facades\DB::raw('purchase_price * qty'));
        $total_expenses = Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
        $gross_profit   = $total_sales - $total_cost;
        $net_profit     = $gross_profit - $total_expenses;

        return view('reports.index', compact('total_sales', 'total_cost', 'total_expenses', 'gross_profit', 'net_profit', 'from', 'to'));
    }

    public function customerLedger(Request $request)
    {
        $customers_data = Customer::all();
        $customers      = Customer::all();
        return view('reports.index', compact('customers_data', 'customers'));
    }



    public function profitLossPdf(Request $request)
{
    $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
    $to   = $request->to ?? now()->format('Y-m-d');

    $total_sales    = Sale::whereBetween('sale_date', [$from, $to])->sum('net_amount');
    $total_cost     = SaleItem::whereHas('sale', fn($q) => $q->whereBetween('sale_date', [$from, $to]))->sum(\Illuminate\Support\Facades\DB::raw('purchase_price * qty'));
    $total_expenses = Expense::whereBetween('expense_date', [$from, $to])->sum('amount');
    $gross_profit   = $total_sales - $total_cost;
    $net_profit     = $gross_profit - $total_expenses;

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.profit-loss-pdf', compact(
        'total_sales', 'total_cost', 'total_expenses',
        'gross_profit', 'net_profit', 'from', 'to'
    ));

    return $pdf->download('profit-loss-' . $from . '-to-' . $to . '.pdf');
}

public function salesPdf(Request $request)
{
    $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
    $to   = $request->to ?? now()->format('Y-m-d');

    $sales          = Sale::with('customer')->whereBetween('sale_date', [$from, $to])->latest()->get();
    $total_sales    = $sales->sum('net_amount');
    $total_recovered = $sales->sum('paid_amount');
    $total_due      = $sales->sum('due_amount');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.sales-pdf', compact(
        'sales', 'total_sales', 'total_recovered', 'total_due', 'from', 'to'
    ));

    return $pdf->download('sales-report-' . $from . '-to-' . $to . '.pdf');
}

public function recoveryPdf(Request $request)
{
    $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
    $to   = $request->to ?? now()->format('Y-m-d');

    $recoveries      = Recovery::with('customer', 'sale')->whereBetween('payment_date', [$from, $to])->latest()->get();
    $total_recovered = $recoveries->sum('amount');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.recovery-pdf', compact(
        'recoveries', 'total_recovered', 'from', 'to'
    ));

    return $pdf->download('recovery-report-' . $from . '-to-' . $to . '.pdf');
}

public function customerLedgerPdf(Request $request)
{
    $customers_data = \App\Models\Customer::all();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.customer-ledger-pdf', compact('customers_data'));

    return $pdf->download('customer-ledger.pdf');
}
}