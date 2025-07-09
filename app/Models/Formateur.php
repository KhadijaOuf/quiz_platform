<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Formateur extends Model
{
    protected $fillable = ['user_id', 'nom_complet', 'cin', 'adresse', 'departement'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modules(): BelongsToMany  // méthode retourne les modules associé a un formateur
    {
        return $this->belongsToMany(Module::class, 'formateur_module');
    }

    //
}
