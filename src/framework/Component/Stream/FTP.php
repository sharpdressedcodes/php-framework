<?php

namespace WebsiteConnect\Framework\Component\Stream;

class FTP extends Socket {

	const DEFAULT_PORT = 21;
	const DEFAULT_USER = 'anonymous';
	const DEFAULT_PASSWORD = 'test@example.com';

	public function __construct($server = null, $port = self::DEFAULT_PORT){
		parent::__construct($server, $port);
	}

	public function open(array $params = array()){

		$user = self::DEFAULT_USER;
		$password = self::DEFAULT_PASSWORD;

		$this->close();
		$this->_handle = ftp_connect($this->_server, $this->_port);

		if (!is_bool($this->_handle)){
			if (array_key_exists('user', $params))
				$user = $params['user'];
			if (array_key_exists('password', $params))
				$password = $params['password'];
			return ftp_login($this->_handle, $user, $password);
		}

		return false;

	}

	public function close(){

		if (!is_null($this->_handle)){
			ftp_close($this->_handle);
			$this->_handle = null;
		}

	}

	public function put($data){

		if (is_null($this->_handle))
			return false;

		return ftp_put($this->_handle, $data['remoteFile'], $data['localFile'], FTP_ASCII);

	}

	public function get($data = null){

		if (is_null($this->_handle))
			return false;

		return ftp_get($this->_handle, $data['localFile'], $data['remoteFile'], FTP_ASCII);

	}

	public function getLine(){return null;}

}