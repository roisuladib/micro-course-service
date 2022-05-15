<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table = 'chapters';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'name', 'courses_id',
    ];

    public function lessons() {
        return $this->hasMany('App\Lesson', 'chapters_id')->orderBy('id', 'DESC');
    }
}
