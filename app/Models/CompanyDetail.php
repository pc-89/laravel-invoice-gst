<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $table = 'company_details';

    protected $fillable = [
        'user_id', 'company_name', 'address', 'mobile', 'email',
        'gstin', 'state_name', 'state_code', 'account_name',
        'account_no', 'bank_name', 'ifsc_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
