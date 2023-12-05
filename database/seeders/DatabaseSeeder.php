<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\account;
use App\Models\catergory;
use App\Models\company;
use App\Models\products;
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
            'doller' => '284.75'
        ]);

        catergory::create(
            [
                'cat' => "Battery",
            ]
        );
        catergory::create(
            [
                'cat' => "UPS",
            ]
        );

        company::create(
            [
                'name' => "OSAKA"
            ]
        );
        company::create(
            [
                'name' => "Phonex"
            ]
        );

        products::create(
            [
                'name' => 'TX-125',
                'coy' => 1,
                'cat' => 1,
                'price' => 5000,
            ]
        );

        products::create(
            [
                'name' => 'ABC-500wt',
                'coy' => 2,
                'cat' => 2,
                'price' => 10000,
            ]
        );

        account::create(['title' => 'Cash', 'type' => 'Business', 'Category' => 'Cash']);
        account::create(['title' => 'Test Vendor', 'type' => 'Vendor']);
        account::create(['title' => 'Test Customer', 'type' => 'Customer']);
    }
}
