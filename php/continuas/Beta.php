<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Beta
 *
 * @author jaime
 */
include_once('Gamma.php');
class Beta {
    //put your code here
    private $a;
    private $b;
    private $beta = array();
    private $va;

    public function __construct($a, $b, VariablesAleatoriasUniforme $va) {
        $this->a = $a;
        $this->b = $b;
        $this->va = $va;
    }

    public function generar($n){

        $gamma = new Gamma($this->a, $this->va);
        $distribucionGamma1 = $gamma->generar($n);

        $gamma = new Gamma($this->b, $this->va);
        $distribucionGamma2 = $gamma->generar($n);

        $beta=array();
        for($i = 0; $i < $n; $i++){
            $beta[$i] = $distribucionGamma1[$i]/($distribucionGamma1[$i] + $distribucionGamma2[$i]);
        }
        return $beta;
    }

	public function next(){
	}
}

?>
