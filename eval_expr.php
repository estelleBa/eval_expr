<?php

Class Eval_Expr {
	public function __construct($expr){
		$this->expr = $expr;
		$this->final_expr = '';
		$this->stored_expr = null;
		$this->loop = true;
	}
	public function eval(){
		if(!is_string($this->expr)) return "parameter must be a string\n";
		$res = $this->loop($this->expr, false);
		echo "\n\ncalcul: $this->final_expr\n\nresult: $res\n\n";
	}
	public function loop($expr, $parentheses=false){
		$parentheses_expr = '';
		for($i=0; $i<strlen($expr); $i++){
			if($this->loop){
				if(preg_match_all("/(\(|\)\d+|-|\+|\/|\*|\%)/", $expr)){
					if($expr[$i]==='('){
						$this->stored_expr = $i;
						$this->loop(substr($expr, $i+1), true);
					}
					else if($expr[$i]===')' && $parentheses){
						$this->stored_expr++;
						$this->loop = false;
						$parentheses = false;
						$this->final_expr .= $this->calcul($parentheses_expr);
						if(strlen($this->expr) > $this->stored_expr){
							$this->loop = true;
							$this->loop(substr($this->expr, $this->stored_expr+1));
							$this->loop = false;
							$this->stored_expr = null;
						}
						else break;
					}
					else {
						if(!$parentheses){
							$this->final_expr .= $expr[$i];
						}
						else {
							$this->stored_expr++;
							$parentheses_expr .= $expr[$i];
						}
					}
				}
				else return "function loop: syntax error\n\n";
			}
		}
		return $this->calcul($this->final_expr);
	}
	public function calcul($expr){
		$res = '';
		preg_match_all("/(\d+|-|\+|\/|\*|\%)/", $expr, $output);
		if(!preg_match("/(\d+)/", $output[0][0])) return;
		//var_dump($output[0]);
		//var_dump(array_search('*', $output[0]));
		// if($index = array_search('*', $output[0])){
		// 	//var_dump($output[0], $index, 'TOO');
		// 	$toto = array_splice($output[0], $index-1, 3);var_dump($toto);
		// 	$res .= $this->switch($toto);
		// }
		$res .= $this->switch($output[0]);
		return $res;
	}

	public function switch($array){//var_dump($array);
		$val = $array[0];
		$n = '';
		for($i=1; $i<count($array);$i++){
			switch($array[$i]){
				case "-":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val - $array[$i+1];
					break;
				case "+":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val + $array[$i+1];
					break;
				case "*":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val * $array[$i+1];
					break;
				case "/":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val / $array[$i+1];
					break;
				case "%":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val % $array[$i+1];
					break;
				default:
					break;
			}
		}
		return $val;
	}
}
$expr = new Eval_Expr("4+2*(5+6)");
$expr->eval();
