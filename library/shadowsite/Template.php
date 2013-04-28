<?php

namespace Ss;

class Template
{
	private $di;

	private $name;

	public function getDi() {
		return $this->di;
	}

	public function setDi($di) {
		$this->di = $di;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}
}