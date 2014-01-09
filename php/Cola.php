<?php
class Cola{
	private $nextEventArriveQueue;
	private $longQueue;
	private $promedioCola = array();
	private $tiempoEnCola = array();

	function __construct($nextEventArriveQueue){
		$this->longQueue = 0.0;
		$this->nextEventArriveQueue = $nextEventArriveQueue;
		$this->promedioCola[0] = array(
			'start' 	=> 0.0,
			'end' 		=> 0.0,
			'parcial' 	=> 0.0
		);

		$this->addTiempoEnCola($nextEventArriveQueue);
	}

	private function addTiempoEnCola($clock){
		array_push($this->tiempoEnCola, array('start' => $clock, 'end' => 0.0, 'diff' => 0.0));
	}

	public function updateTiempoEnCola($clock){
		$indice = count($this->tiempoEnCola) - $this->getLongQueue() - 1;
		$this->tiempoEnCola[$indice]['end'] = $clock;
		$this->tiempoEnCola[$indice]['diff'] = $this->tiempoEnCola[$indice]['end'] - $this->tiempoEnCola[$indice]['start'];
	}

	public function getLongQueue(){
		return $this->longQueue;
	}

	public function setLongQueue($longQueue){
		$this->longQueue = $longQueue;
	}

	public function lowLongQueue($clock){
		$this->promedioColaEnd($clock);
		$this->longQueue -= 1;
		$this->promedioColaStart($clock);
	}

	public function addLongQueue($clock){
		$this->promedioColaEnd($clock);
		$this->longQueue += 1;
		$this->promedioColaStart($clock);
	}

	public function getNextEventArriveQueue(){
		return $this->nextEventArriveQueue;
	}

	public function setNextEventArriveQueue($nextEventArriveQueue){
		$this->nextEventArriveQueue = $nextEventArriveQueue;
		$this->addTiempoEnCola($nextEventArriveQueue);
	}

	public function promedioColaEnd($clock){
		$this->promedioCola[$this->longQueue]['end'] = $clock;
		$this->promedioCola[$this->longQueue]['parcial'] += ($this->promedioCola[$this->longQueue]['end'] - $this->promedioCola[$this->longQueue]['start']);
	}

	private function promedioColaStart($clock){
		if(isset($this->promedioCola[$this->longQueue])){
			$this->promedioCola[$this->longQueue]['start'] = $clock;
			$this->promedioCola[$this->longQueue]['end'] = 0.0;
		}else{
			$this->promedioCola[$this->longQueue] = array(
				'start' 	=> $clock,
				'end' 		=> 0.0,
				'parcial' 	=> 0.0
			);
		}
	}

	public function promedioElementoCola($finalClock){
		$ocupacion = 0.0;
		if($finalClock == 0){
			return null;
		}else{
			for($i = 0 ; $i < count($this->promedioCola) ; $i++)
				$ocupacion += ($i*$this->promedioCola[$i]['parcial']);
			return $ocupacion/$finalClock;
		}
	}

	public function getPromedioCola(){
		return $this->promedioCola;
	}


}
?>
