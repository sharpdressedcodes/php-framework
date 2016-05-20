<?php

namespace WebsiteConnect\Framework\Widget\Table;

class Widget extends \WebsiteConnect\Framework\Widget\Widget {

	protected $_images;

	public function __construct(array $params = array()){

//		$config = $params['controller']->getConfig();
//		$params['dir'] = __DIR__;
//		$this->_images = $params['images'];
//
//		$params['controller']->addInlineJsonWithReceiver(
//			'carousel',
//			json_encode($this->getImages()),
//			$config['carouselDataLayerId']
//		);

		parent::__construct($params, array(), __DIR__);

	}

//	public function getImages(){
//		return $this->_images;
//	}

}