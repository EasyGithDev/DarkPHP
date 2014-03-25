<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core;

/**
 * Description of debuging
 *
 * @author florent
 */
class Profiler {

    protected static $instance = NULL;
    protected $start = 0;
    protected $end = 0;

    protected function __construct() {
	$this->start = microtime(true);
    }
    
    public static function create() {
	if (is_null(self::$instance)) {
	    self::$instance = new self;
	}
	return self::$instance;
    }

    public function getTime() {
	$this->end = microtime(true);
	return $this->end - $this->start;
    }

    public function __toString() {

	return 'TIME ' . PHP_EOL . $this->getTime() . PHP_EOL .
		'MEMORY ' . PHP_EOL . memory_get_usage() . PHP_EOL .
		'POST ' . PHP_EOL . '<pre>' . var_export($_POST, 1) . '</pre>' . PHP_EOL .
		'GET ' . PHP_EOL . '<pre>' . var_export($_GET, 1) . '<pre>' . PHP_EOL .
		'SESSION ' . PHP_EOL . '<pre>' . (isset($_SESSION) ? var_export($_SESSION, 1) : '') . '<pre>' . PHP_EOL .
		'COOKIE ' . PHP_EOL . '<pre>' . (isset($_COOKIE) ? var_export($_COOKIE, 1) : '') . '<pre>' . PHP_EOL;
    }

}
