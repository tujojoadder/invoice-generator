<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('image')) {
            $logoPath = $request->file('image')->store('invoices/logos', 'public');
        }

        // Create invoice
        $invoice = Invoice::create([
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
        $items = $request->items;
        foreach ($items as $item) {
            $invoice->items()->create($item);
        }

        return response()->json([
            'success' => true,
            'invoice_id' => $invoice->id,
            'message' => 'Invoice saved successfully'
        ]);
    }
}