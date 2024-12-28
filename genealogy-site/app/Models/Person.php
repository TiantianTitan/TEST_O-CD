<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
