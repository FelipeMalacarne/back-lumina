<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function transactions(): HasMany
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
