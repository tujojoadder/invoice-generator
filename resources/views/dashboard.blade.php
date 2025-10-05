<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white p-3" style="min-height: 100vh; position: fixed; width: 16.6667%;">
                <h5 class="mb-4">Settings</h5>

                <div class="mb-3">
                    <label class="form-label small">Currency</label>
                    <select class="form-select form-select-sm" id="currency">
                        <option value="$">USD ($)</option>
                        <option value="€">EUR (€)</option>
                        <option value="£">GBP (£)</option>
                        <option value="৳">BDT (৳)</option>
                        <option value="₹">INR (₹)</option>
                        <option value="¥">JPY (¥)</option>
                    </select>
                </div>



                <hr class="border-secondary">

                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-sm" id="downloadPdfBtn">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </button>
                    <button class="btn btn-outline-light btn-sm" id="printBtn">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button class="btn btn-outline-danger btn-sm" id="resetBtn">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>

                    {{-- logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Invoice -->
            <div class="p-4" style="margin-left: 16.6667%; width: 83.3333%;">
                <div id="invoiceContent" class="  rounded shadow p-5  border-top "
                    style="max-width: 900px; margin: 0 auto;">

                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col-6">
                            <input type="file" id="logoInput" class="d-none" accept="image/*">
                            <label for="logoInput" class="mb-3">
                                <div class="border rounded p-2 text-center"
                                    style="cursor: pointer; height: 100px; display: flex; align-items: center; justify-content: center;">
                                    <div id="logoPlaceholder">
                                        <i class="fas fa-image text-muted"></i>
                                        <p class="mb-0 small text-muted">Upload Logo</p>
                                    </div>
                                    <img id="logoPreview" src="" class="img-fluid d-none"
                                        style="max-height: 90px;">
                                </div>
                            </label>

                            <textarea style="resize: none" class="form-control border small" rows="2" id="companyDetails"
                                placeholder="who is this from?"></textarea>
                        </div>
                        <div class="col-6 text-end">
                            <h1 class="mb-3">INVOICE</h1>
                            <table class="table table-sm table-borderless ms-auto" style="max-width: 300px;">
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm border-0 fw-bold"
                                            value="Invoice #"></td>
                                    <td><input type="text" class="form-control form-control-sm" value="INV-001"
                                            id="invoiceNumber"></td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm border-0 fw-bold"
                                            value="Date"></td>
                                    <td><input type="date" class="form-control form-control-sm" id="invoiceDate">
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm border-0 fw-bold"
                                            value="Payment Terms"></td>
                                    <td><input type="text" class="form-control form-control-sm" value="Net 30"
                                            id="paymentTerms">
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm border-0 fw-bold"
                                            value="Due Date"></td>
                                    <td><input type="date" class="form-control form-control-sm" id="dueDate">
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control form-control-sm border-0 fw-bold"
                                            value="PO Number"></td>
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
                            <input type="text" class="form-control mb-2 form-control-sm border-0 fw-bold"
                                value="Bill To">
                            <textarea style="resize: none" class="form-control" rows="2" id="billTo" placeholder="who is this to?"></textarea>
                            <input type="text" class="form-control form-control-sm mt-3" id="phoneNumber"
                                placeholder="Phone Number">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control mb-2 form-control-sm border-0 fw-bold"
                                value="Ship To">
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

                    <button class="btn btn-sm btn-outline-primary mb-4" id="addItemBtn">
                        <i class="fas fa-plus"></i> Add Item
                    </button>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-7">
                            <strong>Notes</strong>
                            <textarea class="form-control mt-2" rows="4" id="notes" placeholder="Thank you for your business!"></textarea>
                        </div>
                        <div class="col-5">
                            <table class="table table-sm">

                                {{-- tax type --}}
                                <tr>
                                    <div class="mb-3">
                                        <th style="width: 30%"> <label class="form-label small">Tax Type</label></th>
                                        <th style="width: 70%"><select class="form-select form-select-sm"
                                                id="taxType">
                                                <option value="percentage">Percentage (%)</option>
                                                <option value="flat">Flat Amount</option>
                                            </select></th>
                                    </div>
                                </tr>
                                {{-- tax rate --}}
                                <tr>
                                    <div class="mb-3">
                                        <th style="width: 30%"> <label class="form-label small">Tax Rate (%)</label>
                                        </th>
                                        <th style="width: 70%"><input type="number"
                                                class="form-control form-control-sm" id="taxValue" value="0"
                                                min="0" step="1"></th>
                                    </div>
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
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        $(document).ready(function() {
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

            // Logo upload
            $('#logoInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        logoBase64 = e.target.result;
                        $('#logoPreview').attr('src', logoBase64).removeClass('d-none');
                        $('#logoPlaceholder').hide();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Tax type change
            $('#taxType').on('change', function() {
                const taxType = $(this).val();
                if (taxType === 'percentage') {
                    $('#taxLabel').text('Tax Rate (%)');
                    $('#taxValue').attr('max', '100');
                } else {
                    $('#taxLabel').text('Tax Amount');
                    $('#taxValue').removeAttr('max');
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
                            <td><input type="number" class="form-control form-control-sm item-qty" value="${item.quantity}" min="0"></td>
                            <td><input type="number" class="form-control form-control-sm item-rate" value="${item.rate}" min="0" step="1"></td>
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
                renderItems();
            });

            // Update item rate in array
            $(document).on('input', '.item-rate', function() {
                const index = $(this).closest('.item-row').data('index');
                const rate = parseFloat($(this).val()) || 0;
                invoiceItems[index].rate = rate;
                invoiceItems[index].amount = invoiceItems[index].quantity * rate;
                renderItems();
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

                return {
                    logo: logoBase64,
                    from_company: $('#companyDetails').val(),
                    invoice_number: $('#invoiceNumber').val(),
                    invoice_date: $('#invoiceDate').val(),
                    payment_terms: $('#paymentTerms').val(),
                    due_date: $('#dueDate').val(),
                    po_number: $('#poNumber').val(),
                    bill_to: $('#billTo').val(),
                    phone_number: $('#phoneNumber').val(),
                    ship_to: $('#shipTo').val(),
                    items: invoiceItems,
                    notes: $('#notes').val(),
                    currency: $('#currency').val(),
                    tax_type: taxType,
                    tax_value: taxValue,
                    subtotal: subtotal,
                    tax_amount: taxAmount,
                    total: total
                };
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

                // POST request to save invoice
                $.ajax({
                    url: '/save-invoice', // Change this to your Laravel route
                    type: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
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

                html2canvas($('#invoiceContent')[0], {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    width: $('#invoiceContent').outerWidth(),
                    height: $('#invoiceContent').outerHeight()
                }).then(canvas => {
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const imgWidth = 210;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    const pageHeight = 297;

                    let heightLeft = imgHeight;
                    let position = 0;

                    const imgData = canvas.toDataURL('image/png');
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    pdf.save('invoice-' + $('#invoiceNumber').val() + '.pdf');
                    button.prop('disabled', false).html(
                        '<i class="fas fa-download me-1"></i> Download PDF');
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
</body>

</html
