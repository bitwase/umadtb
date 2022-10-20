<?php
include 'conexao.php';
include 'config.php';
include 'config.mail.php';


$vrMail = mysql_query("select e.*, c.nome from email e inner join clientes c on e.cliente = c.id where e.st = 1 and e.dt_en <= now()");

while($mn = mysql_fetch_assoc($vrMail)){
$mail->AddAddress("$mn[email]", "$mn[nome]");

$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

// Define a mensagem (Texto e Assunto)
$mail->Subject  = "$mn[assunto]"; // Assunto da mensagem
$mail->Body = "$mn[mensagem]";

$enviado = $mail->Send();
// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();
// Exibe uma mensagem de resultado
if ($enviado) {
	mysql_query("update email set st = '2' where id = $mn[id]");  
//echo "E-mail enviado com sucesso!";
} else {
  echo "Não foi possível enviar o e-mail.";
  echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
}
}
?>
