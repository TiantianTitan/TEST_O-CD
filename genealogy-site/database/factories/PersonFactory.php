<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = \App\Models\Person::class;

    public function definition()
    {
        return [
            'created_by' => 1, // 默认由用户ID为1创建
            'first_name' => $this->faker->firstName,
            'last_name' => strtoupper($this->faker->lastName),
            'birth_name' => strtoupper($this->faker->lastName),
            'middle_names' => $this->faker->optional()->words(2, true), // 随机中间名或NULL
            'date_of_birth' => $this->faker->date('Y-m-d', '2000-01-01'), // 2000年之前的随机生日
        ];
    }
}
