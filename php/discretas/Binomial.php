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
class Binomial {
    //put your code here
    private $p;
    private $n;
    private $bin;
    private $binomial = array();
    private $unif01;

    function __construct($p, $n, VariablesAleatoriasUniforme $unif01) {
        $this->n = $n;
        $this->p = $p;
        $this->bin = 0.0;
        $this->unif01 = $unif01;
    }

    function generar($m){
        for($j = 0; $j < $m; $j++){
			$x = 0;
			for($i = 0; $i < $this->n; $i++) {
				if($this->unif01->nextUniforme() < $this->p)
					$x++;
			}
			$this->binomial[$j] = $x;
        }
        return $this->binomial;
    }

    public function next(){
		$x = 0;
		for($i = 0; $i < $this->n; $i++) {
			if($this->unif01->nextUniforme() < $this->p)
				$x++;
		}
		$this->bin = $x;
        return $this->bin;
	}

	public function get(){
		return $this->bin;
	}
}

?>
