@forelse ($invoices as $invoice)
<tr>
    <td>{{ $invoice->bill_to }}</td>
    <td>{{ $invoice->invoice_number }}</td>
    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
    <td>{{ $invoice->currency ?? '$' }} {{ number_format($invoice->total, 2) }}</td>
    <td>
        <a href="{{ route('invoices.view', $invoice->id) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center text-muted">No invoices found.</td>
</tr>
@endforelse
