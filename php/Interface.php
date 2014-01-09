<?php

//~ ini_set('display_errors', 'On');
//~ error_reporting(E_ALL | E_STRICT);

header('Content-type: application/json');
require_once './VariablesAleatoriasUniforme2.php';
require_once './Cola.php';
require_once './Servicio.php';
//~ require_once './Simulador.php';

//Esto permite tanto post como get.
$timeStop	 	= (isset($_REQUEST['time_stop']) and !empty($_REQUEST['time_stop']))?$_REQUEST['time_stop']:null;
$numServidores	= isset($_REQUEST['num_servidores'])?$_REQUEST['num_servidores']:2;
$iterator	 	= isset($_REQUEST['iteraciones'])?$_REQUEST['iteraciones']:null;
$semilla 		= isset($_REQUEST['semilla'])?$_REQUEST['semilla']:rand();

//Inicializo mi objeto VariablesAleatoriasUniforme, el cual sigue una distribucion unifomre 0,1
$va = new VariablesAleatoriasUniforme($semilla);

//Instancio el objeto del servidor y el objeto de la cola
$queueDistribution 	= instanceObject('cola', $va);
$systemDistribution = instanceObject('servidor', $va);

// Eb caso que use el objeto
// $simulador = new Simulador($queueDistribution, $systemDistribution, $iterator, $timeStop, $numServidores);

$eventArriveQueue;
$arrayServicios = array();
for($i = 0; $i < $numServidores; $i++){
	array_push($arrayServicios, new Servicio());
}
$finalArray	= array(
	'numero_promedio_sistema' 				=> 0.0,
	'numero_promedio_cola' 					=> 0.0,
	'tiempo_promedio_sistema' 				=> 0.0,
	'tiempo_promedio_cola' 					=> 0.0,//ok
	'porcentaje_ocupacion_servidor' 		=> array(),//ok
	'numero_abandonos_sistema' 				=> 0.0,
	'tasa_clientes_efectivamente_atendidos' => 0.0,
	'tiempo_cola_compelta' 					=> 0.0,
);
$cola 		= new Cola($queueDistribution->next());
$clock	 	= 0.0;
$i 			= 0;
$efectivamente_atendidos 	= 0;

//~ var_dump($cola->getNextEventArriveQueue());exit;
$tiempo_inicio = microtime_float();
//=========================================//
//COMIENZO DE LA SIMULACION

while($i < $iterator || $clock < $timeStop){

	//Ordeno todos los servidores en base al proximo evento
	$arrayServicios = serarchNextEventSystemOutput($arrayServicios);

	//saco el tiempo del evento del servidor y de la cola
	$eventSystemOutput = $arrayServicios[0]->getNextEventSystemOutput();
	$eventArriveQueue = $cola->getNextEventArriveQueue();

	if($clock == $eventSystemOutput){
		/*
		 * Evento: salida del sistema
		 * Acciones:
		 *  Aumento el total de clientes atendidos
		 *	Si la cola posee algun elemento, lo saco de la cola y pasa al servicio,
		 *  y calculo el tiempo de salida para el elemento entrante
		 *  Si la cola no posee elementos, cambio el estado del servidor a vacio
		 */
		$arrayServicios[0]->addTotalAtendidos();
		if($cola->getLongQueue() > 0){
			$cola->lowLongQueue($clock);
			//Actualizo el tiempo en la cola
			$cola->updateTiempoEnCola($clock);

			$arrayServicios[0]->setNextEventSystemOutput($systemDistribution->next());
		}else{
			$arrayServicios[0]->setStatus(true);
			//Si el seervidor esta vacion, comienzo a contar el tiempo en el cual esta vacio
			$arrayServicios[0]->setStarEmpty($clock);
		}
	}elseif($clock == $eventArriveQueue){
		/*
		 * Evento: llegada a la cola
		 * Acciones:
		 *  Calculo el proximo evento de ingreso a la cola,
		 *  Busco si algun servidor esta vacio,
		 *  si lo esta, paso el elemento al servicio, calculando el proximo evento de salida, y seteando el estado a ocupado
		 *  Si no encuentran servidores vacios, aumento el tamaño de la cola
		 */
		$cola->setNextEventArriveQueue($queueDistribution->next());
		$indice = searchEmptySystem($arrayServicios);
		if($indice !== null){
			$arrayServicios[$indice]->setStatus(false);
			$arrayServicios[$indice]->setNextEventSystemOutput($systemDistribution->next());
			//Seteo las variables para calcular el tiempo parcial de ocupacion
			$arrayServicios[$indice]->setEndEmpty($clock);
			$arrayServicios[$indice]->ocupacionParcial();
			//Actualizo el tiempo en la cola
			$cola->updateTiempoEnCola($clock);
		}else{
			$cola->addLongQueue($clock);
		}
	}

	//Agrego al reloj el siguiente evento
	if($eventArriveQueue < $eventSystemOutput){
		$clock += $eventArriveQueue;
	}else{
		$clock += $eventSystemOutput;
	}

	//aumento el indice de iteraciones
	$i++;
}

$cola->promedioColaEnd($clock);

$promedioElementoCola = $cola->promedioElementoCola($clock);

for($i = 0; $i < $numServidores; $i++){
	$finalArray['porcentaje_ocupacion_servidor'][$i] = $arrayServicios[$i]->calcularPorcentajeOcupacion($clock);
}

$finalArray['tiempo_promedio_cola'] = $promedioElementoCola;

//=========================================//
$tiempo_fin = microtime_float();
$tiempo = $tiempo_fin - $tiempo_inicio;


if(isset($_GET['callback'])){ // Si es una petición cross-domain
   echo $_GET['callback'].'('.json_encode($finalArray).')';
}
else echo json_encode($finalArray);

//Funcion que busca un servidor vacio
function searchEmptySystem($servicios){
	$isEmpty = false;
	for($i = 0; $i < count($servicios) ; $i++){
		if($servicios[$i]->getStatus()){
			$isEmpty = true;
			break;
		}
	}
	return ($isEmpty)?$i:null;
}

//Funcion para ordenar los eventos de salida del sistema de menor a mayor (busqueda basada en mergersort)
function serarchNextEventSystemOutput($data){
	if(count($data)>1) {
		// Find out the middle of the current data set and split it there to obtain to halfs
		$data_middle = round(count($data)/2, 0, PHP_ROUND_HALF_DOWN);
		// and now for some recursive magic
		$data_part1 = serarchNextEventSystemOutput(array_slice($data, 0, $data_middle));
		$data_part2 = serarchNextEventSystemOutput(array_slice($data, $data_middle, count($data)));

		// Setup counters so we can remember which piece of data in each half we're looking at
		$counter1 = $counter2 = 0;

		// iterate over all pieces of the currently processed array, compare size & reassemble
		for ($i=0; $i<count($data); $i++) {
			// if we're done processing one half, take the rest from the 2nd half
			if($counter1 == count($data_part1)) {
				$data[$i] = $data_part2[$counter2];
				++$counter2;
			// if we're done with the 2nd half as well or as long as pieces in the first half are still smaller than the 2nd half
			} elseif (($counter2 == count($data_part2)) or ($data_part1[$counter1]->getNextEventSystemOutput() < $data_part2[$counter2]->getNextEventSystemOutput())) {
				$data[$i] = $data_part1[$counter1];
				++$counter1;
			} else {
				$data[$i] = $data_part2[$counter2];
				++$counter2;
			}
		}
	}
	return $data;
}

function microtime_float() {
    list($useg, $seg) = explode(" ", microtime());
    return ((float)$useg + (float)$seg);
}

function udate($format, $utimestamp = null) {
    if (is_null($utimestamp))
        $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);
    $milliseconds = round(($utimestamp - $timestamp) * 1000000);

    return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}


function instanceObject($tipo, $va){
	$params 			= array();
	$object 			= null;
	$request	 		= substr($_REQUEST[$tipo], 0, -1);
	$request 			= substr($request, 1);
	parse_str($request, $params);

	$a 					= isset($params[$tipo."_a"])?$params[$tipo."_a"]:null;
	$b 					= isset($params[$tipo."_b"])?$params[$tipo."_b"]:null;
	$p 					= isset($params[$tipo."_p"])?$params[$tipo."_p"]:null;
	$n 					= isset($params[$tipo."_n"])?$params[$tipo."_n"]:null;
	$teta 				= isset($params[$tipo."_teta"])?$params[$tipo."_teta"]:null;
	$mu 				= isset($params[$tipo."_mu"])?$params[$tipo."_mu"]:null;
	$lambda 			= isset($params[$tipo."_lambda"])?$params[$tipo."_lambda"]:null;
	$tipo_distribucion 	= isset($params[$tipo."_tipo_distribucion"])?$params[$tipo."_tipo_distribucion"]:null;

	switch($tipo_distribucion){
		case 'normal':
			include_once('continuas/Normal.php');
			$object = new Normal($mu, $teta, $va);
		break;
		case 'log-normal':
			include_once('continuas/LogNormal.php');
			$object = new LogNormal($mu, $teta, $va);
		break;
		case 'uniforme continuia':
			include_once('continuas/Uniforme.php');
			$object = new Uniforme($a, $b, $va);
		break;
		case 'uniforme discreta':
			include_once('discretas/Uniforme.php');
			$object = new Uniforme($a, $b, $va);
		break;
		case 'beta':
			include_once('continuas/Beta.php');
			$object = new Beta($a, $b, $va);
		break;
		case 'gamma':
			include_once('continuas/Gamma.php');
			$object = new Gamma($lambda, $b, $va);
			//~ $object = new Gamma($lambda, $va);
		break;
		case 'bernoulli':
			include_once('discretas/Bernoulli.php');
			$object = new Bernoulli($p, $va);
		break;
		case 'geométrica':
			include_once('discretas/Geometrica.php');
			$object = new Geometrica($p, $va);
		break;
		case 'binomial':
			include_once('discretas/Binomial.php');
			$object = new Binomial($p, $n, $va);
		break;
		case 'poisson':
			include_once('discretas/Poisson.php');
			$object = new Poisson($lambda, $va);
		break;
		case 'exponencial':
			include_once('continuas/Exponencial.php');
			$object = new Exponencial($lambda, $va);
		break;
		default:
			echo '<h2>Problemas al generar la distribución</h2><br>';
					echo "distribucion:".$_GET['tipo_distribucion']."<br>";
					echo "semilla:".$_GET['semilla']."<br>";
					echo "lambda:".$_GET['lambda']."<br>";
			exit;
		break;
	}
	return $object;
}

?>
