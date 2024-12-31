<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_id',
        'proposed_by',
        'type',
        'content',
        'status',
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function target()
    {
        return $this->belongsTo(Person::class, 'target_id');
    }

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }
}