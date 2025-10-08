<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceTitleController extends Controller
{
   public function updateField(Request $request)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'required|string',
        ]);

        $user = Auth::user();
        $invoiceTitle = $user->invoiceTitle; // get related record

        if ($invoiceTitle && in_array($request->field, [
            'invoice_number_title',
            'invoice_date_title',
            'payment_terms_title',
            'due_date_title',
            'po_number_title',
            'bill_to_title',
            'ship_to_title',
        ])) {
            $invoiceTitle->update([
                $request->field => $request->value,
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}
