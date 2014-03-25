<?php

namespace Dark\Core\Db;

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

/**
 * Description of Db
 *
 * @author florent
 */
class DbMapper extends DbTable {

    protected $idx = 'id';
    protected $id = NULL;
    protected $data = array();

    public function __construct($name, Db $db = NULL) {
	parent::__construct($name, $db);
    }

    public function setId($id) {
	$this->id = $id;
	return $this;
    }

    public function getId() {
	return $this->id;
    }

    public function load() {
	if (!is_null($this->id)) {
	    $sql = array('where' => array($this->idx, '=', $this->id));
	    $row = $this->fetchOne($sql);

	    foreach ($row as $key => $val) {
		$this->data[$key] = $val;
	    }
	    unset($this->data[$this->idx]);
	}
	return $this;
    }

    public function save() {
	if (is_null($this->id)) {
	    $this->id = $this->insert($this->data);
	} else {
	    $where = array($this->idx, '=', $this->id);
	    $this->update($this->data, $where);
	}
	return $this;
    }

    public function __get($key) {
	return isset($this->data[$key]) ? $this->data[$key] : FALSE;
    }

    public function __set($key, $value) {
	$this->data[$key] = $value;
    }

}
