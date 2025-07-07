<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    // veut dire que seuls ces champs peuvent être remplis automatiquement
    protected $fillable = ['titre', 'description', 'duration', 'note_reussite', 'module_id', 'formateur_id', 'created_at'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class);
    }
}
