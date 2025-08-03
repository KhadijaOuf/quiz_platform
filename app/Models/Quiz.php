<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    // veut dire que seuls ces champs peuvent être remplis automatiquement
    protected $fillable = ['title', 'description', 'duration', 'note_reussite', 'module_id', 'formateur_id', 'disponible_du',
        'disponible_jusquau', 'est_actif', 'archived'];

    protected $casts = [
        'disponible_du' => 'datetime',
        'disponible_jusquau' => 'datetime',
        'est_actif' => 'boolean',
        'archived' => 'boolean',
    ];
    protected $appends = ['status'];

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

    //  un accessor (propriété virtuelle et ne correspond pas à une vraie colonne en base de données)
    public function getStatusAttribute(): string
    {
        if ($this->archived) {
            return 'archivé';
        }
        if (!$this->est_actif) {
            return 'inactif';
        }
        if ($this->disponible_du && \Carbon\Carbon::parse($this->disponible_du)->isFuture()) {
            return 'inactif'; // Pas encore disponible
        }

        if ($this->disponible_jusquau && \Carbon\Carbon::parse($this->disponible_jusquau)->isPast()) {
            return 'archivé'; // Date de fin dépassée
        }
        return 'actif';
    }
    
    public function getNoteTotaleAttribute(): float
    {
        return $this->questions()->sum('note');
    }

    public static function booted()
    {
        static::deleting(function ($quiz) {
            // Supprimer les questions et leurs réponses attendues
            foreach ($quiz->questions as $question) {
                $question->reponseAttendues()->delete();
                $question->delete();
            }
        });
    }
}
