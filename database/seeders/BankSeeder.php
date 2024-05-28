<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        Bank::insert(collect(config('banks'))->map(fn ($bank) => [
            'name' => $bank['name'],
            'id' => $bank['code'],
            'updated_at' => now(),
            'created_at' => now(),
        ])->toArray());
    }
}
