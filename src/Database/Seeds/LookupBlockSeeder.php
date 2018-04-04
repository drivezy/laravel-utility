<?php

use Drivezy\LaravelUtility\Models\LookupType;
use Drivezy\LaravelUtility\Models\LookupValue;
use Illuminate\Database\Seeder;

class LookupBlockSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run () {
        //block the lookup type till id 100
        $obj = LookupType::create([
            'id'          => 100,
            'name'        => 'Blocker Address',
            'description' => 'Make sure the new ids generated are beyond 100',
        ]);
        $obj->delete();

        //block the lookup value record till 1000
        $obj = LookupValue::create([
            'id'             => 1000,
            'lookup_type_id' => 100,
            'name'           => 'Blocker Address',
            'value'          => 'Blocker Address',
            'description'    => 'Make sure the new ids are generated beyond 1000',
        ]);
        $obj->delete();
    }
}
