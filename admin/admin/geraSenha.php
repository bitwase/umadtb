<?php
include "../seguranca.php";

$ret = "";
$r = auxSenha($ret);

$pa = array(
    "pass" => $r
);
if ($pa) {
    echo json_encode($pa, JSON_PRETTY_PRINT);
}

function auxSenha($ret){
    //chamar a função de acordo com o tipo
    //padrão será: m#MNM
    for($i = 0; $i<5; $i++){
        #$geraSenha(qt, min, mai, num, simb);
        if($i == 0 || $i == 2 || $i == 4){//letras
            $ret .= geraSenha(1, true, true, false, false);
        }

        if($i == 1){//símbolo
            $ret .= geraSenha(1, false, false, false, true);
        }
        if($i == 3){//número
            $ret .= geraSenha(1, false, false, true, false);
        }
    }
    return $ret;
} 

function geraSenha($tamanho = 8, $minusculas = true, $maiusculas = true, $numeros = true, $simbolos = true)
{
    $lmin = 'abcdefghjkmnpqrstuvwxyz';
    $lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    $num = '23456789';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';

    #$caracteres .= $lmin;
    if ($minusculas) $caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;

    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}
