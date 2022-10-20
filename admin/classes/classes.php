<?php

class config
{
	public $pdo;

	function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	function configuracoes()
	{
		//echo "<br><br><br>PDO: ";
		//print_r($p);
		$config = $this->pdo->query("select * from config where id = 1");
		$config = $config->fetch();
		return $config;
		//print_r($config); 
	}
}

/* Cadastros de dados de NF */

class regNf
{
	public $pdo;
	public $chave;
	public $serie;
	public $nf;
	public $emissao;
	public $fornecedor;
	public $autorizacao;
	public $dt_autorizacao;
	public $xml;
	public $vBC;
	public $vICMS;
	public $vICMSDeson;
	public $vFCPUFDest;
	public $vICMSUFDest;
	public $vICMSUFRemet;
	public $vFCP;
	public $vBCST;
	public $vST;
	public $vFCPST;
	public $vFCPSTRet;
	public $vProd;
	public $vFrete;
	public $vSeg;
	public $vDesc;
	public $vII;
	public $vIPI;
	public $vIPIDevol;
	public $vPIS;
	public $vCOFINS;
	public $vOutro;
	public $vNF;
	public $vTotTrib;


	function __construct($pdo, $chave, $serie, $nf, $emissao, $fornecedor, $autorizacao, $dt_autorizacao,  $vBC, $vICMS, $vICMSDeson, $vFCPUFDest, $vICMSUFDest, $vICMSUFRemet, $vFCP, $vBCST, $vST, $vFCPST, $vFCPSTRet, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vIPIDevol, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib, $xml)
	{
		$this->pdo = $pdo;
		$this->chave = $chave;
		$this->serie = $serie;
		$this->nf = $nf;
		$this->emissao = $emissao;
		$this->fornecedor = $fornecedor;
		$this->autorizacao = $autorizacao;
		$this->dt_autorizacao = $dt_autorizacao;
		$this->xml = addslashes($xml);
		$this->vBC = $vBC;
		$this->vICMS = $vICMS;
		$this->vICMSDeson = $vICMSDeson;
		$this->vFCPUFDest = $vFCPUFDest;
		$this->vICMSUFDest = $vICMSUFDest;
		$this->vICMSUFRemet = $vICMSUFRemet;
		$this->vFCP = $vFCP;
		$this->vBCST = $vBCST;
		$this->vST = $vST;
		$this->vFCPST = $vFCPST;
		$this->vFCPSTRet = $vFCPSTRet;
		$this->vProd = $vProd;
		$this->vFrete = $vFrete;
		$this->vSeg = $vSeg;
		$this->vDesc = $vDesc;
		$this->vII = $vII;
		$this->vIPI = $vIPI;
		$this->vIPIDevol = $vIPIDevol;
		$this->vPIS = $vPIS;
		$this->vCOFINS = $vCOFINS;
		$this->vOutro = $vOutro;
		$this->vNF = $vNF;
		$this->vTotTrib = $vTotTrib;
	}

	function registraNota()
	{
		//procurar se não existe com a mesma chave de acesso
		$sql = "SELECT * FROM nfe_nfe WHERE chave='$this->chave'";
		//echo $sql;
		$v = $this->pdo->query($sql);
		$cnt = $v->rowCount(); //mysql_num_rows($cnf);
		//se não existir, registrar contas a pagar
		//pegar categoria, subcategoria e centro de custo da configuração;
		//se não existe, grava
		if (!$cnt) {
			try {
				$sql = "INSERT into nfe_nfe (chave, serie, nf, emissao, fornecedor, autorizacao, dt_autorizacao, vBC, vICMS, vICMSDeson, vFCPUFDest, vICMSUFDest, vICMSUFRemet, vFCP, vBCST, vST, vFCPST, vFCPSTRet, vProd, vFrete, vSeg, vDesc, vII, vIPI, vIPIDevol, vPIS, vCOFINS, vOutro, vNF, vTotTrib, xml) values(
					'$this->chave',
					'$this->serie',
					'$this->nf',
					'$this->emissao',
					'$this->fornecedor',
					'$this->autorizacao',
					'$this->dt_autorizacao',
					'$this->vBC',
					'$this->vICMS',
					'$this->vICMSDeson',
					'$this->vFCPUFDest',
					'$this->vICMSUFDest',
					'$this->vICMSUFRemet',
					'$this->vFCP',
					'$this->vBCST',
					'$this->vST',
					'$this->vFCPST',
					'$this->vFCPSTRet',
					'$this->vProd',
					'$this->vFrete',
					'$this->vSeg',
					'$this->vDesc',
					'$this->vII',
					'$this->vIPI',
					'$this->vIPIDevol',
					'$this->vPIS',
					'$this->vCOFINS',
					'$this->vOutro',
					'$this->vNF',
					'$this->vTotTrib',
					'$this->xml'
				)";
				//echo $sql;
				$reg = $this->pdo->query($sql);
				//return $this->pdo;
			} catch (PDOException $e) {
				return 'ERROR: ' . $e->getMessage();
			}
		}
	}
}

class regFornecedor
{
	public $pdo;
	public $doc;
	public $fantasia;
	public $nome;
	public $end;
	public $num;
	public $compl;
	public $bairro;
	public $codMun;
	public $cidade;
	public $uf;
	public $cep;
	public $pais;
	public $codpais;
	public $tel;
	public $ie;
	public $crt;

	function __construct($pdo, $doc, $fantasia, $nome, $end, $num, $compl, $bairro, $codMun, $cidade, $uf, $cep, $pais, $codpais, $tel, $ie, $crt)
	{
		$this->pdo = $pdo;
		$this->doc = $doc;
		$this->fantasia = $fantasia;
		$this->nome = $nome;
		$this->end = $end;
		$this->num = $num;
		$this->compl = $compl;
		$this->bairro = $bairro;
		$this->codMun = $codMun;
		$this->cidade = $cidade;
		$this->uf = $uf;
		$this->cep = $cep;
		$this->pais = $pais;
		$this->codpais = $codpais;
		$this->tel = $tel;
		$this->ie = $ie;
		$this->crt = $crt;
	}

	function registraFornecedor()
	{
		//procurar se não existe com o mesmo doc
		$sql = "SELECT * FROM est_fornecedores WHERE doc = '$this->doc'";
		//echo $sql;
		$v = $this->pdo->query($sql);
		$cnt = $v->rowCount(); //mysql_num_rows($cnf);
		//se não existe, grava
		if (!$cnt) {
			try {
				$sql = "INSERT into est_fornecedores (doc, fantasia, nome, end, num, compl, bairro, codMun, cidade, uf, cep, pais, cod_pais, tel, ie, crt) values(
					'$this->doc', 
					'$this->fantasia', 
					'$this->nome', 
					'$this->end', 
					'$this->num', 
					'$this->compl', 
					'$this->bairro', 
					'$this->codMun', 
					'$this->cidade', 
					'$this->uf', 
					'$this->cep', 
					'$this->pais', 
					'$this->codpais', 
					'$this->tel', 
					'$this->ie', 
					'$this->crt' 
				)";
				$reg = $this->pdo->query($sql);
				//return $this->pdo;
			} catch (PDOException $e) {
				return 'ERROR: ' . $e->getMessage();
			}
		}
	}
}

class regCliente
{
	public $pdo;
	public $tipo;
	public $doc;
	public $fantasia;
	public $nome;
	public $end;
	public $num;
	public $compl;
	public $bairro;
	public $codMun;
	public $cidade;
	public $uf;
	public $cep;
	public $pais;
	public $codpais;
	public $ie;
	public $crt;
	public $telefone;
	public $email;

	function __construct($pdo, $tipo, $doc, $fantasia, $nome, $end, $num, $compl, $bairro, $codMun, $cidade, $uf, $cep, $pais, $codpais, $ie, $crt, $telefone, $email)
	{
		$this->pdo = $pdo;
		$this->tipo = $tipo;
		$this->doc = $doc;
		$this->fantasia = $fantasia;
		$this->nome = $nome;
		$this->end = $end;
		$this->num = $num;
		$this->compl = $compl;
		$this->bairro = $bairro;
		$this->codMun = $codMun;
		$this->cidade = $cidade;
		$this->uf = $uf;
		$this->cep = $cep;
		$this->pais = $pais;
		$this->codpais = $codpais;
		$this->ie = $ie;
		$this->crt = $crt;
		$this->telefone = $telefone;
		$this->email = $email;
	}

	function registraCliente()
	{
		//procurar se não existe com o mesmo doc
		$sql = "SELECT * FROM tb_clientes WHERE doc = '$this->doc'";
		//echo $sql;
		$v = $this->pdo->query($sql);
		$cnt = $v->rowCount(); //mysql_num_rows($cnf);
		//se não existe, grava
		if (!$cnt) {
			try {
				$sql = "INSERT into tb_clientes (tipo, doc, fantasia, nome, end, num, compl, bairro, codMun, cidade, uf, cep, pais, cod_pais, tel, email) values(
					'$this->tipo', 
					'$this->doc', 
					'$this->fantasia', 
					'$this->nome', 
					'$this->end', 
					'$this->num', 
					'$this->compl', 
					'$this->bairro', 
					'$this->codMun', 
					'$this->cidade', 
					'$this->uf', 
					'$this->cep', 
					'$this->pais', 
					'$this->codpais', 
					'$this->telefone', 
					'$this->email' 
				)";
				$reg = $this->pdo->query($sql);
				//return $this->pdo;
			} catch (PDOException $e) {
				return 'ERROR: ' . $e->getMessage();
			}
		}
	}
}

class regColaborador
{
	public $pdo;
	public $nome;
	public $telefone;
	public $funcao;
	public $admissao;
	public $end;
	public $num;
	public $compl;
	public $bairro;
	public $cidade;
	public $uf;
	public $cep;
	public $us;

	function __construct($pdo, $nome, $telefone, $funcao, $admissao, $end, $num, $compl, $bairro, $cidade, $uf, $cep, $us)
	{
		$this->pdo = $pdo;
		$this->nome = $nome;
		$this->telefone = $telefone;
		$this->funcao = $funcao;
		$this->admissao = $admissao;
		$this->end = $end;
		$this->num = $num;
		$this->compl = $compl;
		$this->bairro = $bairro;
		$this->cidade = $cidade;
		$this->uf = $uf;
		$this->cep = $cep;
		$this->us = $us;
	}

	function registraColaborador()
	{
		//procurar se não existe com o mesmo doc
		//		$sql = "SELECT * FROM tb_colaboradores WHERE doc = '$this->doc'";
		//		$v = $this->pdo->query($sql);
		//		$cnt = $v->rowCount(); //mysql_num_rows($cnf);
		//se não existe, grava
		$cnt = false;
		if (!$cnt) {
			try {
				$sql = "INSERT into tb_colaboradores (nome, telefone, dataAdmissao, funcao, end, num, complemento, bairro, cidade, uf, cep) values(
					'$this->nome', 
					'$this->telefone', 
					'$this->admissao', 
					'$this->funcao', 
					'$this->end', 
					'$this->num', 
					'$this->compl', 
					'$this->bairro', 
					'$this->cidade', 
					'$this->uf', 
					'$this->cep' 
				)";
				$reg = $this->pdo->query($sql);
				$rcId = $this->pdo->lastInsertId();
				//inserir os dados na tabela tb_custoscolaboradores
				$sqlCusto = "insert into tb_custoscolaboradores (colaborador) values($rcId)";
				$this->pdo->query($sqlCusto);

				//registrar log de inclusão
				$rl = new logColaborador($this->pdo, $rcId, $this->us, "Registro de dados");
				$rl->registraLog();

				//return $this->pdo;
			} catch (PDOException $e) {
				return 'ERROR: ' . $e->getMessage();
			}
		}
	}
}

class regProduto
{
	public $pdo;
	public $chave;
	public $linhaNf;
	public $cProd;
	public $cEAN;
	public $cBarra;
	public $xProd;
	public $NCM;
	public $CST;
	public $cBenef;
	public $CFOP;
	public $uCom;
	public $qCom;
	public $vUnCom;
	public $vProd;
	public $cEANTrib;
	public $cBarraTrib;
	public $uTrib;
	public $qTrib;
	public $vUnTrib;
	public $vFrete;
	public $vSeg;
	public $vDesc;
	public $vOutro;
	public $indTot;
	public $xPed;
	public $nItemPed;
	public $vBCICMS;
	public $pICMS;
	public $vICMS;
	public $vBCIPI;
	public $pIPI;
	public $vIPI;
	public $custoUnitario;


	function __construct($pdo, $chave, $linhaNf, $cProd, $cEAN, $cBarra, $xProd, $NCM, $CST, $cBenef, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $cBarraTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $vBCICMS, $pICMS, $vICMS, $vBCIPI, $pIPI, $vIPI, $custoUnitario)
	{
		$this->pdo = $pdo;
		$this->chave = $chave;
		$this->linhaNf = $linhaNf;
		$this->cProd = $cProd;
		$this->cEAN = $cEAN;
		$this->cBarra = $cBarra;
		$this->xProd = $xProd;
		$this->NCM = $NCM;
		$this->CST = $CST;
		$this->cBenef = $cBenef;
		$this->CFOP = $CFOP;
		$this->uCom = $uCom;
		$this->qCom = $qCom;
		$this->vUnCom = $vUnCom;
		$this->vProd = $vProd;
		$this->cEANTrib = $cEANTrib;
		$this->cBarraTrib = $cBarraTrib;
		$this->uTrib = $uTrib;
		$this->qTrib = $qTrib;
		$this->vUnTrib = $vUnTrib;
		$this->vFrete = $vFrete;
		$this->vSeg = $vSeg;
		$this->vDesc = $vDesc;
		$this->vOutro = $vOutro;
		$this->indTot = $indTot;
		$this->xPed = $xPed;
		$this->nItemPed = $nItemPed;
		$this->vBCICMS = $vBCICMS;
		$this->pICMS = $pICMS;
		$this->vICMS = $vICMS;
		$this->vBCIPI = $vBCIPI;
		$this->pIPI = $pIPI;
		$this->vIPI = $vIPI;
		$this->custoUnitario = $custoUnitario;
	}

	function registraProduto()
	{
		//procurar se não existe com o mesmo doc
		$sql = "SELECT * FROM nfe_produtos WHERE linhaNf = '$this->linhaNf' and chave = '$this->chave' and cProd = '$this->cProd'";
		//echo $sql;
		$v = $this->pdo->query($sql);
		$cnt = $v->rowCount(); //mysql_num_rows($cnf);
		//se não existe, grava
		if (!$cnt) {
			try {
				$sql = "INSERT INTO nfe_produtos (chave, linhaNf, cProd, cEAN, cBarra, xProd, NCM, CST, cBenef, CFOP, uCom, qCom, vUnCom, vProd, cEANTrib, cBarraTrib, uTrib, qTrib, vUnTrib, vFrete, vSeg, vDesc, vOutro, indTot, xPed, nItemPed, vBCICMS, pICMS, vICMS, vBCIIPI, pIPI, vIPI, custoUnitario) VALUES (
					'$this->chave', 
					'$this->linhaNf', 
					'$this->cProd', 
					'$this->cEAN', 
					'$this->cBarra', 
					'$this->xProd', 
					'$this->NCM', 
					'$this->CST', 
					'$this->cBenef', 
					'$this->CFOP', 
					'$this->uCom', 
					'$this->qCom', 
					'$this->vUnCom', 
					'$this->vProd', 
					'$this->cEANTrib', 
					'$this->cBarraTrib', 
					'$this->uTrib', 
					'$this->qTrib', 
					'$this->vUnTrib', 
					'$this->vFrete', 
					'$this->vSeg', 
					'$this->vDesc', 
					'$this->vOutro', 
					'$this->indTot', 
					'$this->xPed', 
					'$this->nItemPed', 
					'$this->vBCICMS', 
					'$this->pICMS', 
					'$this->vICMS', 
					'$this->vBCIPI', 
					'$this->pIPI', 
					'$this->vIPI',
					'$this->custoUnitario'
				)";
				//echo $sql."<br>";
				$reg = $this->pdo->query($sql);
				//return $this->pdo;
			} catch (PDOException $e) {
				return 'ERROR: ' . $e->getMessage();
			}
		}
	}
}

class pdtFornecedor
{
	public $pdo;
	public $fornecedor;
	public $cod_fornecedor;
	public $cod_interno;
	public $descricao;
	public $ncm;
	public $unidade;

	function __construct($pdo, $fornecedor, $cod_fornecedor, $cod_interno, $descricao, $ncm, $unidade)
	{
		$this->pdo = $pdo;
		$this->fornecedor = $fornecedor;
		$this->cod_fornecedor = $cod_fornecedor;
		$this->cod_interno = $cod_interno;
		$this->descricao = $descricao;
		$this->ncm = $ncm;
		$this->unidade = $unidade;
	}

	function registraPdtFornecedor()
	{
		//procurar se não existe com o mesmo doc
		$sql = "SELECT * FROM pdt_fornecedor WHERE fornecedor = '$this->fornecedor' and cod_fornecedor = '$this->cod_fornecedor'";
		//echo $sql;
		$v = $this->pdo->query($sql);
		$cnt = $v->rowCount(); //mysql_num_rows($cnf);
		//se não existe, grava
		if (!$cnt) {
			try {
				$sql = "INSERT INTO pdt_fornecedor (fornecedor, cod_fornecedor, cod_interno, descricao, ncm, unidade) VALUES (
					'$this->fornecedor',
					'$this->cod_fornecedor',
					'$this->cod_interno',
					'$this->descricao',
					'$this->ncm',
					'$this->unidade'
				)";
				//				echo $sql."<br>";
				$reg = $this->pdo->query($sql);
				//return $this->pdo;
			} catch (PDOException $e) {
				return 'ERROR: ' . $e->getMessage();
			}
		}
	}
}

class regCentro
{
	public $pdo;
	public $centro;
	public $categoria;
	public $us;
	public $cliente;
	public $ref;

	function __construct($pdo, $centro, $categoria, $us, $cliente, $ref)
	{
		$this->pdo = $pdo;
		$this->centro = $centro;
		$this->categoria = $categoria;
		$this->us = $us;
		$this->cliente = $cliente;
		$this->ref = $ref;
	}

	function registraCentro()
	{
		try {
			$co = $this->pdo->query("select catProjeto from config")->fetch();
			if ($this->categoria != $co['catProjeto']) {
				//verificar se nome não existe
				$ve = $this->pdo->query("select * from tb_centrocusto where categoria = '$this->categoria' and centro = upper('$this->centro')")->rowCount();

				if (!$ve) {
					$sql = "INSERT INTO tb_centrocusto (centro, categoria, st, us, inclusao) VALUES (
					upper('$this->centro'),
					'$this->categoria',
					'1',
					'$this->us',
					now()
					)";
				}
			}
			if ($this->categoria == $co['catProjeto']) {
				//centro = cliente.seq - texto
				//$projeto = intval($this->centro);
				$sql = "INSERT INTO tb_centrocusto (centro, categoria, st, us, inclusao, ref, cliente) VALUES (
					upper('$this->centro'),
					'$this->categoria',
					'1',
					'$this->us',
					now(),
					'$this->ref',
					'$this->cliente'
				)";
			}
			//						echo $sql."<br>";
			$reg = $this->pdo->query($sql);
			$rc = $this->pdo->lastInsertId();

			//retornar cadastro de centro de custo
			//return $this->pdo->lastInsertId();
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class custoCentro
{
	public $pdo;
	public $us;
	public $centro;
	public $categoria;
	public $descricao;
	public $cod;
	public $qt;
	public $un;
	public $valor;
	public $referencia;
	public $nf;
	public $chave;
	public $linha;
	public $projeto;
	public $manual;
	public $data;
	public $tipoHora;
	public $inicio;
	public $fim;
	public $garantia;
	public $catFin;
	public $subCatFin;

	function __construct($pdo, $us, $centro, $categoria, $descricao, $cod, $qt, $un, $valor, $referencia, $nf, $chave, $linha, $projeto, $manual, $data, $tipoHora, $inicio, $fim, $garantia, $catFin, $subCatFin)
	{
		$this->pdo = $pdo;
		$this->us = $us;
		$this->centro = $centro;
		$this->categoria = $categoria;
		$this->descricao = $descricao;
		$this->cod = $cod;
		$this->qt = $qt;
		$this->un = $un;
		$this->valor = $valor;
		$this->referencia = $referencia;
		$this->nf = $nf;
		$this->chave = $chave;
		$this->linha = $linha;
		if ($this->linha == "") {
			$this->linha = 0;
		}
		$this->projeto = $projeto;
		$this->manual = $manual;
		$this->data = $data;
		$this->tipoHora = $tipoHora;
		$this->inicio = $inicio;
		$this->fim = $fim;
		$this->garantia = $garantia;
		$this->catFin = empty($catFin) ? 'null' : (int) $catFin;
		$this->subCatFin = empty($subCatFin) ? 'null' : (int) $subCatFin;
	}

	function registraCustoCento()
	{
		try {
			if ($this->referencia == "NF" && !$this->manual) { //se for nota fiscal e não for manual apenas...
				$sql = "INSERT INTO tb_custo_centrocusto (us, data, centro, categoria, descricao, cod, qt, un, valor, referencia, nf, chave, linha, projeto, garantia, catFin, subCatFin) values(
				'$this->us',
				now(),
				'$this->centro',
				'$this->categoria',
				(select xProd from nfe_produtos where chave = '$this->chave' and linhaNf = '$this->linha'),
				(select cProd from nfe_produtos where chave = '$this->chave' and linhaNf = '$this->linha'),
				'$this->qt',
				(select uCom from nfe_produtos where chave = '$this->chave' and linhaNf = '$this->linha'),
				(select custoUnitario from nfe_produtos where chave = '$this->chave' and linhaNf = '$this->linha'),
				'$this->referencia',
				(select nf from nfe_nfe where chave = '$this->chave'),
				'$this->chave',
				'$this->linha',
				(select ref from tb_centrocusto where id = '$this->centro'),
				'$this->garantia',
				$this->catFin,
				$this->subCatFin
				)";
			}

			if (($this->referencia == "NF" || $this->referencia == "ME" || $this->referencia == "CD") && $this->manual) { //se for nota fiscal e for manual...
				$sql = "INSERT INTO tb_custo_centrocusto (us, data, centro, categoria, descricao, cod, qt, un, valor, referencia, nf, chave, linha, projeto, garantia, catFin, subCatFin) values(
				'$this->us',
				now(),
				'$this->centro',
				'$this->categoria',
				'$this->descricao',
				'$this->cod',
				'$this->qt',
				'$this->un',
				'$this->valor',
				'$this->referencia',
				'$this->nf',
				'$this->chave',
				'$this->linha',
				(select ref from tb_centrocusto where id = '$this->centro'),
				'$this->garantia',
				$this->catFin,
				$this->subCatFin
				)";
			}
			//incluir regra para quando for material de estoque e mão de projeto

			if ($this->referencia == "MO" && ($this->manual == "false")) { //se for Mão de Obra e não for manual...
				$sql = "INSERT INTO tb_custo_centrocusto (us, data, centro, categoria, descricao, cod, qt, un, valor, referencia, tipoHora, inicio, fim, projeto, garantia) values(
				'$this->us',
				'$this->data',
				'$this->centro',
				'$this->categoria',
				(select nome from tb_colaboradores where id = '$this->cod'),
				'$this->cod',
				'$this->qt',
				'HORA',
				'$this->valor',
				'$this->referencia',
				'$this->tipoHora',
				'$this->inicio',
				'$this->fim',
				(select ref from tb_centrocusto where id = '$this->centro'),
				'$this->garantia'
				)";
			}

			if ($this->referencia == "MO" && ($this->manual == "true")) { //se for Mão de Obra e for manual...
				$sql = "INSERT INTO tb_custo_centrocusto (us, data, centro, categoria, descricao, cod, qt, un, valor, referencia, tipoHora, inicio, fim, projeto, garantia) values(
				'$this->us',
				'$this->data',
				'$this->centro',
				'$this->categoria',
				'$this->descricao',
				'$this->cod',
				'$this->qt',
				'HORA',
				'$this->valor',
				'$this->referencia',
				'$this->tipoHora',
				'$this->inicio',
				'$this->fim',
				(select ref from tb_centrocusto where id = '$this->centro'),
				'$this->garantia'
				)";
			}
			echo $sql . "<br>";
			$reg = $this->pdo->query($sql);
			//retornar cadastro de centro de custo
			return $this->pdo->lastInsertId();
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class regProjeto
{
	public $pdo;
	public $cliente;
	public $nome;
	public $orcamento;
	public $pedido;
	public $prevInicio;
	public $prevConclusao;
	public $us;
	public $vlOrcado;
	public $ordemCliente;

	function __construct($pdo, $cliente, $nome, $orcamento, $pedido, $prevInicio, $prevConclusao, $us, $vlOrcado)
	{
		$this->pdo = $pdo;
		$this->cliente = $cliente;
		$this->nome = $nome;
		$this->orcamento = $orcamento;
		$this->pedido = $pedido;
		$this->prevInicio = $prevInicio;
		$this->prevConclusao = $prevConclusao;
		$this->us = $us;
		$this->vlOrcado = $vlOrcado;

		$sqlOrdem = "select max(ordemCliente)+1 as 'ordem' from tb_projetos where cliente = '$this->cliente'";
		$vo = $this->pdo->query($sqlOrdem)->fetch();

		$this->ordemCliente = $vo['ordem'];
		if ($this->ordemCliente == 0 || $this->ordemCliente == null) {
			$this->ordemCliente = 1;
		}
	}

	function registraProjeto()
	{
		try {
			$sql = "INSERT INTO tb_projetos (cliente, nomeProjeto, orcamento, pedido, inicioPrevisto, conclusaoPrevisto, st, us, data, valorOrcado, ordemCliente) VALUES (
					'$this->cliente',
					'$this->nome',
					'$this->orcamento',
					'$this->pedido',
					'$this->prevInicio',
					'$this->prevConclusao',
					'1',
					'$this->us',
					now(),
					'$this->vlOrcado',
					'$this->ordemCliente'
				)";
			//echo $sql."<br>";
			$reg = $this->pdo->query($sql);
			$rcId = $this->pdo->lastInsertId();

			//realizar cadastro de centro de custo
			$cat = $this->pdo->query("select catProjeto from config")->fetch();
			$cat = $cat['catProjeto'];
			$rc = new regCentro($this->pdo, "$this->cliente.$this->ordemCliente - $this->nome", $cat, $this->us, $this->cliente, $rcId);
			$rc->registraCentro();

			//registrar log de inclusão
			$rl = new logProjeto($this->pdo, $rcId, $this->us, "Registro de dados");
			$rl->registraLog();
			//return $this->pdo->lastInsertId();
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class logConfig
{
	public $pdo;
	public $us;
	public $log;

	function __construct($pdo, $us, $log)
	{
		$this->pdo = $pdo;
		$this->us = $us;
		$this->log = $log;
	}

	function registraLog()
	{
		try {
			$sql = "INSERT INTO log_config (data, us, log) VALUES (
					now(),
					'$this->us',
					'$this->log'
				)";
			//echo "$sql";

			$log = $this->pdo->query($sql);
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}
class logProjeto
{
	public $pdo;
	public $projeto;
	public $us;
	public $log;

	function __construct($pdo, $projeto, $us, $log)
	{
		$this->pdo = $pdo;
		$this->projeto = $projeto;
		$this->us = $us;
		$this->log = $log;
	}

	function registraLog()
	{
		try {
			$sql = "INSERT INTO log_projetos (projeto, data, us, log) VALUES (
					'$this->projeto',
					now(),
					'$this->us',
					'$this->log'
				)";
			//echo "$sql";

			$log = $this->pdo->query($sql);
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class logColaborador
{
	public $pdo;
	public $colaborador;
	public $us;
	public $log;

	function __construct($pdo, $colaborador, $us, $log)
	{
		$this->pdo = $pdo;
		$this->colaborador = $colaborador;
		$this->us = $us;
		$this->log = $log;
	}

	function registraLog()
	{
		try {
			$sql = "INSERT INTO log_colaborador (colaborador, data, us, log) VALUES (
					'$this->colaborador',
					now(),
					'$this->us',
					'$this->log'
				)";
			//echo "$sql";

			$log = $this->pdo->query($sql);
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class logCliente
{
	public $pdo;
	public $cliente;
	public $us;
	public $log;

	function __construct($pdo, $cliente, $us, $log)
	{
		$this->pdo = $pdo;
		$this->cliente = $cliente;
		$this->us = $us;
		$this->log = $log;
	}

	function registraLog()
	{
		try {
			$sql = "INSERT INTO log_cliente (cliente, data, us, log) VALUES (
					'$this->cliente',
					now(),
					'$this->us',
					'$this->log'
				)";
			//echo "$sql";

			$log = $this->pdo->query($sql);
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class regCurso
{
	public $pdo;
	public $colaborador;
	public $curso;
	public $dataRealizado;
	public $dataVencimento;
	public $certificado;
	public $instituicao;

	function __construct($pdo, $colaborador, $curso, $dataRealizado, $dataVencimento, $certificado, $instituicao)
	{
		$this->pdo = $pdo;
		$this->colaborador = $colaborador;
		$this->curso = $curso;
		$this->dataRealizado = $dataRealizado;
		$this->dataVencimento = $dataVencimento;
		$this->certificado = $certificado;
		$this->instituicao = $instituicao;
	}

	function registraCurso()
	{
		try {
			$sql = "INSERT INTO tb_cursos_colaborador (colaborador, curso, dataRealizado, dataVencimento, certificado, instituicao) VALUES (
					'$this->colaborador',
					'$this->curso',
					'$this->dataRealizado',
					'$this->dataVencimento',
					'$this->certificado',
					'$this->instituicao'
				)";
			//echo "$sql";

			$log = $this->pdo->query($sql);
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

class atNF
{
	/** Atualização de dados de NF para verificar se existe pendência */
	/**
	 * Receber chave
	 * Procurar todos os itens ver se já foram atribuídos
	 * Atualizar todas as linhas necessárias
	 * Verificar se será necessário atualizar a NF também
	 * sempre atualizar para 0 ou 1 de acordo com o atribuído, pois a regra será para inclusão ou remoção
	 */
	public $pdo;
	public $chave;

	function __construct($pdo, $chave)
	{
		$this->pdo = $pdo;
		$this->chave = $chave;
	}

	function atualiza()
	{
		$sql = "select * from nfe_produtos where chave = '$this->chave'";
		$lp = $this->pdo->query($sql);

		while ($l = $lp->fetch()) {
			//procurar as quantidades atribuídas se coincidem e fazer as atualizações nas linhas
			$sql2 = "select sum(qt) as 'qt' from tb_custo_centrocusto where chave = '$this->chave' and linha = '$l[linhaNf]'";
			$lt = $this->pdo->query($sql2)->fetch();

			if (!$lt) { //se não houver um resultado atualiza para 1
				$sql3 = "update nfe_produtos set pendencia = '1' where id = $l[id]";
			}

			if ($lt) { //se houver um resultado
				if ($lt['qt'] >= $l['qCom']) { //se quantidade atribuída for maior ou igual ao total...
					//atualiza a pendencia da linha para zero
					$sql3 = "update nfe_produtos set pendencia = '0' where id = $l[id]";
				}
				if ($lt['qt'] < $l['qCom']) { //se quantidade atribuída for menor que o total...
					//atualiza a pendencia da linha para 1 (foi removido ou atualizado)
					$sql3 = "update nfe_produtos set pendencia = '1' where id = $l[id]";
				}
			}
			$this->pdo->query($sql3);
		}
		//validar a NF
		$sql4 = "select sum(pendencia) as 'pendencia' from nfe_produtos where chave = '$this->chave'";
		$r = $this->pdo->query($sql4)->fetch();
		if ($r['pendencia'] > 0) { //igual a quantidade de linhas
			//verificar a quantidade total atribuída
			$qta = $this->pdo->query("select coalesce(sum(qt),0) as 'qt' from tb_custo_centrocusto where chave = '$this->chave'")->fetch();
			$qtn = $this->pdo->query("select sum(qCom) as 'qt' from nfe_produtos where chave = '$this->chave'")->fetch();

			if ($qta['qt'] == 0) { //se for igual a zero, totalmente pendente
				$sql5 = "update nfe_nfe set pendencia = '1' where chave = '$this->chave'";
			}
			if ($qta['qt'] < $qtn['qt'] && $qta['qt'] > 0) { //se for menor, parcialemte pendente
				$sql5 = "update nfe_nfe set pendencia = '2' where chave = '$this->chave'";
			}
		}
		if ($r['pendencia'] == 0) {
			$sql5 = "update nfe_nfe set pendencia = '0' where chave = '$this->chave'";
		}
		$this->pdo->query($sql5);
	}
}

class regContato
{
	public $pdo;
	public $nome;
	public $email;
	public $telefone;
	public $tipo;
	public $ref;

	function __construct($pdo, $nome, $email, $telefone, $tipo, $ref)
	{
		$this->pdo = $pdo;
		$this->nome = $nome;
		$this->email = $email;
		$this->telefone = $telefone;
		$this->tipo = $tipo;
		$this->ref = $ref;
	}

	function registraContato()
	{
		try {
			$sql = "insert into tb_contatos (nome, email, telefone, tipo, ref) values('$this->nome', '$this->email', '$this->telefone', '$this->tipo', '$this->ref')";
			$this->pdo->query($sql);
			return "ok";
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}
/* REGISTROS DE DADOS GENÉRICOS */

class regDados
{
	public $pdo;
	public $sql;

	function __construct($pdo, $sql)
	{
		$this->pdo = $pdo;
		$this->sql = $sql;
	}

	function registra()
	{
		try {
			$reg = $this->pdo->query($this->sql);
			//return $reg;
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}

	function consulta()
	{
		try {
			$reg = $this->pdo->query($this->sql);
			$r = $reg->fetch();
			return $r;
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}
class conexao
{
	public $pdo;
	public $us;
	public $senha;
	public $sistema;
	//dados do usuário
	public $nome;
	public $setor;
	public $email;
	public $setorId; //setorId 
	public $nvAcesso; //nível de acesso de acordo com a tb_acesso
	public $usId; //id de usuário
	public $situacao;
	public $usuario;

	function __construct($us, $senha, $pdo)
	{
		$this->us = $us;
		$this->senha = $senha;
		$this->pdo = $pdo;
	}

	function validaUsuario()
	{
		$sql = "SELECT * FROM tb_usuario WHERE usuario='$this->us' AND senha = '$this->senha'";
		//echo $sql;
		$cnf = $this->pdo->query("select * from tb_usuario");
		$cnt = $cnf->rowCount(); //mysql_num_rows($cnf);
		return $cnt;
	}

	function validaAcesso($s)
	{
	}

	function retornaDadosUsuario()
	{
	}
}

class envEmail
{
	public $e; //email
	public $n; //nome
	public $a; //assunto
	public $m; //mensgem
	public $t; //tipo de envio... 1-envio de acionamento
	public $s; //setor

	function __construct($e, $n, $a, $m, $t, $s)
	{
		$this->e = $e;
		$this->n = $n;
		$this->a = $a;
		$this->m = $m;
		$this->t = $t;
		$this->s = $s;
	}

	function enviaEmail()
	{
		//incluir aqui arquivo de configurações do email...
		include 'config.mail.php';
		//quem irá receber...
		if ($this->t == 1) {
			$lu = mysql_query("select nome, email from app_acessos.tb_usuario where id in (select us from app_acessos.tb_acessos where st = 1 and pg = '2')");
			while ($l = mysql_fetch_assoc($lu)) {
				$mail->AddAddress($l[email], $l[nome]);
				//				echo "mail->AddAddress('$l[email]', '$l[nome]')";
			}
		}

		$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
		$mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)
		// Define a mensagem (Texto e Assunto)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		$mail->Subject  = $this->a; // Assunto da mensagem
		$mail->Body = $this->m;
		//$mail->AltBody = "Registrado ";
		// Define os anexos (opcional)
		// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
		//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
		// Envia o e-mail

		$enviado = $mail->Send();
		// Limpa os destinatários e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		// Exibe uma mensagem de resultado
		if ($enviado) {
			echo "Email enviado com sucesso.";
		} else {
			echo "Não foi possível enviar o e-mail.";
			echo "<b>Informações do erro:</b> " . $mail->ErrorInfo;
		}
	}
}
