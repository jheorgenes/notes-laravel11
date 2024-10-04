<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  // Fazendo o relacionamento com a tabela Notes (um para muitos)
  public function notes() {
    return $this->hasMany(Note::class);
  }
}
