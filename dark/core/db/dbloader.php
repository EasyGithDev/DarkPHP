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

/*
  LOAD DATA [LOW_PRIORITY | CONCURRENT] [LOCAL] INFILE 'file_name'
  [REPLACE | IGNORE]
  INTO TABLE tbl_name
  [CHARACTER SET charset_name]
  [{FIELDS | COLUMNS}
  [TERMINATED BY 'string']
  [[OPTIONALLY] ENCLOSED BY 'char']
  [ESCAPED BY 'char']
  ]
  [LINES
  [STARTING BY 'string']
  [TERMINATED BY 'string']
  ]
  [IGNORE number LINES]
  [(col_name_or_user_var,...)]
  [SET col_name = expr,...]
 */

class DbLoader {

    protected $priority = '';
    protected $local = false;
    protected $insertionType = '';
    protected $tablename; //where to import to
    protected $filename;  //where to import from
    protected $headers = array(); //list of the headers
    protected $ignoredLines = 1; //number of ignored lines
    protected $fieldSeparateby = ","; //character to separate fields
    protected $fieldEncloseby = "\""; //character to enclose fields, which contain separator char into content
    protected $fieldEscapeby = "\\";  //char to escape special symbols
    protected $lineStartingby = "";
    protected $lineTerminatedby = "\n";
    protected $charsetFile; //charset of the file
    protected $saticValues;

    public function __construct($filename, $tablename, $options = array()) {
	$this->filename = $filename;
	$this->tablename = $tablename;
    }

    public function __toString() {

	$fields = '';
	if (!empty($this->fieldSeparateby))
	    $fields .= ' TERMINATED BY "' . addslashes($this->fieldSeparateby) . '"';

	if (!empty($this->fieldEncloseby))
	    $fields .= PHP_EOL . 'OPTIONALLY ENCLOSED BY "' . addslashes($this->fieldEncloseby) . '"';

	if (!empty($this->fieldEscapeby))
	    $fields .= PHP_EOL . 'ESCAPED BY "' . addslashes($this->fieldEscapeby) . '"';

	$lines = '';
	if (!empty($this->lineStartingby))
	    $lines .= 'STARTING BY "' . addslashes($this->lineStartingby) . '"';

	if (!empty($this->lineTerminatedby))
	    $lines .= 'TERMINATED BY "' . addslashes($this->lineTerminatedby) . '"';

	$search = array(
	    '[LOW_PRIORITY | CONCURRENT]',
	    '[LOCAL]',
	    'file_name',
	    '[REPLACE | IGNORE]',
	    'tbl_name',
	    '[CHARACTER SET charset_name]',
	    '[FIELDS]',
	    '[LINES]',
	    '[IGNORE number LINES]',
	    '[(col_name_or_user_var,...)]',
	    '[SET col_name = expr,...]',
	);

	$replace = array(
	    $this->priority,
	    ($this->local) ? 'LOCAL' : '',
	    $this->filename,
	    $this->insertionType,
	    $this->tablename,
	    $this->charsetFile,
	    (!empty($fields)) ? 'FIELDS ' . $fields : '',
	    (!empty($lines)) ? 'LINES ' . $lines : '',
	    ($this->ignoredLines) ? 'IGNORE 1 LINES' : '',
	    (count($this->headers)) ? implode(',', $this->headers) : '',
	    $this->saticValues
	);

	$subject = 'LOAD DATA [LOW_PRIORITY | CONCURRENT] [LOCAL] INFILE "file_name"
	    [REPLACE | IGNORE]
	    INTO TABLE tbl_name
	    [CHARACTER SET charset_name]
	    [FIELDS]
	    [LINES]
	    [IGNORE number LINES]
	    [(col_name_or_user_var,...)]
	    [SET col_name = expr,...]';

	return str_replace($search, $replace, $subject);
    }

    public function getPriority() {
	return $this->priority;
    }

    public function setPriority($priority) {
	$this->priority = $priority;
	return $this;
    }

    public function getLocal() {
	return $this->local;
    }

    public function setLocal($local) {
	$this->local = $local;
	return $this;
    }

    public function getInsertionType() {
	return $this->insertionType;
    }

    public function setInsertionType($insertionType) {
	$this->insertionType = $insertionType;
	return $this;
    }

    public function getTablename() {
	return $this->tablename;
    }

    public function setTablename($tablename) {
	$this->tablename = $tablename;
	return $this;
    }

    public function getFilename() {
	return $this->filename;
    }

    public function setFilename($filename) {
	$this->filename = $filename;
	return $this;
    }

    public function getHeaders() {
	return $this->headers;
    }

    public function setHeaders($headers) {
	$this->headers = $headers;
	return $this;
    }

    public function getIgnoredLines() {
	return $this->ignoredLines;
    }

    public function setIgnoredLines($ignoredLines) {
	$this->ignoredLines = $ignoredLines;
	return $this;
    }

    public function getFieldSeparateby() {
	return $this->fieldSeparateby;
    }

    public function setFieldSeparateby($fieldSeparateby) {
	$this->fieldSeparateby = $fieldSeparateby;
	return $this;
    }

    public function getFieldEncloseby() {
	return $this->fieldEncloseby;
    }

    public function setFieldEncloseby($fieldEncloseby) {
	$this->fieldEncloseby = $fieldEncloseby;
	return $this;
    }

    public function getFieldEscapeby() {
	return $this->fieldEscapeby;
    }

    public function setFieldEscapeby($fieldEscapeby) {
	$this->fieldEscapeby = $fieldEscapeby;
	return $this;
    }

    public function getLineStartingby() {
	return $this->lineStartingby;
    }

    public function setLineStartingby($lineStartingby) {
	$this->lineStartingby = $lineStartingby;
	return $this;
    }

    public function getLineTerminatedby() {
	return $this->lineTerminatedby;
    }

    public function setLineTerminatedby($lineTerminatedby) {
	$this->lineTerminatedby = $lineTerminatedby;
	return $this;
    }

    public function getCharsetFile() {
	return $this->charsetFile;
    }

    public function setCharsetFile($charsetFile) {
	$this->charsetFile = $charsetFile;
	return $this;
    }

    public function getSaticValues() {
	return $this->saticValues;
    }

    public function setSaticValues($saticValues) {
	$this->saticValues = $saticValues;
	return $this;
    }

}