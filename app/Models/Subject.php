<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class);
    }
    public function categories()
    {
        return $this->hasMany(Category::class, 'teacher_subject_id', 'id');
    }
    public function subjectTeachers(){
        return $this->hasMany(SubjectTeacher::class) ;
    }
    public function exam()
    {
        return $this->hasOne(Exam::class);
    }
}
