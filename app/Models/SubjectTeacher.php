<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectTeacher extends Model
{
    use HasFactory;
    protected $table = 'subject_teacher' ;
    protected $fillable = ['teacher_id' , 'subject_id'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
    public function teacher() {
        return $this->belongsTo(User::class);
    }
}
