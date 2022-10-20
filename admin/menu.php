<div class="nav-side-menu">
	<div class="brand"><a href="index.php"><img src="arquivos/imagens/logo-login.png" width="130px" /></a></div>
	<i class="fa fa-bars fa-2x toggle-btn collapsed" data-bs-toggle="collapse" data-bs-target="#menu-content"></i>

	<div class="menu-list">

		<ul id="menu-content" class="menu-content out collapse" style="height: 0px;">
			<li data-bs-toggle="collapse" class="active collapsed"><i class="fa fa-search fa-lg"></i> <input type="text" name="buscaMenu" id="buscaMenu" size="20" placeholder="Busque no menu" onkeyup="atListaMenu()"></li>

			<span id="atualizaMenu"></span>

			<br><br>
	</div>
</div>
<script>
	//aqui json para atualizar...
	//ajax com reponse text
	atListaMenu();

	function atListaMenu() {
		var f = document.getElementById("buscaMenu").value;
		$.ajax({
			url: "retMenu.php?f=" + f,
			complete: function(response) {
				var ret = response.responseText;
				document.getElementById("atualizaMenu").innerHTML = ret;
				//	alert(response.responseText);
			},
			error: function() {
				alert("Erro");
			}
		});
	}



	/*setTimeout(atStatusMenu,500);

	function atStatusMenu() {

		$.getJSON("accordion.php?a=2&p=menu", function(atData) {
			var campo = [];
			var vis = [];
			$(atData).each(function(key, value) {
				campo.push(value.campo);
				vis.push(value.vis);
			});
			//rodar um each aqui, ver os valores e ajustar
			campo.forEach(mostra);

			function mostra(i, v) {
				if (vis[v] == "hidde") {
					$("#" + i).removeClass("show");
					//console.log("[data-bs-target='" + i + "']");                    
					$("[data-bs-target='#" + i + "']").addClass("collapsed");
				}
				if (vis[v] == "show") {
					$("#" + i).addClass("show");      
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
        $(".acao-menu").click(function(r) {
            var id = r.target.getAttribute('data-bs-target');
            var id2 = r.target.getAttribute('data-value');
            //verificar se mostra ou esconde...
			console.log(r);
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
                p: "menu",
                i: id,
                v: valor,
            }, function(response) {

            });

        });

    });*/
</script>