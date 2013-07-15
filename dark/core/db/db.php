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
class Db {

    private $link;

    public function __construct(DbConnection $connection) {

	$this->link = mysqli_connect($connection->getHost(), $connection->getUser(), $connection->getPassword(), $connection->getPort());

	/* Vérification de la connexion */
	if (mysqli_connect_errno()) {
	    printf("Échec de la connexion : %s\n", mysqli_connect_error());
	    die();
	}

	$charset = $connection->getCharset();
	if (!empty($charset))
	    if (!mysqli_set_charset($charset)) {
		printf("Erreur lors du chargement du jeu de caractères utf8 : %s\n", mysqli_error($this->link));
		die();
	    }
	mysqli_select_db($this->link, $connection->getDb());
    }

    private function prepareValues($values) {
	$str_values = '';
	foreach ($values as $k => $v)
	    $str_values .= $k . '="' . $this->quote($v) . '",';
	return rtrim($str_values, ',');
    }

    private function prepareCriterias($where) {
	$str_where = '';
	foreach ($where as $criterias) {
	    $size = count($criterias);
	    if ($size == 1 OR $size == 3) {
		if ($size == 3)
		    $criterias[2] = '"' . $this->quote($criterias[2]) . '"';
		$str_where .= implode(' ', $criterias) . ' ';
	    } else {
		return FALSE;
	    }
	}
	return trim($str_where);
    }

    public function __destruct() {
	/* Fermeture de la connexion */
	mysqli_close($this->link);
    }

    public function getLink() {
	return $this->link;
    }

    public function query($sql, $bind = array()) {
	
	if(count($bind)) {
	    $replace = array_map(function($elt) {return $this->quote($elt);}, $bind);
	    $search = array_fill(0, count($replace), '?');
	    $sql = str_replace($search, $replace, $sql);
	}
	
	if (!($result = mysqli_query($this->link, $sql)))
	    throw new \Exception(\mysqli_error($this->link));

	return $result;
    }

    public function fetchIterator($sql, $mode = DbIterator::MODE_ASSOC) {
	$result = $this->query($sql);
	if (is_object($result))
	    return new DbIterator($result, $mode);
	return $result;
    }

    public function fetchOne($sql) {
	$result = $this->query($sql);
	if (is_object($result))
	    return mysqli_fetch_assoc($result);
	return $result;
    }

    public function fetchAll($sql) {
	$result = $this->query($sql);
	if (is_object($result)) {
	    $rows = array();
	    while ($row = mysqli_fetch_assoc($result))
		$rows[] = $row;
	    return $rows;
	}
	return $result;
    }

    public function fetchJSON($sql) {
	$result = $this->fetchAll($sql);
	if (is_array($result))
	    return json_encode($result);
	return $result;
    }

    public function quote($str) {
	return mysqli_real_escape_string($this->link, $str);
    }

    public function getInsertId() {
	return mysqli_insert_id($this->link);
    }

    public function getAffectedRows() {
	return mysqli_affected_rows($this->link);
    }

    public function insert($table, $values, $ignore = FALSE, $priority = '') {

	if (empty($table) OR !is_array($values))
	    return FALSE;

	if (!count($values))
	    return FALSE;

	$sql = 'INSERT ' . $priority . (($ignore) ? ' IGNORE' : '') .
		' INTO ' . $table .
		' SET ' . $this->prepareValues($values);

	return $this->query($sql);
    }

    public function update($table, $values, $where = array(), $ignore = FALSE, $priority = '') {

	if (empty($table) OR !is_array($values) OR !is_array($where))
	    return FALSE;

	if (!count($values))
	    return FALSE;

	$str_where = '';
	if (count($where)) {
	    if (!($str_where = $this->prepareCriterias($where)))
		throw new \Exception('Criterias parsing error');
	}

	$sql = 'UPDATE ' . $priority . (($ignore) ? ' IGNORE' : '') .
		' ' . $table .
		' SET ' . $this->prepareValues($values) .
		(($str_where) ? ' WHERE ' . $str_where : '');

	return $this->query($sql);
    }

    public function delete($table, $where = array(), $ignore = FALSE, $priority = '') {

	if (empty($table) OR !is_array($where))
	    return FALSE;

	$str_where = '';
	if (count($where)) {
	    if (!($str_where = $this->prepareCriterias($where)))
		throw new \Exception('Criterias parsing error');
	}


	$sql = 'DELETE ' . $priority . (($ignore) ? ' IGNORE' : '') .
		' FROM ' . $table .
		(($str_where) ? ' WHERE ' . $str_where : '');

	return $this->query($sql);
    }

}