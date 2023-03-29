<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'package_id',
        'start_date',
        'end_date',
    ];
}
