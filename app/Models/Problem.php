<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id' , 'name' , 'description' , 'teacher_code_solve', 'active'
    ];
    protected $hidden = [
        'teacher_code_solve'
    ];
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

   public function testCases(): HasMany
   {
       return $this->hasMany(TestCase::class);
   }

   public function exams(): BelongsToMany
   {
       return $this->belongsToMany(Exam::class);
   }
   


}
