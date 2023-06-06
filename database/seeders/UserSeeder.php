<?php

namespace Database\Seeders;

use App\Http\Enums\UserTypeEnums;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->create([
            'user_type' => UserTypeEnums::ADMIN,
        ]);
        User::factory()->count(3)->create([
           'user_type' => UserTypeEnums::MODERATOR,
        ]);
        User::factory()->count(20)->create([
           'user_type' => UserTypeEnums::USER,
        ]);
        User::factory()->count(40)->create([
           'user_type' => UserTypeEnums::NEWBIE,
        ]);
    }
}
