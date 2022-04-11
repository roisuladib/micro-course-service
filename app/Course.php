<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'name', 'certificate', 'thumbnail', 'type', 'status', 'price', 'level', 'description', 'mentors_id'
    ];
    
    public function mentor() {
        return $this->belongsTo('App\Mentor', 'mentors_id');
    }
    public function chapter() {
        return $this->hasMany('App\Chapter', 'courses_id')->orderBy('id', 'ASC');
    }
    public function image() {
        return $this->hasMany('App\ImageCourse', 'courses_id')->orderBy('id', 'DESC');
    }
}
