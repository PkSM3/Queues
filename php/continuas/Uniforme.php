<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Uniforme
 *
 * @author jaime
 */
class Uniforme {
    //put your code here
    private $a;
    private $b;
    private $uniformes = array();
    private $uniforme;
    private $va;

    public function __construct($a, $b, VariablesAleatoriasUniforme $va) {
        $this->a = $a;
        $this->b = $b;
        $this->va = $va;
        $this->uniforme = 0.0;
    }

    public function generar($n){
        $diff = $this->b-$this->a;
        for($i = 0; $i  < $n; $i++){
            $this->uniformes[$i] = $this->a + ($diff*$this->va->nextUniforme());
        }
        return $this->uniformes;
    }

    public function next(){
        $this->uniforme = $this->a + (($this->b-$this->a)*$this->va->nextUniforme());
        return $this->uniforme;
	}

	public function get(){
		return $this->uniforme;
	}
}

?>
