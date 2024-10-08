<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Http\Request;

class MainController extends Controller
{
  public function index()
  {
    // load user's notes
    $id = session('user.id'); //Busca da sessão (cookies) do navegador o id do usuário
    // $user = User::find($id)->toArray(); //Buscando usuários no banco via ID (Utilizando o Eloquent Model)
    // $notes = User::find($id)->notes()->get()->toArray(); //Buscando as notas relacionadas com User utilizando o método do Eloquent que foi inserido no User
    $notes = User::find($id)->notes()->whereNull('deleted_at')->get()->toArray();

    //show home view
    return view('home', ['notes' => $notes]); //Passando notes para a view
  }

  public function newNote()
  {
    return view('new_note');
  }

  public function newNoteSubmit(Request $request)
  {
    // validate request
    $request->validate(
      [
        'text_title' => 'required|min:3|max:200',
        'text_note' => 'required|min:3|max:3000',
      ],
      [
        'text_title.required' => 'O título é obrigatório',
        'text_title.min' => 'O título deve ter pelo menos :min caracteres',
        'text_title.max' => 'O título deve ter no máximo :max caracteres',
        'text_note.required' => 'A nota é obrigatória',
        'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
        'text_note.max' => 'A nota deve ter no máximo :max caracteres'
      ]
    );

    // get user id
    $id = session('user.id');

    // create new note
    $note = new Note();
    $note->user_id = $id;
    $note->title = $request->text_title;
    $note->text = $request->text_note;
    $note->save();

    // redirect to home
    return redirect()->route('home');
  }

  public function editNote($id)
  {
    $id = Operations::decryptId($id);

    if($id == null) {
      return redirect()->route('home');
    }

    // load note
    $note = Note::find($id);
    // show edit note view
    return view('edit_note', ['note' => $note]);
  }

  public function editNoteSubmit(Request $request) {
    // validate request
    $request->validate(
      [
        'text_title' => 'required|min:3|max:200',
        'text_note' => 'required|min:3|max:3000',
      ],
      [
        'text_title.required' => 'O título é obrigatório',
        'text_title.min' => 'O título deve ter pelo menos :min caracteres',
        'text_title.max' => 'O título deve ter no máximo :max caracteres',
        'text_note.required' => 'A nota é obrigatória',
        'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
        'text_note.max' => 'A nota deve ter no máximo :max caracteres'
      ]
    );

    // check if note_id exists
    if($request->note_id == null){
      return redirect()->route('home');
    }

    // decrypt note_id
    $id = Operations::decryptId($request->note_id);

    if($id == null) {
      return redirect()->route('home');
    }

    //load note
    $note = Note::find($id);

    // update note
    $note->title = $request->text_title;
    $note->text = $request->text_note;
    $note->save();
    // redirect to home
    return redirect()->route('home');
  }

  public function deleteNote($id)
  {
    $id = Operations::decryptId($id);

    if($id == null) {
      return redirect()->route('home');
    }

    // load note
    $note = Note::find($id);

    //show delete note confirmation
    return view('delete_note', ['note' => $note]);
  }

  public function deleteNoteConfirm($id)
  {
    //Check if $id is encrypted
    $id = Operations::decryptId($id);

    if($id == null) {
      return redirect()->route('home');
    }

    $note = Note::find($id);

    //1. hard delete
    // $note->delete();

    //2. soft delete
    // $note->deleted_at = date('Y-m-d H:i:s');
    // $note->save();

    //3. soft delete (usado a propriedade SoftDeletes no model)
    $note->delete();

    //4. hard delete (usado a propriedade SoftDeletes no model)
    // $note->forcedelete();

    return redirect()->route('home');
  }

  // private function decyptId($id)
  // {
  //   // check if $id is encrypted
  //   try {
  //     $id = Crypt::decrypt($id);
  //   } catch (DecryptException $e) {
  //     return redirect()->route('home');
  //   }
  //   return $id;
  // }
}
