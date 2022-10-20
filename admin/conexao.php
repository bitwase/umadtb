<?php #conexao com bd
//https://phpdelusions.net/pdo_examples/select
//

class conecta
{

	public $srv;
	public $db;
	public $db_user;
	public $db_senha;
	public $db_serv;

	function __construct()
	{
		$this->srv = $_SERVER["HTTP_HOST"];
		$this->loc = $_SERVER["PHP_SELF"];
		$this->lc = explode("/", $this->loc);

		if(!isset($this->lc[2])){
			$this->lc[2] = "";
		}
		
		if ($this->srv == "localhost") {
			$this->db = "bw_umadtb";
			$this->db_user = "root";
			$this->db_senha = "";
			$this->db_serv = "localhost";
		}

		if ($this->srv != "localhost") {
			/*if ($this->lc[2] == "dev") {
				$this->db = "bwhom_engerede"; //se for teste
				$this->db_user = "bwhom_engerede";
				$this->db_senha = "B3tw1s2@2022";
				$this->db_serv = "bdhost0012.servidorwebfacil.com";
			} */
			if ($this->lc[2] != "dev") {
				$this->db = "bwumadtb"; //se for produção
				$this->db_user = "bwumadtb";
				$this->db_senha = "b3tw1s2@";
				$this->db_serv = "mysql.uhserver.com";
			}
		}
		//echo $this->srv;
		//print_r($this->lc[2]);


	}

	function conecta()
	{
		try {
			$this->pdo = new PDO('mysql:host=' . $this->db_serv . ';charset=utf8; dbname=' . $this->db, $this->db_user, $this->db_senha);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $this->pdo;
		} catch (PDOException $e) {
			return 'ERROR: ' . $e->getMessage();
		}
	}
}

$pdo = new conecta();
$retPdo = $pdo->conecta();
$pdo = $pdo->conecta();
