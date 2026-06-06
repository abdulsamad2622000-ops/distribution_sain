 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #0d1b2a; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #0d1b2a; }
        .period { background: #f0f2f5; padding: 8px 15px; margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #0d1b2a; color: white; padding: 8px; text-align: left; font-size: 10px; }
        td { padding: 7px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .summary { background: #f0f2f5; padding: 10px 15px; border-radius: 5px; }
        .summary table { margin: 0; }
        .summary td { border: none; padding: 4px 8px; }
        .green { color: #27ae60; }
        .red { color: #e74c3c; }
        .badge-paid { background: #27ae60; color: white; padding: 2px 8px; border-radius: 10px; font-size: 9px; }
        .badge-partial { background: #f39c12; color: white; padding: 2px 8px; border-radius: 10px; font-size: 9px; }
        .badge-unpaid { background: #e74c3c; color: white; padding: 2px 8px; border-radius: 10px; font-size: 9px; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #999; }
    </style>
</head>
<body>

<div class="header">
    <h2>NSH Distribution ERP</h2>
    <p style="margin:3px 0 0;color:#666;font-size:11px;">Sales Report</p>
</div>

<div class="period">
    <strong>Period:</strong> {{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
    &nbsp;&nbsp;
    <strong>Generated:</strong> {{ now()->format('d M Y h:i A') }}
</div>

<!-- Summary -->
<div class="summary" style="margin-bottom:15px;">
    <table>
        <tr>
            <td>Total Sales:</td><td><strong class="green">PKR {{ number_format($total_sales) }}</strong></td>
            <td>&nbsp;&nbsp;</td>
            <td>Total Recovered:</td><td><strong>PKR {{ number_format($total_recovered) }}</strong></td>
            <td>&nbsp;&nbsp;</td>
            <td>Outstanding:</td><td><strong class="red">PKR {{ number_format($total_due) }}</strong></td>
        </tr>
    </table>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Invoice #</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Type</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $sale->invoice_no }}</strong></td>
            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
            <td>PKR {{ number_format($sale->net_amount) }}</td>
            <td class="green">PKR {{ number_format($sale->paid_amount) }}</td>
            <td class="red">PKR {{ number_format($sale->due_amount) }}</td>
            <td>{{ ucfirst($sale->payment_type) }}</td>
            <td><span class="badge-{{ $sale->status }}">{{ ucfirst($sale->status) }}</span></td>
            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;color:#999;">No data found</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    NSH Distribution ERP — Generated on {{ now()->format('d M Y h:i A') }}
</div>

</body>
</html>