<?php
include 'seguranca.php';
$dd1 = mysql_query("select m.assunto, m.mensagem, c.email, c.nome from email m
inner join clientes c on m.dest = c.id 
order by m.id desc limit 1");
$dd = mysql_fetch_assoc($dd1);

envia_email("$dd[email]","$dd[nome]","$dd[assunto]","$dd[mensagem]");

function envia_email($email,$nome,$assunto,$mensagem){
include 'config.mail.php';

// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAddress("$email", "$nome");
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
$mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = "$assunto"; // Assunto da mensagem
$mail->Body = '<hr><p>Não responder este email. Se necessário entrar em contato através de nossos telefones.</p><hr>'.$mensagem.'
<hr><p>Não responder este email. Se necessário entrar em contato através de nossos telefones.</p><hr>';
//$mail->AltBody = "Registrado ";
// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
// Envia o e-mail

$enviado = $mail->Send();
// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();
// Exibe uma mensagem de resultado
if ($enviado) {
  echo "Email enviado com sucesso.";
} else {
  echo "Não foi possível enviar o e-mail.";
  echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
}
}
?>
