<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Registry;

/**
 * Description of Registry
 *
 * @author florent
 */
class Registry {

    protected static $instance;
    private $values;

    protected function __construct() {
	$this->values = array();
    }

    public static function create() {
	if (is_null(self::$instance)) {
	    self::$instance = new self($filename);
	}
	return self::$instance;
    }

    public function __get($name) {
	if (empty($name))
	    return FALSE;
	if (!isset($this->values[$name]))
	    return FALSE;
	return $this->values[$name];
    }

    public function __set($name, $value) {
	if (!empty($name)) {
	    $this->values[$name] = $value;
	}
    }

    public function __unset($name) {
	if (!empty($name) && isset($this->values[$name])) {
	    unset($this->values[$name]);
	}
    }

}