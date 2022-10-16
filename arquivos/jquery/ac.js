$(document).ready(function() {

$.getJSON('rv.php', function(modeloData){
var cliente = [];
//var pa = [];
//var axl = [];
//var desc = [];
//var pppb = [];
//var obs = [];

$(modeloData).each(function(key, value) {
cliente.push(value.id+" - "+value.nome);
//pa.push(value.pa);
//axl.push(value.descppb);
//desc.push(value.descricao);
//pppb.push(value.pppb);
//obs.push(value.obs);
});

$('#cliente').autocomplete({ source: cliente, minLength: 3});
//$('#pa').autocomplete({ source: pa, minLength: 3});
//$('#axl').autocomplete({ source: axl, minLength: 3});
//$('#desc').autocomplete({ source: desc, minLength: 3});
//$('#pppb').autocomplete({ source: pppb, minLength: 3});
//$('#obs').autocomplete({ source: obs, minLength: 3});
});

});