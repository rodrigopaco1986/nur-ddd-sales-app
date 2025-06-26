<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Src\Sales\Invoice\Infraestructure\Mails\Invoice as InvoiceMailable;
use Src\Sales\Invoice\Infraestructure\Repositories\InvoiceRepository;
use Src\Sales\Invoice\Presentation\Controllers\CreateInvoiceController;
use Src\Sales\Invoice\Presentation\Controllers\ViewInvoiceController;
use Src\Sales\Invoice\Presentation\Resources\InvoiceResource;

Route::post('/create', CreateInvoiceController::class);
Route::get('/view/{id}', ViewInvoiceController::class);

Route::get('/preview/{type}/{id}', function (string $type, string $id) {
    $invoice = (new InvoiceRepository)->findById($id);

    $invoiceData = json_decode((new InvoiceResource($invoice))->toJson());
    $pdf = Pdf::loadView('invoice::Pdf.invoice', ['invoice' => $invoiceData->invoice]);
    $pdfContent = $pdf->output();

    $response = match ($type) {
        'email' => (new InvoiceMailable($pdfContent, $invoice->getId()))->render(),
        'pdf' => Response::make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-{$id}.pdf"',
        ]),
        default => Response::make('Option not found!', 404)
    };

    return $response;
});
