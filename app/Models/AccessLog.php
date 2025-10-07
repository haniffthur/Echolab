<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccessLog extends Model
{
    use HasFactory;

    /**
     * Di migrasi, kita hanya membuat created_at, jadi matikan updated_at.
     */
    public const UPDATED_AT = null;

    /**
     * Izinkan semua atribut untuk diisi (mass assignable).
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Tentukan tipe data untuk kolom timestamp.
     */
    protected $casts = [
        'tap_time' => 'datetime',
    ];

    /**
     * Sebuah log akses milik satu Gate.
     */
    public function gate(): BelongsTo
    {
        return $this->belongsTo(Gate::class);
    }

    /**
     * Sebuah log akses milik satu Kartu.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Sebuah log akses milik satu User (Employee, Visitor, dll).
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }
}