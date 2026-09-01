<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function profile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, Department::class);
    }
}
