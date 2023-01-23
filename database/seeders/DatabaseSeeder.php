<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(GenreTableSeeder::class);
        $this->call(AuthorTableSeeder::class);
        $this->call(RatingTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(MovieTableSeeder::class);
        $this->call(MovieTagTableSeeder::class);
    }
}
