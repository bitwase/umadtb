<?php

$p = $_REQUEST['p'];

//buscar os dados do produto
?>

<div id="campoAcoes">
    <div class="row">
        <div class="col-md-2 col-xs-2">
            <div class="row">
                <div class="col-md-12 col-xs-2">
                    <div class="form-group">
                        <button type="button" name="" id="" class="btn btn-primary btn-block">Editar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<h4 class="pdt_nome">...</h4>

<div class="accordion">

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeral" aria-expanded="true" aria-controls="collapseGeral">
                Geral
            </button>
        </h2>
        <div id="collapseGeral" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

                <div class="row">
                    <div class="col-md-12 col-xs-2">
                        <div class="form-group">
                            <label for="pdtDescricao">Descrição</label>
                            <input type="text" class="form-control" name="pdtDescricao" id="pdtDescricao">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-xs-2">
                        <div class="form-group">
                            <label for="">Grupo</label>
                            <input list="listaGrupo" class="form-control" name="grupo" id="grupoLista" aria-describedby="helpId" onchange="validaGrupo()" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-2">
                        <div class="form-group">
                            <label for="">Subgrupo</label>
                            <input list="listaSubgrupo" class="form-control" name="subgrupo" id="subLista" aria-describedby="helpId" onchange="validaSubgrupo()" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-2">
                        <div class="form-group">
                            <label for="pdtUnidade">Unidade</label>
                            <select class="form-control select2-selection select2-selection--multiple" name="pdtUnidade" id="pdtUnidade" style="width:100%">
                                <option value="" selected></option>
                                <?php
                                $lc = $pdo->query("select * from est_unidades where st = 1 order by unidade");
                                while ($l = $lc->fetch()) {
                                    echo "<option value='$l[id]'>$l[unidade]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEstoque" aria-expanded="true" aria-controls="collapseEstoque">
                Estoque
            </button>
        </h2>
        <div id="collapseEstoque" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

                <div class="row">
                    <div class="col-md-2 col-xs-2">
                        <div class="form-group">
                            <label for="">Atual</label>
                            <input type="text" class="form-control" name="" id="pdtQtAtual" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-2">
                        <div class="form-group">
                            <label for="">Disponível</label>
                            <input type="text" class="form-control" name="" id="pdtQtDisponivel" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-2">
                        <div class="form-group">
                            <label for="">Reservado</label>
                            <input type="text" class="form-control" name="" id="pdtQtReservado" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-2">
                        <div class="form-group">
                            <label for="">Encomendado</label>
                            <input type="text" class="form-control" name="" id="pdtQtEncomendado" aria-describedby="helpId" placeholder="" disabled>
                        </div>
                    </div>

                    <div class="col-md-2 col-xs-2">
                        <div class="form-group">
                            <label for="">Qt. Mínima</label>
                            <span data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Quantidade mínima para alerta de necessidade de compra.">
                                <input type="text" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompra" aria-expanded="true" aria-controls="collapseCompra">
                Compra
            </button>
        </h2>
        <div id="collapseCompra" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

                Compras
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVenda" aria-expanded="true" aria-controls="collapseVenda">
                Vendas
            </button>
        </h2>
        <div id="collapseVenda" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

                Vendas
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTransacoes" aria-expanded="true" aria-controls="collapseTransacoes">
                Transações
            </button>
        </h2>
        <div id="collapseTransacoes" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">

                Vendas
            </div>
        </div>
    </div>
</div>

<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
    retDados();

    function retDados() {
        let p = "<?php echo $p; ?>";
        $.getJSON("estoque/dadosProdutos.php?a=2&p=" + p, function(atData) {
            var ID = [];
            var Descricao = [];
            var Grupo = [];
            var Subgrupo = [];
            var Un = [];
            var Situacao = [];
            var DataRegistro = [];
            var UsRegistro = [];
            var Disponivel = [];
            var Reservado = [];
            var Encomendado = [];
            $(atData).each(function(key, value) {
                ID.push(value.ID);
                Descricao.push(value.Descricao);
                Grupo.push(value.Grupo);
                Subgrupo.push(value.Subgrupo);
                Un.push(value.Un);
                Situacao.push(value.Situacao);
                DataRegistro.push(value.DataRegistro);
                UsRegistro.push(value.UsRegistro);
                Disponivel.push(value.Disponivel);
                Encomendado.push(value.Encomendado);
                Reservado.push(value.Reservado);
            });

            //preencher dados
            $(".pdt_nome").html(ID + " - " + Descricao);
            $("#pdtQtAtual").val(parseFloat(Disponivel) + parseFloat(Reservado));
            $("#pdtQtDisponivel").val(Disponivel);
            $("#pdtQtEncomendado").val(Encomendado);
            $("#pdtQtReservado").val(Reservado);
        });
    }

    atAccordion();

    function atAccordion() {

        $.getJSON("accordion.php?a=2&p=produto", function(atData) {
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
                p: "produto",
                i: id,
                v: valor,
            }, function(response) {

            });

        });

    });
</script>