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

    public function __construct($name, Db $db) {
	$this->name = $name;
	$this->db = $db;
    }

    /**
     * 
     * 'colums' => array(), 'where' => array(), 'groupby' => array(), 'having' => array(), 'orderby' => array()
     * 
     * @param type $colums
     * @param type $where
     * @param type $groupby
     * @param type $orderby
     */
    public function get($sql = array()) {

	$str_colums = (isset($sql['colums'])) ? implode(',', $sql['colums']) : '*';
	$str_groupby = (isset($sql['groupby'])) ? 'GROUP BY ' . implode(',', $sql['groupby']) : '';
	$str_orderby = (isset($sql['orderby'])) ? 'ORDER BY ' . implode(',', $sql['orderby']) : '';
	$str_where = '';

	$sql = 'SELECT ' . $str_colums . '
		FROM ' . $this->name .
		$str_where .
		$str_groupby .
		$str_orderby;
    }

    public function getOne($sql = array()) {
	
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