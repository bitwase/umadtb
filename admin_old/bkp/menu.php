<?php ?>
  <ul>
<li> <img src="arquivos/imagens/lg3.png" height="30" width="30" class="lg1" id="minilogo" ></li>
  <?php if($nv_acesso <= 2){
?><li class='has-sub'><a href='#'><span>Cadastro</span></a>
   <ul>
   <li><a href="#" onclick="mostraCadInscricao()"><span>Inscrição</span></a></li>
   </ul>
   </li><?php }?>
   <li><a href="#">Lista</a>
   <ul>
   <li><a href='?pg=v.inscritos'><span>Inscrições</span></a></li>  
  </ul>
   </li>	
   <li><a href="?pg=altera.senha">Altera Senha</a></li>
   <li><a href="logout.php">Sair</a></li>
</ul>
