<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); 
	setcookie ("matricula",'',time()+3600);
	setcookie ("senha",'',time()+3600);
	header ("Location: login.php");	

?>