<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Gamma
 *
 * @author jaime
 */

class Gamma {
    //put your code here
    private $lambda;
    private $b;
    private $unif01;

    public function __construct($lambda, $b, VariablesAleatoriasUniforme $unif01) {
        $this->lambda = $lambda;
        $this->b = $b;
        $this->unif01 = $unif01;
    }
    //~ public function __construct($lambda, VariablesAleatoriasUniforme $unif01) {
        //~ $this->lambda = $lambda;
        //~ $this->unif01 = $unif01;
    //~ }

    //Hay que ver, que onda el parametro...
    //  es necesario que x sea una va uniforme?
    //  Al parecer no
    //~ public function gen1($x){
        //~ // Algorithm:
        //~ // http://en.wikipedia.org/wiki/Lanczos_approximation
        //~ $ret=(  1.000000000190015 +
                //~ 76.18009172947146 / ($x + 1) +
                //~ -86.50532032941677 / ($x + 2) +
                //~ -1.231739572450155 / ($x + 4) +
                //~ 1.208650973866179e-3 / ($x + 5) +
                //~ -5.395239384953e-6 / ($x + 6));
        //~
        //~ return abs($ret * sqrt(2*pi())/$x * pow($x + 5.5, $x+.5) * exp(-1*$x-5.5));
    //~ }
    //~
    //~ public function generar($n){
        //~
        //~ $gamma=array();
        //~ $unif=$this->unif01->generar($n);//
        //~ for($i = 0; $i < $n; $i++){
            //~ $gamma[$i] = $this->gen1($this->unif01->nextUniforme());
        //~ }
        //~ return $gamma;
    //~ }

    public function generar($n){
        $gamma=array();
        $mult = 1.0;
        for($i = 0; $i < $n; $i++){
			for($j =  0; $j < $this->b; $j++){
				$mult *= $this->unif01->nextUniforme();
			}
            $gamma[$i] = -1.0*$this->lambda*log($mult);
            $mult = 1.0;
        }
        return $gamma;
    }

    public function setB($b){
        $this->b = $b;
    }
}

?>
