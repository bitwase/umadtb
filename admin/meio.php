<?php 
if($pagina == "relatorio.php" || $pagina == "historico.php" || $pagina == "consulta.colaborador.php" || $pagina == "lista.ramais.php" || $pagina == "lista.email.php" || $pagina == "lista.maquinas.php" || $pagina == "lista.usuarios.php" || $pagina == "alteracoes.php" || $pagina == "lista.setor.php"){
    include $pagina;
}
if($pagina != "relatorio.php" && $pagina != "historico.php" && $pagina != "consulta.colaborador.php" && $pagina != "lista.ramais.php" && $pagina != "lista.email.php" && $pagina != "lista.maquinas.php" && $pagina != "lista.usuarios.php" && $pagina != "alteracoes.php" && $pagina != "lista.setor.php"){
    echo "<div id='meio'>";
    echo "<div id='inclui_pg'>"; include $pagina; echo "</div>"; 
echo "<div id='titulo_meio'>$titulob</div>";
}
?>
</div>
<?php  #encerra verificação se logado ou não ?>
