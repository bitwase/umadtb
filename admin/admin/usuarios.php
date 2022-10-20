<h3>Usuários</h3>
<?php
############# CARREGAMENTO DE GRID ####################

$idsGrid = "1"; //se houver mais de uma, separar por vírgula.

$lgrid = $pdo->query("select * from tb_grid where id in($idsGrid)");
while ($l = $lgrid->fetch()) {
    //enquanto houver... procurar se existe um padrao para o usuário na tabela tb_gridUsr
    $ve = $pdo->query("select * from tb_gridusuario where grid = '$l[id]' and usuario = '$cod_us'")->rowCount();
    if ($ve) { //se houver...
        $vg = $pdo->query("select * from tb_gridusuario where grid = '$l[id]' and usuario = '$cod_us'")->fetch();
        $grid1 = $vg['padrao'];
    }
    if (!$ve) { //se não houver...
        $grid1 = $l['padrao'];
    }
?>
    <script>
        var xpt = <?php echo "$grid1"; ?>;
        //alert(xpt);
        xpt = JSON.stringify(xpt);
        //alert(xpt);
        localStorage["<?php echo $l['grid']; ?>"] = xpt;
    </script>
<?php }

############# CARREGAMENTO DE GRID ####################

$adicionar = $_POST['adicionar'];

if ($adicionar) {
    $nomeReg = $_POST['nome'];
    $usuarioReg = $_POST['usuarioReg'];
    $senhaReg = hash('whirlpool', $_POST['senhaReg']);
    $emailReg = $_POST['email'];

    $sql = "insert into tb_usuario (nome, usuario, senha, email) values(
        '$nomeReg',
        '$usuarioReg',
        '$senhaReg',
        '$emailReg'
    )";
    //verificar se usuário não existe
    $pdo->query($sql);
}

$atualizar = $_POST['atualizar'];

if ($atualizar) {

    $id = $_POST['id'];

    $nomeReg = $_POST['nome'];
    $usuarioReg = $_POST['usuarioReg'];
    $senhaReg = hash('whirlpool', $_POST['senhaReg']);
    $emailReg = $_POST['email'];

    $sql = "update tb_usuario
        set nome = '$nomeReg',
        usuario = '$usuarioReg',
        email = '$emailReg'
        where id = '$id'
        ";
    //verificar se usuário não existe
    $pdo->query($sql);
    if (!empty($_POST['senhaReg'])) {
        $sql = "update tb_usuario
            set senha = '$senhaReg' where id = '$id'
            ";
        //verificar se usuário não existe
        $pdo->query($sql);
    }
}
?>

<form action="#" method="POST">
    <input type="hidden" name="adicionar" id="acaoReg" value="1">
    <input type="hidden" name="id" id="idReg" value="1">

    <div class="row">
        <div class="col-md-3 col-xs-2">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome">
            </div>
        </div>

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="usuario">Usuário</label>
                <input type="text" class="form-control" name="usuarioReg" id="usuario">
            </div>
        </div>

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" name="senhaReg" id="senha" onkeyup="validaSenha()">
            </div>
        </div>

        <div class="col-md-2 col-xs-2">
            <div class="form-group">
                <label for="senhaRp">Repete Senha</label>
                <input type="password" class="form-control" name="senhaRp" id="senhaRp" onkeyup="validaSenha()">
                <small id="helpSenha" class="txt-vermelho"></small>
            </div>
        </div>

        <div class="col-md-1 col-xs-2">
            <div class="form-group">
                <label for="senhaRp"><br></label>
                <button type="button" name="" id="" class="btn btn-primary btn-block" onclick="geraSenha()"><i class="fa-solid fa-shuffle"></i></button>
            </div>
        </div>

        <div class="col-md-1 col-xs-2">
            <div class="form-group">
                <label for="senhaRp"><br></label>
                <button type="button" name="" id="" class="btn btn-primary btn-block" onmousedown="espiaSenha()" onmouseup="escondeSenha()"><i class="fa-thin fa-eye"></i></button>
            </div>
        </div>

        <div class="col-md-1 col-xs-2">
            <div class="form-group">
                <label for="senhaRp"><br></label>
                <button type="button" name="" id="" class="btn btn-primary btn-block" onclick="copyToClipboard('#senha')"><i class="fa-thin fa-clone"></i></button>
            </div>
        </div>

        <div class="col-md-4 col-xs-2">
            <div class="form-group">
                <label for="senha">Email</label>
                <input type="text" class="form-control" name="email" id="email">
            </div>
        </div>
        <div class="col-md-2 col-xs-2">
            <label for=""><br></label>
            <button type="submit" name="" id="btInserir" disabled class="btn btn-primary btn-block">Inserir</button>
        </div>

        <div class="col-md-2 col-xs-2">
            <label for=""><br></label>
            <button type="button" name="" id="" class="btn btn-warning btn-block" onclick="limparDados()">Limpar</button>
        </div>
    </div>
</form>

<hr>

<div id="grid"></div>


<script>
    /* GRID CENTRO DE CUSTO */
    $(document).ready(function() {
        var dataSource = new kendo.data.DataSource({
            data: [
                <?php
                $cl1 = $pdo->query("select * from tb_usuario order by nome");
                $od = 0;
                while ($cli = $cl1->fetch()) {
                    $od++; //define ordem
                    $nome = strtoupper($cli['nome']);
                    $usuario = $cli['usuario'];
                    $email = $cli['email'];

                    if ($cli['situacao'] == 0) {
                        $st = "Inativo";
                    }
                    if ($cli['situacao'] == 1) {
                        $st = "Ativo";
                    }
                    $lk = "<i class=\'fa fa-pencil fa-lg\' onclick=\'editar($cli[id],\"$nome\", \"$usuario\", \"$email\")\'></i> <a href=\'?pg=acessos&u=$cli[id]\'><i class=\'fa fa-key fa-lg\'></i></a>";

                    $id = $cli['id'];

                    echo "{
		ID: '$id',
		Lk: '$lk',
		Nome: '$nome',
		Usuario:'$usuario',
		Email: '$email',
		Status: '$st'
		},";
                    $od++;
                }

                ?>

            ]
        }); //fim dos dados dataSource

        //ver: https://docs.telerik.com/kendo-ui/knowledge-base/auto-resize-grid-when-hiding-and-showing-columns
        $("#grid").kendoGrid({
            toolbar: ["pdf", "excel"],
            /*items: [
                {
            	 type: "buttonGroup",
            	 buttons: [
            	   { text: "foo" },
            	   { text: "bar" },
            	   { text: "baz" }
            	 ]
                }
              ],*/
            pdf: {
                fileName: "usuarios.pdf",
                allPages: true,
                avoidLinks: true,
                paperSize: "A4",
                margin: {
                    top: "2cm",
                    left: "1cm",
                    right: "1cm",
                    bottom: "1cm"
                },
                landscape: true,
                repeatHeaders: true,
                template: $("#page-template").html(),
                scale: 0.8
            },
            excel: {
                fileName: "usuarios.xlsx",
                filterable: true
            },

            autoBind: false,
            height: "550px",
            groupable: true,
            sortable: true,
            columnMenu: true,
            reorderable: true,
            resizable: true,
            filterable: true,
            pageable: {
                refresh: false,
                pageSizes: false,
                buttonCount: false,
                previousNext: false,
                info: false,
                numeric: false
            },
            //persistSelection: true,
            dataSource: dataSource,
            schema: {
                model: {
                    fields: {}
                }
            },
            columns: [{
                    field: "ID",
                    title: "ID",
                    hidden: true,
                    type: "number",
                },
                {
                    field: "Lk",
                    title: " ",
                    width: 80,
                    template: "#=Lk#",
                    groupable: false,
                    sortable: false,
                    filterable: false,
                    exportable: {
                        pdf:false,
                        excel: false
                    }
                },
                {
                    field: "Nome",
                    title: "Nome",
                    width: 200
                },
                {
                    field: "Usuario",
                    title: "Usuário",
                    width: 80
                },
                {
                    field: "Email",
                    title: "Email",
                    width: 150
                },
                {
                    field: "Status",
                    title: "Status",
                    width: 150
                },
            ],
            excelExport: function(e) { //remover HTML de grids
                var rows = e.workbook.sheets[0].rows;
                for (var ri = 0; ri < rows.length; ri++) {
                    var row = rows[ri];
                    if (row.type == "data") {
                        for (var ci = 0; ci < row.cells.length; ci++) {
                            var cell = row.cells[ci];
                            //se existir o atributo
                            if (typeof cell.value != "undefined" && cell.value != "" && cell.value != null && cell.value instanceof Date == false) {
                                var val = cell.value.replace(/<[^>]+>/g, '');
                                cell.value = val;
                            }
                        }
                    }
                }
            },
            columnReorder: function(e) {
                setTimeout(salvar, 5);
            },
            columnShow: function(e) {
                setTimeout(salvar, 5); //setGridWidth(e);
            },
            columnHide: function(e) {
                setTimeout(salvar, 5); //setGridWidth(e);
            },
            columnResize: function(e) {
                setTimeout(salvar, 5); //console.log(e.column.field, e.newWidth, e.oldWidth);
            }
        });

        var grid = $("#grid").data("kendoGrid");
        /*$("#sort").click(function() {
        	grid.dataSource.sort(grid.dataSource.options.sort);
        });*/

        $("#save").click(function(e) {
            salvar();
        });

        function salvar() {
            //         e.preventDefault();
            //var xpt = kendo.stringify(grid.getOptions());
            var options = grid.getOptions();
            var xpt = kendo.stringify(options);
            //						document.getElementById("stateNovo").value = xpt;
            localStorage["kendo-grid-options-listaUsuarios"] = xpt;
            //  alert(xpt);

            //abaixo, g: é o id da grid na base de dados, u é id do usuário e s: o stete novo
            var u = "<?php echo $cod_us; ?>";
            $.ajax({
                method: "POST",
                url: "atualizaGrid.php",
                data: {
                    g: "1",
                    u: u,
                    s: xpt
                },
                success: function(response) {
                    //alert(response);
                    //		       			value = response.company;
                }
            });

        }

        load();
        $("#load").click(function(e) {
            load();
        });

        function load() {
            var options = localStorage["kendo-grid-options-listaUsuarios"];
            if (options) {
                var parsedOptions = JSON.parse(options);
                grid.setOptions({
                    columns: parsedOptions.columns,
                    group: parsedOptions.group
                });

                //		                       grid.refresh();
                grid.dataSource.fetch();
                //  $("#grid").data("kendoGrid").dataSource.read();                  
            }
        }
        dataSource.read();
    });

    function validaSenha() {
        let s1 = $("#senha").val();
        let s2 = $("#senhaRp").val();

        let bt = $("#btInserir").text();

        if (bt == "Inserir") {
            if (s1 == "") {
                $("#helpSenha").addClass("txt-vermelho");
                $("#helpSenha").removeClass("txt-verde");
                $("#helpSenha").html("Senha não informada.");

                $("#btInserir").prop("disabled", true);
            }

            if (s1 != s2) {
                $("#helpSenha").addClass("txt-vermelho");
                $("#helpSenha").removeClass("txt-verde");
                $("#helpSenha").html("Senhas informadas são diferentes.");

                $("#btInserir").prop("disabled", true);
            }

            if (s1 == s2 && s1 != "") {
                $("#helpSenha").removeClass("txt-vermelho");
                $("#helpSenha").addClass("txt-verde");
                $("#helpSenha").html("Senhas informadas são iguais.");

                $("#btInserir").prop("disabled", false);
            }
        }

        if (bt == "Atualizar") {
            if (s1 == "") {
                $("#helpSenha").addClass("txt-vermelho");
                $("#helpSenha").removeClass("txt-verde");
                $("#helpSenha").html("Senha não será alterada.");
                $("#btInserir").prop("disabled", false);
            }

            if (s1 != s2) {
                $("#helpSenha").addClass("txt-vermelho");
                $("#helpSenha").removeClass("txt-verde");
                $("#helpSenha").html("Senhas informadas são diferentes.");

                $("#btInserir").prop("disabled", true);
            }

            if (s1 == s2 && s1 != "") {
                $("#helpSenha").removeClass("txt-vermelho");
                $("#helpSenha").addClass("txt-verde");
                $("#helpSenha").html("Senhas informadas são iguais.");

                $("#btInserir").prop("disabled", false);
            }
        }
    }

    function editar(id, nome, usuario, email) {
        $("#idReg").val(id);
        $("#nome").val(nome);
        $("#usuario").val(usuario);
        $("#email").val(email);

        $("#acaoReg").prop("name", "atualizar");
        $("#btInserir").prop("disabled", false);
        $("#btInserir").html("Atualizar");
    }

    function limparDados() {
        $("#idReg").val("");
        $("#nome").val("");
        $("#usuario").val("");
        $("#senha").val("");
        $("#email").val("");

        $("#acaoReg").prop("name", "adicionar");
        $("#btInserir").prop("disabled", true);
        $("#btInserir").html("Inserir");
    }

    function espiaSenha() {
        $("#senha").attr("type", "text");
        $(".fa-eye").addClass("fa-eye-slash");
        $(".fa-eye").removeClass("fa-eye");
    }

    function escondeSenha() {
        $("#senha").attr("type", "password");
        $(".fa-eye-slash").addClass("fa-eye");
        $(".fa-eye-slash").removeClass("fa-eye-slash");
    }

    function copyToClipboard() {
        $("#senha").attr("type", "text");
        $("#senha").select();
        //var temp = $("#senha").select();
        //       temp.val($(element).text()).select();
        document.execCommand("copy");
        $("#senha").attr("type", "password");
        //       temp.remove();
    }

    function geraSenha() {
        //chamar a função externa que irá gerar, e atribuir nos dois campos.
        //habilitar "olho" para poder ver a senha gerada.
        $.getJSON('admin/geraSenha.php', function(pagaData) {

            var pass = [];

            $(pagaData).each(function(key, value) {
                pass.push(value.pass);
            });
            $("#senha").val(pass);
            $("#senhaRp").val(pass);

        });
    }
</script>