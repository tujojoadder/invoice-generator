<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        $invoiceTitles = $authUser->invoiceTitle;
        return view('home',compact('invoiceTitles'));
    }
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'logo' => 'required', // path or file

        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            /*   নতুন ফাইল আপলোড হলে */
            $logoPath = $request->file('logo')->store('invoices/logos', 'public');
        } else {
            /* নতুন ফাইল নেই, আগের path ব্যবহার */
            $logoPath = $request->logo;
        }

        // Create invoice
        $invoice = Invoice::create([
            'user_id'        => auth()->id(),
            'invoice_number' => $request->invoice_number,
            'from_company'   => $request->from_company,
            'bill_to'        => $request->bill_to,
            'phone_number'   => $request->phone_number,
            'ship_to'        => $request->ship_to,
            'po_number'      => $request->po_number,
            'payment_terms'  => $request->payment_terms,
            'invoice_date'   => $request->invoice_date,
            'due_date'       => $request->due_date,
            'currency'       => $request->currency,
            'tax_type'       => $request->tax_type,
            'tax_value'      => $request->tax_value,
            'subtotal'       => $request->subtotal,
            'tax_amount'     => $request->tax_amount,
            'total'          => $request->total,
            'notes'          => $request->notes,
            'logo_path'      => $logoPath,
        ]);

        // Save items
        $items = json_decode($request->items, true);
        foreach ($items as $item) {
            $invoice->items()->create($item);
        }

        return response()->json([
            'success' => true,
            'invoice_id' => $invoice->id,
            'message' => 'Invoice saved successfully'
        ]);
    }


    public function update(Request $request, $id)
    {
       
         // Find existing invoice
       $invoice = Invoice::where('invoice_number', $id)->firstOrFail();
        // Validation
        $request->validate([
            'invoice_number' => 'required|string|max:50',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'logo' => 'nullable', 
        ]);

        // Handle logo upload
          // Handle logo upload
        $logoPath = $invoice->logo_path; // default old logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('invoices/logos', 'public');
        }


       // Update invoice
    $invoice->update([
        'invoice_number' => $request->invoice_number,
        'from_company'   => $request->from_company,
        'bill_to'        => $request->bill_to,
        'phone_number'   => $request->phone_number,
        'ship_to'        => $request->ship_to,
        'po_number'      => $request->po_number,
        'payment_terms'  => $request->payment_terms,
        'invoice_date'   => $request->invoice_date,
        'due_date'       => $request->due_date,
        'currency'       => $request->currency,
        'tax_type'       => $request->tax_type,
        'tax_value'      => $request->tax_value,
        'subtotal'       => $request->subtotal,
        'tax_amount'     => $request->tax_amount,
        'total'          => $request->total,
        'notes'          => $request->notes,
        'logo_path'      => $logoPath,
    ]);

    // Update items
    $invoice->items()->delete(); // পুরানো item ডিলিট করবো
    $items = json_decode($request->items, true);
    foreach ($items as $item) {
        $invoice->items()->create($item);
    }

    return response()->json([
        'success' => true,
        'invoice_id' => $invoice->id,
        'message' => 'Invoice updated successfully'
    ]);
    }
public function history()
{
    return view('history');
}



public function getHistoryData(Request $request)
{
    $query = Invoice::query();

    if ($request->filled('bill_to')) {
        $query->where('bill_to', 'like', '%' . $request->bill_to . '%');
    }

    if ($request->filled('from_date')) {
        $query->whereDate('invoice_date', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('invoice_date', '<=', $request->to_date);
    }

    return DataTables::of($query)
        ->editColumn('invoice_date', function ($invoice) {
            return $invoice->invoice_date ? $invoice->invoice_date->format('d M, Y') : '';
        })
        ->editColumn('due_date', function ($invoice) {
            return $invoice->due_date ? $invoice->due_date->format('d M, Y') : '';
        })
        ->addColumn('action', function ($invoice) {
            $viewUrl = route('invoices.view', $invoice->id);
            $editUrl = route('invoices.edit', $invoice->id);
            $deleteUrl = route('invoices.destroy', $invoice->id);

            return '
                <a href="'.$viewUrl.'" class="btn btn-sm btn-primary">View</a>
                <a href="'.$editUrl.'" class="btn btn-sm btn-info" title="Edit Invoice">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="'.$deleteUrl.'" method="POST" class="d-inline-block delete-form" data-invoice-id="'.$invoice->id.'">
                    '.csrf_field().method_field('DELETE').'
                    <button type="submit" class="btn btn-sm btn-danger delete-btn" title="Delete Invoice">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
}




    public function destroy(Invoice $invoice)
    {
        // Optional: delete related items
        $invoice->items()->delete();
        /* <<<--- Not deleting the physical image because it could still be referenced as the company logo */
        // Delete the invoice
        $invoice->delete();

        return redirect()->route('invoices.history')->with('success', 'Invoice deleted successfully.');
    }


    public function edit(Invoice $invoice)
    {
        $invoice->load('items'); // Load related items
        $invoiceTitles = auth()->user()->invoiceTitle;
        return view('edit_invoice', compact('invoice','invoiceTitles'));
    }

    public function view($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $invoiceTitles = auth()->user()->invoiceTitle;
        return view('invoices.view', compact('invoice','invoiceTitles'));
    }
}