<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Db;

/**
 * Description of DBIterator
 *
 * @author florent
 */
class DbIterator implements \Iterator, \Countable {

    const MODE_ASSOC = 1;
    const MODE_ROW = 2;
    const MODE_OBJECT = 3;

    protected $result;
    protected $current;
    protected $mode;

    public function __construct($result, $mode = self::MODE_ASSOC) {
	$this->result = $result;
	$this->current = 0;
	$this->mode = $mode;
    }

    public function setMode($mode) {
	$this->mode = $mode;
    }

    public function free() {
	$this->result->free();
    }

    public function rewind() {
	$this->current = 0;
    }

    public function next() {
	$this->current++;
    }

    public function key() {
	return $this->current + 1;
    }

    public function current() {
	if ($this->mode == self::MODE_ASSOC)
	    return mysqli_fetch_assoc($this->result);
	elseif ($this->mode == self::MODE_OBJECT)
	    return mysqli_fetch_object($this->result);
	else
	    return mysqli_fetch_row($this->result);
    }

    public function valid() {
	if (!($this->current < $this->result->num_rows)) {
	    $this->result->free();
	    return FALSE;
	}
	return TRUE;
    }

    public function count() {
	return $this->result->num_rows;
    }

}