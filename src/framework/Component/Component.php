<?php

namespace WebsiteConnect\Framework\Component;

abstract class Component implements \WebsiteConnect\Framework\Component\Event\IObservable {

	protected $_observers;

	public function __construct(){
		$this->_observers = array();
	}

	public function addObserver(\WebsiteConnect\Framework\Component\Event\IObserver $observer, $event){

		$events = is_array($event) ? $event : array($event);

		if (is_null($this->_observers))
			throw new \Exception('Concrete class must call parent::__construct in Constructor');

		foreach ($events as $event){

			if (!array_key_exists($event, $this->_observers))
				$this->_observers[$event] = array();

			$this->_observers[$event][] = $observer;

		}

		return $this;

	}

	public function dispatchEvent($event, array &$params = array()){

		if (is_null($this->_observers)){
			throw new \Exception('Concrete class must call parent::__construct in Constructor');
		}

		if (!array_key_exists($event, $this->_observers)){
			return true;
		}

		foreach ($this->_observers[$event] as $observer) {
			if ($observer->onEvent($this, $event, $params) === false) {
				return false;
			}
		}

		return true;

	}

}