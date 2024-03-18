<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'teacher_subject_id',
        'number_of_lessons',
        'mark_of_commings',
        'mark_of_ratings',
        ] ;

    public function teacherStudent(): BelongsTo
    {
        return $this->belongsTo(SubjectTeacher::class);
    }
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
 
    public function subjectTeacher()
    {
        return $this->belongsTo(SubjectTeacher::class, 'teacher_subject_id', 'id');
    }
}
