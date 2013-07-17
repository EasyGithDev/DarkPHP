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
class AesCookie extends Cookie {

    protected $key = 'very secret key';

    public static function create($params = array()) {
	parent::create($params);
    }

    public function encrypt($value) {

	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_OFB, '');
	$iv = substr(md5($this->_key), 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_OFB));
	$ks = mcrypt_enc_get_key_size($td);
	$key = substr(md5($this->key), 0, $ks);

	mcrypt_generic_init($td, $key, $iv);

	$encrypted = mcrypt_generic($td, $value);

	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

	return trim($encrypted);
    }

    public function decrypt($value) {

	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_OFB, '');
	$iv = substr(md5($this->_key), 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_OFB));
	$ks = mcrypt_enc_get_key_size($td);
	$key = substr(md5($this->key), 0, $ks);

	mcrypt_generic_init($td, $key, $iv);

	$decrypted = mdecrypt_generic($td, $value);

	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);

	return trim($decrypted);
    }

}