<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
  //Consolidando o relacionamento (muitos para um)
  public function user() {
    return $this->belongsTo(User::class);
  }
}
