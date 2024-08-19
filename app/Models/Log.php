<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

        // Define the table associated with the model (optional if using Laravel conventions)
        protected $table = 'logs';

        // Define the fillable fields to allow mass assignment
        protected $fillable = [
            'task_id',
            'user_id',
            'task_title',
            'description',
            'action',
            'action_date',
        ];
    
        // Define relationships, if any
        public function task()
        {
            return $this->belongsTo(Task::class);
        }
    
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
    

