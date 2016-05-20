<?php

namespace WebsiteConnect\Framework\Component\Event;

interface IObservable {

	public function addObserver(\WebsiteConnect\Framework\Component\Event\IObserver $observer, $event);
	public function dispatchEvent($event, array &$params = array());

}