<?php

namespace WebsiteConnect\Framework\Widget;

abstract class Widget
	extends \WebsiteConnect\Framework\Component\Component
	implements \WebsiteConnect\Framework\Component\Event\IObserver {

	protected $_params;
	protected $_tasks;
	protected $_config;

	public function __construct(array $params = array(), array $tasks = array(), $dir){

		$params['dir'] = $dir;

		$this->_params = $params;
		$this->_tasks = $tasks;
		$this->_config = $params['controller']->getConfig();
		$taskManager = $params['controller']->getTaskManager();

		if (!is_null($taskManager) && $params['controller']->getRunTaskManager() && count($tasks) > 0)
			$taskManager->runTasks($params, $tasks);

	}

	public function render(){

//		$controller = $this->_params['controller'];
//		$view = $this->_params['view'];
//		$dir = $this->_params['dir'];
		extract($this->_params);

		include $dir . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $view;

	}

	public function onEvent(\WebsiteConnect\Framework\Component\Event\IObservable $source, $event, array &$params = array()){
		//
	}

}