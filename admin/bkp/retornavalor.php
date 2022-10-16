<?php
include 'conexao.php';

$term = trim(strip_tags($_GET['term']));
$term = "w";
$qstring = "SELECT nome FROM pacientes WHERE nome LIKE '%".$term."%'";
$result = mysql_query($qstring);
while ($row = mysql_fetch_assoc($result)) 
{
    $row['nome'] = htmlentities(stripslashes($row['nome']));
    $row_set[] = $row['nome'];
}
echo json_encode($row_set);
?>
