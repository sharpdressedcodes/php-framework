<?php

namespace Controller;

class Main extends \WebsiteConnect\Framework\Controller\Controller {

	public function __construct(array $params = array()){

		parent::__construct($params);

		$this->_layoutFile = ($this->_config['debug'] ? 'Debug' : 'Main') . '.php';

	}

	public function rules(){
		return array();
	}

}