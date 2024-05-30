<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
