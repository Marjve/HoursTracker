<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Department::create([
            'name' => 'C4S'
        ]);
        \App\Department::create([
            'name' => 'HM'
        ]);
        \App\Department::create([
            'name' => 'General'
        ]);
        \App\Department::create([
            'name' => 'Other'
        ]);
    }
}
