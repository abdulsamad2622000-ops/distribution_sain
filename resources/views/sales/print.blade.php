 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice — {{ $sale->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 22px; }
        .header p { margin: 3px 0; font-size: 12px; }
        .info { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .info div { width: 48%; }
        .info table { width: 100%; }
        .info td { padding: 3px 0; font-size: 12px; }
        .info td:first-child { color: #666; width: 40%; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: #f0f2f5; padding: 8px; text-align: left; font-size: 12px; border: 1px solid #ddd; }
        table.items td { padding: 7px 8px; border: 1px solid #ddd; font-size: 12px; }
        .totals { float: right; width: 300px; }
        .totals table { width: 100%; }
        .totals td { padding: 4px 8px; font-size: 13px; }
        .totals td:last-child { text-align: right; font-weight: bold; }
        .total-row { border-top: 2px solid #333; font-size: 15px; }
        .footer { text-align: center; margin-top: 40px; font-size: 11px; color: #666; }
        .badge-paid { background: #27ae60; color: white; padding: 3px 10px; border-radius: 10px; font-size: 11px; }
        .badge-partial { background: #f39c12; color: white; padding: 3px 10px; border-radius: 10px; font-size: 11px; }
        .badge-unpaid { background: #e74c3c; color: white; padding: 3px 10px; border-radius: 10px; font-size: 11px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>NSH Distribution</h2>
        <p>Sales Invoice</p>
    </div>

    <div class="info">
        <div>
            <table>
                <tr><td>Customer:</td><td><strong>{{ $sale->customer->name }}</strong></td></tr>
                <tr><td>Phone:</td><td>{{ $sale->customer->phone ?? 'N/A' }}</td></tr>
                <tr><td>Area:</td><td>{{ $sale->customer->area ?? 'N/A' }}</td></tr>
                <tr><td>Address:</td><td>{{ $sale->customer->address ?? 'N/A' }}</td></tr>
            </table>
        </div>
        <div>
            <table>
                <tr><td>Invoice #:</td><td><strong>{{ $sale->invoice_no }}</strong></td></tr>
                <tr><td>Date:</td><td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td></tr>
                <tr><td>Type:</td><td>{{ ucfirst($sale->payment_type) }}</td></tr>
                <tr><td>Status:</td><td>
                    <span class="badge-{{ $sale->status }}">{{ ucfirst($sale->status) }}</span>
                </td></tr>
            </table>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Unit</th>
                <th>Rate</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>{{ $item->product->unit ?? 'pcs' }}</td>
                <td>PKR {{ number_format($item->unit_price) }}</td>
                <td>{{ $item->qty }}</td>
                <td>PKR {{ number_format($item->total_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr><td>Subtotal:</td><td>PKR {{ number_format($sale->total_amount) }}</td></tr>
            <tr><td>Discount:</td><td>-PKR {{ number_format($sale->discount) }}</td></tr>
            <tr class="total-row"><td>Net Total:</td><td>PKR {{ number_format($sale->net_amount) }}</td></tr>
            <tr><td>Paid:</td><td>PKR {{ number_format($sale->paid_amount) }}</td></tr>
            <tr><td style="color:#e74c3c;">Due:</td><td style="color:#e74c3c;">PKR {{ number_format($sale->due_amount) }}</td></tr>
        </table>
    </div>

    <div style="clear:both;"></div>

    @if($sale->notes)
    <div style="margin-top:15px;padding:10px;background:#f8f9fa;border-radius:5px;">
        <strong>Notes:</strong> {{ $sale->notes }}
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>

    <div class="no-print" style="text-align:center;margin-top:20px;">
        <button onclick="window.print()" style="padding:10px 30px;background:#00b4d8;border:none;color:white;border-radius:5px;cursor:pointer;font-size:14px;">
            Print Invoice
        </button>
    </div>
</body>
</html>