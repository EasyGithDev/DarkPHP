<?php

namespace Dark\Db;

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
class Db {

    protected $link;
    protected $debug;

    public function __construct(DbConnector $connector) {

	$this->link = mysqli_connect($connector->getHost(), $connector->getUser(), $connector->getPassword(), $connector->getDbname(), $connector->getPort());

	/* Vérification de la connexion */
	if (mysqli_connect_errno()) {
	    printf("Échec de la connexion : %s\n", mysqli_connect_error());
	    die();
	}

	/* Modification du jeu de résultats */
	$charset = $connector->getCharset();
	if (!empty($charset))
	    if (!mysqli_set_charset($this->link, $charset)) {
		printf("Erreur lors du chargement du jeu de caractères %s : %s\n", $charset, mysqli_error($this->link));
		die();
	    }
	$this->debug = FALSE;
    }

    public function prepareValues($values) {
	$str_values = '';
	foreach ($values as $k => $v)
	    $str_values .= $k . '="' . $this->quote($v) . '",';
	return rtrim($str_values, ',');
    }

    public function prepareCriterias($where) {
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

    public function setLink($link) {
	$this->link = $link;
	return $this;
    }

    public function getLink() {
	return $this->link;
    }

    public function getDebug() {
	return $this->debug;
    }

    public function setDebug($debug) {
	$this->debug = $debug;
	return $this;
    }

    public function query($sql) {

	if ($this->debug)
	    return $sql;

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
		return FALSE;
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
		return FALSE;
	}

	$sql = 'DELETE ' . $priority . (($ignore) ? ' IGNORE' : '') .
		' FROM ' . $table .
		(($str_where) ? ' WHERE ' . $str_where : '');

	return $this->query($sql);
    }

}
