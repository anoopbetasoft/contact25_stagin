<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PRoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('p_rooms')->insert([
        	[
            'type' => 'office',
            'display_text' => 'Office',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'shed',
            'display_text' => 'Shed',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'attic',
            'display_text' => 'Attic',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'warehouse',
            'display_text' => 'Warehouse',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	]
        ]);
    }
}
