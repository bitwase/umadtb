<?php
## CONFIGURAÇÕES ##

$cnf = mysql_fetch_assoc(mysql_query("select * from config where id = 1"));

## RECEBE DADOS ATUALIZADOS E FAZ UPDATE ##

$atualiza = $_POST[atualiza];

if($atualiza == 1){
	mysql_query("update config set
	sms = '$_POST[envSms]',
	smsConsulta = '$_POST[smsCons]',
	smsPagamento = '$_POST[smsPg]',
	smsAniversario = '$_POST[smsAn]',
	mSmsConsulta = '$_POST[mSmsCon]',
	mSmsPg = '$_POST[mSmsPg]',
	dAtrasoSms = '$_POST[dAtrasoSms]',
	mSmsAn = '$_POST[mSmsAn]',
	email = '$_POST[envEmail]',
	emailConsulta = '$_POST[mailCons]',
	emailPagamento = '$_POST[mailPg]',
	dAtrasoEmail = '$_POST[dAtrasoEmail]',
	emailAniversario = '$_POST[mailAn]',
	mEmailConsulta = '$_POST[mEmailCon]',
	mEmailPg = '$_POST[mEmailPg]',
	mEmailAn = '$_POST[mEmailAn]'
	where id = 1
") or die(mysql_error());

echo "<meta http-equiv='refresh' content='0'>";
}

## Envios de SMS - Por padrão é configurado como ativo, cliente define e altera - Opção para alterar deverá ser inclusa no módulo de atendimento da bitwase ##

if($cnf_sms){
	$envH = "checked";
	$envD = "";
}
else{
	$envH = "";
	$envD = "checked";
}

if($cnf_smsConsulta){
	$smsConsH = "checked";
	$smsConsD = "";
}
else{
	$smsConsH = "";
	$smsConsD = "checked";
}

if($cnf_smsPagamento){
	$smsPgH = "checked";
	$smsPgD = "";
}
else{
	$smsPgH = "";
	$smsPgD = "checked";
}

if($cnf_smsAniversario){
	$smsAnH = "checked";
	$smsAnD = "";
}
else{
	$smsAnH = "";
	$smsAnD = "checked";
}

/* EMAIL */
if($cnf_email){
	$mailH = "checked";
	$mailD = "";
}
else{
	$mailH = "";
	$mailD = "checked";
}

if($cnf_emailConsulta){
	$mailConsH = "checked";
	$mailConsD = "";
}
else{
	$mailConsH = "";
	$mailConsD = "checked";
}

if($cnf_emailPagamento){
	$mailPgH = "checked";
	$mailPgD = "";
}
else{
	$mailPgH = "";
	$mailPgD = "checked";
}

if($cnf_emailAniversario){
	$mailAnH = "checked";
	$mailAnD = "";
}
else{
	$mailAnH = "";
	$mailAnD = "checked";
}
?>
<input type="button" value="Configurações" id="btAba1" onclick="aba(1)">
<input type="button" value="Lista SMS" id="btAba2" onclick="aba(2)">
<input type="button" value="Lista Email" id="btAba3" onclick="aba(3)">
<div id="ddCnf" style="display:none">
<?php
echo "
<span class='tt_pg'><b>Configurações</b></span><br><br>
<span class='color:red'><h2>Ambiente para demostração. SMS não será enviado.</h2></span><br>
<b>Agendas Contratadas: </b>$cnf_agenda<br>
<b>Usuários Contratados: </b>$cnf_usuarios<br><br>
";
?>
<form action="#" method="POST">
<input type="hidden" name="atualiza" value="1">
<b>SMS contratado:</b> <?php echo "$cnf_smsContrato";?> <br>
<b>Valor SMS excedente:</b> <?php echo "R$".number_format($cnf_smsExtra,"2",".","");?> <br>
<b>Envio de SMS:</b><input type="radio" name="envSms" id="envH" <?php echo $envH;?> value="1" onchange="sms()"><label for="envH">Habilitado</label>
<input type="radio" name="envSms" id="envD" <?php echo $envD;?> value="0" onchange="sms()"><label for="envD">Desabilitado</label><br>
<div id="mSms" style="display:block">
<b>Lembrete de Atendimento SMS:</b><input type="radio" name="smsCons" id="smsConsH" <?php echo $smsConsH;?> value="1"><label for="smsConsH">Habilitado</label>
<input type="radio" name="smsCons" id="smsConsD" <?php echo $smsConsD;?> value="0"><label for="smsConsD">Desabilitado</label><br>

<b>Lembrete de Pagamento SMS:</b><input type="radio" name="smsPg" id="smsPgH" <?php echo $smsPgH;?> value="1"><label for="smsPgH">Habilitado</label>
<input type="radio" name="smsPg" id="smsPgD" <?php echo $smsPgD;?> value="0"><label for="smsPgD">Desabilitado</label> <b>Dias de Atraso:</b><input type="text" name="dAtrasoSms" size="3" value="<?php echo $cnf_dAtrasoSms;?>"><br>

<b>Mensagem Aniversário SMS:</b><input type="radio" name="smsAn" id="smsAnH" <?php echo $smsAnH;?> value="1"><label for="smsAnH">Habilitado</label>
<input type="radio" name="smsAn" id="smsAnD" <?php echo $smsAnD;?> value="0"><label for="smsAnD">Desabilitado</label><br><br>
</div>


<b>Envio de Email:</b><input type="radio" name="envEmail" id="mailH" <?php echo $mailH;?> value="1"  onchange="mail()"><label for="mailH">Habilitado</label>
<input type="radio" name="envEmail" id="mailD" <?php echo $mailD;?> value="0" onchange="mail()"><label for="mailD">Desabilitado</label><br>

<div id="mEmail" style="display:block">
<b>Lembrete de Atendimento Email:</b><input type="radio" name="mailCons" id="mailConsH" <?php echo $mailConsH;?> value="1"><label for="mailConsH">Habilitado</label>
<input type="radio" name="mailCons" id="mailConsD" <?php echo $mailConsD;?> value="0"><label for="mailConsD">Desabilitado</label><br>

<b>Lembrete de Pagamento Email:</b><input type="radio" name="mailPg" id="mailPgH" <?php echo $mailPgH;?> value="1"><label for="mailPgH">Habilitado</label>
<input type="radio" name="mailPg" id="mailPgD" <?php echo $mailPgD;?> value="0"><label for="mailPgD">Desabilitado</label> <b>Dias de Atraso:</b><input type="text" name="dAtrasoEmail" size="3" value="<?php echo $cnf_dAtrasoEmail;?>"><br>

<b>Mensagem Aniversário Email:</b><input type="radio" name="mailAn" id="mailAnH" <?php echo $mailAnH;?> value="1"><label for="mailAnH">Habilitado</label>
<input type="radio" name="mailAn" id="mailAnD" <?php echo $mailAnD;?> value="0"><label for="mailAnD">Desabilitado</label><br><br>
</div>

<div id="txtSms" style="display:block;">
<b>SMS Atendimento</b><br>
<textarea name="mSmsCon" rows="3" cols="60" maxlength="140"><?php echo $cnf_mSmsConsulta;?></textarea><br>
<b>SMS Pagamento</b><br>
<textarea name="mSmsPg" rows="3" cols="60" maxlength="140"><?php echo $cnf_mSmsPagamento;?></textarea><br>
<b>SMS Aniversario</b><br>
<textarea name="mSmsAn" rows="3" cols="60" maxlength="140"><?php echo $cnf_mSmsAniversario;?></textarea><br><br>
</div>

<div id="txtEmail" style="display:block;">
<b>Email Atendimento</b><br>
<textarea name="mEmailCon" rows="3" cols="60" maxlength="140"><?php echo $cnf_mEmailConsulta;?></textarea><br>
<b>Email Pagamento</b><br>
<textarea name="mEmailPg" rows="3" cols="60" maxlength="140"><?php echo $cnf_mEmailPagamento;?></textarea><br>
<b>Email Aniversario</b><br>
<textarea name="mEmailAn" rows="3" cols="60" maxlength="140"><?php echo $cnf_mEmailAniversario;?></textarea>
</div>
<br><br>
<input type="submit" value="Salvar">

</form>
</div> <?php //fim div de configurações ?>

<div id="smsEnviadas" style="display:none">
<span class='tt_pg'><b>SMS's Enviados</b></span><br><br>
<table id="smsEnv" class="display" width="100%"></table>
</div>

<div id="emailEnviados" style="display:none">
<span class='tt_pg'><b>Emails Enviados</b></span><br><br>
<table id="mailEnv" class="display" width="100%"></table>
</div>
<script>
sms();
mail();
function sms(){
	if(document.getElementById("envH").checked == true){
		document.getElementById("mSms").style.display = "block";
		document.getElementById("txtSms").style.display = "block";
	}
	else if(!document.getElementById("envH").checked == true){
		document.getElementById("mSms").style.display = "none";
		document.getElementById("txtSms").style.display = "none";
	}
}

function mail(){
	if(document.getElementById("mailH").checked == true){
		document.getElementById("mEmail").style.display = "block";
		document.getElementById("txtEmail").style.display = "block";	
}
	else if(!document.getElementById("mailH").checked == true){
		document.getElementById("mEmail").style.display = "none";
		document.getElementById("txtEmail").style.display = "none";
}
}

// DADOS DE SMS E EMAIL
var smsSet = [
<?php
$sms1= mysql_query("select s.mensagem, s.st, date_format(s.dt_en,'%d/%m/%Y') as 'env', c.nome from tb_sms s inner join clientes c on s.cliente = c.id order by s.id desc");
 $od = 0;
while($sms = mysql_fetch_assoc($sms1)){
$od++;//define ordem
$mens =  $sms[mensagem];
$mens = str_replace("+"," ",$mens);
	if($sms[st] == 0){
		$st = "Processando";
	}
	if($sms[st] == 1){
		$st = "Agendado";
	}
	if($sms[st] == 2){
		$st = "Enviado";
	}
	if($sms[st] == 3){
		$st = "Cancelado";
	}
	echo "
	['$od','$sms[env]','$sms[nome]','$mens','$st'],";
}
?>
];
 
$(document).ready(function() {
    $('#smsEnv').DataTable( {
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: smsSet,
        columns: [
            { title: "" },
            { title: "Dt. Envio" },
	{ title: "Cliente" },
            { title: "Mensagem" },
            { title: "Situação" }
        ]
    } );
} );


var dataSet = [
<?php
$mail1= mysql_query("select s.mensagem, s.assunto, s.st, date_format(s.dt_en,'%d/%m/%Y') as 'env', c.nome from email s inner join clientes c on s.cliente = c.id order by s.id desc");
 $od = 0;
while($mail = mysql_fetch_assoc($mail1)){
$od++;//define ordem
$mens =  $mail[mensagem];
$mens = str_replace("+"," ",$mens);
	if($mail[st] == 0){
		$st = "Processando";
	}
	if($mail[st] == 1){
		$st = "Agendado";
	}
	if($mail[st] == 2){
		$st = "Enviado";
	}
	if($mail[st] == 3){
		$st = "Cancelado";
	}
	echo "
	['$od','$mail[env]','$mail[nome]','$mail[assunto]','$mens','$st'],";
}
?>
];
 
$(document).ready(function() {
    $('#mailEnv').DataTable( {
		"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
        ],
	"order": [1,'asc'],
         "paging":         false,
		data: dataSet,
        columns: [
            { title: "" },
            { title: "Dt. Envio" },
	{ title: "Cliente" },
	{ title: "Assunto" },
            { title: "Mensagem" },
            { title: "Situação" }
        ]
    } );
} );


//função para botões
aba(1);
function aba(x){
	if(x == 1){
		document.getElementById("ddCnf").style.display = "block";
		document.getElementById("smsEnviadas").style.display = "none";
		document.getElementById("emailEnviados").style.display = "none";

		document.getElementById("btAba1").style.opacity = "1.0";
		document.getElementById("btAba2").style.opacity = "0.5";
		document.getElementById("btAba3").style.opacity = "0.5";
	}
	if(x == 2){
		document.getElementById("ddCnf").style.display = "none";
		document.getElementById("smsEnviadas").style.display = "block";
		document.getElementById("emailEnviados").style.display = "none";

		document.getElementById("btAba1").style.opacity = "0.5";
		document.getElementById("btAba2").style.opacity = "1.0";
		document.getElementById("btAba3").style.opacity = "0.5";
	}
	if(x == 3){
		document.getElementById("ddCnf").style.display = "none";
		document.getElementById("smsEnviadas").style.display = "none";
		document.getElementById("emailEnviados").style.display = "block";

		document.getElementById("btAba1").style.opacity = "0.5";
		document.getElementById("btAba2").style.opacity = "0.5";
		document.getElementById("btAba3").style.opacity = "1.0";
	}
}


</script>
