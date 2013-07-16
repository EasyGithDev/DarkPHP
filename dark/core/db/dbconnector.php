<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Db;

/**
 * Description of Connection
 *
 * @author florent
 */
class DbConnector {

    protected $name;
    protected $host;
    protected $user;
    protected $password;
    protected $port;
    protected $dbname;
    protected $charset;
    protected $profiling;

    public static function create($array = array()) {

	$instance = new self();
	$class_vars = get_class_vars(__CLASS__);

	foreach ($class_vars as $name => $value) {
	    if (isset($array[$name]))
		$instance->$name = $array[$name];
	}

	return $instance;
    }

    public function getHost() {
	return $this->host;
    }

    public function setHost($host) {
	$this->host = $host;
	return $this;
    }

    public function getUser() {
	return $this->user;
    }

    public function setUser($user) {
	$this->user = $user;
	return $this;
    }

    public function getPassword() {
	return $this->password;
    }

    public function setPassword($password) {
	$this->password = $password;
	return $this;
    }

    public function getPort() {
	return $this->port;
    }

    public function setPort($port) {
	$this->port = $port;
	return $this;
    }

    public function getDbname() {
	return $this->dbname;
    }

    public function setDbname($dbname) {
	$this->dbname = $dbname;
	return $this;
    }

    public function getName() {
	return $this->name;
    }

    public function setName($name) {
	$this->name = $name;
	return $this;
    }

    public function getCharset() {
	return $this->charset;
    }

    public function setCharset($charset) {
	$this->charset = $charset;
	return $this;
    }

    public function getProfiling() {
	return $this->profiling;
    }

    public function setProfiling($profiling) {
	$this->profiling = $profiling;
    }

}