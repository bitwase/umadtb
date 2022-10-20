<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//recebe tres valores
//id do agendamento, id colaborador, acao
$acao = $_REQUEST[a];//1-inserir 2-desativar
$agendamento = $_REQUEST[ag];
$colaborador = $_REQUEST[c];

if($acao == 1){
$vf = mysql_num_rows(mysql_query("select * from tb_matricula where agendamento = '$agendamento' AND colaborador = '$colaborador'"));
if($vf == 0){
mysql_query("insert into tb_matricula
		(agendamento,colaborador,situacao)
		values('$agendamento','$colaborador','1')"); 
		echo "<meta http-equiv='refresh' content='0;URL=http://www.serdia.com.br/intranet/rh/treinamentos/index.php?pg=prog&id=$agendamento'>";
	}
else if($vf == 1){
mysql_query("update tb_matricula
		set situacao = '1' where agendamento = '$agendamento' AND colaborador = '$colaborador'"); 
echo "<meta http-equiv='refresh' content='0;URL=http://www.serdia.com.br/intranet/rh/treinamentos/index.php?pg=prog&id=$agendamento'>";
}

//pegar dados para email
$dd = mysql_fetch_assoc(mysql_query("
select c.nome, c.email, t.titulo, t.assunto, date_format(a.data_inicio,'%d/%m/%Y %H:%i') as 'data', a.local
from tb_matricula m
inner join tb_colaboradores c on m.colaborador = c.id
inner join tb_agenda a on m.agendamento = a.id
inner join tb_cursos t on a.curso = t.id
where agendamento = '$agendamento' AND colaborador = '$colaborador'
"));

//fim dados para email

email_inscricao($dd[nome],$dd[email],$dd[titulo],$dd[data],$dd[local],$dd[assunto]);
}
if($acao == 2){
mysql_query("update tb_matricula
		set situacao = '0' where agendamento = '$agendamento' AND colaborador = '$colaborador'"); 
		echo "<meta http-equiv='refresh' content='0;URL=http://www.serdia.com.br/intranet/rh/treinamentos/index.php?pg=prog&id=$agendamento'>";
//pegar dados para email
$dd = mysql_fetch_assoc(mysql_query("
select c.nome, c.email, t.titulo, t.assunto, date_format(a.data_inicio,'%d/%m/%Y %H:%i') as 'data', a.local
from tb_matricula m
inner join tb_colaboradores c on m.colaborador = c.id
inner join tb_agenda a on m.agendamento = a.id
inner join tb_cursos t on a.curso = t.id
where agendamento = '$agendamento' AND colaborador = '$colaborador'
"));

//fim dados para email

email_remove($dd[nome],$dd[email],$dd[titulo]);

}

function email_inscricao($colaborador,$email,$curso,$data,$local,$descricao){
        // Passando os dados obtidos pelo formulÃƒÂ¡rio para as variÃƒÂ¡veis abaixo
        $nomeremetente     = 'Treinamentos'; //
        $emailremetente    = 'Treinamentos@serdia.com.br';//sempre este email
        $emaildestinatario = $email; // pegar este email no cadstro, verificar se usuÃ¡rio que esta nofificando Ã© tec ou usuario
        $assunto          = 'Curso '.$curso;
        //$mensagem          = $_POST['mensagem'];


        /* Montando a mensagem a ser enviada no corpo do e-mail. */
        $mensagemHTML = '<hr><p>Não responder este email.</p><hr>
        <p><b>'.$colaborador.'</b>, foi realizada sua inscrição para o curso <b>'.$curso.'</b>.</p>
        <p><b>Data:</b> '.$data.'
        <p><b>Local:</b> '.$local.'
        <p><b>Descrição:</b>
	<p>'.$descricao.'
        <hr><p>Não responder este email.</p><hr>';
        //utf8_encode($mensagemHTML);


        // O remetente deve ser um e-mail do seu domÃƒÂ­nio conforme determina a RFC 822.
        // O return-path deve ser ser o mesmo e-mail do remetente.
        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: $emailremetente\r\n"; // remetente
        $headers .= "Return-Path: $emaildestinatario \r\n"; // return-path
        $envio = mail($emaildestinatario, $assunto, $mensagemHTML, $headers); 
        
        echo "$email_res, $assunto, $mensagemHTML, $headers";
        if($envio)
        {
            echo "Enviado email para $email_res";
        }
    }

function email_remove($colaborador,$email,$curso){
        // Passando os dados obtidos pelo formulÃƒÂ¡rio para as variÃƒÂ¡veis abaixo
        $nomeremetente     = 'Treinamentos'; //
        $emailremetente    = 'Treinamentos@serdia.com.br';//sempre este email
        $emaildestinatario = $email; // pegar este email no cadstro, verificar se usuÃ¡rio que esta nofificando Ã© tec ou usuario
        $assunto          = 'Curso '.$curso;
        //$mensagem          = $_POST['mensagem'];


        /* Montando a mensagem a ser enviada no corpo do e-mail. */
        $mensagemHTML = '<hr><p>Não responder este email.</p><hr>
        <p><b>'.$colaborador.'</b>, foi cancelada sua inscrição para o curso <b>'.$curso.'</b>.</p>
        <hr><p>Não responder este email.</p><hr>';
        //utf8_encode($mensagemHTML);


        // O remetente deve ser um e-mail do seu domÃƒÂ­nio conforme determina a RFC 822.
        // O return-path deve ser ser o mesmo e-mail do remetente.
        $headers = "MIME-Version: 1.1\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: $emailremetente\r\n"; // remetente
        $headers .= "Return-Path: $emaildestinatario \r\n"; // return-path
        $envio = mail($emaildestinatario, $assunto, $mensagemHTML, $headers); 
        
        echo "$email_res, $assunto, $mensagemHTML, $headers";
        if($envio)
        {
            echo "Enviado email para $email_res";
        }
    }
?>

