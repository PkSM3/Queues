var limitN=10000;

function distribucion(element){
	var distriucion = $(element).val();
	var nombre = '';
	var form = $(element).attr('id');
	switch(distriucion.toLowerCase()){
		case 'normal':
		case 'log-normal':
			removeA(form);
			removeB(form);
			removeLambda(form);
			removeP(form);
			removeN(form);

			showMu(form);
			showTeta(form);
		break;
		case 'uniforme continuia':
		case 'uniforme discreta':
		case 'beta':
			removeLambda(form);
			removeP(form);
			removeTeta(form);
			removeMu(form);
			removeN(form);

			showA(form);
			showB(form);
		break;
		case 'gamma':
			removeA(form);
			removeP(form);
			removeTeta(form);
			removeMu(form);
			removeN(form);

			showLambda(form);
			showB(form);
		break;
		case 'binomial':
			removeA(form);
			removeB(form);
			removeLambda(form);
			removeTeta(form);
			removeMu(form);

			showN(form);
			showP(form);
		break;
		case 'bernoulli':
		case 'geométrica':
			removeA(form);
			removeB(form);
			removeLambda(form);
			removeTeta(form);
			removeMu(form);
			removeN(form);

			showP(form);
		break;
		case 'poisson':
		case 'exponencial':
			removeA(form);
			removeB(form);
			removeP(form);
			removeTeta(form);
			removeMu(form);
			removeN(form);

			showLambda(form);
		break;
	}
	if(distribucion != -1){
		nombre = (form == 'cola')?'la':'el';
		$('#form_title_'+form).text('Distribución para '+nombre+' '+form+': '+distriucion);
		$('#'+form+'_tipo_distribucion').val(distriucion.toLowerCase());
		$('#form_'+form).show(200);
	}
	return false;
}

function showA(form){
	$("#"+form+"_a_label").show();
	$("#"+form+"_a").show();
}

function showB(form){
	$("#"+form+"_b_label").show();
	$("#"+form+"_b").show();
}

function showLambda(form){
	$("#"+form+"_lambda_label").show();
	$("#"+form+"_lambda").show();
}

function showMu(form){
	$("#"+form+"_mu_label").show();
	$("#"+form+"_mu").show();
}

function showP(form){
	$("#"+form+"_p_label").show();
	$("#"+form+"_p").show();
}

function showTeta(form){
	$("#"+form+"_teta_label").show();
	$("#"+form+"_teta").show();
}

function showN(form){
	$("#"+form+"_n_label").show();
	$("#"+form+"_n").show();
}


function removeN(form){
	$("#"+form+"_n_label").hide();
	$("#"+form+"_n").hide();
}

function removeA(form){
	$("#"+form+"_a_label").hide();
	$("#"+form+"_a").hide();
}

function removeB(form){
	$("#"+form+"_b_label").hide();
	$("#"+form+"_b").hide();
}

function removeLambda(form){
	$("#"+form+"_lambda_label").hide();
	$("#"+form+"_lambda").hide();
}

function removeMu(form){
	$("#"+form+"_mu_label").hide();
	$("#"+form+"_mu").hide();
}

function removeP(form){
	$("#"+form+"_p_label").hide();
	$("#"+form+"_p").hide();
}

function removeTeta(form){
	$("#"+form+"_teta_label").hide();
	$("#"+form+"_teta").hide();
}

function ejecutarSimulador(){
        $('#histo').show();
        $('#chart_div').html("<img src='img/ajax-loader.gif' />");
        logTiempos="<ul><h3>";
        logTiempos+="<li>"+getClientTime()+": Inicio de generador de VAs"+"</li>";
	$.ajax({
		method:'POST',
		url:'php/Interface.php',
		cache: false,
		data: {'cola': JSON.stringify($("#form_cola").serialize()), 'servidor': JSON.stringify($("#form_servidor").serialize()), 'iteraciones': $('#iteraciones').val(),'semilla': $('#semilla').val() },
		//~ data: $("#form_data").serialize(),
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

