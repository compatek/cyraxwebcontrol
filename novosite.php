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
    fclose($arquivo);// Fecha arquivo aberto
    return $texto;//retorna o conteudo do arquivo
}

function gravarArquivo($caminho, $conteudo, $permisao = false){
    
    if($permisao==true){ 
        shell_exec("sudo chmod 777 {$caminho}");
    }
    $arquivo = fopen($caminho, "w");
    fwrite($arquivo, $conteudo);
    fclose($arquivo);// Fecha arquivo aberto
    if($permisao==true){ 
        shell_exec("sudo chmod 744 {$caminho}");
    }
    
}


/*=======================================================================================/
    Funções da Aplicação                                                                 /  
/=======================================================================================*/

function acrecentarLinhatexto($texto,$linha,$valor=""){
    //,$linha,$valor
    $nv_texto = "";
    if($valor!=""){ $valor = "{$valor} \n"; }

    $lista = explode(" \n", $texto);

    foreach ($lista as $nv_linha) { //roda um loop das linha encontradas no $texto

        //verifica a linha do texto para saber se é igual ao parametro $linha passado 
        // ou se a linha do texto é igual ao parametro $valor
        if($nv_linha==$linha or $nv_linha." \n"==$valor) { //caso alguma das verificações acima for verdadeira rodas o bloco se não roda o else
            
            //verifica se a linha é diferente do parametro valor 
            if($nv_linha." \n"!=$valor){ //caso seja roda o bloco se não pula o bloco

                $nv_texto .= "{$nv_linha} \n";//adiciona a linha do texto
                $nv_texto .= $valor;//adiciona o parametro $valor numa nova linha

            }

        }else{ 

            $nv_texto .= "{$nv_linha} \n"; //adiciona a linha do texto

        }
    }
    return $nv_texto;
}

/*_______________________________________________________________________________________/
    Aplicação                                                                            /
/_______________________________________________________________________________________*/
$texto = lerArquivo("/etc/hosts");
$conteudo = acrecentarLinhatexto($texto,"127.0.1.1 rooter","{$_SERVER['REMOTE_ADDR']} www.texte.local");
gravarArquivo("/etc/hosts", $conteudo, true);