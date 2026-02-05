<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
        }

        .paid {
            color: green;
            font-weight: bold;
        }

        .unpaid {
            color: red;
            font-weight: bold;
        }

        .summary-box {
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #d4d4d8;
            border-radius: 0.375rem;
            background-color: #ffffff;
            color: #18181b;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .label {
            font-weight: 600;
        }

        .value {
            font-weight: 700;
        }

        .value.blue {
            color: #2563eb;
        }
        .value.red {
            color: #eb2525;
        }

        .value.neutral {
            color: #3f3f46;
        }
    </style>
</head>
<body>
<h1 style="text-align:center">JIA's Internet</h1>
<h2>Subscriber: {{$full_name}}</h2>
<h2>Mikrotik Name: {{$Mikrotik_Name}}</h2>
<h2>Month(Year) from: {{$Month_Cover_From->format('F Y')}}</h2>
<h2>Month(Year) to: {{$Month_Cover_To->format('F Y')}}</h2>
<h3>Billing Status Summary</h3>
<div class="summary-box">
    <div class="summary-row">
        <span class="label">Expected Total Amount:</span>
        <span class="value neutral">
            ₱{{ number_format($totals['expected_total'], 2) }}
        </span>
    </div>

    <div class="summary-row">
        <span class="label">Total Paid:</span>
        <span class="value blue">
            ₱{{ number_format($totals['total_paid'], 2) }}
        </span>
    </div>

    <div class="summary-row">
        <span class="label">Remaining Balance:</span>
        <span class="value red">
            ₱{{ number_format($totals['remaining_balance'], 2) }}
        </span>
    </div>

    <div class="summary-row">
        <span class="label">Total Discount:</span>
        <span class="value blue">
            ₱{{ number_format($totals['total_discount'], 2) }}
        </span>
    </div>
</div>
<table>
    <thead>
    <tr>
        <th>Month (Year)</th>
        <th>Expected Amount</th>
        <th>Status</th>
        <th>Paid Amount</th>
        <th>Remaining Balance</th>
        <th>Discount</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($billingSummary as $row)
        <tr>
            <td>{{ $row['month'] }}</td>
            <td>
                ₱{{ number_format($row['expected_amount'], 2) }}
            </td>
            <td class="{{ $row['status'] === 'Paid' ? 'paid' : 'unpaid' }}">
                {{ $row['status'] }}
            </td>
            <td>{{ number_format($row['paid_amount'], 2) }}</td>
            <td>
                ₱{{ number_format($row['remaining_balance'], 2) }}
            </td>
            <td>₱{{ number_format($row['discount_amount'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
