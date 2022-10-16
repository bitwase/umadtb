<?php
$tp = $_REQUEST[tp];
$pdo = new PDO("mysql:host=localhost; dbname=bwclinicas; charset=utf8;", "root", "b3tw1s2@");
$pa = $pdo->prepare("SELECT id, nome FROM clientes order by nome");
$pa->execute();
echo json_encode($pa->fetchAll(PDO::FETCH_ASSOC));

?>
