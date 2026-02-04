<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all deposits for this bank
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get all withdrawals for this bank
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get settlements where this bank is the sender
     */
    public function settlementsOut(): HasMany
    {
        return $this->hasMany(Settlement::class, 'from_bank_id');
    }

    /**
     * Get settlements where this bank is the receiver
     */
    public function settlementsIn(): HasMany
    {
        return $this->hasMany(Settlement::class, 'to_bank_id');
    }

    /**
     * Get all bank closings
     */
    public function closings(): HasMany
    {
        return $this->hasMany(BankClosing::class);
    }
}
