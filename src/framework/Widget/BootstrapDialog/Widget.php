<?php

namespace WebsiteConnect\Framework\Widget\BootstrapDialog;

class Widget extends \WebsiteConnect\Framework\Widget\Widget {

	public function __construct(array $params = array()){

//		$config = $params['controller']->getConfig();

		parent::__construct($params, array(), __DIR__);

	}

}