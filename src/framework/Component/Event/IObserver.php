<?php

namespace WebsiteConnect\Framework\Component\Event;

interface IObserver {

	public function onEvent(\WebsiteConnect\Framework\Component\Event\IObservable $source, $event, array &$params = array());

}
