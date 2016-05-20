<?php

namespace WebsiteConnect\Framework\Component\Stream;

class HTTPS extends Socket {

	const DEFAULT_PORT = 443;

	public function __construct($server = null, $port = self::DEFAULT_PORT){
		parent::__construct($server, $port);
	}

	public function open(array $params = array()){

		$this->close();

		$this->_handle = curl_init();
		curl_setopt($this->_handle, CURLOPT_URL, 'https://' . $this->_server . $params['url']);
		curl_setopt($this->_handle, CURLOPT_PORT, $this->_port);
		curl_setopt($this->_handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->_handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_handle, CURLOPT_FOLLOWLOCATION, true);

		if (array_key_exists('user', $params) && array_key_exists('password', $params))
			curl_setopt($this->_handle, CURLOPT_USERPWD, "{$params['user']}:{$params['password']}");

		if (array_key_exists('headers', $params))
			foreach ($params['headers'] as $header)
				curl_setopt($this->_handle, CURLOPT_HEADER, $header);

	}

	public function close(){

		if (!is_null($this->_handle)){
			curl_close($this->_handle);
			$this->_handle = null;
		}

	}

	public function put($data){

		if (is_null($this->_handle))
			return false;

		if (array_key_exists('method', $data) && $data['method'] === 'post'){
			curl_setopt($this->_handle, CURLOPT_POST, count($data['data']));
			curl_setopt($this->_handle, CURLOPT_POSTFIELDS, $data['data']);
		}

		return array_key_exists('data', $data) ? strlen($data['data']) : true;

	}

	public function get($data = null){

		if (is_null($this->_handle))
			return false;

		$result = curl_exec($this->_handle);
		return $result;

	}

	public function getLine(){return null;}

}