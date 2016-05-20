<?php

class Autoload {

	public static function run($className){

		$fileName  = '';
		$className = ltrim($className, '\\');

		if ($lastNsPos = strrpos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}

		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		// Setup framework path
		$fileName = str_replace('WebsiteConnect' . DIRECTORY_SEPARATOR . 'Framework', 'framework', $fileName);

		if (!file_exists($fileName)){
			$prefix = 'app' . DIRECTORY_SEPARATOR;
			if (file_exists($prefix . $fileName))
				$fileName = $prefix . $fileName;
			else
				$fileName = $prefix . 'vendor' . DIRECTORY_SEPARATOR . $fileName;
		}

		if (file_exists($fileName))
			require_once $fileName;

	}

}

spl_autoload_register('Autoload::run');