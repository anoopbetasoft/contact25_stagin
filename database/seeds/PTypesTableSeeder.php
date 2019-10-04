<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::table('p_types')->insert([
        	[
            'type' => 'item',
            'display_text' => 'Item',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'service',
            'display_text' => 'Service',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'subs_member',
            'display_text' => 'Subscription / Membership',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        ]);
    }
}
