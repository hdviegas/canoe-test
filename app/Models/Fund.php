<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'fund_manager_id',
        'name',
        'start_year',
        'aliases',
    ];

    protected $casts = [
        'aliases' => 'array',
    ];

    public function fundManager()
    {
        return $this->belongsTo(FundManager::class);
    }
}
