<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __constructor()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if( $validator->fails() ){
            return response()->json($validator->errors(),400);
        }

        $token_validity = 24 * 60;
        $this->guard()->factory()->setTTL($token_validity);

        if(!$token = $this->guard()->attempt($validator->validated())) {
            return response()->json(['error' => 'Não foi autorizado!'], 401);
        }
        return $this->respondWithToken($token);
    }//end login()

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        if( $validator->fails() ){
            return response()->json([
                $validator->errors()
            ],422);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' =>bcrypt($request->password)]
        ));

        return response()->json(['message' => 'Usuário criado com sucesso!', 'user' => $user]);
    }//end register()

    public function logout()
    {
        $verify =  $this->guard()->logout();

        if($verify == true){
            return response()->json(['message' => 'Não sei o que aconteceu!']);
        }else{
            return response()->json(['message' => 'Usuário desconectado com sucesso!']);
        }
    }//end logout()

    public function profile()
    {
        return response()->json($this->guard()->user());
    }//end profile()

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }//end refresh()

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => (auth('api')->factory()->getTTL() * 60),

        ]);
    }//end respondWithToken()

    protected function guard()
    {
        return Auth::guard();
    }//end guard()


//    Rota de Teste
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
}
