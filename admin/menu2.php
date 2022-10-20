<?php ?>
<ul>
	<li><a href='#'><span>Cadastros</span></a>
		<ul>
			<li><a href='?pg=fornecedor'><span>Fornecedor / Cliente</span></a></li>
			<li><a href='?pg=local'><span>Local</span></a></li>
			<li><a href='?pg=responsavel'><span>Responsável</span></a></li>
			<li><a href='?pg=familia'><span>Família de Produtos</span></a></li>
			<li><a href='?pg=versao'><span>Versão de Licenças</span></a></li>
			<li><a href='?pg=equipamento'><span>Equipamentos</span></a></li>
		</ul>
	</li>
	
	<li><a href='#'><span>Licenças</span></a>
		<ul>
			<li><a href='?pg=entrada'><span>Registra Entrada</span></a></li>
			<li><a href='?pg=consultaEntrada'><span>Consulta Entrada</span></a></li>
			<li><a href='?pg=vinculo'><span>Vincular Licença com Equipamento</span></a></li>
			<li><a href='#'><span>Licenças Utilizadas</span></a>
			<ul>
			<li><a href='?pg=consultaLicencas'><span>Geral</span></a></li>
			<li><a href='?pg=consultaLicencasSerdia'><span>Serdia</span></a></li>
			<li><a href='?pg=consultaLicencasCliente'><span>Cliente</span></a></li>
			</ul>
			</li>
			<li><a href='?pg=estoqueLicencas'><span>Estoque de Licenças</span></a></li>
			<li><a href='?pg=cobertura'><span>Informar Cobertura</span></a></li>
			<?php if($usEspecial){ ?>
			<!--li><a href='?pg=vinculoAlternativo'><span>Vincular Licença com Equipamento - Pendente</span></a></li>
			<li><a href='?pg=consultaLicencasAlternativo'><span>Licenças Utilizadas - Pendente</span></a></li-->
			<?php }?>
		</ul>
	</li>
	
	<li><a href='#'><span>Softwares</span></a>
		<ul>
			
			<li><a href='?pg=vinculoSoftware'><span>Vincular Softwares com Equipamento</span></a></li>
			<li><a href='#'><span>Softwares Utilizados</span></a>
			<ul>
			<li><a href='?pg=softwaresUtilizados'><span>Geral</span></a></li>
			<li><a href='?pg=softwaresUtilizadosSerdia'><span>Serdia</span></a></li>
			<li><a href='?pg=softwaresUtilizadosCliente'><span>Cliente</span></a></li>
			</ul>
			</li>
			<?php if($usEspecial){ ?>
			<li><a href='#'><span>Softwares Pendentes</span></a>
			<ul>
			<li><a href='?pg=softwaresPendentes'><span>Geral</span></a></li>
			<li><a href='?pg=softwaresPendentesSerdia'><span>Serdia</span></a></li>
			<li><a href='?pg=softwaresPendentesCliente'><span>Cliente</span></a></li>
			</ul>
			</li>
			<?php }?>
		</ul>
	</li>

	<li><a href="?pg=altera.senha.php">Altera Senha</a></li>
	<li><a href="logout.php">Sair</a></li>
</ul>
