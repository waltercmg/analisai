<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TemplateController extends Controller
{
    public function index(){
        $conteudo = file_get_contents('/home/ubuntu/workspace/public/python/input/templates/template_basico.html');
        return view('template.edit', compact('conteudo'));
    }
    public function update(Request $request)
    {
        $arquivo = fopen("/home/ubuntu/workspace/public/python/input/templates/template_basico.html", "w") or die("Unable to open file!");
        $conteudo = $request->conteudo;
        fwrite($arquivo, $conteudo);
        fclose($arquivo);
        return view('template.edit', compact('conteudo'));
    }
    
}
