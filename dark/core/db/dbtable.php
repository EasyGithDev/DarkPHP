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
class DbTable {

    protected $name;
    protected $db;

    public function __construct($name, Db $db = NULL) {
	$this->name = $name;
	$this->db = (!is_null($db)) ? $db : DbPool::get();
    }

    public function getDb() {
	return $this->db;
    }

    public function setDb(Db $db) {
	$this->db = $db;
	return $this;
    }

    /**
     * 
     * $sql = array(
     * 		    'colums' => array(), 
     * 		    'where' => array(), 
     * 		    'groupby' => array(), 
     * 		    'having' => array(), 
     * 		    'orderby' => array(),
     * 		    'limit' => array()
     * 	)
     * 
     */
    public function fetchIterator($sql = array(), $mode = DbIterator::MODE_ASSOC) {

	$str_colums = (isset($sql['colums']) and count($sql['colums'])) ? implode(',', $sql['colums']) : '*';
	$str_where = (isset($sql['where']) and count($sql['where'])) ? ' WHERE ' . $this->db->prepareCriterias($sql['where']) : '';
	$str_groupby = (isset($sql['groupby']) and count($sql['groupby'])) ? ' GROUP BY ' . implode(',', $sql['groupby']) : '';
	$str_having = (isset($sql['groupby']) and count($sql['having'])) ? ' HAVING ' . implode(',', $sql['having']) : '';
	$str_orderby = (isset($sql['orderby']) and count($sql['orderby'])) ? ' ORDER BY ' . implode(',', $sql['orderby']) : '';
	$str_limit = (isset($sql['limit']) and count($sql['limit'])) ? ' LIMIT ' . implode(',', $sql['limit']) : '';

	$sql = 'SELECT ' . $str_colums . '
		FROM ' . $this->name .
		$str_where .
		$str_groupby .
		$str_having .
		$str_orderby .
		$str_limit;
	
	//echo $sql;
	
	return $this->db->fetchIterator($sql, $mode);
    }

    public function insert($values, $ignore = FALSE, $priority = '') {
	return $this->db->insert($this->name, $values, $ignore, $priority);
    }

    public function update($values, $where = array(), $ignore = FALSE, $priority = '') {
	return $this->db->update($this->name, $values, $where, $ignore, $priority);
    }

    public function delete($where = array(), $ignore = FALSE, $priority = '') {
	return $this->db->delete($this->name, $where, $ignore, $priority);
    }

}