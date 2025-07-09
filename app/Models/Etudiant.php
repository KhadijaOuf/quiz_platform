<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etudiant extends Model
{
    protected $fillable = ['user_id','nom_complet', 'cin', 'age', 'adresse', 'specialite', 'annee_inscription'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'etudiant_module');
    }

    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class);
    }
    //
}
