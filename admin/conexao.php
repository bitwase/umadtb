<?php #conexao com bd
$db="bwumadtb";
$db_user="bwumadtb";
$db_senha="b3tw1s2@";
$db_serv="mysql.uhserver.com";

if(!($id = @mysql_connect($db_serv,$db_user,$db_senha)))
{
   echo "Não foi possível estabelecer uma conexão com o Banco de Dados. Favor Contactar o Administrador.";
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
