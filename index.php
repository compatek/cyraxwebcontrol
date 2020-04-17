<?php
/*_______________________________________________________________________________________/
/ Para ativar o sudo no php seguir os passos                                             /
/ 1- sudo su                                                                             /
/ 2- visudo -f /etc/sudoers                                                              /      
/ 3- acrecentar na ultima linha do arquivo                                               /
/       www-data ALL=NOPASSWD: ALL visudo -f /etc/sudoers                                /
/_______________________________________________________________________________________*/

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++/
    variaveis do sistema                                                                 /
/+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
$dirAtivos = "/etc/apache2/sites-enabled/"; //Pasta de sites ativos do apache
$dirAvaliacao = "/etc/apache2/sites-available/"; //Pasta de sites em avaliação do apache
$dirProjetos = "/var/www/html/projetos/"; //Pasta dos sites apache
$arqPhpMyAdmin = "phpmyadmin.conf"; //arquivo de configuração php my admin
$arqCyrax = "cyrax.conf"; //arquivo de configuração sistema
$arqProjetos = "projetos.conf"; //arquivo de configuração pagina Projetos

/*=======================================================================================/
    Funções genericas                                                                    /  
/=======================================================================================*/
function buscarArquivos($caminho){
    $lista = [];

    if ($handle = opendir($caminho)) { //verifica se tem um valor  
        while (false !== ($entry = readdir($handle))) {
            if(empty($entry) || $entry == '..' || $entry == '.') continue;
                array_push($lista, $entry); //adiciona um valor no fim da $lista
        }
        
        closedir($handle);

        return json_encode($lista);
    }

}

function buscarDiretorios($caminho){
    $lista = [];
    $pastas = scandir($caminho);

    foreach ($pastas as $value) {
        if(empty($value) || $value == '..' || $value == '.') continue;
            array_push($lista, $value); //adiciona um valor no fim da $lista
    }
    return json_encode($lista);
}

/*=======================================================================================/
    Funções da Aplicação                                                                 /  
/=======================================================================================*/
function buscarListaAtivos(){

    global $dirAtivos, $arqPhpMyAdmin, $arqProjetos, $arqCyrax;

    $lista = json_decode(buscarArquivos($dirAtivos), true);

    echo "<table>";
    foreach ($lista as $value) {
        if($value == $arqCyrax || $value == $arqProjetos || $value == $arqPhpMyAdmin) continue;
            echo "<tr>";
            echo "<td>{$value}</td>";
            echo "<td><a href='index.php?f=disabilitarSite&v={$value}'>Desabilitar</a></td>";
            $value = "www.".$value;
            echo "<td><a href='http://".trim($value, "/.conf/").".local'>Visitar</a></td>";
            echo "</tr>";
    }
    echo "</table>";
}

function buscarListaInativos(){

    global $dirAtivos,$dirAvaliacao;
    $value = 0;

    $listaAvaliacao = json_decode(buscarArquivos($dirAvaliacao), true);
    $listaAtivo = json_decode(buscarArquivos($dirAtivos), true);

    foreach ($listaAvaliacao as $valueAv) {
        
        foreach ($listaAtivo as $valueAt) {
            
            if($valueAv == $valueAt){
                $value = 1;                
            }
        }

        if($value == 0 and $valueAv != "000-default.conf" and $valueAv != "default-ssl.conf"){
            //echo "$valueAv <br>";
            echo "<a href='index.php?f=abilitarSite&v={$valueAv}'>{$valueAv}</a><br>";
        }
        $value = 0;
    }
}

function buscarPastasSites(){

    global $dirProjetos;

    $lista = json_decode(buscarDiretorios($dirProjetos), true);

    foreach ($lista as $value) {
                echo "$value <br>";
        }
}

function disabilitarSite($site){
    echo shell_exec("sudo a2dissite {$site}");
    echo shell_exec("sudo systemctl reload apache2");
}

function abilitarSite($site){
    echo shell_exec("sudo a2ensite {$site}");
    echo shell_exec("sudo systemctl reload apache2");
}

/*_______________________________________________________________________________________/
    Aplicação                                                                            /
/_______________________________________________________________________________________*/
echo "<ul>";
echo "<li><a href='novosite.php'>Adicionar Novo Site</a></li>";
echo "<li><a href='index.php?f=listarSitesAtivos'>Sites Ativos</a></li>";
echo "<li><a href='index.php?f=listarSitesInativos'>Sites Inativos</a></li>";
echo "<li><a href='index.php?f=pastaSites'>Pasta dos Sites</a></li>";
echo "</ul>";

switch ($_GET[f]) {
    case 'listarSitesAtivos':
        buscarListaAtivos();
    break;
    case 'listarSitesInativos':
        buscarListaInativos();
    break;
    case 'pastaSites':
        buscarPastasSites();
    break;
    case 'disabilitarSiteLista':
        disabilitarSiteLista();
    break;
    case 'disabilitarSite':
        disabilitarSite($_GET[v]);
    break;
    case 'abilitarSite':
        abilitarSite($_GET[v]);
    break;
}