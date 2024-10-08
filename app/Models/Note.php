<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
  //Ao declarar usar SoftDeletes no model, estou ativando automaticamente ao executar a função delete
  use SoftDeletes;

  //Consolidando o relacionamento (muitos para um)
  public function user() {
    return $this->belongsTo(User::class);
  }
}
