<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profit & Loss Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d1b2a; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 20px; color: #0d1b2a; }
        .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
        .period { background: #f0f2f5; padding: 8px 15px; border-radius: 5px; margin-bottom: 20px; font-size: 12px; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table td { padding: 10px 15px; border-bottom: 1px solid #eee; }
        .summary-table td:last-child { text-align: right; font-weight: bold; }
        .green { color: #27ae60; }
        .red { color: #e74c3c; }
        .total-row td { background: #0d1b2a; color: white; font-size: 14px; font-weight: bold; border-radius: 5px; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .box { border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
        .box-title { font-size: 11px; color: #666; margin-bottom: 5px; }
        .box-value { font-size: 18px; font-weight: bold; }
        .boxes { width: 100%; }
        .boxes td { width: 25%; padding: 5px; vertical-align: top; }
    </style>
</head>
<body>

<div class="header">
    <h2>NSH Distribution ERP</h2>
    <p>Profit & Loss Report</p>
</div>

<div class="period">
    <strong>Period:</strong> {{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
    &nbsp;&nbsp;&nbsp;
    <strong>Generated:</strong> {{ now()->format('d M Y h:i A') }}
</div>

<!-- Summary Boxes -->
<table class="boxes">
    <tr>
        <td>
            <div class="box">
                <div class="box-title">Sales Revenue</div>
                <div class="box-value green">PKR {{ number_format($total_sales) }}</div>
            </div>
        </td>
        <td>
            <div class="box">
                <div class="box-title">Purchase Cost</div>
                <div class="box-value red">PKR {{ number_format($total_cost) }}</div>
            </div>
        </td>
        <td>
            <div class="box">
                <div class="box-title">Expenses</div>
                <div class="box-value red">PKR {{ number_format($total_expenses) }}</div>
            </div>
        </td>
        <td>
            <div class="box">
                <div class="box-title">Net Profit</div>
                <div class="box-value {{ $net_profit >= 0 ? 'green' : 'red' }}">
                    PKR {{ number_format($net_profit) }}
                </div>
            </div>
        </td>
    </tr>
</table>

<!-- Detailed Table -->
<table class="summary-table">
    <tr>
        <td>Total Sales Revenue</td>
        <td class="green">PKR {{ number_format($total_sales) }}</td>
    </tr>
    <tr>
        <td>(-) Cost of Goods Sold</td>
        <td class="red">PKR {{ number_format($total_cost) }}</td>
    </tr>
    <tr style="background:#f8f9fa;">
        <td><strong>Gross Profit</strong></td>
        <td><strong>PKR {{ number_format($gross_profit) }}</strong></td>
    </tr>
    <tr>
        <td>(-) Total Expenses</td>
        <td class="red">PKR {{ number_format($total_expenses) }}</td>
    </tr>
    <tr class="total-row">
        <td>Net Profit / Loss</td>
        <td>PKR {{ number_format($net_profit) }}</td>
    </tr>
</table>

<div class="footer">
    NSH Distribution ERP — Generated on {{ now()->format('d M Y h:i A') }}
</div>

</body>
</html>