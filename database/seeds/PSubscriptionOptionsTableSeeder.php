<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PSubscriptionOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	DB::table('p_subscription_options')->insert([
        	[
            'type' => 'location',
            'display_text' => 'Location',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'delivered',
            'display_text' => 'delivered',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        	[
            'type' => 'time',
            'display_text' => 'Time',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],

        	[
            'type' => 'group',
            'display_text' => 'Group',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        	],
        ]);
    }
}
