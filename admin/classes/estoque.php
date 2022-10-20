<?php

class regProdutoEstoque
{
    public $pdo;
    public $descricao;
    public $grupo;
    public $subgrupo;
    public $unidade;
    public $us;

    function __construct($pdo, $descricao, $grupo, $subgrupo, $unidade, $us)
    {
        $this->pdo = $pdo;
        $this->descricao = $descricao;
        $this->grupo = $grupo;
        $this->subgrupo = $subgrupo;
        $this->unidade = $unidade;
        $this->us = $us;
    }

    function registraProduto()
    {
        try {
            $sql = "insert into est_produtos (descricao, grupo, subgrupo, um, us, inclusao) values(
                '$this->descricao',
                '$this->grupo',
                '$this->subgrupo',
                '$this->unidade',
                '$this->us',
                now()
            )";
            $this->pdo->query($sql);
            //retornar o último id?
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}

class regCompra
{
    public $pdo;
    public $fornecedor;
    public $previsaoEntrega;
    public $us;

    function __construct($pdo, $fornecedor, $previsaoEntrega, $us)
    {
        $this->pdo = $pdo;
        $this->fornecedor = $fornecedor;
        $this->previsaoEntrega = $previsaoEntrega;
        $this->us = $us;
    }

    function registraCompra()
    {
        try {
            $sql = "insert into est_compras (fornecedor, previsaoEntrega, us, dataInclusao) values(
            '$this->fornecedor',
            '$this->previsaoEntrega',
            '$this->us',
            now()
        )";

            $this->pdo->query($sql);
            $r = $this->pdo->lastInsertId();
            return $r;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}

class addItemCompra
{
    public $pdo;
    public $compra;
    public $item;
    public $unidade;
    public $quantidade;
    public $valor;
    public $us;

    function __construct($pdo, $compra, $item, $unidade, $quantidade, $valor, $us)
    {
        $this->pdo = $pdo;
        $this->compra = $compra;
        $this->item = $item;
        $this->unidade = $unidade;
        $this->quantidade = $quantidade;
        $this->valor = $valor;
        $this->us = $us;
    }

    function adicionaItem()
    {
        try {
            $li = $this->pdo->query("select coalesce(max(linha),0)+1 as 'linha' from est_compra_produto where compra = '$this->compra'")->fetch();
            $linha = $li['linha'];

            $sql = "insert into est_compra_produto (compra, linha, produto, unidade, quantidade, valor, us, inclusao, st) values(
                '$this->compra',
                '$linha',
                '$this->item',
                '$this->unidade',
                '$this->quantidade',
                '$this->valor',
                '$this->us',
                now(),
                '1'
            )";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            return "$sql ???" . $e->getMessage();
        }

        $a = new atualizaEncomendado($this->pdo, $this->item);
        $a->atualiza();
    }
}

class atualizaCompra
{
    public $pdo;
    public $id;
    public $valor;
    public $campo;

    function __construct($pdo, $id, $valor, $campo)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->valor = $valor;
        $this->campo = $campo;
    }

    function atualiza()
    {
        try {
            $sql = "update est_compras set $this->campo = '$this->valor' where id = '$this->id'";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}

class atualizaLinhaCompra
{
    public $pdo;
    public $id;
    public $valor;
    public $campo;

    function __construct($pdo, $id, $valor, $campo)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->valor = $valor;
        $this->campo = $campo;
    }

    function atualiza()
    {
        try {
            $sql = "update est_compra_produto set $this->campo = '$this->valor' where id = '$this->id'";
            $this->pdo->query($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        //buscar o item para atualizar
        $item = $this->pdo->query("select produto from est_compra_produto where id = '$this->id'")->fetch();
        $a = new atualizaEncomendado($this->pdo, $item['produto']);
        $a->atualiza();
    }
}

class atualizaEncomendado
{
    public $pdo;
    public $item;

    function __construct($pdo, $item)
    {
        $this->pdo = $pdo;
        $this->item = $item;
    }

    function atualiza()
    {
        //est_compra_produto
        //quantidade - quantidadeRecebido - quantidadeCancelado
        $sql = "select (sum(quantidade) - sum(quantidadeRecebido) - sum(quantidadeCancelado)) as 'encomendado' from est_compra_produto where produto = $this->item";
        $r = $this->pdo->query($sql)->fetch();

        $sql2 = "delete from est_estoque where item = $this->item and status = 'ENCOMENDADO'";
        $this->pdo->query($sql2);

        $sql3 = "insert into est_estoque (item, quantidade, status) values ('$this->item', '$r[encomendado]', 'ENCOMENDADO')";
        $this->pdo->query($sql3);
    }
}