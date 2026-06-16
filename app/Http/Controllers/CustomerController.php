<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Customer, Recovery};
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::withSum('sales', 'net_amount')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->outstanding, fn($q) => $q->where('balance', '>', 0))
            ->paginate(20);

        $totalCustomers    = Customer::count();
        $totalReceivable   = Customer::sum('balance');
        $customersWithDues = Customer::where('balance', '>', 0)->count();

        return view('customers.index', compact('customers', 'totalCustomers', 'totalReceivable', 'customersWithDues'));
    }

    public function create()
    {
        return view('customers.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'area'    => $request->area,
            'balance' => $request->balance ?? 0,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added!');
    }

    public function show(Customer $customer)
    {
        $sales      = $customer->sales()->with('items.product')->latest()->paginate(10);
        $recoveries = $customer->recoveries()->latest()->paginate(10);
        return view('customers.show', compact('customer', 'sales', 'recoveries'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'area'    => $request->area,
            'balance' => $request->balance ?? $customer->balance,
        ]);

        return redirect()->route('customers.show', $customer)->with('success', 'Customer updated!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted!');
    }

    public function ledger(Customer $customer)
    {
        $sales      = $customer->sales()->with('items.product')->latest()->get();
        $recoveries = $customer->recoveries()->latest()->get();
        return view('customers.ledger', compact('customer', 'sales', 'recoveries'));
    }

    public function ledgerPdf(Customer $customer)
    {
        $sales      = $customer->sales()->with('items.product')->latest()->get();
        $recoveries = $customer->recoveries()->latest()->get();

        $pdf = Pdf::loadView('customers.ledger_pdf', compact('customer', 'sales', 'recoveries'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('ledger-' . $customer->name . '.pdf');
    }
}