<?php
error_reporting(~E_ALL);
include '../conexao.php';
include '../classes.php';

$ok = true;

$id = $_REQUEST['id'];

$mail = $pdo->query("select * from tb_email where id = '$id'")->fetch();

$destinatario = $mail['destinatario'];
$nomeDestinatario = $mail['nomeDestinatario'];
$assunto = $mail['assunto'];
$mensagem = $mail['mensagem']; //formato html
$anexo = $mail['anexo'];

//echo "$destinatario, $nomeDestinatario, $assunto, $mensagem, $anexo";

if ($ok) {
    enviaEmail($pdo, $destinatario, $nomeDestinatario, $assunto, $mensagem, $anexo);
}
function enviaEmail($pdo, $destinatario, $nomeDestinatario, $assunto, $mensagem, $anexo)
{

    $conf = new config($pdo);
    $config = $conf->configuracoes();

    /*    include 'vendor/autoload.php';
    $mailin = new Mailin('noreply@bitwase.com', '6RzcN7w1BnxrbXyg');
    $mailin->addTo($destinatario, $nomeDestinatario)->setFrom('noreply@bitwase.com', 'Campos Belos')->setReplyTo('nfecamposbelos@yahoo.com.br', 'Campos Belos')->setSubject($assunto)->setText('Olá')->setHtml($email);
    $res = $mailin->send();
    /**As mensagens de sucesso foram reenviadas sob esta forma:
                                        {'result' => true, 'message' => 'E-mail enviado'}
     */

    require_once('vendor/autoload.php');

    $cnf = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['mailChave']);

    $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
        new GuzzleHttp\Client(),
        $cnf
    );
    $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
    $sendSmtpEmail['subject'] = $assunto;

    $sendSmtpEmail['htmlContent'] = $mensagem;
    $sendSmtpEmail['sender'] = array('name' => $config['mailNome'], 'email' => $config['mailEmail']);/*$sendSmtpEmail['to'] = array(
        array('email' => $destinatario, 'name' => $nomeDestinatario)
    );*/
    /*$sendSmtpEmail['bcc'] = array(
        array('email' => 'etowuss@gmail.com', 'name' => 'Wellington Santos')
    );*/

    $loc = $_SERVER["HTTP_HOST"];
    if ($anexo) {
        //$pathXML = $anexo;
        //$content = chunk_split(base64_encode(file_get_contents($pathXML)));
        // Ends pdf wrapper
        $urlAnexo = "https://".$loc."/".$anexo;
           $attachment_item = array(
        'url' => $urlAnexo
    );
    $attachment_list = array($attachment_item);

    $sendSmtpEmail['attachment'] = $attachment_list;

    }
    $d = date("YmdHis");
    $sendSmtpEmail['replyTo'] = array('email' => $config['mailEmail'], 'name' => $config['mailNome']);
    $sendSmtpEmail['headers'] = array('Some-Custom-Name' => 'bw_' . $d);
    //$sendSmtpEmail['params'] = array('parameter' => 'My param value', 'subject' => 'New Subject');

    $d = explode(",", $destinatario);
    $nd = explode(",", $nomeDestinatario);

    try {
        foreach ($d as $des => $i) {
            $sendSmtpEmail['to'] = array(
                array('email' => $i, 'name' => $nd[$des])
            );
        #    echo "<pre>";
       #     print_r($sendSmtpEmail);
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            print_r($result);
        }
        //remover o arquivo
        //unlink($chave . '.xml');
    } catch (Exception $e) {
        echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
    }
}
