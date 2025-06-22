@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; }
        .invoice-details { margin: 20px 0; }
        .invoice-details table { width: 100%; border-collapse: collapse; }
        .invoice-details th, .invoice-details td { padding: 8px; }
        .items-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <h1>INVOICE</h1>
                    <p><strong>Invoice #:</strong> {{ $invoice->id }}</p>
                    <p><strong>Invoice Date:</strong> {{ Carbon::parse($invoice->invoice_date)->format('F j, Y') }}</p>
                </td>
                <td style="width: 50%; text-align: right;">
                    <h2>{{ $company->getName() }}</h2>
                    <p>{{ $company->getAddress() }}, {{ $company->getNit() }}<br>{{ $company->getPhone() }}</p>
                </td>
            </tr>
        </table>

        <div class="invoice-details">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <strong>Bill To:</strong><br>
                        {{ $invoice->customer_name }}<br>
                        {{ $invoice->customer_nit }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $item)
                <tr>
                    <td>{{ $item->service_name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ $item->price }} Bs</td>
                    <td class="text-right">{{ $item->discount }} Bs</td>
                    <td class="text-right">{{ $item->subtotal }} Bs</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right total">Total Amount:</td>
                    <td class="text-right total">{{ $invoice->total }} Bs</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer" style="margin-top: 40px;">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
