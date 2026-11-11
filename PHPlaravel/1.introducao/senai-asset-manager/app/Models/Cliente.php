<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes"; //nome da tabela no
    protected $fillable = ["razao_social", "cnpj", "setor", "status"];
}
