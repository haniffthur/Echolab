<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Card extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cardno',
        'type', 
        'cardable_id',
        'cardable_type',
        'is_active',
    ];

    /**
     * Sebuah Kartu dimiliki oleh satu model (Employee, Visitor, dll).
     */
    public function cardable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Sebuah Kartu bisa memiliki banyak log akses.
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(AccessLog::class);
    }
}