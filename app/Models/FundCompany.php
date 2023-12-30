<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundCompany extends Model
{
    use HasFactory;

    // Assuming you have fund_id and company_id columns in your fund_companies table
    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
