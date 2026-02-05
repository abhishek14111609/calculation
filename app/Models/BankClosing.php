<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankClosing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date',
        'bank_id',
        'actual_closing',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'actual_closing' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the bank this closing belongs to
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
