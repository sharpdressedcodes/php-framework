<?php

namespace WebsiteConnect\Framework\Model;

abstract class Model extends \WebsiteConnect\Framework\Component\Component {

	protected $_table = null;
	protected $_client = null;

	const QUERY_REG_EX_FIRST_NAME_LAST_NAME = '^[a-z \'\-\.\,\(\)]{1,%d}$';
	const QUERY_REG_EX_FIRST_NAME_LAST_NAME_MODS = 'i';

	public function __construct(Array $config){

		$this->_table = $config['table'];
		$this->_client = $config['client'];

	}

	public abstract function create();
	public abstract function read();
	public abstract function update();
	public abstract function delete();

	//public static function readAll(){}

}