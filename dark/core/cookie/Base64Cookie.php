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

    public static function create($params = array()) {
	parent::create($params);
    }

    protected function encrypt($value) {
	return base64_encode($value);
    }

    protected function decrypt($value) {
	return base64_decode($value);
    }

}