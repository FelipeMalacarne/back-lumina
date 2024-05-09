<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $connection = 'mongodb';

    protected $fillable = [
        'amount',
        'date_posted',
        'fitid',
        'memo',
        'currency',
        'account_id',
        'created_at',
    ];

    protected $dates = [
        'created_at',
        'date_posted'
    ];

    protected function casts(): array
    {
        return [
            'date_posted' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
        ];
    }
}
