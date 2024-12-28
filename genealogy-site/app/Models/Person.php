<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by', 'first_name', 'last_name', 'birth_name', 'middle_names', 'date_of_birth'
    ];

    // 一个用户可以创建多个人物
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 一个人可以有多个子女
    public function children()
    {
        return $this->hasMany(Relationship::class, 'parent_id');
    }

    // 一个人可以有多个父母
    public function parents()
    {
        return $this->hasMany(Relationship::class, 'child_id');
    }


    public function getDegreeWith($target_person_id)
    {
        // BFS（广度优先搜索）算法实现
        $visited = []; // 已访问节点
        $queue = [[$this->id, 0]]; // 初始化队列，格式：[person_id, degree]

        while (!empty($queue)) {
            [$current_id, $degree] = array_shift($queue);

            // 如果超过25度，则停止搜索
            if ($degree > 25) {
                return false;
            }

            // 如果找到目标人物，返回度数
            if ($current_id == $target_person_id) {
                return $degree;
            }

            // 标记当前节点为已访问
            if (in_array($current_id, $visited)) {
                continue;
            }
            $visited[] = $current_id;

            // 查找与当前节点关联的父母和子女
            $relations = DB::table('relationships')
                ->where('parent_id', $current_id)
                ->orWhere('child_id', $current_id)
                ->get();

            foreach ($relations as $relation) {
                $next_id = $relation->parent_id == $current_id ? $relation->child_id : $relation->parent_id;
                if (!in_array($next_id, $visited)) {
                    $queue[] = [$next_id, $degree + 1];
                }
            }
        }

        // 未找到目标人物
        return false;
    }

}
