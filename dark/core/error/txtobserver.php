<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Error;

/**
 * Description of CsvWritter
 *
 * @author florent
 */
class TxtObserver implements \SplObserver {

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