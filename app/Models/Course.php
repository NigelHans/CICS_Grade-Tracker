<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_code',
        'course_title',
        'description'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}