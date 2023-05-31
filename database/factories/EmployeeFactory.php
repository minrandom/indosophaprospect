<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $sex = ['male', 'female'];
        $marital=["Single","Married","Widowed"];
        $area=["National","Barat","Timur","HO"];
        return [
            'longname' => $this->faker->name,
            'sex' => $sex[rand(0,1)],
            'birthdate' => $this->faker->date,
            'birthplace' => $this->faker->city,
            'NIK' => "31234444214241",
            'marital_status' => $marital[rand(0,2)],
            'address'=> $this->faker-> address,
            'position'=>$this->faker->jobTitle,
            'area' => $area[rand(0,3)],
        ];
    }
}
