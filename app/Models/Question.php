<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['quiz_id', 'enonce', 'type', 'note'];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function reponsesAttendues(): HasMany
    {
        return $this->hasMany(ReponseAttendue::class);
    }

    // lister les réponses données (par exemple, pour voir ce que les étudiants ont répondu à cette question
    public function reponsesDonnees(): HasMany
    {
        return $this->hasMany(ReponseDonnee::class);
    }
    //
}
