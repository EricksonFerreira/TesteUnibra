<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use app\Http\Interfaces\IArtigoSave;
use Illuminate\Http\Request;
use App\Models\Artigo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Contracts\Helpers\RocketShipContract;

class ArtigoController extends Controller
{
    
    protected $user;
    protected $artigoSave;
    

    public function __construct(IArtigoSave $artigoSave)
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
        $this->artigoSave = $artigoSave;
    }

    public function index()
    {
        $artigos = Artigo::get(['id','nome', 'descricao', 'concluido', 'created_by']);
        return response()->json($artigos->toArray());
    }// end index()

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'     => 'required|string',
            'descricao'      => 'required|string',
            'concluido' => 'required|boolean'
        ]);

        if( $validator->fails() ){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ],400);
        }

        $artigo            = new Artigo();
        $this->artigoSave->save($request, $artigo);

    }// end store()


    public function show(Artigo $artigo)
    {
        return $artigo;
    }// end show()


    public function update(Request $request, Artigo $artigo)
    {
        $validator = Validator::make($request->all(), [
            'nome'     => 'required|string',
            'descricao'      => 'required|string',
            'concluido' => 'required|boolean'
        ]);

        if( $validator->fails() ){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ],400);
        }

        $artigo->nome     = $request->nome;
        $artigo->descricao      = $request->descricao;
        $artigo->concluido = $request->concluido;

        // if($this->user->artigos()->save($artigo))
        // {
        //     return response()->json(
        //         [
        //             'status' => true,
        //             'artigo'   => $artigo,
        //         ]
        //     );
        // }else{
        //     return response()->json(
        //         [
        //             'status' => false,
        //             'artigo'   => 'Oops, não consegui atualizar!',
        //         ]
        //     );
        // }

    }// end update()


    public function destroy(Artigo $artigo)
    {
        if($artigo->delete())
        {
            return response()->json(
                [
                    'status' => true,
                    'artigo'   => $artigo,
                ]
            );
        }else{
            return response()->json(
                [
                    'status' => false,
                    'artigo'   => 'Oops, não consegui apagar.',
                ]
            );
        }
    }// end destroy()


    protected function guard()
    {
        return Auth::guard();
    }// end guard()

}
