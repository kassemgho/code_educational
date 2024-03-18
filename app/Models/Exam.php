<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = ['passwd' , 'adminstrator_id' , 'name' ,'time', 'subject_id'] ;

    public function qustions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'exam_studentt');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }

    public function trueFalseQuestions(): HasMany
    {
        return $this->hasMany(TrueFalseQuestion::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
