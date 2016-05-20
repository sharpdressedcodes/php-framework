<?php

namespace WebsiteConnect\Framework\Widget;

class NavbarWidget extends Widget {

	protected $_side;

	public function __construct(array $params = array()){

		$config = $params['controller']->getConfig();
		$this->_side = $params['side'];

		//$params['controller']->addNavbarContent('', $this->_side);

		parent::__construct($params, array(), $params['dir']);
		//parent::__construct($params, array(), __DIR__);

	}

//	public function render(){
//
//		///parent::render();
//
//		$controller = $this->_params['controller'];
//		$view = $this->_params['view'];
//		$dir = $this->_params['dir'];
//
////		//$output = include __DIR__ . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $view;
//		$output = include $dir . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $view;
//
//		$controller->addNavbarContent($output, $this->_side);
//
//	}

	public function getSide(){
		return $this->_side;
	}

	public function onEvent(\WebsiteConnect\Framework\Component\Event\IObservable $source, $event, array &$params = array()){

//		$controller = $this->_params['controller'];
//		$config = $controller->getConfig();
//
//		$controller->addInlineXmlWithReceiver(
//			'weather',
//			file_get_contents($this->_params['weatherFile']),
//			$config['weather']['dataLayerId']
//		);

	}

}