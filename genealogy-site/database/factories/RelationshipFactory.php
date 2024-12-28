<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RelationshipFactory extends Factory
{
    protected $model = \App\Models\Relationship::class;

    public function definition()
    {
        return [
            'created_by' => 1, // 默认由用户ID为1创建
            'parent_id' => \App\Models\Person::inRandomOrder()->first()->id, // 随机父母ID
            'child_id' => \App\Models\Person::inRandomOrder()->first()->id, // 随机子女ID
        ];
    }
}

