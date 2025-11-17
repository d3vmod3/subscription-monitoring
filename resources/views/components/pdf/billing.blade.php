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
    </style>
</head>
<body>
<h1 style="text-align:center">JIA's Internet</h1>
<h2>Subscriber: {{$full_name}}</h2>
<h2>Mikrotik Name: {{$Mikrotik_Name}}</h2>
<h2>Month(Year) to: {{$Month_Cover_From->format('F Y')}}</h2>
<h2>Month(Year) to: {{$Month_Cover_To->format('F Y')}}</h2>
<h3>Billing Status Summary</h3>
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
