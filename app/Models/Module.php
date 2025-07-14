<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = ['nom'];

    public function formateurs(): BelongsToMany
    {
        return $this->belongsToMany(Formateur::class, 'formateur_module');
    }

    public function specialites(): BelongsToMany
    {
        return $this->belongsToMany(Specialite::class, 'module_specialite');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
