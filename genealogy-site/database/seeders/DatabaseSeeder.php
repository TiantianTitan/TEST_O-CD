<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Relationship;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 生成 100 个人物数据
        Person::factory()->count(100)->create();

        // 生成 200 条关系数据
        Relationship::factory()->count(200)->create();
    }
}
