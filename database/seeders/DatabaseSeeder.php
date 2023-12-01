<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\account;
use App\Models\catergory;
use App\Models\company;
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

        catergory::create(
            [
                'cat' => "Currency"
            ]
        );

        company::create(
            [
                'name' => "US Doller"
            ]
        );

        company::create(
            [
                'name' => "Iranian Tuman"
            ]
        );
        account::create(
            [
                'title' => "Cash",
                'type' => 'Business',
                'Category' => 'Cash',
            ]
        );
        account::create(
            [
                'title' => "Customer",
                'type' => 'Customer',
            ]
        );
       /*  account::create(
            [
                'title' => "Supplier",
                'type' => 'Vendor',
            ]
        ); */
    }
}
