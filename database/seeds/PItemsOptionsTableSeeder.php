<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PItemsOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('p_items_options')->insert([
        	[
            'type' => 'all_friends',
            'display_text' => 'All Friends',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'family',
            'display_text' => 'Family',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'work',
            'display_text' => 'Work',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'neighbours',
            'display_text' => 'Neighbours',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'uk',
            'display_text' => 'UK',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        ]);
    }
}
