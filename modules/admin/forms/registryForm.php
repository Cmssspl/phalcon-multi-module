<?php

namespace Admin\Forms;

use Phalcon\Forms\Form,
	Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Submit;

use Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\StringLength,
	Phalcon\Validation\Validator\Identical,
	Phalcon\Validation\Validator\Confirmation;

class RegistryForm extends Form {
	public function initialize() {
		//nick
		$nick = new Text('nick', array(
			'placeholder' => 'Login:'
		));

		$nick->addValidator(new PresenceOf(array(
			'message' => 'Nick jest wymagany'
		)));

		$nick->addValidator(new StringLength(array(
			'min' => 4,
			'max' => 32,
			'messageMinimum' => 'Nick jest za krótki',
			'messageMaximum' => 'Nick jest za długi'
		)));

		$this->add($nick);

		//email
		$email = new Text('email', array(
			'placeholder' => 'Email:'
		));

		$email->addValidator(new PresenceOf(array(
			'message' => 'Email jest wymagany'
		)));

		$email->addValidator(new Email(array(
			'message' => 'Email jest nie poprawny'
		)));

		$this->add($email);

		//pass
		$pass = new Password('pass', array(
			'placeholder' => 'Hasło:'
		));

		$pass->addValidator(new PresenceOf(array(
			'message' => 'Hasło jest wymagany'
		)));

		$pass->addValidator(new StringLength(array(
			'min' => 4,
			'max' => 32,
			'messageMinimum' => 'Hasło jest za krótkie',
			'messageMaximum' => 'Hasło jest za długie'
		)));

		$pass->addValidator(new Confirmation(array(
			'with' => 'pass2',
			'message' => 'Hasła muszą być identyczne'
		)));

		$this->add($pass);

		//pass2
		$pass2 = new Password('pass2', array(
			'placeholder' => 'Powtórz hasło:'
		));

		$pass2->addValidator(new PresenceOf(array(
			'message' => 'Hasło jest wymagany'
		)));

		$pass2->addValidator(new StringLength(array(
			'min' => 4,
			'max' => 32,
			'messageMinimum' => 'Hasło jest za krótkie',
			'messageMaximum' => 'Hasło jest za długie'
		)));

		$this->add($pass2);

		//imie
		$name = new Text('name', array(
			'placeholder' => 'Imie:'
		));

		$name->addValidator(new PresenceOf(array(
			'message' => 'Imie jest wymagane'
		)));

		$name->addValidator(new StringLength(array(
			'min' => 2,
			'max' => 32,
			'messageMinimum' => 'Imie jest za krótkie',
			'messageMaximum' => 'Imie jest za długie'
		)));

		$this->add($name);

		//nazwisko
		$surname = new Text('surname', array(
			'placeholder' => 'Nazwisko:'
		));

		$surname->addValidator(new PresenceOf(array(
			'message' => 'Nazwisko jest wymagane'
		)));

		$surname->addValidator(new StringLength(array(
			'min' => 2,
			'max' => 32,
			'messageMinimum' => 'Nazwisko jest za krótkie',
			'messageMaximum' => 'Nazwisko jest za długie'
		)));

		$this->add($surname);

		//telefon
		$phone = new Text('phone', array(
			'placeholder' => 'Telefon:'
		));

		$phone->addValidator(new PresenceOf(array(
			'message' => 'Telefon jest wymagany'
		)));

		$phone->addValidator(new StringLength(array(
			'min' => 2,
			'max' => 32,
			'messageMinimum' => 'Telefon jest za krótki',
			'messageMaximum' => 'Telefon jest za długi'
		)));

		$this->add($phone);

		//przycisk
		$login = new Submit('registry', array(
			'value' => 'adsadsads'
		));

		$this->add($login);
	}
}