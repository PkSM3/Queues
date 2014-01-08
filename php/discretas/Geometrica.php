<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Geometrica
 *
 * @author jaime
 */
class Geometrica {
    //put your code here
    private $p;
    private $geo;
    private $geometrica = array();
    private $unif01;

    function __construct($p, VariablesAleatoriasUniforme $unif01) {
        $this->p = $p;
        $this->geo = 0.0;
        $this->unif01 = $unif01;
    }

    function generar($n){
        //$restaP = 1- $this->p;
        for($i = 0; $i < $n; $i++){
            $this->geometrica[$i] = ceil(log($this->unif01->nextUniforme()) / log(1.0 - $this->p));
        }
        return $this->geometrica;
    }

    public function next(){
		$this->geo = ceil(log($this->unif01->nextUniforme()) / log(1.0 - $this->p));
        return $this->geo;
	}

	public function get(){
		return $this->geo;
	}
}

?>
