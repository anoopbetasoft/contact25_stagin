<?php


use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PSellOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('p_sell_options')->insert([
        	[
            'name' => 'all_friends',
            'display_name' => 'All Friends',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'name' => 'family',
            'display_name' => 'Family',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'name' => 'work',
            'display_name' => 'Work',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'name' => 'neighbours',
            'display_name' => 'Neighbours',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'name' => 'uk',
            'display_name' => 'UK',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        ]);
    }
}
