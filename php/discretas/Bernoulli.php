<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of binomial
 *
 * @author jaime
 */
class Bernoulli {
    //put your code here
    private $p;
    private $binomial = array();
    private $bin;
    private $unif01;

    function __construct($p, VariablesAleatoriasUniforme $unif01) {
        $this->p = $p;
        $this->bin = 0.0;
        $this->unif01 = $unif01;
    }

    function generar($n){
        for($i = 0; $i < $n; $i++){
            $this->binomial[$i] = ($this->unif01->nextUniforme() <= $this->p)?1:0;
        }
        return $this->binomial;
    }

    public function next(){
        $this->bin = ($this->unif01->nextUniforme() <= $this->p)?1:0;
        return $this->bin;
	}

	public function get(){
		return $this->bin;
	}
}

?>
