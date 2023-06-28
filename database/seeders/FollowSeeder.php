<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<30; $i++) {
            User::query()->inRandomOrder()->limit(1)->first()->followers()->attach(rand(1,64));
            User::query()->inRandomOrder()->limit(1)->first()->followed()->attach(rand(1,64));
        }
    }
}
