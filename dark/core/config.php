<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core;

/**
 * Description of Config
 *
 * @author florent
 */
class Config {

    protected static $instance = NULL;
    private $infos;

    protected function __construct() {
	$this->infos = array();
    }

    public static function create() {
	if (is_null(self::$instance)) {
	    self::$instance = new self();
	}
	return self::$instance;
    }

    public static function reset() {
	self::$instance = NULL;
	return self::create();
    }

    public function load($filepath) {
	if (file_exists($filepath))
	    $this->infos = array_merge($this->infos, parse_ini_file($filepath, TRUE));
	return $this;
    }

    public function __get($name) {
	if (!isset($this->infos[$name]))
	    return FALSE;
	return json_decode(json_encode($this->infos[$name]));
    }

}