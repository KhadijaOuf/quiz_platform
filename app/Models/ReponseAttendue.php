<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReponseAttendue extends Model
{
    protected $fillable = ['question_id', 'texte', 'est_correcte', 'valeur'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
