<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $casts = [
        'invoice_date' => 'date',   // এখন invoice_date হবে Carbon instance
        'due_date' => 'date',
    ];
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'from_company',
        'bill_to',
        'phone_number',
        'ship_to',
        'po_number',
        'payment_terms',
        'invoice_date',
        'due_date',
        'currency',
        'tax_type',
        'tax_value',
        'subtotal',
        'tax_amount',
        'total',
        'notes',
        'logo_path'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}