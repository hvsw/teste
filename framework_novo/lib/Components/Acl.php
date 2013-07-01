<?php
class ACL
{
    private $permissions;
	
	public function __construct() {
		
	}
	
	public function setResource($controller,$action) {
		$this->permissions[$controller][] = $action;
	}
	
	public function hasPermission($controller,$action) {
		return in_array($action,$this->permissions[$controller]);
	}
	
	public function removePermission($controller, $action = null) {
		if(!is_null($action)) {
			//
		} else {
			//
		}
	}
	
}