<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Etudiant extends Model
{
    protected $fillable = ['user_id','nom_complet', 'cin', 'date_naissance', 'adresse', 'specialite_id', 'annee_inscription'];

public function getAgeAttribute()
{
    return $this->date_naissance ? \Carbon\Carbon::parse($this->date_naissance)->age : null;
}

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialite()
    {
    return $this->belongsTo(Specialite::class);
    }

    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class);
    }
    //
}
