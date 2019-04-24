<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Carga;
use App\Relatorio;
use App\User;
use App\Lotacao;
use Auth;
use DateTime;

class CargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cargas = Carga::orderby('id','desc')->paginate(5); //show only 5 items at a time in descending order
        return view('cargas.index', compact('cargas'));
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
        return view('cargas.create',compact('users', 'lotacoes'));

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
        
        $carga = Carga::create(['data_extracao'=>$data_extracao, 'usuario_id'=>$usuario_id]);
        echo "ID DA CARGA: ".$carga->id;
        $this->salvarArquivosBD($data_extracao, $carga->id);
        
        return redirect()->route('cargas.index')
            ->with('flash_message', 'Carga,
             '. $carga->nome.' criada');
             
    }
    
    private function salvarArquivosBD($data_extracao, $carga_id){
        $dir = "/home/ubuntu/workspace/public/python/output/html/";
        $handle = opendir($dir);
        while (false !== ($file = readdir($handle))) {
            $lotacao = rtrim($file,".html");
            $lot = Lotacao::where('nome', substr($lotacao,-5))->get();
            if($lot->count()==1){
                $resultado = "".file_get_contents($dir.$file);
                $relatorio = new Relatorio();
                $relatorio = $relatorio->create(['lotacao_id'=> $lot[0]->id,
                                                 'carga_id'=> $carga_id,
                                                 'conteudo' => $resultado,
                                                 'dt_extracao' => $data_extracao]);       
            }
        }
    }


    public function avaliarArquivo(Request $request)
    {
        $lotacoes = array();
        $this->validate($request, [
            'arquivo'=>'required',
            ]);

        $this->salvar($request);
        $resultado = $this->executarPython();
        if(is_array($resultado)){
            //print_r($resultado);
            foreach($resultado as $lotacao){
                $lotacao = substr($lotacao, -5);
                $lot = Lotacao::where('nome', $lotacao)->get()->count();
                if($lot == 1){
                    array_push($lotacoes, "<font color=green>".$lotacao."</font>");
                }else{
                    array_push($lotacoes, "<font color=red>".$lotacao."</font>");
                }
            }
        }else{
            echo "ALGO DEU ERRADO: ".$resultado;    
        }
        
        return view('cargas.create',compact('lotacoes'));
        
    }
    
    
    public function salvar(Request $request)
    {
        if( $request->hasFile('arquivo') ) {
            $data = new DateTime();
            $arq = $request->file('arquivo');
            $dt_extracao = $request->data_extracao."";
            $nm_arq = "extracao_".str_replace('/','-',$dt_extracao)."_".$data->getTimestamp().".zip";
            $arq->move('./python/input/zip', $nm_arq);
        }else{
            echo "Nada";
        }
        
    }
    
    private function executarPython(){
        setlocale(LC_CTYPE, "en_US.UTF-8");
        $lotacoes = json_decode(exec('/usr/bin/python2.7 ./python/Principal.py'), true);
        
        return $lotacoes;
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carga = Carga::findOrFail($id); //Find post of id = $id
        return view ('cargas.show', compact('carga'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $carga = Carga::findOrFail($id);
        return view('cargas.edit', compact('carga'));
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
        $carga = Carga::findOrFail($id);//Get role with the given id
        //Validate name and permission fields
        $this->validate($request, [
            'data_extracao'=>'required',
            ]);
        
        $carga->fill($request->all())->save();

        return redirect()->route('cargas.index')
            ->with('flash_message',
             'Lotação '. $carga->nome.' atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carga = Carga::findOrFail($id); 
        $carga->relatorios()->delete();
        $carga->delete();

        return redirect()->route('cargas.index')
            ->with('flash_message',
             'Lotação excluída com sucesso.');
    }
}
