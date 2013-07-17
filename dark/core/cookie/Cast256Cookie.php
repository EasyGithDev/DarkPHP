<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Cookie;

/**
 * Description of ExtendCookie
 *
 * @author florent
 */
class Cast256Cookie extends Cookie {

    protected $key = 'very secret key';

    public static function create($params = array()) {
	parent::create($params);
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