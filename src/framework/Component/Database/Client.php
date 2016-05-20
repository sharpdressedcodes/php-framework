<?php

namespace WebsiteConnect\Framework\Component\Database;

class Client extends \WebsiteConnect\FrameWork\Component\Component {

	private $_lastInsertId = null;
	private $_lastDatabase = null;
	private $_lastError = null;

	protected $_host = null;
	protected $_port = null;
	protected $_username = null;
	protected $_password = null;
	protected $_type = null;
	protected $_persistent = false;
	protected $_pdo = null;
	protected $_connected = false;

	public function __construct(Array $config = array()){
		$this->_loadConfig($config);
	}

	public function __destruct(){
		$this->disconnect();
	}

	private function _loadConfig($config){

		array_key_exists('host', $config) && ($this->_host = $config['host']);
		array_key_exists('port', $config) && ($this->_port = $config['port']);
		array_key_exists('username', $config) && ($this->_username = $config['username']);
		array_key_exists('password', $config) && ($this->_password = $config['password']);
		array_key_exists('type', $config) && ($this->_type = $config['type']);
		array_key_exists('database', $config) && ($this->_lastDatabase = $config['database']);
		array_key_exists('persistent', $config) && ($this->_persistent = $config['persistent']);

	}

	public function getLastDatabase(){
		return $this->_lastDatabase;
	}

	public function getLastInsertId(){
		return $this->_lastInsertId;
	}

	public function getHost(){
		return $this->_host;
	}

	public function getPort(){
		return $this->_port;
	}

	public function getUsername(){
		return $this->_username;
	}

	public function getPassword(){
		return $this->_password;
	}

	public function getType(){
		return $this->_type;
	}

	public function getPdo(){
		return $this->_pdo;
	}

	public function isConnected(){
		return $this->_connected;
	}

	public function connect(Array $config = array()){

		$this->_loadConfig($config);

		$this->_lastInsertId = null;
		$this->_lastError = null;

		try {

			$this->_pdo = new \PDO("{$this->_type}:host={$this->_host};port={$this->_port};dbname={$this->_lastDatabase}",
				$this->_username,
				$this->_password,
				array(
					\PDO::ATTR_PERSISTENT => $this->_persistent
				)
			);

			$this->_connected = true;

		} catch (\PDOException $ex){

			$this->_lastError = $ex;
			$this->_connected = false;

		}

		return $this->_connected;

	}

	public function disconnect(){
		$this->_connected = false;
		$this->_pdo = null;
	}

	public function query($sql){
		return $this->_pdo->query($sql);
	}

	public function prepare($sql){
		return $this->_pdo->prepare($sql);
	}

	public function quote($sql){
		return $this->_pdo->quote($sql);
	}

}