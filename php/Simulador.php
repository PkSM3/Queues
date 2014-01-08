<?php
require_once 'Cola.php';
require_once 'Servicio.php';

class Simulador{

	//VARIABLES USADAS PARA CRITERIOS DE PARADA
	private $clock;
	private $iterator;
	private $timeStop;

	private $eventArriveQueue;
	private $eventSystemOutput;
	private $queueDistribution;
	private $systemDistribution;
	private $cola;
	private $arrayServicios = array();

	function __construct($queueDistribution, $systemDistribution, $iterator, $timeStop, $numServidores = 2){
		$this->queueDistribution	= $queueDistribution;
		$this->systemDistribution	= $systemDistribution;
		$this->iterator				= $iterator;
		$this->timeStop				= $timeStop;
		for($i = 0; $i < $numServidores; $i++)
			array_push($arrayServicios, new Servicio());
	}

	public function simular(){
		$this->cola 	= new Cola($this->queueDistribution->next());
		$this->clock 	= 0.0;
		$i 				= 0;
		while($i < $this->iterator || $this->clock < $this->timeStop){

			//Ordeno todos los servidores en base al proximo evento
			$this->arrayServicios = $this->serarchNextEventSystemOutput($this->arrayServicios);

			//saco el tiempo del evento del servidor y de la cola
			$this->eventSystemOutput = $this->arrayServicios[0]->getNextEventSystemOutput();
			$this->eventArriveQueue = $this->cola->getNextEventArriveQueue();

			if($this->clock == $this->eventSystemOutput){
				/*
				 * Evento: salida del sistema
				 * Acciones:
				 *	Si la cola posee algun elemento, lo saco de la cola y pasa al servicio,
				 *  y calculo el tiempo de salida para el elemento entrante
				 *  Si la cola no posee elementos, cambio el estado del servidor a vacio
				 */
				if($this->cola->getLongQueue() > 0){
					$this->cola->lowLongQueue();
					$this->arrayServicios[0]->setNextEventSystemOutput($this->systemDistribution->next());
				}else{
					$this->arrayServicios[0]->setStatus(true);
				}
			}elseif($this->clock == $this->eventArriveQueue){
				/*
				 * Evento: llegada a la cola
				 * Acciones:
				 *  Calculo el proximo evento de ingreso a la cola,
				 *  Busco si algun servidor esta vacio,
				 *  si lo esta, paso el elemento al servicio, calculando el proximo evento de salida, y seteando el estado a ocupado
				 *  Si no encuentran servidores vacios, aumento el tamaño de la cola
				 */
				$this->cola->setNextEventArriveQueue($this->queueDistribution->next());
				$indice = $this->searchEmptySystem($this->arrayServicios);
				if($indice != null){
					$this->arrayServicios[$indice]->setStatus(false);
					$this->arrayServicios[$indice]->setNextEventSystemOutput($this->systemDistribution->next());
				}else{
					$this->cola->addLongQueue();
				}
			}

			//Agrego al reloj el siguiente evento
			if($this->eventArriveQueue < $this->eventSystemOutput){
				$this->addClock($this->eventArriveQueue);
			}else{
				$this->addClock($this->eventSystemOutput);
			}

			//aumento el indice de iteraciones
			$i++;
		}
	}

	//Funcion que busca un servidor vacio
	private function searchEmptySystem($servicios){
		$isEmpty = false;
		for($i = 0; $i < count($servicios) ; $i++){
			if($servicios[$i]->getStatus()){
				$isEmpty = true;
				break;
			}
		}
		return ($isEmpty)?$i:null;
	}

	//Funcion que aumenta el reloj
	public function addClock($clock){
		$this->clock += $clock;
	}

	//Funcion para ordenar los eventos de salida del sistema de menor a mayor (busqueda basada en mergersort)
	private function serarchNextEventSystemOutput($data){
		if(count($data)>1) {
			// Find out the middle of the current data set and split it there to obtain to halfs
			$data_middle = round(count($data)/2, 0, PHP_ROUND_HALF_DOWN);
			// and now for some recursive magic
			$data_part1 = mergesort(array_slice($data, 0, $data_middle), $key_merge);
			$data_part2 = mergesort(array_slice($data, $data_middle, count($data)), $key_merge);

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


}
?>
