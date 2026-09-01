<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description'];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class)->withTimestamps();
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
