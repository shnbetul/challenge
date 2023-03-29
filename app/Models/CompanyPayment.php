<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'package_id',
        'package_price',
        'payment_status',
        'card_info',
        'receipt'
    ];

    protected $casts = [
        'card_info' => 'array'
    ];
}
