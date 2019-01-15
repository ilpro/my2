<?php

class Entropy{   

	public $salt;				// Соль хеширования пароля

	public $iterationCount;		// Число итераций засоливания	

	public function __construct($salt='', $iterationCount=0){	

		$this->salt = $salt ? $salt : $this->generateRandomString();

		$this->iterationCount = $iterationCount ? $iterationCount : mt_rand(20, 100);

	}

	private function generateRandomString(){

		$salt = base64_encode(md5(mt_rand()));

		return substr($salt, 0, strlen($salt)-2);

	}	

	 // "Засоливание" пароля RFC2898

	public function getSaltedHash($password){

		$saltedHash = $password;

		if ($this->iterationCount < 1) $this->iterationCount = 1;

		for ($i = 0; $i < $this->iterationCount; $i++)

			$saltedHash = md5($this->salt . $saltedHash);

		return $saltedHash;

	}

	public function getCook(){

		$saltedHash = '';

		if ($this->iterationCount < 1) $this->iterationCount = 1;

		for ($i = 0; $i < $this->iterationCount; $i++)

			$saltedHash = md5($this->salt. $saltedHash);

		return $saltedHash;

	}



}



?>