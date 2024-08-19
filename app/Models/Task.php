<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'user_id',
        'status',
    ];

    public function assigner(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function assignees(){
        return $this->belongsToMany(User::class,'user_tasks','task_id','user_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tasks', 'task_id', 'user_id')
                    ->withTimestamps()
                    ->withPivot('completed');
    }

public function user()
{
    return $this->belongsTo(User::class);
}

}
