<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

use DB;
use Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Employee::factory(10)->create();
    }
}
