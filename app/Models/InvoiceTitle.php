<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTitle extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'invoice_number_title',
        'invoice_date_title',
        'payment_terms_title',
        'due_date_title',
        'po_number_title',
        'bill_to_title',
        'ship_to_title',
    ];

    // Relation with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
