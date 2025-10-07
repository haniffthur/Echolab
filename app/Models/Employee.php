<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'employee_id',
        'department',
        'is_active',
    ];

    /**
     * Seorang Karyawan bisa punya banyak kartu (polymorphic).
     * Berguna jika kartu hilang dan diganti baru.
     */
    public function cards(): MorphMany
    {
        return $this->morphMany(Card::class, 'cardable');
    }

    /**
     * Seorang Karyawan bisa punya banyak log akses (polymorphic).
     */
    public function accessLogs(): MorphMany
    {
        return $this->morphMany(AccessLog::class, 'userable');
    }
}