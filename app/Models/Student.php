<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' , 'university_id' , 'phone_number' , 'date_of_birth'] ;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function problems(): BelongsToMany
    {
        return $this->belongsToMany(Problem::class);
    }
        public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_student')
            ->withPivot('mark', 'code1', 'code2');
    }
        public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_student')
           ->withPivot('attendance_marks','assessment_marks','number_of_assessment');
    }

    public function contests(): BelongsToMany
    {
        return $this->belongsToMany(Contest::class);
    }
}
