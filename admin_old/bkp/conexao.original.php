<?php #conex�o com db
$db="bwclinica";
$db_user="bwclinica";
$db_senha="b3tw1s2@";
$db_serv="bwclinica.mysql.uhserver.com";

if(!($id = @mysql_connect($db_serv,$db_user,$db_senha)))
{
   echo "N�o foi poss�vel estabelecer
uma conex�o com o gerenciador MySQL. Favor Contactar o Administrador.";
   exit;
} 
if(!($con=@mysql_select_db($db,$id))) { 
   echo "N�o foi poss�vel estabelecer
uma conex�o com o gerenciador MySQL. Favor Contactar o Administrador.";
   exit; 
} 
mysql_set_charset('UTF8', $id);
?>