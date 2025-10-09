@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
    <div class="container my-4" style="max-width: 950px;">

        <!-- 🔹 Top Action Buttons -->
        <div class="d-flex mx-3 justify-content-between align-items-center mb-4 ">
            <a href="{{ route('invoices.history') }}" class="btn btn-secondary me-2">
                ← Back to History
            </a>
            <div>

                <button class="btn btn-success me-2" onclick="window.print()">
                    🖨️ Print
                </button>
                <button class="btn btn-danger" id="downloadPDF">
                    📄 Download PDF
                </button>
            </div>
        </div>

        <!-- 🔹 Invoice Content -->
        <div id="invoiceContent" class="rounded shadow p-5 border-top bg-white">
            <style>
                /* --- Common Styling --- */
                #invoiceContent input,
                #invoiceContent textarea,
                #invoiceContent select,
                #invoiceContent button,
                #invoiceContent input[type="file"],
                #invoiceContent label {
                    border: none !important;
                    background-color: transparent !important;
                    box-shadow: none !important;
                    pointer-events: none !important;
                    color: inherit !important;
                    font-weight: normal;
                    cursor: default !important;
                }

                #invoiceContent textarea {
                    resize: none;
                }

                /* --- Print Styles --- */
                @media print {
                    body * {
                        visibility: hidden;
                    }

                    #invoiceContent,
                    #invoiceContent * {
                        visibility: visible;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }

                    #invoiceContent {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        box-shadow: none !important;
                    }
                }


                /* For PDF generation */
                .pdf-mode #invoiceContent {
                    padding: 30px 40px !important;
                    box-shadow: none !important;
                    border: none !important;
                    margin: 0 !important;
                }
            </style>

            <!-- Header -->
            <div class="row mb-4">
                <div class="col-6">
                    {{-- Company Logo --}}
                    <input type="file" id="logoInput" class="d-none" accept="image/*">
                    <label for="logoInput" class="mb-3">
                        <div class="rounded p-2 text-center "
                            style="cursor: pointer; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <img id="logoPreview" src="{{ asset('storage/' . $invoice->logo_path) }}" class="img-fluid"
                                style="max-height: 90px;">
                        </div>
                    </label>
                    <textarea class="form-control border-0 small" rows="2" readonly>{{ $invoice->from_company }}</textarea>
                </div>

                <div class="col-6 text-end">
                    <h2 class="fw-bold mb-3">INVOICE</h2>
                    <table class="table table-sm table-borderless ms-auto" style="max-width: 350px;">
                        <tr>
                            <td class="fw-bold">{{ $invoiceTitles->invoice_number_title ?? 'Invoice #' }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ $invoiceTitles->invoice_date_title ?? 'Date' }}</td>
                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ $invoiceTitles->payment_terms_title ?? 'Payment Terms' }}</td>
                            <td>{{ $invoice->payment_terms }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ $invoiceTitles->due_date_title ?? 'Due Date' }}</td>
                            <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">{{ $invoiceTitles->po_number_title ?? 'PO Number' }}</td>
                            <td>{{ $invoice->po_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <!-- Bill To / Ship To -->
            <div class="row mb-4">
                <div class="col-6">
                    <div class="fw-bold mb-1">{{ $invoiceTitles->bill_to_title ?? 'Bill To' }}</div>
                    <textarea class="form-control mb-2" rows="2" readonly>{{ $invoice->bill_to }}</textarea>
                    <div>Phone: {{ $invoice->phone_number }}</div>
                </div>
                <div class="col-6">
                    <div class="fw-bold mb-1">{{ $invoiceTitles->ship_to_title ?? 'Ship To' }}</div>
                    <textarea class="form-control" rows="2" readonly>{{ $invoice->ship_to }}</textarea>
                </div>
            </div>

            <!-- Items Table -->
            <table class="table  align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Description</th>
                        <th style="width: 12%">Qty</th>
                        <th style="width: 15%">Rate</th>
                        <th style="width: 18%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @foreach ($invoice->items as $item)
                        @php
                            $amount = $item->quantity * $item->rate;
                            $subtotal += $amount;
                        @endphp
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $invoice->currency ?? '$' }} {{ number_format($item->rate, 2) }}</td>
                            <td>{{ $invoice->currency ?? '$' }} {{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            @php
                $taxAmount =
                    $invoice->tax_type === 'percentage' ? ($subtotal * $invoice->tax_value) / 100 : $invoice->tax_value;
                $total = $subtotal + $taxAmount;
            @endphp
            <div class="row mt-4">
                <div class="col-7">
                    <strong>Notes:</strong>
                    <textarea class="form-control mt-2" rows="4" readonly>{{ $invoice->notes }}</textarea>
                </div>
                <div class="col-5">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td class="text-end">{{ $invoice->currency ?? '$' }} {{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tax
                                    {{ $invoice->tax_type === 'percentage' ? "({$invoice->tax_value}%)" : '' }}:</strong>
                            </td>
                            <td class="text-end">{{ $invoice->currency ?? '$' }} {{ number_format($taxAmount, 2) }}</td>
                        </tr>
                        <tr class="table-dark">
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong>{{ $invoice->currency ?? '$' }}
                                    {{ number_format($total, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.getElementById('downloadPDF').addEventListener('click', async function() {
    const { jsPDF } = window.jspdf;
    const button = this;

    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';

    const invoice = document.getElementById('invoiceContent');

    // Clone invoice for PDF rendering
    const clone = invoice.cloneNode(true);
    clone.style.position = 'absolute';
    clone.style.top = '-9999px';
    clone.style.left = '0';
    clone.style.width = invoice.offsetWidth + 'px';
    document.body.appendChild(clone);

    // Remove unnecessary elements
    clone.querySelectorAll('hr, .remove-item, #addItemBtn, button').forEach(el => el.remove());

    // Clean form inputs inside clone
    clone.querySelectorAll('input, textarea, select, label').forEach(el => {
        el.style.border = 'none';
        el.style.backgroundColor = 'transparent';
        el.style.boxShadow = 'none';
        el.style.pointerEvents = 'none';
        el.style.color = 'inherit';
        el.style.fontWeight = 'normal';
        el.style.cursor = 'default';
    });

    try {
        const canvas = await html2canvas(clone, { scale: 2.5, useCORS: true, backgroundColor: '#ffffff' });
        clone.remove();

        const pdf = new jsPDF('p', 'mm', 'a4');
        const pageWidth = 210;
        const pageHeight = 297;
        const margin = 15;
        const contentWidth = pageWidth - margin * 2;
        const contentHeight = pageHeight - margin * 2;

        const imgWidth = contentWidth;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;

        let remainingHeight = imgHeight;
        let sourceY = 0;

        while (remainingHeight > 0) {
            const heightOnPage = Math.min(remainingHeight, contentHeight);
            const srcHeight = (heightOnPage / imgWidth) * canvas.width;

            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = canvas.width;
            tempCanvas.height = srcHeight;

            const tempCtx = tempCanvas.getContext('2d');
            tempCtx.drawImage(canvas, 0, sourceY, canvas.width, srcHeight, 0, 0, canvas.width, srcHeight);

            const pageImgData = tempCanvas.toDataURL('image/png');
            pdf.addImage(pageImgData, 'PNG', margin, margin, imgWidth, heightOnPage);

            remainingHeight -= heightOnPage;
            sourceY += srcHeight;

            if (remainingHeight > 0) pdf.addPage();
        }

        pdf.save('invoice-{{ $invoice->invoice_number }}.pdf');
    } catch (err) {
        console.error('PDF generation error:', err);
        alert('Failed to generate PDF. Please try again.');
    } finally {
        button.disabled = false;
        button.innerHTML = '📄 Download PDF';
    }
});
    </script>
@endsection
