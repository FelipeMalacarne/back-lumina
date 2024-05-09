<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\HybridRelations;

class Account extends Model
{
    use HasFactory, HasUuids, HybridRelations;

    protected $fillable = [
        'name',
        'type',
        'number',
        'check_digit',
        'balance',
        'bank_id',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getDecimalBalanceAttribute(): float
    {
        return $this->balance / 100;
    }

    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->decimal_balance, 2);
    }
}
