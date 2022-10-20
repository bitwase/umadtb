<?php
include 'conexao.php';

//Procurar os aniversariantes do dia
//procurar onde fazem aniversario no dia, e são do setor 2
$la = mysql_query("select * from tb_inscritos where date_format(nascimento,'%m-%d') = date_format(now(),'%m-%d') and setor = '2 - Uvaranas'");
$an = "";
$q = 0;
while($l = mysql_fetch_assoc($la)){
	$an .= "$l[nome] - ";
	$q++;
}
$an = substr($an,0,-2);
//01/04 - cleiton | 28/11 rafael

function sendMessage($an) {
    $content      = array(
        "en" => $an
    );
	$heading = array(
		"en" => 'Aniversariantes do Dia'
	);
    $hashes_array = array();
    /*array_push($hashes_array, array(
        "id" => "like-button",
        "text" => "Like",
        "icon" => "http://i.imgur.com/N8SN8ZS.png",
        "url" => "https://yoursite.com"
    ));
    array_push($hashes_array, array(
        "id" => "like-button-2",
        "text" => "Like2",
        "icon" => "http://i.imgur.com/N8SN8ZS.png",
        "url" => "https://yoursite.com"
    ));*/
    $fields = array(
        'app_id' => "f6eb233f-6433-4b68-9dec-987d419be263",
        'included_segments' => array(
            'All'
        ),
        'data' => array(
            "foo" => "bar"
        ),
        'contents' => $content,
		'headings' => $heading,
        'web_buttons' => $hashes_array
    );
    
    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic MmY2YTEwOTItNDM2ZC00YWRmLTgxOWMtOTc2YTcyZTdiYjA5'//REST API KEY
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

if($q > 0){
$response = sendMessage($an);
}
$return["allresponses"] = $response;
$return = json_encode($return);
echo "<pre>";
$data = json_decode($response, true);
print_r($data);
$id = $data['id'];
print_r($id);

print("\n\nJSON received:\n");
print($return);
print("\n");
?>