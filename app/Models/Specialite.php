<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    // Une spécialité a plusieurs étudiants
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    // Une spécialité a plusieurs modules
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_specialite');
    }

    protected static function booted()
    {
        static::deleting(function ($specialite) {
            $specialite->modules()->detach();
        });
    }
}
