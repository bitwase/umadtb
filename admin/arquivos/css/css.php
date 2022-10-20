<?php
header("Content-type: text/css");
include "../../conexao.php";
$r = $pdo->query("select cor1, cor2 from config where id = 1")->fetch();
?>

::-webkit-scrollbar-thumb, .banner, .nav-side-menu li:hover, .nav-side-menu .brand, #bodyLogin, .k-header.k-grid-toolbar, .k-grouping-header, #loader {
	background: <?php echo $r['cor1']; ?> !important;
}