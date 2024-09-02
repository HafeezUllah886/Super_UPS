<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\expense_categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
         'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin'),
            'lang' => 'en',
        ]);

        $cats = [
            ['exp_cat' => 'گھر کھاتہ'],
            ['exp_cat' => 'ڈاکٹر خرچہ'],
            ['exp_cat' => 'سکول خرچہ'],
            ['exp_cat' => 'بلز'],
            ['exp_cat' => 'دکان خرچہ'],
            ['exp_cat' => 'گائے خرچہ'],
            ['exp_cat' => 'زکوٰۃ کھاتہ'],
            ['exp_cat' => 'خیرات کھاتہ'],
            ['exp_cat' => 'کارکن خرچہ'],
        ];

        expense_categories::insert($cats);
    }
}
