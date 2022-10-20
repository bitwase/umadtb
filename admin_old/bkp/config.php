<?php
## CONFIGURAÇÕES ##

$cnf = mysql_fetch_assoc(mysql_query("select * from config where id = 1"));

$cnf_Sistema = $cnf[sistema];
$cnf_contrato = $cnf[contrato];//número do contrato com Bitwase


## Quantidade de Agendas/Usuário ## de acordo com o contrato... esse número deve ser alterado diretamente pelo sistema de controles da Bitwase ##
$cnf_agenda = $cnf[agenda];
$cnf_usuarios = $cnf[usuarios];

$cnf_senha = $cnf[senha];//senha padrão para novos cadastros

## Envios de SMS - Por padrão é configurado como ativo, cliente define e altera - Opção para alterar deverá ser inclusa no módulo de atendimento da bitwase ##

$cnf_smsContrato = $cnf[smsContrato];//número de sms contratado.. estes devem ser cobrados 0.10 cada
$cnf_smsVlr = $cnf[smsVlr];//valor de sms por contrato.. deve ser alterado pelo sistema BW
$cnf_smsExtra = $cnf[smsExtra]; //valor de sms extra por contrato.. deve ser alterado pelo sistema BW
$cnf_sms = $cnf[sms];//confirma se envia sms ou não, sendo falso este, o restante automaticamente é falso
$cnf_smsConsulta = $cnf[smsConsulta];//habilita lembretes de consulta
$cnf_smsPagamento = $cnf[smsPagamento];//habilita lembretes de pagamento atrasado
$cnf_dAtrasoSms = $cnf[dAtrasoSms];//dias de atraso para enviar sms
$cnf_smsAniversario = $cnf[smsAniversario];//habilita sms de aniversario

## Envios de SMS - Por padrão é configurado como ativo, cliente define e altera - Opção para alterar deverá ser inclusa no módulo de atendimento da bitwase ##
$cnf_email = $cnf[email];
$cnf_emailConsulta = $cnf[emailConsulta];
$cnf_emailPagamento = $cnf[emailPagamento];
$cnf_dAtrasoEmail = $cnf[dAtrasoEmail];//dias de atraso para enviar sms
$cnf_emailAniversario = $cnf[emailAniversario];

## MODELOS DE MENSAGEM ##

$cnf_mSmsConsulta = $cnf[mSmsConsulta];
$cnf_mSmsPagamento = $cnf[mSmsPg];
$cnf_mSmsAniversario = $cnf[mSmsAn];

## MODELOS DE EMAIL ##

$cnf_mEmailConsulta = $cnf[mEmailConsulta];
$cnf_mEmailPagamento = $cnf[mEmailPg];
$cnf_mEmailAniversario = $cnf[mEmailAn];

## DEFINIÇÕES SMTP ##

$cnf_smtp = $cnf[mailSmtp];
$cnf_smtp_usr = $cnf[mailUsr];
$cnf_smtp_senha = $cnf[mailSenha];
$cnf_smtp_envia = $cnf[mailEnvia];
$cnf_smtp_nome = $cnf[mailNome];

## CONTAGEM DE ALERTAS ##
//inserido em index devido ao fato de utilizar confir nos emails, isto geraria um erro...
//envio de email junto com alerta.
?>
