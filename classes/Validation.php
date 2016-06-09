<?php
class Validation {
	private $_passed =false,
			$_errors = array(),
			$_db = null;
			
	public function __construct(){
		$this->_db = Db::getInstance();
	}
	
	public function check($source, $items = array()){
		foreach($items as $item =>$rules){
			foreach($rules as $rule => $rule_value)	{
				
				$value = trim($source[$item]);
				$item = $item;
				if($rule === 'required' && empty($value)){
					$this->addError("Het veld {$item} moet nog worden ingevult.");
				}else{ if(!empty($value))
					{
						switch($rule) {
							case 'min':
								
								if(strlen($value) < $rule_value){
									$this->addError("{$item} moet een minimale lengte hebben van {$rule_value}");
								}
							break;
							case 'email':
							
								if(!filter_var($rule_value, FILTER_VALIDATE_EMAIL)){
									break;
								}
								else{
									$this->addError("Er is geen geldig email adress ingevoerd.");	
								}
							break;
							case 'max':
								if(strlen($value) > $rule_value){
									$this->addError("{$item} mag een  maximale lengte hebben van {$rule_value}");
								}
							break;
							case 'number':
							if(!ctype_digit($value)){
 								$this->addError("Het personeelsnummer kan alleen nummers bevatten.");
} 
							break;
							case 'matches':
								if($value != $source[$rule_value]){
									$this->addError("{$rule_value} moet het zelfde zijn als {$item}");
								}
							break;
							case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
						
							if($check->count()){
									echo'er is iets fout gegaan';
									$this->addError("Dit {$item} bestaat al.");
								}
							break;
						}
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed=true;
		}
		
		return $this;
	}
	
	private function addError($error)
	{
		$this->_errors[] = $error;
	}
	
	public function errors(){
		return $this->_errors;
	}
	
	public function passed(){
		return $this->_passed;
	}
	
}
