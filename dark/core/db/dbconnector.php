<?php

/**
 * DarkPHP - a PHP library of components
 *
 * @author      Florent Brusciano
 * @copyright   2013 Florent Brusciano
 * @version     1.0.0
 * @package     Dark\Core
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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