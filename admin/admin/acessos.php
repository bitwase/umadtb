<h3>Controle de Acessos</h3>
<?php

$us = $_REQUEST['u'];
if ($us == "") {
    echo "<script>$('#alerta').html('<h3>Usuário Inválido.</h3><br><a href=\"?pg=usuarios\"><button type=\"button\" class=\"btn btn-primary btn-block\">Voltar</button></a>');
	$('#alerta').fadeIn('slow');
	$('#mascara').fadeIn('slow');
	</script>";
}

$nc = $pdo->query("select * from tb_usuario where id = '$us'")->fetch();

echo "<br><h3>$nc[nome]</h3><br>";

echo "<div class='accordion'>";

$la = $pdo->query("select distinct diretorio from diretorios where diretorio != 'dashboard' order by diretorio");
$aux = 0;
while ($l = $la->fetch()) {
    $aux++;
    $dir = $l['diretorio']; //para busca de arquiv
    if ($l['diretorio'] == "") {
        $l['diretorio'] = "Geral";
    }

    echo "<div class='accordion-item'>";


    echo "
    <h2 class='accordion-header' id='heading_$aux'>
            <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse_$aux' aria-expanded='true' aria-controls='collapse_$aux'>
                " . strtoupper($l['diretorio']) . "
            </button>
        </h2>
    ";

 echo "
 <div id='collapse_$aux' class='accordion-collapse collapse' aria-labelledby='heading_$aux'>
            <div class='accordion-body'>
 ";

    echo "
  <div class='card card-body'><div class='row'>";
    /**Listar para cada diretorio, as páginas */
    $lps = $pdo->query("select distinct arquivo, titulo from diretorios where diretorio = '$dir' order by titulo");
    while ($ls = $lps->fetch()) {
        //verificar se o usuário em questão possui acesso, se sim, marcar "checked"
        $va = $pdo->query("select * from tb_acessos where us = '$us' and tipo = 'P' and pg = '$ls[arquivo]'")->rowCount();
        if ($va) {
            $ckd = "checked";
        } else {
            $ckd = "";
        }
        echo "<div class='col-md-3 col-xs-2 mb-1'><input type='checkbox' class='toggle' name='' data-on='$ls[titulo]' data-off='$ls[titulo]' $ckd data-toggle='toggle' data-value='$ls[arquivo]' data-tipo='P' onchange='alteraAcesso(this)'></div>";
        //echo "<input type='checkbox' name='' id='inc_garantia' data-on='$ls[arquivo]' data-off='$ls[arquivo]' onchange='alteraAcesso(this.id)' data-toggle='toggle'>";
    }
    echo "</div></div>
    </div></div>";
    echo "</div>"; //fecha item do accordion
    //echo "$l[diretorio] - $l[arquivo]<br>";
}


$la = $pdo->query("select distinct diretorio from diretorios where diretorio = 'dashboard' order by diretorio");
$aux = 0;
while ($l = $la->fetch()) {
    $aux++;
    $dir = $l['diretorio']; //para busca de arquivo
    if ($l['diretorio'] == "") {
        $l['diretorio'] = "Geral";
    }

    echo "<div class='accordion-item'>";

    echo "
    <h2 class='accordion-header' id='headingDash_$aux'>
            <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseDash_$aux' aria-expanded='true' aria-controls='collapseDash_$aux'>
                " . strtoupper($l['diretorio']) . "
            </button>
        </h2>
    ";

 echo "
 <div id='collapseDash_$aux' class='accordion-collapse collapse' aria-labelledby='headingDash_$aux'>
            <div class='accordion-body'>
 ";

    echo "
  <div class='card card-body'><div class='row'>";
    /**Listar para cada diretorio, as páginas */
    $lps = $pdo->query("select distinct arquivo, titulo from diretorios where diretorio = '$dir' order by titulo");
    while ($ls = $lps->fetch()) {
        //verificar se o usuário em questão possui acesso, se sim, marcar "checked"
        $va = $pdo->query("select * from tb_acessos where us = '$us' and tipo = 'D' and pg = '$ls[arquivo]'")->rowCount();
        if ($va) {
            $ckd = "checked";
        } else {
            $ckd = "";
        }
        echo "<div class='col-md-3 col-xs-2 mb-1'><input type='checkbox' class='toggle' name='' data-on='$ls[titulo]' data-off='$ls[titulo]' $ckd data-toggle='toggle' data-value='$ls[arquivo]' data-tipo='D' onchange='alteraAcesso(this)'></div>";
        //echo "<input type='checkbox' name='' id='inc_garantia' data-on='$ls[arquivo]' data-off='$ls[arquivo]' onchange='alteraAcesso(this.id)' data-toggle='toggle'>";
    }
    echo "</div></div></div></div></div>";
    echo "</div>"; //fecha acordion
    //echo "$l[diretorio] - $l[arquivo]<br>";
}
?>
<br><br><br>
<script>
    $('.toggle').bootstrapToggle({
        width: '100%',
    });

    function alteraAcesso(x) {
        var a = $(x).data("value");
        var t = $(x).data("tipo");
        var u = "<?php echo $us; ?>";
        //passar como post para a alteração
        $.post('retAcessos.php', {
            u: u,
            a: a,
            t: t,
        }, function(response) {

        });
    }

    atAccordion();

    function atAccordion() {

        $.getJSON("accordion.php?a=2&p=acessos", function(atData) {
            var campo = [];
            var vis = [];
            $(atData).each(function(key, value) {
                campo.push(value.campo);
                vis.push(value.vis);
            });
            //rodar um each aqui, ver os valores e ajustar
            campo.forEach(mostra);

            function mostra(i, v) {
                //alert();
                //validar qual valor está definido
                //console.log(i);
                //console.log(vis[v]);
                if (vis[v] == "hidde") {
                    $("#" + i).removeClass("show");
                    //console.log("[data-bs-target='" + i + "']");                    
                    $("[data-bs-target='#" + i + "']").addClass("collapsed");
                }
                if (vis[v] == "show") {
                    $("#" + i).addClass("show");
                    //console.log("[data-bs-target='" + i + "']");                    
                    $("[data-bs-target='#" + i + "']").removeClass("collapsed");
                }
            }
        });

        //esconder
        //remover class show do id em questão
        //add a classe collapsed de onde for data-bs-target = ID informado
        //mostrar
        //add class show no id em questão
        //remove a classe collapsed de onde for data-bs-target = ID informado
    }

    $(document).ready(function() {
        $(".accordion-button").click(function(r) {
            var id = r.target.getAttribute('aria-controls');
            //verificar se mostra ou esconde...
            var sit = $("[data-bs-target='#" + id + "']").hasClass("collapsed");
            if (sit == true) { //esconde
                var valor = "hidde";
            }
            if (sit == false) { //mostra
                var valor = "show";
            }

            //chamar atualização de status
            $.post('accordion.php', {
                a: 1,
                p: "acessos",
                i: id,
                v: valor,
            }, function(response) {

            });

        });

    });
</script>