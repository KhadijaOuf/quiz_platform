<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tentative extends Model
{
    protected $fillable = ['quiz_id', 'etudiant_id', 'commence_a', 'termine_a', 'score', 'passed'];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function reponsesDonnees(): HasMany
    {
        return $this->hasMany(ReponseDonnee::class);
    }
}
