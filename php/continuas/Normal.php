<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Normal
 *
 * @author jaime
 */
class Normal {
    //put your code here
    private $mean;
    private $sdv;
    private $normal;
    private $unif01;

    public function __construct($mean,$sdv, VariablesAleatoriasUniforme $unif01) {
        $this->mean = $mean;
        $this->sdv = $sdv;
        $this->unif01 = $unif01;
        $this->normal = 0.0;
    }

    public function gen1(){

        $u1 = $this->unif01->nextUniforme();  //Uniform 1
        $u2 = $this->unif01->nextUniforme();  //Uniform 2

        $r = sqrt( -2.0*log($u1) );
        $theta = 2.0*pi()*$u2;
        return $this->mean + $this->sdv * $r * sin($theta);
        //el retorno debe ser positivo y mayor que 1!!
    }

    public function generar($n){
        $normal=array();
        for($i = 1; $i < $n; $i++){
            $normal[$i]=$this->gen1();
        }
        return $normal;
    }

    public function next(){
        $u1 = $this->unif01->nextUniforme();  //Uniform 1
        $u2 = $this->unif01->nextUniforme();  //Uniform 2

        $r = sqrt( -2.0*log($u1) );
        $theta = 2.0*pi()*$u2;
        $this->normal = $this->mean + $this->sdv * $r * sin($theta);
        return $this->normal;
	}

	public function get(){
		return $this->normal;
	}
}

?>
