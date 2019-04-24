<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Lotacao;
use App\Relatorio;
use Auth;


class RelatorioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lotacoes = Lotacao::where('lider_id',Auth::user()->id)->orderBy('nome')->paginate(5);
        // foreach($lotacoes as $lotacao){
        //     echo "LOTACAO: ".$lotacao->nome."<br>";
        //     foreach($lotacao->relatorios as $relatorio){
        //         echo "RELATORIO: ".$relatorio->id."<br>";
        //     }
        // }
        return view('relatorios.index', compact('lotacoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderby('name')->lists('name','id'); //show only 5 items at a time in descending order
        $lotacoes = array();
        return view('relatorios.create',compact('users', 'lotacoes'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data_extracao'=>'required',
            ]);

        $data_extracao = UtilController::converteTextoEmData($request['data_extracao']);
        $usuario_id = Auth::user()->id;
        
        $carga = Relatorio::create(['data_extracao'=>$data_extracao, 'usuario_id'=>$usuario_id]);
        echo "ID DA CARGA: ".$carga->id;
        $this->salvarArquivosBD($data_extracao, $carga->id);
        
        return redirect()->route('relatorios.index')
            ->with('flash_message', 'Relatorio,
             '. $carga->nome.' criada');
             
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carga = Relatorio::findOrFail($id); //Find post of id = $id
        return view ('relatorios.show', compact('carga'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $relatorio = Relatorio::findOrFail($id);
        return view('relatorios.edit', compact('relatorio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $relatorio = Relatorio::findOrFail($id);//Get role with the given id
        $relatorio->fill($request->all())->save();

        return redirect()->route('relatorios.index')
            ->with('flash_message',
             'Relatorio da '. $relatorio->lotacao->nome.' atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carga = Relatorio::findOrFail($id); 
        $carga->relatorios()->delete();
        $carga->delete();

        return redirect()->route('relatorios.index')
            ->with('flash_message',
             'Lotação excluída com sucesso.');
    }
}
