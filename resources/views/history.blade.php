@extends('layouts.app')

@section('title', 'Invoice History')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Invoice History</h4>

    {{-- Filters --}}
    <div class="row mb-3">
        <div class="col-md-4 mb-2">
            <input type="text" id="searchCustomer" class="form-control" placeholder="Search by Customer Name (bill_to)">
        </div>
        <div class="col-md-3 mb-2">
            <input type="date" id="fromDate" class="form-control" placeholder="From Date">
        </div>
        <div class="col-md-3 mb-2">
            <input type="date" id="toDate" class="form-control" placeholder="To Date">
        </div>
        <div class="col-md-2 mb-2">
            <button id="resetFilters" class="btn btn-secondary w-100">Reset</button>
        </div>
    </div>

    {{-- DataTable --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="invoiceTable" style="width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Customer Name</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- DataTables & Bootstrap --}}
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    // Initialize DataTable
    let table = $('#invoiceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('invoices.history.data') }}",
            data: function (d) {
                d.bill_to = $('#searchCustomer').val();
                d.from_date = $('#fromDate').val();
                d.to_date = $('#toDate').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'invoice_number', name: 'invoice_number' },
            { data: 'bill_to', name: 'bill_to' },
            { data: 'invoice_date', name: 'invoice_date' },
            { data: 'due_date', name: 'due_date' },
            { data: 'total', name: 'total' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        lengthMenu: [10, 25, 50],
        pagingType: 'simple', // Next / Previous only
        order: [[0, 'desc']], // default sorting by ID desc
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        },
      
    });

    // Filters
    $('#searchCustomer, #fromDate, #toDate').on('keyup change', function () {
        table.ajax.reload();
    });

    // Reset button
    $('#resetFilters').on('click', function () {
        $('#searchCustomer').val('');
        $('#fromDate').val('');
        $('#toDate').val('');
        table.ajax.reload();
    });
});
</script>
@endsection
