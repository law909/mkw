<?php
namespace mkwhelpers\Exceptions;

class WrongValueTypeException extends \Exception {

	public function __construct($goodtype,$wrongtype) {
		parent::__construct($goodtype.' <-> '.$wrongtype);
	}
}