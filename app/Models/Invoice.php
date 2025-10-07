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
        'invoice_date',
        'payment_terms',
        'due_date',
        'po_number',
        'bill_to',
        'ship_to',
        'phone_number',
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