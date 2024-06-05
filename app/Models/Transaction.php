<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'amount',
        'date_posted',
        'fitid',
        'memo',
        'currency',
        'account_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_posted',
    ];

    protected function casts(): array
    {
        return [
            'date_posted' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function getDecimalAmountAttribute(): float
    {
        return $this->amount / 100;
    }

    public function getFormattedAmountAttribute(): string
    {
        return Number::currency($this->decimal_amount, in: $this->currency);
    }
}
