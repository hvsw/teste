<?php
class FormField {
		
	private $attributes = array();
	private $required 	= false;
	private $label		= null;
	
	public function __construct($type,$name) {
		$this->attr('type', $type);
		$this->attr('name', $name);
	}
	
	public function attr($name,$value) {
		$this->attributes[$name] = $value;
		
		return $this;
	}
	
	public function setLabel($label) {
		$this->setLabel($label);
		
		return $this;
	}
	
	public function get($label = null) {
		if(!is_null($label))
			$this->label = $label;
		
		return $this;
	} 
	
	public function removeAttr($attr) {
		unset($this->attributes[$attr]);
	}
	
	public function getType() {
		return $this->attributes['type'];
	}
	
	public function getAttr($attr) {
		return isset($this->attributes[$attr]) ? $this->attributes[$attr] : null; 
	}
	
	public function setRequired($required = true) {
		$this->required = $required;
	}
	
	public function setValue($value) {
		$this->attributes['value'] = $value;
		
		return $this;
	}
	
	public function setChecked($status = true) {
		if($status)
			$this->attr('checked', 'checked');
		else
			$this->removeAttr('checked');
	}
	
	public function textArea() {
		$str_return = "<textarea";
		foreach($this->attributes as $name => $value) {
			if($name != 'value')		
				$str_return .= " {$name}=\"{$value}\" ";
		}
		
		$str_return .= ">";
		if(isset($this->attributes['value']))
			$str_return .= $this->attributes['value'];

		$str_return .= "</textarea>";
		return $str_return;
	}
	
	public function input() {

		$str_return = "<input ";
		foreach($this->attributes as $name => $value)		
				$str_return .= " {$name}=\"{$value}\" ";

		$str_return .= "/>";
		
		return $str_return;
	}
	
	public function label() {
		$str_label = null;
		if(!is_null($this->label)) {
			$str_label = "<label ";
			if($this->getAttr('id'))
				$str_label .= " for=\" ". $this->getAttr("id") ."\"";
			$str_label .= ">" . $this->label . "</label>";	
		}
		
		return $str_label;
	}
	
	public function __toString() {
		
		if($this->getType()  == "textarea")
			return $this->label() . $this->textArea();
		else
			return $this->label() . $this->input();
	}
	
}