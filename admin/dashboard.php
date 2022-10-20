<?php


//ver se usuário em questão tem acesso para os cards
/*
$res = $pdo->prepare("select * from tb_acessos where tipo = 'D' and us = '$cod_us'");
$res->execute();
$cards = $res->fetchAll();
*/
?>
<h3>Dashboard</h3>
<div class="row">

  <div class="card text-white bg-success mb-2 card-bw d-hidden" data-dash="total.inscritos" id="cardTotalInscritos">
    <div class="card-header">Total de Inscritos</div>
    <div class="centro-card-bw" id="qtTotalInscritos">...</div>
  </div>

</div>

<div style="height:60px;width:20px;"></div>

<script>
  dadosDashboard();
  validaAcesso();

  function dadosDashboard() {

    $.getJSON("dadosDashboard.php", function(atData) {
      var totalInscritos = [];
      
      $(atData).each(function(key, value) {
        totalInscritos.push(value.totalInscritos);
      });

      $("#qtTotalInscritos").html(totalInscritos);

    });

  }

  function validaAcesso() {
    let us = "<?php echo $cod_us; ?>";
    $.getJSON("retAcessos.php?acao=dash&u=" + us, function(atData) {
      const pg = [];
      $(atData).each(function(key, value) {
          pg.push(value.pg);
        });
        
        pg.forEach(mostra);
        
        function mostra(i, v) {
          $("[data-dash='" + i + "']").removeClass("d-hidden");
          }
        }); 
        carregou();
    }
</script>