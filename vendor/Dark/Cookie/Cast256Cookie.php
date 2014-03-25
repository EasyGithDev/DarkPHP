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
 * Description of ExtendCookie
 *
 * @author florent
 */
class Cast256Cookie extends Cookie {

    protected $key = 'very secret key';

    public function __construct($params = array()) {
	parent::__construct($params);
    }

    public static function create($params = array()) {
	return new self($params);
    }

    protected function decrypt($value) {
	$c_t = base64_decode($value);
	$iv = substr(md5($this->key), 0, mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB));
	$p_t = mcrypt_cfb(MCRYPT_CAST_256, $this->key, $c_t, MCRYPT_DECRYPT, $iv);
	return trim($p_t);
    }

    protected function encrypt($value) {
	$value = trim($value);
	$iv = substr(md5($this->key), 0, mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB));
	$c_t = mcrypt_cfb(MCRYPT_CAST_256, $this->key, $value, MCRYPT_ENCRYPT, $iv);
	return( base64_encode($c_t));
    }

}