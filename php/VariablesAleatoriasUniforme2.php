<?php
class VariablesAleatoriasUniforme{
    private $a=16807;
    private $b=0;
    private $m=2147483647;
    private $x;
    private $u;
    private $semilla;

    function __construct($semilla = null){
        $this->semilla =($semilla != null)?$semilla: rand(1, 10000);
        $this->x = $this->semilla;
    }

    public function generar($n){
        $uniforme = array();
        $x = 0.0;
        $x = $this->semilla;
        for($i = 1; $i < $n ; $i++){
            $x = (($x*$this->a)+$this->b)%$this->m;
            $uniforme[$i] = $x/$this->m;
        }
        return $uniforme;
    }

    public function nextUniforme(){
        $x = (($this->x*$this->a)+$this->b)%$this->m;
        $this->x = $x;
        $this->u = $this->x/$this->m;
        return $this->u;
    }

    public function getSemilla(){
                return $this->semilla;
        }
}
?>
