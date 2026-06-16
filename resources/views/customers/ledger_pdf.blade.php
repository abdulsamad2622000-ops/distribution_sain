<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Ledger — {{ $customer->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; padding: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d6efd; padding-bottom: 12px; }
        .header h1 { font-size: 20px; color: #0d6efd; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }

        .info-section { display: flex; justify-content: space-between; margin-bottom: 16px; }
        .info-box { width: 48%; background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px; }
        .info-box h3 { font-size: 12px; color: #0d6efd; border-bottom: 1px solid #dee2e6; padding-bottom: 5px; margin-bottom: 8px; }
        .info-box table { width: 100%; }
        .info-box table td { padding: 3px 0; font-size: 11px; }
        .info-box table td:first-child { color: #666; width: 45%; }
        .info-box table td:last-child { font-weight: bold; }

        .summary-boxes { display: flex; justify-content: space-between; margin-bottom: 16px; }
        .summary-box { width: 30%; text-align: center; padding: 10px; border-radius: 4px; border: 1px solid #dee2e6; }
        .summary-box .label { font-size: 10px; color: #666; text-transform: uppercase; margin-bottom: 4px; }
        .summary-box .value { font-size: 15px; font-weight: bold; }
        .summary-box.blue .value { color: #0d6efd; }
        .summary-box.green .value { color: #198754; }
        .summary-box.red .value { color: #dc3545; }

        .section-title { font-size: 13px; font-weight: bold; color: #0d6efd; border-bottom: 1px solid #0d6efd; padding-bottom: 4px; margin-bottom: 8px; margin-top: 16px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        table.data-table thead { background: #0d6efd; color: white; }
        table.data-table thead th { padding: 7px 8px; font-size: 11px; text-align: left; }
        table.data-table tbody tr:nth-child(even) { background: #f8f9fa; }
        table.data-table tbody td { padding: 6px 8px; font-size: 11px; border-bottom: 1px solid #dee2e6; }
        table.data-table tfoot td { padding: 7px 8px; font-size: 12px; font-weight: bold; background: #e9ecef; }

        .badge-paid { color: #198754; font-weight: bold; }
        .badge-partial { color: #fd7e14; font-weight: bold; }
        .badge-unpaid { color: #dc3545; font-weight: bold; }

        .footer { margin-top: 20px; border-top: 1px solid #dee2e6; padding-top: 10px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Customer Ledger</h1>
        <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h3>Customer Information</h3>
            <table>
                <tr><td>Name</td><td>{{ $customer->name }}</td></tr>
                <tr><td>Phone</td><td>{{ $customer->phone ?? 'N/A' }}</td></tr>
                <tr><td>Area</td><td>{{ $customer->area ?? 'N/A' }}</td></tr>
                <tr><td>Address</td><td>{{ $customer->address ?? 'N/A' }}</td></tr>
            </table>
        </div>
        <div class="info-box">
            <h3>Account Summary</h3>
            <table>
                <tr><td>Total Sales</td><td>PKR {{ number_format($sales->sum('net_amount')) }}</td></tr>
                <tr><td>Total Recovered</td><td>PKR {{ number_format($recoveries->sum('amount')) }}</td></tr>
                <tr><td>Outstanding</td><td style="color:#dc3545;">PKR {{ number_format($customer->balance) }}</td></tr>
            </table>
        </div>
    </div>

    <div class="section-title">Invoices</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_no }}</td>
                <td>PKR {{ number_format($sale->net_amount) }}</td>
                <td>PKR {{ number_format($sale->paid_amount) }}</td>
                <td>PKR {{ number_format($sale->due_amount) }}</td>
                <td>
                    @if($sale->status == 'paid')
                        <span class="badge-paid">Paid</span>
                    @elseif($sale->status == 'partial')
                        <span class="badge-partial">Partial</span>
                    @else
                        <span class="badge-unpaid">Unpaid</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center; color:#999;">No invoices found</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1">Total</td>
                <td>PKR {{ number_format($sales->sum('net_amount')) }}</td>
                <td>PKR {{ number_format($sales->sum('paid_amount')) }}</td>
                <td>PKR {{ number_format($sales->sum('due_amount')) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="section-title">Payment History</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recoveries as $recovery)
            <tr>
                <td>{{ $recovery->sale->invoice_no ?? 'N/A' }}</td>
                <td style="color:#198754; font-weight:bold;">PKR {{ number_format($recovery->amount) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $recovery->payment_method)) }}</td>
                <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center; color:#999;">No payments found</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1">Total Received</td>
                <td style="color:#198754;">PKR {{ number_format($recoveries->sum('amount')) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        This is a system generated document. | NHS Distribution ERP
    </div>

</body>
</html>