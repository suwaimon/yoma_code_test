<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'Ko Ko',
                'email' => 'koko@gmail.com',
                'password' => Hash::make('12345678'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'name' => 'Su Su',
                'email' => 'susu@gmail.com',
                'password' => Hash::make('12345678'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
            [
                'name' => 'Ei Ei',
                'email' => 'eiei@gmail.com',
                'password' => Hash::make('12345678'),
                'created_at'=> Carbon::now(),
                'updated_at'=> Carbon::now()
            ],
        ];
        User::insert($user);
    }
}
