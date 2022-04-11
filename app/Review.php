<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    protected $fillable = [
        'users_id', 'courses_id', 'rating', 'note'
    ];

    public function course() {
        return $this->belongsTo('App\Course', 'courses_id'); 
    }
}
