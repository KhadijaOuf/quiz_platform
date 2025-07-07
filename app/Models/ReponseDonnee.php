<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReponseDonnee extends Model
{
    protected $fillable = ['tentative_id', 'question_id', 'texte', 'note_obtenue'];

    public function tentative(): BelongsTo
    {
        return $this->belongsTo(Tentative::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
