<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrackedOlxSearchesTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('tracked_olx_searches')->truncate();

        $data = [
            [
                'url'               => 'https://www.olx.ua/list/q-lenovo/',
                'active'            => true,
                'tracking_interval' => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        DB::table('tracked_olx_searches')->insert($data);
    }
}
