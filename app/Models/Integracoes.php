<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integracoes extends Model
{
    use HasFactory;

    protected $table = 'integracoes';

    protected $fillable = [
        'nome',
        'descricao',
        'status',
        'usuario',
        'password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];
}
