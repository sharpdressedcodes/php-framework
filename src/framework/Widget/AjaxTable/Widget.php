<?php

namespace WebsiteConnect\Framework\Widget\AjaxTable;

class Widget extends \WebsiteConnect\Framework\Widget\Widget {

	public function __construct(array $params = array()){

//		$config = $params['controller']->getConfig();
//		$params['dir'] = __DIR__;

//		$params['pages'] = (int)ceil($params['total'] / $params['limit']);
//		$params['current'] = (int)ceil($params['start'] + $params['limit'])  / $params['limit'];
//		$params['current'] = min($params['pages'], filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
//			'options' => array(
//				'default'   => 1,
//				'min_range' => 1,
//			),
//		)));

		parent::__construct($params, array(), __DIR__);

	}

}