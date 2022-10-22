<h3>Evento: <span class='nomeEvento'></span></h3>
<h3>Inscrito: <span class='nomeInscrito'></span> <button type="button" name="" id="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#removerInscricao"><i class="fa fa-trash-o" aria-hidden="true"></i></button></h3>

<?php

$id = $_REQUEST['i']; //id da inscrição

/* com este id buscar o título do evento, o nome do usuário, e as situações do pagamento, etc. */


?>
<div id="liveAlertPlaceholder"></div>
<hr>
<!-- Modal Remover -->
<div class="modal fade" id="removerInscricao" tabindex="-1" aria-labelledby="removerInscricao" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Remover Inscrição</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja realmente remover esta inscrição?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="removerInscricao()">Sim</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="cmpId">

<div class="row">
    <div class="col-md-3 col-xs-2">
        <div class="form-group">
            <label for="infPagamento">Pagamento</label>
            <select class="custom-select" name="infPagamento" id="infPagamento" onchange="updateInscricao()">
                <option value="1">Pendente</option>
                <option value="2">Pago em Dinheiro</option>
                <option value="3">Pago com PIX</option>
            </select>
        </div>
    </div>
    <div class="col-md-3 col-xs-2">
        <label for="infPagamento"><br></label>
    </div>
</div>

<script>
    getDados(<?php echo $id; ?>);

    function getDados(i) {

        $("#cmpId").val(i);

        $.getJSON('eventos/dadosInscritos.php', {
            a: 1,
            i: i
        }, function(pagaData) {

            var evento = [];
            var nome = [];
            var pagamento = [];

            $(pagaData).each(function(key, value) {
                evento.push(value.evento);
                nome.push(value.nome);
                pagamento.push(value.pagamento);
            });

            $(".nomeEvento").html(evento);
            $(".nomeInscrito").html(nome);
            $("#infPagamento").val(pagamento);
        });
    }

    function removerInscricao() {
        $.post('eventos/dadosInscritos.php', {
            a: 'delete',
            i: $("#cmpId").val(),
        }, function(response) {

            const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

            const alert = (message, type) => {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');

                alertPlaceholder.append(wrapper);
            }
            alert('Inscrição removida!', 'success');
        });
    }

    function updateInscricao(){
        $.post('eventos/dadosInscritos.php', {
            a: 'update',
            i: $("#cmpId").val(),
            campo: 'pg',
            valor: $("#infPagamento").val(),
        }, function(response) {

            const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

            const alert = (message, type) => {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');

                alertPlaceholder.append(wrapper);
            }
            alert('Status de pagamento atualizado!', 'success');
        });
    }
</script>