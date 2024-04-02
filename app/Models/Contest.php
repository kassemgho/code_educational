<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contest extends Model
{
    use HasFactory;
    protected $fillable  = ['name' ,'duration','start_at' ,'hour', 'password' , 'scour' ] ;
 
    public function problems(): BelongsToMany
    {
        return $this->belongsToMany(Problem::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
