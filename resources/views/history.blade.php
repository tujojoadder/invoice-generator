@extends('layouts.app')

@section('title', 'History')

@section('content')
    <div class="card p-4 shadow-sm">
        <h4 class="mb-3">Invoice History</h4>

        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Customer</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->bill_to }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                        <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                        <td>{{ $invoice->currency ?? '$' }} {{ number_format($invoice->total, 2) }}</td>
                        <td>
                            <!-- 🟢 view Button -->
                            <a href="{{ route('invoices.view', $invoice->id) }}" 
                               class="btn btn-sm btn-info text-white" title="View Invoice">
                                <i class="fas fa-eye"></i> {{-- 👁 Font Awesome icon --}}
                            </a>

                            <!-- ✏️ Edit Button -->
                            <a href="{{ route('invoices.edit', $invoice->id) }}" 
                               class="btn btn-sm btn-primary" title="Edit Invoice">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- ❌ Delete Button -->
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" 
                                  method="POST" class="d-inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Delete Invoice">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No invoices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
