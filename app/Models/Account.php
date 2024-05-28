<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
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
        'description',
        'color',
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

    public function getBankNameAttribute(): string
    {
        return Collection::make(Config::get('banks'))
            ->firstWhere('code', $this->bank_id)['name'];
    }
}
