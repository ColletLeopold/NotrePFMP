<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Lyceen extends Authenticatable
{
    use HasFactory;
    protected $table = 'lyceen';
    protected $fillable = ['nom', 'prenom', 'identifiant', 'mot_de_passe'];
    protected $hidden = ['mot_de_passe'];
}
