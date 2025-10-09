@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div id="invoiceContent" class="  rounded shadow p-5  " style="max-width: 900px; margin: 0 auto;">

        <style>
            @media print {

        /* শুধু Invoice দেখাবে, বাকি সব লুকানো */

        body * {
            visibility: hidden;
        }

        #invoiceContent,
        #invoiceContent * {
            visibility: visible;
            color-adjust: exact;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }


        /*     Invoice ঠিকভাবে পেইজে বসানো */

        #invoiceContent {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 30px 40px !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }


        /* ফর্ম ফিল্ড ডিজেবল করা */

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

            cursor: default !important;
        }

        /* Textarea রিসাইজ বন্ধ করা */

        #invoiceContent textarea {
            resize: none;
        }

        /* Date input এর পিকার/স্পিনার লুকানো */

        #invoiceContent input[type="date"]::-webkit-inner-spin-button,
        #invoiceContent input[type="date"]::-webkit-calendar-picker-indicator {
            -webkit-appearance: none;
            display: none;
            margin: 0;
        }

        #invoiceContent input[type="date"]::-moz-inner-spin-button,
        #invoiceContent input[type="date"]::-moz-calendar-picker-indicator {
            display: none;
        }


        /*  Remove buttons, add buttons, tax rows লুকানো */

        .remove-item,
        #addItemBtn,
        #taxRateRow,
        #taxTypeRow,
        .btn,
        button {
            display: none !important;

        }


        /* Table print settings */

        .table {
            page-break-inside: auto;
            border-collapse: collapse !important;
        }


        .table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .table thead {
            display: table-header-group;
        }

        .table tfoot {
            display: table-footer-group;
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
            <div class="col-6 ">

                {{-- upload logo --}}
                <input type="file" id="logoInput" class="d-none" accept="image/*">
                <label for="logoInput" class="mb-3">
                    <div class="border  p-2 text-center"
                        style="cursor: pointer; height: 100px; display: flex; align-items: center; justify-content: center;">
                        <div id="logoPlaceholder" class="{{ Auth::user()->company_logo ? 'd-none' : '' }}">
                            <i class="fas fa-image text-muted"></i>
                            <p class="mb-0 small text-muted">Upload Logo</p>
                        </div>
                        <img id="logoPreview"
                            src="{{ Auth::user()->company_logo ? asset('storage/' . Auth::user()->company_logo) : '' }}"
                            class="img-fluid {{ Auth::user()->company_logo ? '' : 'd-none' }}" style="max-height: 90px;">
                    </div>
                </label>

                <textarea style="resize: none" class="form-control border small" rows="2" id="companyDetails"
                    placeholder=" Company name"></textarea>
            </div>
            <div class="col-6 text-end">
                <h1 class="mb-3">INVOICE</h1>
                <table class="table table-sm table-borderless ms-auto" style="max-width: 350px;">
                    <tr>
                        <td><input type="text" class="form-control form-control-sm border-0 fw-bold invoice-title-input"
                             data-field="invoice_number_title"   value="{{ $invoiceTitles->invoice_number_title ?? 'Invoice #' }}">
                        </td>
                        <td><input type="text" class="form-control form-control-sm" value="INV-001" id="invoiceNumber">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control form-control-sm border-0 fw-bold invoice-title-input"
                             data-field="invoice_date_title"   value="{{ $invoiceTitles->invoice_date_title ?? 'Date' }}"></td>
                        <td><input type="date" class="form-control form-control-sm" id="invoiceDate">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control form-control-sm border-0 fw-bold invoice-title-input"
                             data-field="payment_terms_title"   value="{{ $invoiceTitles->payment_terms_title ?? 'Payment Terms' }}"></td>
                        <td><input type="text" class="form-control form-control-sm" value="Net 30" id="paymentTerms">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control form-control-sm border-0 fw-bold invoice-title-input"
                               data-field="due_date_title" value="{{ $invoiceTitles->due_date_title ?? 'Due Date' }}">
                        </td>
                        <td><input type="date" class="form-control form-control-sm" id="dueDate">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control form-control-sm border-0 fw-bold invoice-title-input"
                              data-field="po_number_title"  value="{{ $invoiceTitles->po_number_title ?? 'PO Number' }}">
                        </td>
                        <td><input type="text" class="form-control form-control-sm" id="poNumber">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <!-- Bill To / Ship To -->
        <div class="row mb-4">
            <div class="col-6">
                <input type="text" class="form-control mb-2 form-control-sm border-0 fw-bold invoice-title-input"
                  data-field="bill_to_title"  value="{{ $invoiceTitles->bill_to_title ?? 'Bill To' }}">
                <textarea style="resize: none" class="form-control" rows="2" id="billTo" placeholder="who is this to?"></textarea>
                <input type="text" class="form-control form-control-sm mt-3" id="phoneNumber" placeholder="Phone Number">
            </div>
            <div class="col-6">
                <input type="text" class="form-control mb-2 form-control-sm border-0 fw-bold invoice-title-input"
                  data-field="ship_to_title"  value="{{ $invoiceTitles->ship_to_title ?? 'Ship To' }}">
                <textarea style="resize: none" class="form-control" rows="2" id="shipTo" placeholder="(optional)"></textarea>
            </div>
        </div>

        <!-- Items -->
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50%">Description</th>
                    <th style="width: 12%">Qty</th>
                    <th style="width: 15%">Rate</th>
                    <th style="width: 18%">Amount</th>
                    <th style="width: 5%"></th>
                </tr>
            </thead>
            <tbody id="itemsTable">
                <!-- Items will be rendered here from array -->
            </tbody>
        </table>

        <div class="d-flex justify-content-end mx-4">
            <button class="btn btn-sm btn-primary mb-4" id="addItemBtn">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </div>


        <!-- Totals -->
        <div class="row">
            <div class="col-7">
                <strong>Notes</strong>
                <textarea style="resize: none" class="form-control mt-2" rows="4" id="notes" placeholder="Thank you for your business!"></textarea>
            </div>
            <div class="col-5">
                <table class="table table-sm">

                    {{-- Tax Type --}}
                    <tr id="taxTypeRow">
                        <th style="width: 30%">
                            <label for="taxType" class="form-label small">Tax Type</label>
                        </th>
                        <td style="width: 70%">
                            <select class="form-select form-select-sm mb-2" id="taxType">
                                <option value="percentage">Percentage (%)</option>
                                <option value="flat">Flat Amount</option>
                            </select>
                        </td>
                    </tr>

                    {{-- Tax Rate --}}
                    <tr id="taxRateRow">
                        <th style="width: 30%">
                            <label for="taxValue" class="form-label small" id="taxLabel">Tax Rate (%)</label>
                        </th>
                        <td style="width: 70%">
                            <input type="number" class="form-control form-control-sm mb-2 " id="taxValue"
                                value="0" min="0" step="1">
                        </td>
                    </tr>


                    <tr>
                        <td><strong>Subtotal:</strong></td>
                        <td class="text-end" id="subtotal">$0.00</td>
                    </tr>
                    <tr>
                        <td><strong>Tax <span id="displayTax"></span>:</strong></td>
                        <td class="text-end" id="taxAmount">$0.00</td>
                    </tr>
                    <tr class="table-dark">
                        <td><strong>TOTAL:</strong></td>
                        <td class="text-end"><strong id="total">$0.00</strong></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        $(document).ready(function() {

            /* keyup event */
            $(document).on('keyup', '.invoice-title-input', function() {
                let field = $(this).data('field');
                let value = $(this).val();

                // AJAX দিয়ে সার্ভারে পাঠানো
                $.ajax({
                    url: "{{ route('invoiceTitles.updateField') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        field: field,
                        value: value
                    },
                    success: function(res) {
                       /*  console.log('Updated successfully:', field, value); */
                    },
                    error: function(err) {
                       /*  console.error('Update failed:', err); */
                    }
                });
            });


            // Items array - this will store all invoice items
            let invoiceItems = [{
                description: '',
                quantity: 1,
                rate: 0,
                amount: 0
            }];

            let logoBase64 = '';

            // Set dates
            const today = new Date().toISOString().split('T')[0];
            $('#invoiceDate').val(today);
            const dueDate = new Date();
            dueDate.setDate(dueDate.getDate() + 30);
            $('#dueDate').val(dueDate.toISOString().split('T')[0]);




            // Tax type change
            $('#taxType').on('change', function() {
                const taxType = $(this).val();
                if (taxType === 'percentage') {
                    $('#taxLabel').text('Tax Rate (%)');

                } else {
                    $('#taxLabel').text('Tax Amount');

                }
                calculateTotals();
            });

            // Render items from array
            function renderItems() {
                $('#itemsTable').empty();
                invoiceItems.forEach((item, index) => {
                    const row = `
                        <tr class="item-row" data-index="${index}">
                            <td><input type="text" class="form-control form-control-sm item-desc" value="${item.description}" placeholder="Item description"></td>
                            <td><input type="number" class="form-control form-control-sm item-qty" value="${item.quantity}" min="0" step="any"></td>
                            <td><input type="number" class="form-control form-control-sm item-rate" value="${item.rate}" min="0" step="any"></td>
                            <td><input type="text" class="form-control form-control-sm item-amount" value="${item.amount.toFixed(2)}" readonly></td>
                            <td><button class="btn btn-sm btn-danger remove-item" data-index="${index}"><i class="fas fa-times"></i></button></td>
                        </tr>
                    `;
                    $('#itemsTable').append(row);
                });
                calculateTotals();
            }

            // Update item description in array
            $(document).on('input', '.item-desc', function() {
                const index = $(this).closest('.item-row').data('index');
                invoiceItems[index].description = $(this).val();
            });

            // Update item quantity in array

            $(document).on('input', '.item-qty', function() {
                const index = $(this).closest('.item-row').data('index');
                const qty = parseFloat($(this).val()) || 0;
                invoiceItems[index].quantity = qty;
                invoiceItems[index].amount = qty * invoiceItems[index].rate;

                // শুধু amount field update করুন, পুরো row নয়
                $(this).closest('.item-row').find('.item-amount').val(invoiceItems[index].amount.toFixed(
                    2));
                calculateTotals();
            });


            $(document).on('input', '.item-rate', function() {
                const index = $(this).closest('.item-row').data('index');
                const rate = parseFloat($(this).val()) || 0;
                invoiceItems[index].rate = rate;
                invoiceItems[index].amount = invoiceItems[index].quantity * rate;

                // শুধু amount field update করুন, পুরো row নয়
                $(this).closest('.item-row').find('.item-amount').val(invoiceItems[index].amount.toFixed(
                    2));
                calculateTotals();
            });

            // Add new item to array
            $('#addItemBtn').on('click', function() {
                invoiceItems.push({
                    description: '',
                    quantity: 1,
                    rate: 0,
                    amount: 0
                });
                renderItems();
            });

            // Remove item from array
            $(document).on('click', '.remove-item', function() {
                if (invoiceItems.length > 1) {
                    const index = $(this).data('index');
                    invoiceItems.splice(index, 1);
                    renderItems();
                } else {
                    alert('Need at least one item!');
                }
            });

            // Calculate totals
            function calculateTotals() {
                let subtotal = 0;
                invoiceItems.forEach(item => {
                    subtotal += item.amount;
                });

                const taxType = $('#taxType').val();
                const taxValue = parseFloat($('#taxValue').val()) || 0;
                let taxAmount = 0;

                if (taxType === 'percentage') {
                    taxAmount = (subtotal * taxValue) / 100;
                    $('#displayTax').text('(' + taxValue.toFixed(2) + '%)');
                } else {
                    taxAmount = taxValue;
                    $('#displayTax').text('(Flat)');
                }

                const total = subtotal + taxAmount;

                const currency = $('#currency').val();
                $('#subtotal').text(currency + subtotal.toFixed(2));
                $('#taxAmount').text(currency + taxAmount.toFixed(2));
                $('#total').text(currency + total.toFixed(2));
            }

            // Tax/Currency change
            $('#taxValue, #currency').on('input change', calculateTotals);


            // When new file is selected
            $('#logoInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#logoPreview').attr('src', e.target.result).removeClass('d-none');
                        $('#logoPlaceholder').hide();
                    };
                    reader.readAsDataURL(file);
                }
            });
            // Collect all form data for POST request
            function getFormData() {
                let subtotal = 0;
                invoiceItems.forEach(item => {
                    subtotal += item.amount;
                });

                const taxType = $('#taxType').val();
                const taxValue = parseFloat($('#taxValue').val()) || 0;
                let taxAmount = 0;

                if (taxType === 'percentage') {
                    taxAmount = (subtotal * taxValue) / 100;
                } else {
                    taxAmount = taxValue;
                }

                const total = subtotal + taxAmount;

                const formData = new FormData();

                // Regular fields
                formData.append('from_company', $('#companyDetails').val());
                formData.append('invoice_number', $('#invoiceNumber').val());
                formData.append('invoice_date', $('#invoiceDate').val());
                formData.append('payment_terms', $('#paymentTerms').val());
                formData.append('due_date', $('#dueDate').val());
                formData.append('po_number', $('#poNumber').val());
                formData.append('bill_to', $('#billTo').val());
                formData.append('phone_number', $('#phoneNumber').val());
                formData.append('ship_to', $('#shipTo').val());
                formData.append('notes', $('#notes').val());
                formData.append('currency', $('#currency').val());
                formData.append('tax_type', taxType);
                formData.append('tax_value', taxValue);
                formData.append('subtotal', subtotal);
                formData.append('tax_amount', taxAmount);
                formData.append('total', total);

                // Logo: only append if user selected new file
                const logoFile = $('#logoInput')[0].files[0];
                if (logoFile) {
                    formData.append('logo', logoFile);
                } else if ("{{ auth()->user()->company_logo }}") {
                    // If no new file selected, send existing logo path
                    formData.append('logo', "{{ auth()->user()->company_logo }}");
                }

                // Append items
                formData.append('items', JSON.stringify(invoiceItems));

                return formData;
            }


            // Print
            $('#printBtn').on('click', function() {
                window.print();
            });

            // Download PDF - Save to database first, then download
            $('#downloadPdfBtn').on('click', function() {
                const button = $(this);
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                const formData = getFormData();

                $.ajax({
                    url: '/save-invoice',
                    type: 'POST',
                    data: formData,
                    processData: false, // Important for FormData
                    contentType: false, // Important for FormData
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            button.html(
                                '<i class="fas fa-spinner fa-spin"></i> Creating PDF...');
                            generatePDF(button);
                        } else {
                            alert('Failed to save: ' + (response.message || 'Unknown error'));
                            button.prop('disabled', false).html(
                                '<i class="fas fa-download me-1"></i> Download PDF');
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON?.message ||
                            'Failed to save invoice'));
                        button.prop('disabled', false).html(
                            '<i class="fas fa-download me-1"></i> Download PDF');
                    }
                });
            });


            // Generate PDF
            function generatePDF(button) {
                const {
                    jsPDF
                } = window.jspdf;

                // Disable the button while generating
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Generating...');

                // Target original invoice
                const invoice = document.getElementById('invoiceContent');

                // Clone the invoice node
                const clone = invoice.cloneNode(true);
                clone.id = 'invoiceClone'; // optional unique ID
                clone.style.boxShadow = 'none';
                clone.style.position = 'absolute';
                clone.style.top = '-9999px'; // invisible off-screen
                clone.style.left = '0';
                clone.style.width = invoice.offsetWidth + 'px'; // keep original width
                document.body.appendChild(clone);

                // Clean up form-related elements inside the clone
                const inputs = clone.querySelectorAll('input, textarea, select, button, label, input[type="file"]');
                inputs.forEach(el => {
                    el.style.setProperty('border', 'none', 'important');
                    el.style.setProperty('background-color', 'transparent', 'important');
                    el.style.setProperty('box-shadow', 'none', 'important');
                    el.style.setProperty('padding', '0', 'important');
                    el.style.pointerEvents = 'none';
                    el.style.color = 'inherit';
                    el.style.fontWeight = 'normal';
                    el.style.cursor = 'default';
                });

                // ❌ Hide "Remove" buttons only
                const removeButtons = clone.querySelectorAll('.remove-item');
                removeButtons.forEach(btn => {
                    btn.style.display = 'none';
                });
                /* hide add button */
                const addButton = clone.querySelector('#addItemBtn');
                if (addButton) addButton.style.display = 'none';

                // ❌ Hide Tax Type and Tax Rate rows
                const taxRows = clone.querySelectorAll('#taxType, #taxValue, #taxLabel');
                taxRows.forEach(el => {
                    // hide both input/select and their parent <tr>
                    const tr = el.closest('tr');
                    if (tr) tr.style.display = 'none';
                });
                /* add table border */


                // Generate canvas from the clone
                html2canvas(clone, {
                    scale: 2.5,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    width: clone.offsetWidth,
                    height: clone.offsetHeight
                }).then(canvas => {
                    // Remove clone after rendering
                    clone.remove();

                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const pageWidth = 210;
                    const pageHeight = 297;
                    const margin = 15;
                    const contentWidth = pageWidth - (margin * 2);
                    const contentHeight = pageHeight - (margin * 2);

                    const imgWidth = contentWidth;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    const imgData = canvas.toDataURL('image/png', 1.0);

                    let yPosition = margin;
                    let remainingHeight = imgHeight;
                    let sourceY = 0;

                    while (remainingHeight > 0) {
                        const heightOnThisPage = Math.min(remainingHeight, contentHeight);
                        const sourceHeight = (heightOnThisPage / imgWidth) * canvas.width;

                        const tempCanvas = document.createElement('canvas');
                        tempCanvas.width = canvas.width;
                        tempCanvas.height = sourceHeight;

                        const tempCtx = tempCanvas.getContext('2d');
                        tempCtx.drawImage(
                            canvas,
                            0, sourceY,
                            canvas.width, sourceHeight,
                            0, 0,
                            canvas.width, sourceHeight
                        );

                        const pageImgData = tempCanvas.toDataURL('image/png', 1.0);
                        pdf.addImage(pageImgData, 'PNG', margin, yPosition, imgWidth, heightOnThisPage);

                        sourceY += sourceHeight;
                        remainingHeight -= heightOnThisPage;

                        if (remainingHeight > 0) {
                            pdf.addPage();
                            yPosition = margin;
                        }
                    }

                    pdf.save('invoice-' + $('#invoiceNumber').val() + '.pdf');
                    button.prop('disabled', false).html(
                        '<i class="fas fa-download me-1"></i> Download PDF');
                }).catch(error => {
                    console.error('PDF generation error:', error);
                    clone.remove();
                    button.prop('disabled', false).html(
                        '<i class="fas fa-download me-1"></i> Download PDF');
                    alert('Failed to generate PDF. Please try again.');
                });
            }


            // Reset
            $('#resetBtn').on('click', function() {
                if (confirm('Clear all data?')) {
                    invoiceItems = [{
                        description: '',
                        quantity: 1,
                        rate: 0,
                        amount: 0
                    }];
                    logoBase64 = '';
                    location.reload();
                }
            });

            // Initial render
            renderItems();
        });
    </script>
@endsection
