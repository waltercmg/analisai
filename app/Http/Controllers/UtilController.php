<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;

class UtilController extends Controller
{
    public static function converteDataEmTexto($data){
        if($data=='0000-00-00')
            $retorno = '';
        else
            $retorno = Carbon::createFromFormat('Y-m-d', $data)->format('d/m/Y');
        return $retorno;
    }
    
    public static function converteTextoEmData($texto){
        $retorno = '';
        if($texto != ''){
            if(preg_match('/\d{4}-\d{1,2}-\d{1,2}/', $texto) > 0)
                $retorno = Carbon::createFromFormat('Y-m-d', $texto)->format('Y-m-d');
            else
                $retorno = Carbon::createFromFormat('d/m/Y', $texto)->format('Y-m-d');
        }
        return $retorno;
    }

}
