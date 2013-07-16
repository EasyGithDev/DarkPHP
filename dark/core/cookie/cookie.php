<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark;

/**
 * Description of SecureCookie
 *
 * @author florent
 */
class Cookie {

    protected $name;
    protected $value = array();
    protected $expire = 0;
    protected $path = '/';
    protected $domain = null;
    protected $secure = false;
    protected $httponly = false;
    protected $hash = 'Q9S7aw8s';
    protected $keep = array();

    public function __construct($name) {
	if (empty($name))
	    throw new \Exception('Vous devez renseigner le nom du ccokie');
	$this->name = $name;
	$this->read();
	ob_start();
    }

    public function __destruct() {
	$this->write();
	ob_end_flush();
    }

    public function __get($key) {
	return isset($this->value[$key]) ? $this->value[$key] : FALSE;
    }

    public function __set($key, $val) {
	$this->value[$key] = htmlspecialchars(trim($val));
    }

    public function delete($key) {
	if (!isset($this->value[$key]))
	    return FALSE;
	unset($this->value[$key]);
	return TRUE;
    }

    public function clear() {
	$diff = array_diff(array_keys($this->value), $this->keep);
	foreach ($diff as $key => $val)
	    unset($this->value[$val]);
    }

    public function destroy() {
	$this->value = array();
	$this->expire = time() - 24 * 3600;
	setCookie($this->name, '', $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    public function __toString() {
	return print_r($this->value, true);
    }

    private function write() {
	$checksum = $this->checksum($this->value);
	$data = array_merge($this->value, array('hash' => $checksum));
	$serialized = serialize($data);
	$encrypted = $this->encrypt($serialized);
	setCookie($this->name, $encrypted, $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }

    private function read() {
	if (!isset($_COOKIE[$this->name]))
	    return FALSE;

	$decrypted = $this->decrypt($_COOKIE[$this->name]);
	$unserialized = unserialize($decrypted);
	$checksum = array_pop($unserialized);
	$verify = $this->checksum($unserialized);

	if ($checksum != $verify)
	    throw new Exception('Erreur sur la validation du checksum : ' . $checksum . '!=' . $verify);

	$this->value = $unserialized;
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

?>