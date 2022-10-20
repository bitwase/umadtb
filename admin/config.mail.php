<?php
/*
Classe mailer.
Adaptado por Wellington Ulisses Santos, para uso na empresa Serdia.
*/
//echo "<a href='../../arquivos/mailer/PHPMailerAutoload.php'>testes</a>";
include_once("../arquivos/mailer/PHPMailerAutoload.php");

// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require_once("../arquivos/mailer/class.phpmailer.php");
// Inicia a classe PHPMailer
$mail = new PHPMailer();
// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
/*
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "smtp.gmail.com"; // Endereço do servidor SMTP
$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
$mail->Username = 'ti.serdia@gmail.com'; // Usuário do servidor SMTP
$mail->Password = 't3s2rd31@2018'; // Senha do servidor SMTP
// Define o remetente
$mail->From = "ti.serdia@gmail.com"; // Seu e-mail
$mail->FromName = "Chamados"; // Nome que aparecerá p/ quem receber o email
*/

/*
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "smtp.office365.com"; // Endereço do servidor SMTP
$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
$mail->Port = 587;
$mail->Username = 'noreply@serdia.com.br'; // Usuário do servidor SMTP
$mail->Password = 'n0r3plys&rd!@'; // Senha do servidor SMTP
*/
include "../mail.geral.php";
// Define o remetente
$mail->From = "noreply@serdia.com.br"; // Seu e-mail
$mail->FromName = "Controle de Licenças"; // Nome que aparecerá p/ quem receber o email


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
