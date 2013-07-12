<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dark\Core\Error;

/**
 * Description of ErrorHandler
 *
 * @author florent
 */
class Handler implements \SplSubject {

// Ceci est le tableau qui va contenir tous les objets qui nous observent.
    protected $observers = array();
// Attribut qui va contenir notre erreur formatée.
    protected $error;

    public function attach(\SplObserver $observer) {
	$this->observers[] = $observer;
	return $this;
    }

    public function detach(\SplObserver $observer) {
	if (is_int($key = array_search($observer, $this->observers, true))) {
	    unset($this->observers[$key]);
	}
    }

    public function getError() {
	return $this->error;
    }

    public function notify() {
	foreach ($this->observers as $observer) {
	    $observer->update($this);
	}
    }

    public function trace($errno, $errstr, $errfile, $errline) {
	$this->error = '[' . $errno . '] ' . $errstr . "\n" . 'Fichier : ' . $errfile . ' (ligne ' . $errline . ')';
	$this->notify();
    }

}