<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getDecimalAmountAttribute(): string
    {
        return $this->amount / 100;
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->decimal_amount, 2);
    }
}
