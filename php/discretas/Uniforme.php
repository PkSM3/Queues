<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Uniforme {

    private $a;
    private $b;
    private $uniforme;
    private $unif01;

    public function __construct($a,$b, VariablesAleatoriasUniforme $unif01) {
        $this->a = $a;
        $this->b = $b;
        $this->uniforme = 0.0;
        $this->unif01 = $unif01;
    }

    public function generar($n){
        $unifD=array();
        for($i = 0; $i < $n; $i++){
            $unifD[$i] = ceil($this->a + (($this->b-$this->a+1)*$this->unif01->nextUniforme()));
        }
        return $unifD;
    }

    public function next(){
        $this->uniforme = ceil($this->a + (($this->b-$this->a+1)*$this->unif01->nextUniforme()));
        return $this->uniforme;
	}

	public function get(){
		return $this->uniforme;
	}
}

?>
