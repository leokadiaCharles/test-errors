<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_tasks extends Model
{
    use HasFactory;
    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'task_id',
        'user_id',
        'completed',

    ];

}
