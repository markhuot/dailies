<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $mark = new User;
        $mark->name = 'mark huot';
        $mark->email = 'mark@markhuot.com';
        $mark->password = Hash::make('secret');
        $mark->save();
    }
}
