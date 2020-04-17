<?php

function lerArquivo($caminho){
    $texto = "";
    $arquivo = fopen($caminho, "r");
    while(!feof($arquivo)){
        $linha = fgets($arquivo, 1024);
        $texto .= $linha;
    }
    return $texto;
    fclose($arquivo);
}

function gravarArquivo($caminho, $conteudo, $permisao = false){
    
    if($permisao==true){ shell_exec("sudo chmod 777 {$caminho}");}
    $arquivo = fopen($caminho, "w");
    fwrite($arquivo, $conteudo);
    // Fecha arquivo aberto
    fclose($arquivo);
    if($permisao==true){ echo shell_exec("sudo chmod 744 {$caminho}");}
    
}

echo lerArquivo("/etc/hosts");
gravarArquivo("/etc/hosts", $conteudo, true);

function gravarArquivo(){
    if(mb_strpos($linha, $ls)) {
        $texto .= $linha;
        $texto .= "192.168.0.4	www.teste.local\n"; 
    }else{ 
        $texto .= $linha; 
    }
}