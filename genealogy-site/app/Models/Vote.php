<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'modification_id',
        'user_id',
        'vote',
    ];

    public function modification()
    {
        return $this->belongsTo(Modification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
