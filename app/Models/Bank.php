<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $connection = 'pgsql';

    protected $fillable = [
        'name',
        'id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
