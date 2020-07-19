<?php


namespace Mini\Entity;


use ArrayAccess;
use JsonSerializable;

class BaseEntity implements JsonSerializable, ArrayAccess {
	protected $data = [];
	protected $strict = false;


	public function __construct($data = [], $strict = false) {
		$this->strict = $strict;
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$this->$k = $v;
			}
		}
	}

	public function __get($name) {
		return array_key_exists($name, $this->data) ? $this->data[$name] : null;
	}

	public function __set($name, $value) {
		if(!$this->strict || array_key_exists($name, $this->data)){
			$this->data[$name] = $value;
		}
	}

	public function __isset($name) {
		return isset($this->data[$name]);
	}

	public function __unset($name) {
		unset($this->data[$name]);
	}

	/**
	 * @return mixed
	 */
	public function jsonSerialize() {
		return $this->data;
	}

	public function offsetExists($offset) {
		return isset($this->$offset);
	}

	public function offsetGet($offset) {
		return $this->$offset;
	}

	public function offsetSet($offset, $value) {
		if($offset === null){
			$this->data[] = $value;
		} else {
			$this->$offset = $value;
		}
	}

	public function offsetUnset($offset) {
		unset($this->$offset);
	}

	public function getArrayCopy(){
		return $this->data;
	}
}