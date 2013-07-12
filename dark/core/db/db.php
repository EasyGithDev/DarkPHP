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

    private static $instance;
    private $link;

    private function __construct($connection) {

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

    public static function getInstance($connection = '') {

	if (is_object($connection)) {
	    $name = $connection->getName();
	} elseif (is_string($connection)) {
	    $name = $connection;
	} else {
	    return FALSE;
	}

	if (isset(self::$instance[$name]))
	    return self::$instance[$name];

	if (empty($name) && count(self::$instance)) {
	    $values = array_values(self::$instance);
	    return $values[0];
	}

	if (is_object($connection)) {
	    self::$instance[$name] = new self($connection);
	    return self::$instance[$name];
	}

	return FALSE;
    }

    public function getLink() {
	return $this->link;
    }

    public function query($sql) {
	

	//mysqli_query($this->link, 'SET profiling = 1');
	
	if (!($result = mysqli_query($this->link, $sql)))
	    throw new \Exception(\mysqli_error($this->link));
	
	/*
	$show_profiles = mysqli_query($this->link, 'SHOW PROFILE');
	
	while( $row = $show_profiles->fetch_assoc() ) {
    echo '<pre>';   
    print_r( $row );    
    echo '</pre>';
}*/
	
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