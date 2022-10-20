<?php #conexao com bd

$db="dev_engerede";

//include "../config.db.php";

$db_user = "root";
$db_senha = "";
$db_serv = "localhost";

//global $pdo;

try {
  $pdo = new PDO('//mysql:host='.$db_serv.';dbname='.$db, $db_user, $db_senha);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>