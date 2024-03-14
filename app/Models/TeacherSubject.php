<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherSubject extends Model
{
    use HasFactory;
    protected $table = 'teacher_subject' ;
    protected $fillable = ['teacher_id' , 'subject_id'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
