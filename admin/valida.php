<?php
//error_reporting(~E_ALL);
//$ip1 = $_SERVER ['REMOTE_HOST']; //ip de internet
//echo $_SERVER["SERVER_NAME"];
//echo base_url();
//phpinfo();
//exit();
error_reporting(~E_ALL);
$ip2 = $_SERVER['REMOTE_ADDR']; // ip do usuário
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip1 = $_SERVER['REMOTE_ADDR'];
	$ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip1 = $_SERVER['REMOTE_ADDR'];
	$ip2 = "";
}

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

//echo "$usuario  $senha";
$senha = hash('whirlpool', $senha);
//echo $senha;
$lk = $_POST['lk'];
$lk = base64_decode($lk);
include('conexao.php');
$config = $pdo->query("select * from config")->fetch();
//echo "SELECT * FROM tb_usuario WHERE usuario='$usuario' AND senha='$senha'";
//$confirma= //mysqli_query("SELECT * FROM tb_usuario WHERE usuario='$usuario' AND senha='$senha'");


$confirma = $pdo->query("SELECT * FROM tb_usuario WHERE (usuario='$usuario' or email = '$usuario') AND senha='$senha'");
//$confirma->execute();
$cont = $confirma->rowCount();

//$cont=//mysqli_num_rows($confirma);
//echo "Cont: ".$cont;
/*if ($cont == 0) {
	try {
		$pdo->query("insert into login_falha (us, ip, data) values('$usuario','$ip1',now())");
		//verificar a quenatidade de erros
	} catch (PDOException $e) {
		return 'ERROR: ' . $e->getMessage();
	}
}*/
$us_log = $confirma->fetch();
//$cont = 0; 
if ($cont > 0) {
	setcookie('usuario', $usuario, time() + 432000);
	setcookie('senha', $senha, time() + 432000);
	setcookie('usuario', $usuario, time() + 432000, "/");
	setcookie('senha', $senha, time() + 432000, "/");
	$sql_log = "INSERT INTO log_acesso (data, us, ip, ip2) VALUES (now(),'$us_log[id]','$ip1','$ip2')";
	//echo $sql_log;
	$pdo->query($sql_log);
	//	//mysqli_query("INSERT into log_acesso (data,us,ip,ip2) values(now(),'$us_log[id]','$ip1','$ip2')");

	if ($senha == "11d8ce9303e5979200e7acb23522d8c93a5da45f6a387204d769910a555f538e66d4c65be846d22850093b9b568207b1c3e2c8e01fb2bd50d0b03d409671d49d") {
		header("Location: index.php?pg=altera.senha");
	}
	if ($senha != "11d8ce9303e5979200e7acb23522d8c93a5da45f6a387204d769910a555f538e66d4c65be846d22850093b9b568207b1c3e2c8e01fb2bd50d0b03d409671d49d") {
		if ($lk == "") {
			header("Location: index.php");
		}
		if ($lk != "") {
			header("Location: $lk");
		}
	}
} else {
	//insere em falha
	$pdo->query("insert into login_falha (us, ip, data) values('$usuario','$ip1',now())");
	//verificar se quantidade de tentativas atingiu o limite dentro da mesma hora
	$sql = "select us, date_format(data,'%d/%m/%Y %H:%i:%s') as 'data' from login_falha where ip = '$ip1' and date_format(data, '%Y-%m-%d %H') = date_format(now(), '%Y-%m-%d %H')";
	#echo $sql;
	$tentativas = $pdo->query($sql)->rowCount();
	#echo $config['qtAlertaErroLogin'];
	#echo "Tent: " . $tentativas;
	#exit();

	setcookie("usuario", "");
	setcookie("senha", "");
	if ($tentativas < $config['qtAlertaErroLogin']) {
		header("Location: login.php?e=1");
	}

	if ($tentativas >= $config['qtAlertaErroLogin']) {

		//muitas tentativas erradas

		//listar as tentativas
		$tent = "";
		$lt = $pdo->query($sql);
		while($l = $lt->fetch()){
			$tent .= "<b>Data:</b> $l[data] - <b>Usuário:</b> $l[us]<br>";
		}

		$mail_projetos = "Muitas tentativas de acesso com IP $ip1<br><br>$tent";
		$assunto = "Tentativas de Acesso";
		$mf = $config['templateMail'];
		$mf = str_replace("##mail_title##", $assunto, $mf);
		$mf = str_replace("##mail_body##", $mail_projetos, $mf);
		$mf = addslashes($mf);

		//inserer como um registro para ser enviado
		$sqlInsereMail = "insert into tb_email (destinatario, nomeDestinatario, assunto, mensagem, st) values(
            '$config[emailAlertaLogin]',
            '$config[nomeAlertaLogin]',
            '$assunto',
            '$mf',
        '1'
        )";
		$r = $pdo->query($sqlInsereMail);
		$idMailCursos = $pdo->lastInsertId();
		chamaEmail($idMailCursos);

		header("Location: login.php?e=4");
	}
}

function chamaEmail($i)
{
	$ch = curl_init();

	$postRequest = array(
		'id' => $i,
	);
	$srv = $_SERVER["HTTP_HOST"];
	$loc = $_SERVER["PHP_SELF"];
	$lc = explode("/", $loc);
	if (!isset($lc[2])) {
		$lc[2] = "";
	}
	if ($srv == "localhost") {
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/engerede/mail/index.php?id=' . $i);
	}
	if ($srv != "localhost") {
		if ($lc[2] == "dev") {
			curl_setopt($ch, CURLOPT_URL, 'http://hom.bitwase.com/engerede/mail/index.php?id=' . $i);
		}
		if ($lc[2] != "dev") {
			curl_setopt($ch, CURLOPT_URL, 'https://sistema.bitwase.com/mail/index.php?id=' . $i);
		}
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	/*    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $headers[] = 'Content-Type: application/json';
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    */
	$result = curl_exec($ch);
	//echo $result;
	if (curl_errno($ch)) {
		echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
}
