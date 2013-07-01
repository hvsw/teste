<?php
class Form {
	
	private $elements = array();
	private $action;
	private $attributes;
	
	public function __construct($action,$method = 'POST',$name = null) {
		$this->attributes['action'] = $action;
		$this->attributes['method'] = $method;
		if(!is_null($name))
			$this->attributes['name'] = $name;
	}
	
	public function createElement($type,$name) {
		$this->elements[$name] = new FormField($type,$name);
		return $this->elements[$name];
	}
	
	public function createRadio($name,$value) {
		$this->elements[$name][$value] = new FormField('radio',$name);
		$this->elements[$name][$value] -> attr('value',$value);
		return $this->elements[$name][$value];
	}
	
	public function radio($name,$value,$label = null) {
		return isset($this->elements[$name][$value]) ? $this->elements[$name][$value]->get($label) : null;
	}
	
	public function field($name,$label = null) {
		return isset($this->elements[$name]) ? $this->elements[$name]->get($label) : null;
	}
	
	public function initForm() {
		$str_form = "<form ";
		foreach($this->attributes as $name => $value)
			$str_form .= " {$name}=\"{$value}\" ";
		
		$str_form .= ">";
		
		return $str_form;
	}
	
	public function endForm() {
		return "</form>";	
	}
	public function getValues($values) {
		foreach($values as $name => $value){
			if(isset($this->elements[$name])) {

				$field = is_array($this->elements[$name]) ? $this->elements[$name][$value] : $this->elements[$name];
				$tipo  = $field->getType();
				
				if($tipo == "checkbox" || $tipo == "radio")
					$field->setChecked();
				else if($tipo != "password" )
					$field->setValue($value);

			}
			
		}
	}
	
	public function __toString() {
		$str .= $this->initForm();
		foreach($this->elements as $name => $element) {
			$str .= $element->__toString();
		}
		$str .= $this->endForm();
		
		return $str;
	}
	
}