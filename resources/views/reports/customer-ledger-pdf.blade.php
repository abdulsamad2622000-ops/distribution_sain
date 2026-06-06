 
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Ledger</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #0d1b2a; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; color: #0d1b2a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #0d1b2a; color: white; padding: 8px; text-align: left; font-size: 10px; }
        td { padding: 7px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .green { color: #27ae60; font-weight: bold; }
        .red { color: #e74c3c; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #999; }
    </style>
</head>
<body>

<div class="header">
    <h2>NSH Distribution ERP</h2>
    <p style="margin:3px 0 0;color:#666;font-size:11px;">Customer Ledger Report</p>
    <p style="margin:2px 0 0;color:#999;font-size:10px;">Generated: {{ now()->format('d M Y h:i A') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Area</th>
            <th>Total Sales</th>
            <th>Recovered</th>
            <th>Outstanding</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers_data as $customer)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $customer->name }}</strong></td>
            <td>{{ $customer->phone ?? 'N/A' }}</td>
            <td>{{ $customer->area ?? 'N/A' }}</td>
            <td>PKR {{ number_format($customer->totalSales()) }}</td>
            <td class="green">PKR {{ number_format($customer->totalRecovered()) }}</td>
            <td class="{{ $customer->outstanding() > 0 ? 'red' : 'green' }}">
                PKR {{ number_format($customer->outstanding()) }}
            </td>
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