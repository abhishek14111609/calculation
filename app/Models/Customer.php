<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'customer_name',
        'mobile_number',
        'email',
        'address',
        'opening_balance',
        'extra_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'opening_balance' => 'decimal:2',
        'extra_data' => 'array',
    ];

    /**
     * Get all transactions for this customer
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'customer_id');
    }

    /**
     * Calculate total credit for this customer
     */
    public function getTotalCreditAttribute()
    {
        return $this->transactions->where('transaction_type', 'credit')->sum('amount');
    }

    /**
     * Calculate total debit for this customer
     */
    public function getTotalDebitAttribute()
    {
        return $this->transactions->where('transaction_type', 'debit')->sum('amount');
    }

    /**
     * Calculate final balance for this customer
     * Formula: opening_balance + total_credit - total_debit
     */
    public function getFinalBalanceAttribute()
    {
        return $this->opening_balance + $this->total_credit - $this->total_debit;
    }
}
