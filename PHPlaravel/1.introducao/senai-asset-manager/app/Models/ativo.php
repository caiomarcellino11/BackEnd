<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// modelo representativo de um ativo fabril
final class ativo extends Model
{
    use HasFactory; // uma biblioteca para habilitar a fábrica de modelos

    protected $table = "ativos"; //nome da tabela no banco de dados

    //atributos da tabela ativos(colunas) ()
    protected $fillable = [
        "codigo_patrimonio",
        "nome",
        "setor",
        "status",
        "tensao_operacao",
        "horas_trabalhadas"
    ];
}
