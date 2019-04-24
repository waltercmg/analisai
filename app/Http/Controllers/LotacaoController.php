<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Lotacao;
use App\User;

class LotacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lotacoes = Lotacao::orderby('nome')->paginate(5); //show only 5 items at a time in descending order
        return view('lotacoes.index', compact('lotacoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderby('name')->lists('name','id'); //show only 5 items at a time in descending order
        return view('lotacoes.create',compact('users'));

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
            'nome'=>'required|max:5',
            'lider_id' =>'required',
            ]);

        $nome = $request['nome'];
        $lider_id = $request['lider_id'];

        $lotacao = Lotacao::create($request->only('nome', 'lider_id'));

        return redirect()->route('lotacoes.index')
            ->with('flash_message', 'Lotacao,
             '. $lotacao->nome.' criada');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lotacao = Lotacao::findOrFail($id); //Find post of id = $id
        return view ('lotacoes.show', compact('lotacao'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lotacao = Lotacao::findOrFail($id);
        $users = User::orderby('name')->lists('name','id');
        return view('lotacoes.edit', compact('lotacao','users'));
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
        $lotacao = Lotacao::findOrFail($id);//Get role with the given id
    //Validate name and permission fields
        $this->validate($request, [
            'nome'=>'required|max:5|unique:lotacoes,nome,'.$id,
            'lider_id' =>'required',
        ]);
        
        $lotacao->fill($request->all())->save();

        return redirect()->route('lotacoes.index')
            ->with('flash_message',
             'Lotação '. $lotacao->nome.' atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lotacao = Lotacao::findOrFail($id); 
        $lotacao->delete();

        return redirect()->route('lotacoes.index')
            ->with('flash_message',
             'Lotação excluída com sucesso.');
    }
}
