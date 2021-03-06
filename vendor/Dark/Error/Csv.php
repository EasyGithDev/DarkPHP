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


namespace Dark\Error;

/**
 * Description of CsvWritter
 *
 * @author florent
 */
class Csv implements \SplObserver {

    protected $dir;

    public function __construct($dir) {
	$this->dir = $dir;
    }

    public function update(\SplSubject $obj) {
	$filename = 'error-' . date('Y-m-d') . '.txt';
	$fields = array(
	    date('Y-m-d H:i:s'),
	    $obj->getErrno(),
	    $obj->getErrstr(),
	    $obj->getErrfile(),
	    $obj->getErrline());
	$handle = fopen($this->dir . DIRECTORY_SEPARATOR . $filename, 'a+');
	fputcsv($handle, $fields, ';');
	fclose($handle);
    }

}
