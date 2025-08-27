<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oportunidades extends Model
{
    use HasFactory;

    /**
     * Define o nome da tabela no banco de dados.
     * Por padrão, o Eloquent assume o nome da classe no plural (oportunidades).
     * Se o nome da sua tabela for diferente, você deve especificar aqui.
     */
    protected $table = 'oportunidades';

    /**
     * Define a chave primária da tabela.
     * Por padrão, o Eloquent assume que a chave primária é 'id'.
     * A sua tabela usa 'guid' como chave primária.
     */
    protected $primaryKey = 'guid';

    /**
     * Indica se a chave primária é um UUID.
     * Isso é necessário para que o Eloquent entenda que a chave não é um auto-incremento.
     */
    public $incrementing = false;

    /**
     * Define o tipo da chave primária.
     * Por padrão, o Eloquent assume 'integer'.
     * A sua chave primária é uma string UUID.
     */
    protected $keyType = 'string';

    /**
     * As colunas que podem ser preenchidas via atribuição em massa.
     */
    protected $fillable = [
        'cliente',
        'clienteCodigo',
        'codigo',
        'complemento',
        'contato',
        'dataAtualizacao',
        'dataCriacao',
        'dataPrevista',
        'descricao',
        'extra',
        'filial',
        'funil',
        'guid',
        'latitude',
        'local',
        'localEvento',
        'longitude',
        'motivo',
        'numeroProjeto',
        'observacoesEstrategia',
        'orcamento',
        'origem',
        'probabilidade',
        'responsaveis',
        'responsavel',
        'status',
        'valorTotal',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'dataAtualizacao' => 'datetime',
        'dataCriacao' => 'datetime',
        'dataPrevista' => 'datetime',
        'extra' => 'array', // Converte a coluna JSON em um array PHP
        'responsaveis' => 'array', // Converte a coluna JSON em um array PHP
    ];
    public function getRouteKeyName()
    {
        return 'guid';
    }
}
