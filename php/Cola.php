<?php
class Cola{
	private $nextEventArriveQueue;
	private $longQueue;
	private $promedioCola = array();

	function __construct($nextEventArriveQueue){
		$this->longQueue = 0.0;
		$this->nextEventArriveQueue = $nextEventArriveQueue;
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
	}

	private function promedioColaEnd($clock){
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
		for($i = 0 ; $i < count($promedioCola) ; $i++){
			$ocupacion += ($i*$promedioCola[$i]['parcial']);
		}
		return $ocupacion/$finalClock;
	}


}
?>
