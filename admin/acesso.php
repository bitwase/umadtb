<?php #conexao com bd
$db="app_acessos";

include "../config.db.php";

if(!($id = @mysql_connect($db_serv,$db_user,$db_senha)))
{
   echo "Não foi possível estabelecer uma conexão com o servidor de Banco de Dados. Favor Contactar o Administrador.";
exit;
}
 
if(!($con=@mysql_select_db($db,$id))) { 
   echo "Não foi possível estabelecer uma conexão com o Banco de Dados. Favor Contactar o Administrador.";
   //exit; 
}
//mysql_set_charset('UTF8', $con);
mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
?>
