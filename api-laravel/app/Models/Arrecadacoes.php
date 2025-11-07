<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrecadacoes extends Model
{
    /** @use HasFactory<\Database\Factories\ArrecadacoesFactory> */
    use HasFactory;

    const TRIBUTOS = ['IPTU', 'ISS', 'ITBI'];

    protected $table = 'arrecadacoes';
    protected $fillable = [
        'tributo',
        'mes',
        'ano',
        'valor',
    ];
}
