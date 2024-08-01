<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('email', 'admin@gmail.com')->first();

        $vendor = new Vendor();
        $vendor->banner = 'uploads/1343.jpg';
        $vendor->phone = '0375566576';
        $vendor->shop_name = 'Admin Shop';
        $vendor->email = 'admin@gmail.com';
        $vendor->address = 'Ha noi';
        $vendor->description = 'shop description';
        $vendor->user_id = $user->id;
        $vendor->save();
    }
}