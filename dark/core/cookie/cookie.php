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


namespace Dark\Core\Cookie;

/**
 * Description of SecureCookie
 *
 * @author florent
 */
class Cookie {

    protected $name = '';
    protected $values = array();
    protected $expire = 0;
    protected $path = '/';
    protected $domain = null;
    protected $secure = false;
    protected $httponly = false;
    protected $hash = 'azerty123456';
    protected $keep = array();

    public function __construct($params = array()) {
	$class_vars = get_class_vars(__CLASS__);

	foreach ($class_vars as $name => $value) {
	    if (isset($params[$name]))
		$this->$name = $params[$name];
	}

	if (empty($this->name))
	    throw new \Exception('The ccokie name can not be empty');

	$this->read();
	ob_start();
    }

    public static function create($params = array()) {
	return new self($params);
    }

    public function __destruct() {
	$this->write();
	ob_end_flush();
    }

    public function __get($key) {
	return isset($this->values[$key]) ? $this->values[$key] : FALSE;
    }

    public function __set($key, $val) {
	$this->values[$key] = htmlspecialchars(trim($val));
    }

    public function __unset($key) {
	if (!isset($this->values[$key]))
	    return FALSE;
	unset($this->values[$key]);
	return TRUE;
    }

    public function clear() {
	$diff = array_diff(array_keys($this->values), $this->keep);
	foreach ($diff as $key => $val)
	    unset($this->values[$val]);
    }

    public function destroy() {
	$this->values = array();
	$this->expire = time() - 24 * 3600;
	setCookie($this->name, '', $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    public function __toString() {
	return print_r($this->values, true);
    }

    private function write() {
	$checksum = $this->checksum($this->values);
	$data = array_merge($this->values, array('hash' => $checksum));
	$serialized = json_encode($data);
	$encrypted = $this->encrypt($serialized);
	setCookie($this->name, $encrypted, $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    private function read() {
	if (!isset($_COOKIE[$this->name]))
	    return FALSE;

	$decrypted = $this->decrypt($_COOKIE[$this->name]);
	$unserialized = json_decode($decrypted, true);
	$checksum = array_pop($unserialized);
	$verify = $this->checksum($unserialized);

	if ($checksum != $verify)
	    throw new \Exception(sprintf('Error checksum validation : %s != %s ', $checksum, $verify));

	$this->values = $unserialized;
	return TRUE;
    }

    protected function checksum($data) {
	return md5($this->hash . json_encode($data));
    }

    protected function encrypt($value) {
	return $value;
    }

    protected function decrypt($value) {
	return $value;
    }

}