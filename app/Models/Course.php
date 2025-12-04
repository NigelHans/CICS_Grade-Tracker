<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_code',
        'course_title',
        'description',
        'credits',
        'semester',
        'instructor',
        'room',
        'lecturer_id',
        'syllabus'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lecturer()
    {
    return $this->belongsTo(User::class, 'lecturer_id');
    }
}