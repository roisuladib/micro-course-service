<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    protected $table = 'my_courses';
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'courses_id', 'users_id'
    ];

    public function course() {
        return $this->belongsTo('App\Course', 'courses_id'); 
    }
}
