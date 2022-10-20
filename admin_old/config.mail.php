<?php
/*
Classe mailer.
Adaptado por Wellington Ulisses Santos, para uso na empresa Serdia.
*/
//echo "<a href='../../arquivos/mailer/PHPMailerAutoload.php'>testes</a>";
include_once("../../arquivos/mailer/PHPMailerAutoload.php");

// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require_once("../../arquivos/mailer/class.phpmailer.php");
// Inicia a classe PHPMailer
$mail = new PHPMailer();
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "$cnf_smtp"; // Endereço do servidor SMTP
$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
$mail->Username = "$cnf_smtp_usr"; // Usuário do servidor SMTP
$mail->Password = "$cnf_smtp_senha"; // Senha do servidor SMTP
// Define o remetente
$mail->From = "$cnf_smtp_envia"; // Seu e-mail
$mail->FromName = "$cnf_smtp_nome"; // Nome que aparecerá p/ quem receber o email
/*
Necessário inserir no local onde será enviado o email:
-colocar as opções abaixo

-email destinatário/nome
$mail->AddAddress('nitens@serdia.com.br', 'TI-Nitens');

$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

// Define a mensagem (Texto e Assunto)
$mail->Subject  = "Chamado $chamado"; // Assunto da mensagem

$mail->Body = 'Texto Com ou sem formatação HTML';

// Define os anexos (opcional)
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
// Envia o e-mail

$enviado = $mail->Send();
// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();
// Exibe uma mensagem de resultado
if ($enviado) {
  echo "E-mail enviado com sucesso!";
} else {
  echo "Não foi possível enviar o e-mail.";
  echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
}

*/
?>
