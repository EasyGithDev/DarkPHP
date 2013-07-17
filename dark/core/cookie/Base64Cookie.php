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
class Base64Cookie extends Cookie {

    public function __construct($params = array()) {
	parent::__construct($params);
    }

    public static function create($params = array()) {
	return new self($params);
    }

    protected function encrypt($value) {
	return base64_encode($value);
    }

    protected function decrypt($value) {
	return base64_decode($value);
    }

}