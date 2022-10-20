<h3>Alterar Senha</h3>
<?php
//include "seguranca.php";

$altera = $_POST['altera'];

if ($altera) {
	$usuario = $_COOKIE['usuario'];
	$atual = hash('whirlpool', $_POST['sn_atual']);
	$nova = hash('whirlpool', $_POST['sn_nova']);


	$vs = $pdo->query("SELECT * FROM tb_usuario WHERE usuario='$usuario' AND senha='$atual'")->rowCount();
	if ($vs) {
		$pdo->query("UPDATE tb_usuario SET  senha = '$nova' WHERE usuario = '$usuario' AND senha = '$atual'");
	}
	if ($vs == "0") {
		echo "SENHA INFORMADA NÃO CONFERE";
	}
	//    header('Location:index.php?pg=altera.senha.php');
	if ($vs) {
		//echo "<script>document.getElementById('alerta').innerHTML = '<span class=\'tt_pg\'>ALTERANDO A SENHA... AGUARDE.</span>';$('#aguarde').fadeIn();$('#mascara').fadeIn();</script>";
		//echo "<META http-equiv='refresh' content='2;URL=valida.php?a=1&u=$usuario&s=$nova'>";
	}
}
?>
<form id='alt_senha' name='alt_senha' action='#' method='post' style="width:300px;left:0;right:0;margin:auto;">
	<input type="hidden" name="altera" value="1">
	<div class="form-group">
		<label for="sn_atual">Senha Atual</label>
		<input type="password" class="form-control" name="sn_atual" id="sn_atual" placeholder="">
	</div>

	<div class="form-group">
		<label for="sn_nova">Nova Senha</label>
		<input type="password" class="form-control" name="sn_nova" id="sn_nova" placeholder="">
	</div>

	<div class="form-group">
		<label for="rp_nova">Repetir Nova Senha</label>
		<input type="password" class="form-control" name="rp_nova" id="rp_nova" placeholder="" onkeyup="validaSenha()">
	</div>
	<small id="helpId" class="form-text text-muted"></small><br>

	<button type="submit" class="btn btn-primary" id="btAtualizar" disabled>Atualizar</button>
</form>

<script>
	function validaSenha() {
		var sn_nova = $("#sn_nova").val();
		var rp_nova = $("#rp_nova").val();
		if (sn_nova != rp_nova) {
			$("#helpId").html("<span style='color: #f00;'>As senhas estão diferentes.</span>");
			$("#btAtualizar").prop("disabled", true);

		}
		if (sn_nova == rp_nova) {
			$("#helpId").html("<span style='color: #1BA003;'>Senhas iguais.</span>");
			$("#btAtualizar").prop("disabled", false);

		}
	}
</script>