<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (!User::where('email', 'ambatugrow@gmail.com')->exists()) {
            User::create([
                'name' => 'AmbatuGrowOfficial',
                'email' => 'ambatugrow@gmail.com',
                'password' => bcrypt('ambatu12345'),
            ]);
        } else {
            User::where('email', 'ambatugrow@gmail.com')->update(['name' => 'AmbatuGrowOfficial']);
        }

        $this->call([
            SprfSeeder::class,
            MainSeeder::class,
            CrmSeeder::class,
        ]);
    }
}
