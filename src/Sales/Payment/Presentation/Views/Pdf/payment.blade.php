@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt {{ $payment->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header h1 { margin: 0; }
        .details-table { width: 100%; margin: 20px 0; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .total { font-weight: bold; font-size: 1.2em; }
        .thank-you { text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <h1>PAYMENT RECEIPT</h1>
                    <p><strong>Receipt #:</strong> {{ $payment->id }}</p>
                    <p><strong>Payment Date:</strong> {{ Carbon::parse($payment->payed_date)->format('F j, Y') }}</p>
                </td>
                <td style="width: 50%; text-align: right;">
                    <h2>{{ $company->getName() }}</h2>
                    <p>{{ $company->getAddress() }}, {{ $company->getNit() }}<br>{{ $company->getPhone() }}</p>
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px;">
            <strong>Paid By:</strong><br>
            {{ $payment->first_name . ' ' . $payment->last_name }}<br>
            {{ $payment->dni }}
        </div>

        <hr style="margin-top: 20px;">

        <table class="details-table">
            <tr>
                <td style="width: 70%;"><strong>Payment Method</strong></td>
                <td class="text-right">Cash</td>
            </tr>
            <tr class="total">
                <td>Amount Paid</td>
                <td class="text-right">{{ $payment->amount }} Bs</td>
            </tr>
        </table>

        <div class="thank-you">
            <p>Thank you for your payment!</p>
        </div>
    </div>
</body>
</html>
