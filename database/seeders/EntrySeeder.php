<?php

namespace Database\Seeders;

use App\Http\Enums\UserTypeEnums;
use App\Models\Entry;
use App\Models\Header;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<20; $i++) {
            $user = User::inRandomOrder()->limit(1)->first();
            Entry::factory()->count(1)->create([
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'header_id' => Header::inRAndomOrder()->limit(1)->first()->id
            ]);
        }
    }
}
