<?php
/*########## NOVOS AJUSTES PARA MELHORIAS ############
-Remover os cadastros duplicados; [ok]
-Habilitar opções pra alterar dados do inscrito; [ok]
-Colocar alerta de aniversariantes do mês; [ok]
-Colocar alerta de aniversariantes do dia; [ok]
-Ajuste pra envio de email para os líderes com os aniversariantes do dia;

-Possibilidade de enviar sms com os aniversariantes do dia; 

-Ver a possibilidade com Cleyton de envio automático de SMS para o aniversariante;
	-Seria legal, para não passar em branco a data;

-Ver possibilidade de alertar líderes quando houver 3 faltas consecutivas;
    -ao registrar a terceira falta, emitir alerta na tela, onde usuário que estiver logado no momento deve "dar ok" confirmando a ciência da sequência da ausência;
    -criar um relatório com faltas consecutivas;
    -a intenção deste relatório é que o líder possa tomar uma ação, em tentar "resgatar" o jovem, e tentar entender o motivo destas faltas;

Para inserir no relatório de auseências consecutivas, utilizar:

select distinct date_format(data,'%Y-%m-%d') as 'data' from tb_ausente order by data desc limit 3;//nesta situação vai pegar as 3 últimas datas
-primeiro: selecionar as 3 últimas datas;
select distinct date_format(data,'%Y-%m-%d') as 'data' from tb_ausente order by data desc limit 3;
criar uma string separando por vírgulas e utilizando aspas simples;

utilizar a query abaixo para pegar todos;

select * from tb_ausente where data in (XXXXXX);//XXXXX é a variável com a string contendo as 3 datas

-colocar isto em um arquivo externo (consecutivas), onde irá inserir na tb_consecutivas, gerando um alerta para os líderes, para que possam tomar uma ação sobre o jovem.;

no relatório, incluir um campo de observações, onde será de texto livre;
-um 'botão' onde quando clicar, o líder está informando que está ciente da ausência consecutiva;
-pode ser incluso observação a qualquer momento;


*/

?>
