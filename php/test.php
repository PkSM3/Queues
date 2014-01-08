<?php
header('Content-type: application/json');
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$n=100;

$semilla=rand();//rand(10e6 , 10e10);//Resultados decentes, con semilla = [10e6 , 10e10]
include('./VariablesAleatoriasUniforme2.php');
$va = new VariablesAleatoriasUniforme($semilla);

//include_once('./continuas/Exponencial.php');
//$exps = new Exponencial(10, $va);
//$array = $exps->generar($n);

//include_once('./continuas/Gamma.php');
//$gammas = new Gamma(10, $va);
//$array = $gammas->generar($n);

//include_once('./continuas/Normal.php');
//$mean=0;
//$sdv=1;
//$norms = new Normal($mean,$sdv, $va);
//$array = $norms->generar($n);

//include_once('./continuas/LogNormal.php');
//$mean=10;
//$sdv=4;
//$lg = new LogNormal($mean,$sdv, $va);
//$array = $lg->generar($n);

//include_once('./continuas/Beta.php');
//$a=4.0;
//$b=3.0;
//$b = new Beta($a,$b, $va);
//$array = $b->generar($n);


//include_once('./discretas/Binomial.php');
//$p=0.8;
//$bin = new Binomial($p, $va);
//$array = $bin->generar($n);

//include_once('./discretas/Geometrica.php');
//$p=0.9;
//$geo = new Geometrica($p, $va);
//$array = $geo->generar($n);

//include_once('./discretas/Poisson.php');
//$l=31;
//$poi = new Poisson($l, $va);
//$array = $poi->generar($n);

//include_once('./discretas/Uniforme.php');
//$a=5;
//$b=6;
//$ud = new Uniforme($a,$b, $va);
//$array = $ud->generar($n);


//include_once('./continuas/Uniforme.php');
//$a=5;
//$b=6;
//$ud = new Uniforme($a,$b, $va);
//$array = $ud->generar($n);

//include_once('./discretas/Uniforme.php');
//$a=5;
//$b=60;
//$ud = new Uniforme($a,$b, $va);
//$array = $ud->generar($n);

//$finalarray=array();
//$info=array();
//array_push($info,"Dinosaur");
//array_push($info,"Length");
//array_push($finalarray,$info);
//
//foreach($array as $key => $value){
//    $info=array();
//    array_push($info,"s".$key);
//    array_push($info,$value);
//    array_push($finalarray,$info);
//}
//
//echo json_encode($finalarray);

//echo print_r($array);

?>
