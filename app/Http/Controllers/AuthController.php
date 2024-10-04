<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
  public function login()
  {
    return view('login');
  }

  public function logout()
  {
    // logout from the application
    session()->forget('user'); //Manda esquecer ou retirar o user da session (cookies)
    return redirect()->to('/login');
  }

  public function loginSubmit(Request $request)
  {
    //Validate
    $request->validate(
      [
        'text_username' => 'required|email',
        'text_password' => 'required|min:6|max:16',
      ],
      [
        'text_username.required' => 'O username é obrigatório',
        'text_username.email' => 'Username deve ser um email válido',
        'text_password.required' => 'O password é obrigatório',
        'text_password.min' => 'O password deve ter pelo menos :min caracteres',
        'text_password.max' => 'O password deve ter no máximo :max caracteres'
      ]
    );

    // get user input
    $username = $request->input('text_username');
    $password = $request->input('text_password');

    // get all the users from the database
    // $users = User::all()->toArray();

    // as an object instance of the model's class
    // $userModel = new User();
    // $users = $userModel->all()->toArray();

    // check is user exists
    $user = User::where('username', $username)->where('deleted_at', NULL)->first();

    if(!$user) {
      return redirect()
          ->back()
          ->withInput()
          ->with('loginError', 'Username ou password incorretos.');
    }

    //check if password is correct
    if(!password_verify($password, $user->password)) {
      return redirect()
          ->back()
          ->withInput()
          ->with('loginError', 'Username ou password incorretos.');
    }

    // update last login
    $user->last_login = date('Y-m-d H:i:s');
    $user->save();

    //login user (adiciona na sessão (cookies) os dados do user)
    session([
      'user' => [
        'id' => $user->id,
        'username' => $user->username,
      ]
    ]);

    echo 'LOGIN COM SUCESSO';

    // teste database connection
    // try {
    //   // Chamando a classe DB (de Facades) para testar a conexão
    //   DB::connection()->getPdo();
    //   echo 'Connection is OK';
    // } catch (\PDOException $e) {
    //   echo "Connection failed: " . $e->getMessage();
    // }

  }
}
