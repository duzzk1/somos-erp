<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'guid',
        'nome',
        'apelidoFantasia',
        'cpfcnpj',
        'inscricaoEstadual',
        'email',
        'emailAdicional',
        'telefone',
        'celular',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'municipio',
        'municipioIbge',
        'uf',
        'cep',
        'pais',
        'observacoes',
        'origem',
        'situacao',
        'status',
        'ramo',
        'setor',
        'setorialAreaAtuacao',
        'tipo',
        'tipoContribuinte',
        'regimeTributario',
        'site',
        'consumidorFinal',
        'pagamentoCondicao',
        'pagamentoForma',
        'pagamentoTipo',
        'banco',
        'numeroBanco',
        'agencia',
        'contaCorrente',
        'codigo',
        'dataNascimento',
        'dataAtualizacao',
        'pessoa',
        'latitude',
        'longitude',
    ];

    // Se você tiver colunas de data que não são 'created_at' e 'updated_at',
    // você pode adicioná-las aqui para que o Eloquent as trate como objetos Carbon.
    protected $dates = [
        'dataNascimento',
        'dataAtualizacao',
    ];
}