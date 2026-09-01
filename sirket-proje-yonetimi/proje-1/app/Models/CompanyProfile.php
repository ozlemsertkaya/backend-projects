<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'website', 'phone', 'address'];
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
