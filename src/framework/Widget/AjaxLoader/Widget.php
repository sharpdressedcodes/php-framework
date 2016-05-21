<?php

namespace WebsiteConnect\Framework\Widget\AjaxLoader;

class Widget extends \WebsiteConnect\Framework\Widget\Widget {

	const DEFAULT_CLASS_NAME = 'ajax-loader';
	const DEFAULT_BACKGROUND_COLOUR = '#afafb7';
	const DEFAULT_FORECOLOUR = '#5cffd6';

	public function __construct(array $params = array()){

//		$config = $params['controller']->getConfig();
		!array_key_exists('class', $params) && ($params['class'] = self::DEFAULT_CLASS_NAME);
		!array_key_exists('backgroundColour', $params) && ($params['backgroundColour'] = self::DEFAULT_BACKGROUND_COLOUR);
		!array_key_exists('foreColour', $params) && ($params['foreColour'] = self::DEFAULT_FORECOLOUR);

		parent::__construct($params, array(), __DIR__);

	}

}