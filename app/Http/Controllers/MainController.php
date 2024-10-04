<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function index()
  {
    // load user's notes
    $id = session('user.id'); //Busca da sessão (cookies) do navegador o id do usuário
    // $user = User::find($id)->toArray(); //Buscando usuários no banco via ID (Utilizando o Eloquent Model)
    $notes = User::find($id)->notes()->get()->toArray(); //Buscando as notas relacionadas com User utilizando o método do Eloquent que foi inserido no User

    //show home view
    return view('home', ['notes' => $notes]); //Passando notes para a view
  }

  public function newNote()
  {
    echo "I'm creating a new note!.";
  }
}
