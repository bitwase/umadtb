<?php ?>
  <ul>
<li> <img src="arquivos/imagens/lg3.png" height="30" width="30" class="lg1" id="minilogo" ></li>
  <?php if($nivel == 1){?>
  <li class='has-sub'><a href='#'><span>Registro</span></a>
   <ul>
   <li><a href="#" onclick="cadastro()"><span>Inscrição</span></a></li>
   <li><a href="#" onclick="regVisita()"><span>Visita</span></a></li>
   <li><a href="#" onclick="regVisitantes()"><span>Visitantes</span></a></li>
   <li><a href="index.php?pg=reg.ausencia"><span>Ausência</span></a></li>
   <li><a href="#" onclick="mostraCadAtendente()"><span>Usuário</span></a></li>
   <li><a href="index.php?pg=n.evento"><span>Adicionar Evento</span></a></li>
   </ul>
   </li><?php }?>
   <li><a href="#">Consulta</a>
   <ul>
   <?php if($nivel == 1){?>
   <li><a href='?pg=v.usuario'><span>Usuários</span></a></li>  
   <?php }?>
   <?php if($nivel == 1 || $nivel == 2){?>
   <li><a href='?pg=v.inscritos'><span>Cadastros</span></a></li>  
   <li><a href='?pg=v.visitantes'><span>Visitantes</span></a></li>  
   <?php }?>
   <li><a href='?pg=v.inscritosEventos'><span>Inscritos - Eventos</span></a></li>  
  </ul>
   </li>
   <?php if($nivel == 1 || $nivel == 2){?>
   <li><a href="#">Relatórios</a>
   <ul>
      <li><a href="?pg=aniversariantes">Aniversariantes</a></li>
      <?php if($nivel == 1){?>
      <li><a href="?pg=ausencias">Ausências</a></li>
      <?php } ?>
   </ul>
   </li>
   <?php }?>
   <?php if($nivel == 1){?>
   <li><a href="?pg=filtro">Selecionar Congregação</a></li>
   <?php }?>
   <li><a href="?pg=altera.senha">Altera Senha</a></li>
   <li><a href="logout.php">Sair</a></li>
<?php //colocar opção para verificar em configurações se existe aniversariantes, e existindo habilitar a opção no menu. mesma situação para alertas 
if($anMes > 0){ ?>
   <li class="mP"><a href="#" onclick="mostraAniversario(1)">Aniversário Mês: <?php echo $anMes;?></a></li>
<?php }
if($anDia>0){ ?>
   <li class="mP"><a href="#" onclick="mostraAniversario(2)">Aniversário Dia: <?php echo $anDia;?></a></li>
<?php }
if($auCon > 0){ ?>
   <li class="mP"><a href="?pg=ausencias.cons">Aus. Consecutivas: <?php echo $auCon;?></a></li>
<?php }
?>
   <li class='has-sub '><a href="#" onclick="onManageWebPushSubscriptionButtonClicked()"><span>Notificação</span></a></li>
</ul>

<script>
    function onManageWebPushSubscriptionButtonClicked(event) {
        getSubscriptionState().then(function(state) {
            if (state.isPushEnabled) {
                /* Subscribed, opt them out */
                OneSignal.setSubscription(false);
            } else {
                if (state.isOptedOut) {
                    /* Opted out, opt them back in */
                    OneSignal.setSubscription(true);
                } else {
                    /* Unsubscribed, subscribe them */
                    OneSignal.registerForPushNotifications();
                }
            }
        });
        event.preventDefault();
    }

    function updateMangeWebPushSubscriptionButton(buttonSelector) {
        var hideWhenSubscribed = false;
        var subscribeText = "Notificação";
        var unsubscribeText = "Desativar Notificação";

        getSubscriptionState().then(function(state) {
            var buttonText = !state.isPushEnabled || state.isOptedOut ? subscribeText : unsubscribeText;

            var element = document.querySelector(buttonSelector);
            if (element === null) {
                return;
            }

            element.removeEventListener('click', onManageWebPushSubscriptionButtonClicked);
            element.addEventListener('click', onManageWebPushSubscriptionButtonClicked);
            element.textContent = buttonText;

            if (state.hideWhenSubscribed && state.isPushEnabled) {
                element.style.display = "none";
            } else {
                element.style.display = "";
            }
        });
    }

    function getSubscriptionState() {
        return Promise.all([
          OneSignal.isPushNotificationsEnabled(),
          OneSignal.isOptedOut()
        ]).then(function(result) {
            var isPushEnabled = result[0];
            var isOptedOut = result[1];

            return {
                isPushEnabled: isPushEnabled,
                isOptedOut: isOptedOut
            };
        });
    }

    var OneSignal = OneSignal || [];
    var buttonSelector = "#my-notification-button";

    /* This example assumes you've already initialized OneSignal */
    OneSignal.push(function() {
        // If we're on an unsupported browser, do nothing
        if (!OneSignal.isPushNotificationsSupported()) {
            return;
        }
        //updateMangeWebPushSubscriptionButton(buttonSelector);
        OneSignal.on("subscriptionChange", function(isSubscribed) {
            /* If the user's subscription state changes during the page's session, update the button text */
            //updateMangeWebPushSubscriptionButton(buttonSelector);
        });
    });
    </script>