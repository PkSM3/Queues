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
	$('#result').html("<img src='img/ajax-loader.gif' />");
	logTiempos="<ul><h3>";
	$.ajax({
		method:'POST',
		url:'php/Interface.php',
		cache: false,
		data: {'cola': JSON.stringify($("#form_cola").serialize()), 'servidor': JSON.stringify($("#form_servidor").serialize()), 'iteraciones': $('#iteraciones').val(),'semilla': $('#semilla').val(), 'num_servidores': $('#num_servidores').val(),'time_stop': $('#time_stop').val() },
		success:function(res){
			$('#result').empty();
			logTiempos+="<li>"+res['numero_promedio_sistema']+": Número promedio en el sistema</li>";
			logTiempos+="<li>"+res["numero_promedio_cola"]+": Número promedio en la cola</li>";
			logTiempos+="<li>"+res['tiempo_promedio_sistema']+": Tiempo promedio en el sistema</li>";
			logTiempos+="<li>"+res["tiempo_promedio_cola"]+": Tiempo promedio cliente en la cola</li>";
			logTiempos+="<li>Porcentaje de ocupacion del sistema<ul>";
			$.each(res['porcentaje_ocupacion_servidor'], function(index, indice){
				logTiempos+="<li>"+indice+": Sistema "+ (index+1) +"</li>";
			});
			logTiempos+="</ul></li>";
			//~ logTiempos+="<li>"+res['porcentaje_ocupacion_servidor']+": Porcentaje de ocupacion del sistema</li>";
			logTiempos+="<li>"+res["numero_abandonos_sistema"]+": Cliente abandonan el sistema</li>";
			logTiempos+="<li>"+res['tasa_clientes_efectivamente_atendidos']+": Tasa clientes efectivamente atendidos</li>";
			logTiempos+="<li>"+res["tiempo_cola_compelta"]+": Tiempo cola completa</li>";
			logTiempos+="</h3></ul>";
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

