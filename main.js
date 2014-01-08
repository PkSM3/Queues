var limitN=10000;

function distribucion(element){
    console.log("en distribucion");
	var distriucion = $(element).text();
	switch(distriucion.toLowerCase()){
		case 'normal':
		case 'log-normal':
			removeA();
			removeB();
			removeLambda();
			removeP();
			removeN();

			showMu();
			showTeta();
		break;
		case 'uniforme continuia':
		case 'uniforme discreta':
		case 'beta':
			removeLambda();
			removeP();
			removeTeta();
			removeMu();
			removeN();

			showA();
			showB();
		break;
		case 'gamma':
			removeA();
			removeP();
			removeTeta();
			removeMu();
			removeN();

			showLambda();
			showB();
		break;
		case 'binomial':
			removeA();
			removeB();
			removeLambda();
			removeTeta();
			removeMu();

			showN();
			showP();
		break;
		case 'bernoulli':
		case 'geométrica':
			removeA();
			removeB();
			removeLambda();
			removeTeta();
			removeMu();
			removeN();

			showP();
		break;
		case 'poisson':
		case 'exponencial':
			removeA();
			removeB();
			removeP();
			removeTeta();
			removeMu();
			removeN();

			showLambda();
		break;
	}
	$('#distribucion').text('Distribución: '+distriucion);
	$('#tipo_distribucion').val(distriucion.toLowerCase());
	$('#text_inicio').hide(200);
	$('#form_data').show(200);
	return false;
}

function showA(){
	$("#a_label").show();
	$("#a").show();
}

function showB(){
	$("#b_label").show();
	$("#b").show();
}

function showLambda(){
	$("#lambda_label").show();
	$("#lambda").show();
}

function showMu(){
	$("#mu_label").show();
	$("#mu").show();
}

function showP(){
	$("#p_label").show();
	$("#p").show();
}

function showTeta(){
	$("#teta_label").show();
	$("#teta").show();
}

function showN(){
	$("#n_label").show();
	$("#n").show();
}


function removeN(){
	$("#n_label").hide();
	$("#n").hide();
}

function removeA(){
	$("#a_label").hide();
	$("#a").hide();
}

function removeB(){
	$("#b_label").hide();
	$("#b").hide();
}

function removeLambda(){
	$("#lambda_label").hide();
	$("#lambda").hide();
}

function removeMu(){
	$("#mu_label").hide();
	$("#mu").hide();
}

function removeP(){
	$("#p_label").hide();
	$("#p").hide();
}

function removeTeta(){
	$("#teta_label").hide();
	$("#teta").hide();
}

function ejecutarSimulador(){

        $('#result').hide();
        $('#histo').show();
        $('#chart_div').html("<img src='img/ajax-loader.gif' />");
        logTiempos="<ul><h3>";
        logTiempos+="<li>"+getClientTime()+": Inicio de generador de VAs"+"</li>";
	$.ajax({
		method:'GET',
		url:'php/Interface.php',
		cache: false,
                contentType: "application/json",
		data: $("#form_data").serialize(),
		success:function(res){
			$('#result').empty();
			//$('#result').html(res["data"]);
			//$('#result').show();
                        logTiempos+="<li>"+res["time"]+" [s]: Tiempo que demora servidor en generar las VAs"+"</li>";
                        logTiempos+="<li>"+res["inicioRecupJSON"]+": Cliente comienza a recuperar JSON del servidor"+"</li>";
                        logTiempos+="<li>"+getClientTime()+": Cliente recupera JSON del servidor"+"</li>";

                        google.load("visualization", "1", {packages:["corechart"]});
                        google.setOnLoadCallback(drawChart(res["data"]));

                        logTiempos+="<li>"+getClientTime()+": Cliente termina de dibujar histograma con GChart"+"</li>";
                        logTiempos+="</h3></ul";
			$('#result').html(logTiempos);
			$('#result').show();
		},
		error:function(res){
                        alert("mal");
                }
	});
        return false;
}

function getClientTime(){
    var totalSec = new Date().getTime() / 1000;
    var d = new Date();
    var hours = d.getHours();
    var minutes = parseInt( totalSec / 60 ) % 60;
    var seconds = totalSec % 60;
    var result = (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
    return result;
}

function pr(msg){
    console.log(msg);
}
//
function drawChart(res){
    var data=google.visualization.arrayToDataTable(res);
    iteraciones=document.getElementById("iteraciones").value;
    bool=(iteraciones>limitN)?false:true;
    var options = {
          title: 'Valores generados',
          legend: { position: 'none' },
          enableInteractivity: bool
    };

    var chart = new google.visualization.Histogram(document.getElementById('chart_div'));
    chart.draw(data, options);

}
