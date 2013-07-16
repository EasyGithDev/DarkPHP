<?php

namespace Dark\Core\Db;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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

	$str_colums = (isset($sql['colums'])) ? implode(',', $sql['colums']) : '*';
	$str_where = (isset($sql['where'])) ? 'WHERE ' . $this->db->prepareCriterias($sql['where']) : '';
	$str_groupby = (isset($sql['groupby'])) ? 'GROUP BY ' . implode(',', $sql['groupby']) : '';
	$str_orderby = (isset($sql['orderby'])) ? 'ORDER BY ' . implode(',', $sql['orderby']) : '';
	$str_limit = (isset($sql['limit'])) ? 'LIMIT ' . implode(',', $sql['limit']) : '';

	$sql = 'SELECT ' . $str_colums . '
		FROM ' . $this->name .
		$str_where .
		$str_groupby .
		$str_orderby .
		$str_limit;

	$this->db->fetchIterator($sql, $mode);
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