<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_no',
        'invoice_date',
        'client_name',
        'client_address',
        'client_gstin',
        'subtotal',
        'cgst_rate',
        'cgst_amount',
        'sgst_rate',
        'sgst_amount',
        'grand_total'
    ];

    public function items()
    {
        return $this->hasMany(
            InvoiceItem::class
        );
    }
}
