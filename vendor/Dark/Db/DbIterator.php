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
