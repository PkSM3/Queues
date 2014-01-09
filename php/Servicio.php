<?php
class Servicio{

	private $status;
	private $porcentajeOcupacion;
	private $statEmpty;
	private $endEmpty;
	private $totalAtendidos;
	private $nextEventSystemOutput;

	function __construct(){
		$this->nextEventSystemOutput 	= PHP_INT_MAX;
		$this->status 					= true;
		$this->statEmpty				= 0.0;
		$this->endEmpty 				= 0.0;
		$this->totalAtendidos 			= 0.0;
		$this->porcentajeOcupacion 		= 0.0;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getNextEventSystemOutput(){
		return $this->nextEventSystemOutput;
	}

	public function setNextEventSystemOutput($nextEventSystemOutput){
		$this->nextEventSystemOutput = $nextEventSystemOutput;
	}

	public function setStarEmpty($statEmpty){
		$this->statEmpty = $statEmpty;
	}

	public function setEndEmpty($endEmpty){
		$this->endEmpty = $endEmpty;
	}

	public function ocupacionParcial(){
		$this->porcentajeOcupacion += $this->endEmpty - $this->statEmpty;
	}

	public function calcularPorcentajeOcupacion($finalTime){
		$this->porcentajeOcupacion /= $finalTime;
		$this->porcentajeOcupacion *= 100;
		return round($this->porcentajeOcupacion, 2);
	}

	public function addTotalAtendidos(){
		$this->totalAtendidos++;
	}
}
?>
