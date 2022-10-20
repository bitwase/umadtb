<?php
ob_clean();
class regConta
{
    public $pdo;
    public $tipo;
    public $valor;
    public $descricao;
    public $categoria;
    public $subcategoria;
    public $centro;
    public $vencimento;
    public $obs;
    public $parcelar;
    public $entrada;
    public $parcelas;
    public $valorParcela;
    public $repetir;
    public $frequencia;
    public $ocorrencias;
    public $us;
    public $sit;
    public $contaPagamento;
    public $dataPagamento;

    public function __construct($pdo, $tipo, $valor, $descricao, $categoria, $subcategoria, $centro, $vencimento, $obs, $parcelar, $entrada, $parcelas, $valorParcela, $repetir, $frequencia, $ocorrencias, $us, $sit, $contaPagamento, $dataPagamento)
    {
        $this->pdo = $pdo;
        $this->tipo = $tipo; //1 entrada 2 saída
        $this->valor = $valor;
        $this->descricao = $descricao;
        $this->categoria = empty($categoria) ? 'null' : (int) $categoria;
        $this->subcategoria = empty($subcategoria) ? 'null' : (int) $subcategoria;
        $this->centro = empty($centro) ? 'null' : (int) $centro;
        $this->vencimento = $vencimento;
        $this->obs = $obs;
        $this->parcelar = $parcelar;
        $this->entrada = $entrada;
        $this->parcelas = $parcelas;
        $this->valorParcela = $valorParcela;
        $this->repetir = $repetir;
        $this->frequencia = $frequencia;
        $this->ocorrencias = $ocorrencias;
        $this->us = $us;
        $this->sit = $sit;
        $this->contaPagamento = $contaPagamento;
        $this->dataPagamento = $dataPagamento;
    }

    public function registraConta()
    {
        //fazer o simples...
        if (!$this->repetir && !$this->parcelar) { //não divide nem parcela, só insere
            $r = $this->registraDados($this->pdo, $this->tipo, $this->valor, $this->descricao, $this->us, $this->vencimento, $this->obs, $this->categoria, $this->subcategoria, $this->centro, $this->sit, $this->contaPagamento, $this->dataPagamento);
            return $r;
            //echo $r;
        }
        //verificar se é para repetir
        if ($this->parcelar) {
            $venc = $this->vencimento;

            $auxParc = 1;
            if ($this->entrada) {
                $parcelas = ($this->parcelas + 1);
            }
            if (!$this->entrada) {
                $parcelas = $this->parcelas;
            }
            for ($i = 0; $i < $parcelas; $i++) {
                $desc = $this->descricao . " (" . $auxParc . "/" . $parcelas . ")";
                $venc = date_create($venc); //recebe o vencimento original

                //chamar a função para atualizar a data e inserir
                if ($i == 0 && $this->entrada) { //se for zero, é a entrada
                    $venc = date_format($venc, 'Y-m-d');
                    $executa = $this->registraDados($this->pdo, $this->tipo, $this->entrada, $desc, $this->us, $venc, $this->obs, $this->categoria, $this->subcategoria, $this->centro, $this->sit, $this->contaPagamento, $this->dataPagamento);
                    //return $executa;
                }
                if (!$this->entrada && $i == 0) {
                    // $venc = date_format($venc, 'Y-m-d');

                    date_add($venc, date_interval_create_from_date_string("1 months"));
                    $venc = date_format($venc, 'Y-m-d');
                    $executa = $this->registraDados($this->pdo, $this->tipo, $this->valorParcela, $desc, $this->us, $venc, $this->obs, $this->categoria, $this->subcategoria, $this->centro, $this->sit, $this->contaPagamento, $this->dataPagamento);
                    //return $executa;
                }
                if ($i > 0) {
                    date_add($venc, date_interval_create_from_date_string("1 months"));
                    $venc = date_format($venc, 'Y-m-d');
                    $executa = $this->registraDados($this->pdo, $this->tipo, $this->valorParcela, $desc, $this->us, $venc, $this->obs, $this->categoria, $this->subcategoria, $this->centro, $this->sit, $this->contaPagamento, $this->dataPagamento);
                    ///return $executa;
                }

                $auxParc++;
            }
        }
        if ($this->repetir) { //se for 1
            //verificar a frequencia

            switch ($this->frequencia) {
                case 1: //diariamente
                    $val = 1;
                    $per = "days";
                    break;
                case 2:
                    $val = 7; //semanalmente
                    $per = "days";
                    break;
                case 3: //quinzenalmente
                    $val = 15;
                    $per = "days";
                    break;
                case 4: //mensalmente
                    $val = 1;
                    $per = "months";
                    break;
                case 5: //semestralmente
                    $val = 6;
                    $per = "months";
                    break;
                case 6: //anualmente
                    $val = 1;
                    $per = "years";
                    break;
            }
            $venc = $this->vencimento;

            for ($i = 0; $i < $this->ocorrencias; $i++) {
                $venc = date_create($venc); //recebe o vencimento original
                //chamar a função para atualizar a data e inserir
                if ($i > 0) {
                    date_add($venc, date_interval_create_from_date_string("$val $per"));
                    //$venc = date_format($venc, "Y-m-d");
                }
                $venc = date_format($venc, 'Y-m-d');

                $executa = $this->registraDados($this->pdo, $this->tipo, $this->valor, $this->descricao, $this->us, $venc, $this->obs, $this->categoria, $this->subcategoria, $this->centro, $this->sit, $this->contaPagamento, $this->dataPagamento);
                //return $executa;
            }
        }
    }

    public function registraDados($pdo, $tipo, $valor, $descricao, $us, $vencimento, $obs, $categoria, $subcategoria, $centro, $sit, $contaPagamento, $dataPagamento)
    {
        try {
            if ($sit == 1) { //se for agendado
                $sql = "insert into fin_financeiro (data, tipo, valor, motivo, us, sit, dt_ag, obs, categoria, subcategoria, centro) values(now(), 
                '$tipo', 
                '$valor', 
                '$descricao', 
                '$us', 
                '$sit', 
                '$vencimento', 
                '$obs', 
                $categoria, 
                $subcategoria, 
                $centro
                )";
            }
            if ($sit == 2) { //se estiver realizado já
                $sql = "insert into fin_financeiro (data, tipo, valor, motivo, us, sit, dt_ag, obs, categoria, subcategoria, centro, conta, dt_realizado) values(now(), 
                '$tipo', 
                '$valor', 
                '$descricao', 
                '$us', 
                '$sit', 
                '$vencimento', 
                '$obs', 
                '$categoria', 
                '$subcategoria', 
                '$centro',
                '$contaPagamento',
                '$dataPagamento'
                )";
            }

            $pdo->query($sql);

            $rcId = $this->pdo->lastInsertId();
            //inserir no histórico

            if ($tipo == 1) {
                $his = "Conta a receber registrada.";
            }

            if ($tipo == 2) {
                $his = "Conta a pagar registrada.";
            }

            $rh = new hisFinanceiro($this->pdo, $us, $rcId, $his);
            $rh->registraHistorico();
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}

class cancelaConta
{

    public $pdo;
    public $id;
    public $motivo;
    public $us;

    public function __construct($pdo, $id, $motivo, $us)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->motivo = $motivo;
        $this->us = $us;
    }

    public function registraCancelamento()
    {
        try {
            $sql = "update fin_financeiro set sit = '3' where id = '$this->id'";
            $this->pdo->query($sql);

            $his = "Registro de cancelamento.<br><b>Motivo:</b> $this->motivo";

            $rh = new hisFinanceiro($this->pdo, $this->us, $this->id, $his);
            $rh->registraHistorico();
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}

class atualizaConta
{
    public $pdo;
    public $id;
    public $val;
    public $cmp;

    function __construct($pdo, $id, $val, $cmp)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->val = $val;
        $this->cmp = $cmp;
    }

    function atualiza()
    {
        try {
            $sql = "update fin_financeiro set $this->cmp = '$this->val' where id = '$this->id'";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}

class regPagamento
{
    public $pdo;
    public $id;
    public $conta;
    public $dataPg;
    public $horaPg;
    public $us;

    function __construct($pdo, $id, $conta, $dataPg, $horaPg, $us)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->conta = $conta;
        $this->dataPg = $dataPg;
        $this->horaPg = $horaPg;
        $this->us = $us;
    }

    function registra()
    {
        try {
            $sql = "update fin_financeiro set sit = '2', dt_realizado = '$this->dataPg $this->horaPg', us_realizado = '$this->us', conta = '$this->conta' where id = '$this->id'";

            $this->pdo->query($sql);

            //chamar histórico
            $sqlConta = "select conta from fin_conta where id = '$this->conta'";
            $cn = $this->pdo->query($sqlConta)->fetch();

            $date = date_create("$this->dataPg $this->horaPg");
            $his = "Informado como pagamento realizado.<br><b>Data de Pagamento:</b>" . date_format($date, "d/m/Y H:i") . "<br><b>Conta:</b> " . $cn['conta'];

            $rh = new hisFinanceiro($this->pdo, $this->us, $this->id, $his);
            $rh->registraHistorico();
            //update
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}
class hisFinanceiro
{

    public $pdo;
    public $us;
    public $id;
    public $his;

    function __construct($pdo, $us, $id, $his)
    {
        $this->pdo = $pdo;
        $this->us = $us;
        $this->id = $id;
        $this->his = $his;
    }

    function registraHistorico()
    {
        try {
            $sql = "insert into log_financeiro (fin, data, us, log) values('$this->id',now(),'$this->us','$this->his')";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}

## CONCILIAÇÃO ##

class conciliacao
{
    public $pdo;
    public $us;
    public $inicio;
    public $fim;
    public $saldoOfx;

    function __construct($pdo, $us, $inicio, $fim, $saldoOfx)
    {
        $this->pdo = $pdo;
        $this->us = $us;
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->saldoOfx = $saldoOfx;
    }

    function registra()
    {
        try {
            $sql = "insert into fin_conciliacao (data, us, inicio, fim, saldoOfx) values(
                now(),
                '$this->us',
                '$this->inicio',
                '$this->fim',
                '$this->saldoOfx'
                )";
            $r = $this->pdo->query($sql);

            $rcId = $this->pdo->lastInsertId();
            return $rcId;
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}

class Ofx
{
    private $ofxFile;
    public function __construct($ofxFile)
    {
        $this->ofxFile = $this->closeTags($ofxFile); 
    }
    public function closeTags($ofx = null)
    {
        $buffer = '';
        $source = fopen($ofx, 'r') or die("Unable to open file!");
        while (!feof($source)) {
            $line = trim(fgets($source));
            if ($line === '') continue;

            if (substr($line, -1, 1) !== '>') {
                list($tag) = explode('>', $line, 2);
                $line .= '</' . substr($tag, 1) . '>';
            }
            $buffer .= $line . "\n";
        }

        $name = realpath(dirname($ofx)) . '/' . date('Ymd') . '.ofx';
        $file = fopen($name, "w") or die("Unable to open file!");
        fwrite($file, $buffer);
        fclose($file);

        return $name;
    }
    /*     * Converte o arquivo OFX para XML     */
    public function getOfxAsXML()
    {
        $content = utf8_decode(file_get_contents($this->ofxFile));
        $line = strpos($content, "<OFX>");
        $ofx = substr($content, $line - 1);
        $buffer = $ofx;
        $count = 0;
        while ($pos = strpos($buffer, '<')) {
            $count++;
            $pos2 = strpos($buffer, '>');
            $element = substr($buffer, $pos + 1, $pos2 - $pos - 1);
            if (substr($element, 0, 1) == '/') {
                $sla[] = substr($element, 1);
            } else {
                $als[] = $element;
            }
            $buffer = substr($buffer, $pos2 + 1);
        }
        $adif = array_diff($als, $sla);
        $adif = array_unique($adif);
        $ofxy = $ofx;
        foreach ($adif as $dif) {
            $dpos = 0;
            while ($dpos = strpos($ofxy, $dif, $dpos + 1)) {
                $npos = strpos($ofxy, '<', $dpos + 1);
                $ofxy = substr_replace($ofxy, "</$dif>\n<", $npos, 1);
                $dpos = $npos + strlen($element) + 3;
            }
        }
        $ofxy = str_replace('&', '&', $ofxy);
        return $ofxy;
    }    /*     * Retorna o Saldo da conta na data de exportação do extrato     */
    public function getBalance()
    {
        $xml = new SimpleXMLElement($this->getOfxAsXML());
        $balance = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->BALAMT;
        $dateOfBalance = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL->DTASOF;
        $date = strtotime(substr($dateOfBalance, 0, 8));
        $dateToReturn = date('Y-m-d', $date);
        return array('date' => $dateToReturn, 'balance' => $balance);
    }    /*     * Retora um array de objetos com as transações     *      * DTPOSTED => Data da Transação     * TRNAMT   => Valor da Transação     * TRNTYPE  => Tipo da Transação (Débito ou Crédito)     * MEMO     => Descrição da transação     */
    public function getTransactions()
    {
        $xml = new SimpleXMLElement($this->getOfxAsXML());
        $transactions = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN;
        return $transactions;
    }

    public function getDates()
    {
        $xml = new SimpleXMLElement($this->getOfxAsXML());
        $dateStart = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->DTSTART;
        $dateEnd = $xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->DTEND;
        return array('dateStart' => $dateStart, 'dateEnd' => $dateEnd);
    }
}
