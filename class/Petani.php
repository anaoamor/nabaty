<?php
class Petani {
	private $_db = null;
	private $_formItem = [];

	public function validasiLogin($formMethod){
		$validate = new Validate($formMethod);

		$this->_formItem['email'] = $validate->setRules('email', 'Email', [
			'sanitize' => 'string',
			'required' => true,
			'email' => true
		]);

		$this->_formItem['password'] = $validate->setRules('password', 'Password', [
			'sanitize' => 'string',
			'required' => true
		]);

		if(!$validate->passed()) {
			return $validate->getError();
		} else{
			$this->_db = DB::getInstance();
			$this->_db->select('password');
			$result = $this->_db->getWhereOnce('petani', ['email', '=', $this->getItem('email')]);
			if(empty($result) || !password_verify($this->getItem('password'), $result->password)){
				$pesanError['password'] = "Maaf email atau password tidak benar";

				return $pesanError;
			}
		}
	}

	public function getItem($item){
		return isset($this->_formItem[$item]) ? $this->_formItem[$item] : '';
	}

	public function login(){
		$this->_db = DB::getInstance();
		$this->_db->select('id_petani');
		$result = $this->_db->getWhereOnce('petani', ['email', '=', $this->getItem('email')]);
		$this->_formItem['id_petani'] = $result->id_petani;
		$_SESSION['id_petani'] = $this->getItem('id_petani');
		header('Location:home.php');
	}

	public function cekPetaniSession(){
		if(!isset($_SESSION['id_petani'])){
			header("Location: login.php");
		}
	}

	public function logout(){
		unset($_SESSION['id_petani']);
		header("Location: login.php");
	}
}