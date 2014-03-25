<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark;

/**
 * Description of Config
 *
 * @author florent
 */
class Config {

    public static function load($filePath) {
	if (is_readable($filePath))
	    return json_decode(json_encode(parse_ini_file($filePath, TRUE)));
	return FALSE;
    }

}
