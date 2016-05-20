<?php

namespace WebsiteConnect\Framework\Component;

class TaskManager extends \WebsiteConnect\Framework\Component\Component /*implements \WebsiteConnect\Framework\Component\Event\IObservable*/ {

	const DEFAULT_LASTRUN_FILENAME = 'task-manager.lastrun';
	const DEFAULT_DATE_TIME_FORMAT = 'j-m-Y H:i:s';

	protected $_tasks;
	protected $_path;
	protected $_fileName;
	protected $_storage;
	protected $_timeZone;

	public function __construct($timeZone, $path, $fileName = self::DEFAULT_LASTRUN_FILENAME){

		parent::__construct();

		$this->_tasks = array();
		$this->_timeZone = $timeZone;
		$this->_path = $path;
		$this->_fileName = $fileName;

		//$this->_loadStorage();

	}

	public function __destruct(){

		//$this->_saveStorage();

	}

	public function getTasks(){
		return $this->_tasks;
	}

	public function getClonedTasks(){

		$tasks = array();

		foreach ($this->_tasks as $task)
			$tasks[] = clone $task;

		return $tasks;

	}

	public function getPath(){
		return $this->_path;
	}

	public function getFileName(){
		return $this->_fileName;
	}

	public function getTimeZone(){
		return $this->_timeZone;
	}

	public function addTask($task){

		$task->setTimeZone($this->_timeZone);
		$this->_tasks[get_class($task)] = $task;

		return $this;

	}

	public function clearTasks(){
		$this->_tasks = array();
	}

	public function runTasks(array $params = array(), array $tasks = array()){

		$results = array();

		$this->_loadStorage();

		if (count($tasks) === 0)
			$tasks = $this->_tasks;

		$eventParams = array(
			'tasks' => $tasks,
			'params' => $params,
		);

		$this->dispatchEvent('onBeforeRun', $eventParams);

		foreach ($tasks as $name => $task){
			if (is_numeric($name))
				$name = get_class($task);
			if ($task->shouldRun(array_key_exists($name, $this->_storage) ? $this->_storage[$name] : null)){
				$task->dispatchEvent('onBeforeRun');
				$results[$name] = $task->run($params);
				$this->_storage[$name] = $results[$name]['lastRun'];
				$task->dispatchEvent('onAfterRun');
			} else {
				$task->dispatchEvent('onNotRun');
			}
		}

		$this->_saveStorage();

		$eventParams['results'] = $results;
		$this->dispatchEvent('onAfterRun', $eventParams);

		return $results;

	}

	private function _loadStorage(){

		$this->_storage = array();
		$f = $this->_path . $this->_fileName;

		if (!file_exists($f))
			return;

		$items = file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		if ($items !== false){
			foreach ($items as $item){
				$arr = explode('=', $item);
				$this->_storage[$arr[0]] = \DateTime::createFromFormat(self::DEFAULT_DATE_TIME_FORMAT, $arr[1], new \DateTimeZone($this->_timeZone));
			}
		}

	}

	private function _saveStorage(){

		$f = $this->_path . $this->_fileName;

		if (file_exists($f))
			unlink($f);

		if (count($this->_storage) == 0)
			return;

		$file = new \WebsiteConnect\Framework\Component\Stream\File($f);
		$file->open(array('mode'  => 'WRITE'));

		foreach ($this->_storage as $name => $value)
			$file->put("$name={$value->format(self::DEFAULT_DATE_TIME_FORMAT)}\n");

		$file->close();

	}

}