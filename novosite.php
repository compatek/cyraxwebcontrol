<?php


/*=======================================================================================/
    Funções genericas                                                                    /  
/=======================================================================================*/
function lerArquivo($caminho){
    $texto = "";
    $arquivo = fopen($caminho, "r");//Abre o Arquivo no Modo "r" (somente leitura)
    while(!feof($arquivo)){ //Lê o conteúdo do arquivo até o fim
        $linha = fgets($arquivo, 1024);//Mostra uma linha do arquivo
        $texto .= $linha; //Monta o conteudo recebido linha por linha
    }
    return $texto;//retorna o conteudo do arquivo
    fclose($arquivo);// Fecha arquivo aberto
}

function gravarArquivo($caminho, $conteudo, $permisao = false){
    
    if($permisao==true){ 
        shell_exec("sudo chmod 777 {$caminho}");
    }
    $arquivo = fopen($caminho, "w");
    fwrite($arquivo, $conteudo);
    fclose($arquivo);// Fecha arquivo aberto
    if($permisao==true){ echo shell_exec("sudo chmod 744 {$caminho}");}
    
}


/*=======================================================================================/
    Funções da Aplicação                                                                 /  
/=======================================================================================*/

function acrecentarLinhatexto($texto){
    //,$linha,$valor
    var_dump(explode("\n", $texto));
   
   /* if(mb_strpos($texto, $valor)) {
        $texto .= $linha;
        $texto .= "192.168.0.4	www.teste.local\n"; 
    }else{ 
        $texto .= $linha; 
    }*/
}

/*_______________________________________________________________________________________/
    Aplicação                                                                            /
/_______________________________________________________________________________________*/
$texto = lerArquivo("/etc/hosts");
acrecentarLinhatexto($texto);
echo $texto;
//gravarArquivo("/etc/hosts", $conteudo, true);