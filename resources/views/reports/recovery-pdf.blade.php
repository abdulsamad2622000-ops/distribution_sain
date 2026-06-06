 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recovery Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #0d1b2a; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #0d1b2a; }
        .period { background: #f0f2f5; padding: 8px 15px; margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #0d1b2a; color: white; padding: 8px; text-align: left; font-size: 10px; }
        td { padding: 7px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .green { color: #27ae60; font-weight: bold; }
        .total-box { background: #0d1b2a; color: white; padding: 10px 15px; border-radius: 5px; text-align: center; margin-bottom: 15px; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #999; }
    </style>
</head>
<body>

<div class="header">
    <h2>NSH Distribution ERP</h2>
    <p style="margin:3px 0 0;color:#666;font-size:11px;">Payment Recovery Report</p>
</div>

<div class="period">
    <strong>Period:</strong> {{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
    &nbsp;&nbsp;
    <strong>Generated:</strong> {{ now()->format('d M Y h:i A') }}
</div>

<div class="total-box">
    Total Recovered: <strong style="font-size:16px;">PKR {{ number_format($total_recovered) }}</strong>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Invoice #</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Reference</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($recoveries as $recovery)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $recovery->customer->name ?? 'N/A' }}</strong></td>
            <td>{{ $recovery->sale->invoice_no ?? 'N/A' }}</td>
            <td class="green">PKR {{ number_format($recovery->amount) }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $recovery->payment_method)) }}</td>
            <td>{{ $recovery->reference_no ?? 'N/A' }}</td>
            <td>{{ \Carbon\Carbon::parse($recovery->payment_date)->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#999;">No data found</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    NSH Distribution ERP — Generated on {{ now()->format('d M Y h:i A') }}
</div>

</body>
</html>