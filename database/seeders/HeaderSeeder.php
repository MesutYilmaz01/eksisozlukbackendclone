<?php

namespace Database\Seeders;

use App\Http\Enums\UserTypeEnums;
use App\Models\Header;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<20; $i++) {
            Header::factory()->count(1)->create([
                'created_by' => User::where('user_type','!=', UserTypeEnums::NEWBIE)->inRandomOrder()->limit(1)->first()->id
            ]);
        }
    }
}
