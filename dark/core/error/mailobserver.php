<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Error;

/**
 * Description of MailSender
 *
 * @author florent
 */
class MailObserver implements \SplObserver {

    protected $email;

    public function __construct($email) {
	$this->email = $email;
    }

    public function update(\SplSubject $obj) {
	$subject = $_SERVER['PHP_HOST'] . ' Error';
	$body = "Erreur n° : " . $obj->getErrno() . "\n" .
		"Message : " . $obj->getErrstr() . "\n" .
		"File : " . $obj->getErrfile() . "\n" .
		"Line : " . $obj->getErrline();
	mail($this->email, $subject, $body);
    }

}