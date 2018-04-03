<?php

use Hemantanshu\LaravelUtility\Models\LookupType;
use Hemantanshu\LaravelUtility\Models\LookupValue;
use Illuminate\Database\Seeder;

class LookupBlockSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
        LookupType::create([
            'id'          => 100,
            'name'        => 'Blocker Address',
            'description' => 'Make sure the new ids generated are beyond 100',
        ]);

        LookupValue::create([
            'id'             => 1000,
            'lookup_type_id' => 100,
            'name'           => 'Blocker Address',
            'value'          => 'Blocker Address',
            'description'    => 'Make sure the new ids are generated beyond 1000',
        ]);
    }
}
