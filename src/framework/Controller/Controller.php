<?php

namespace WebsiteConnect\Framework\Controller;

use \WebsiteConnect\Framework\Component\TaskManager as TaskManager;

abstract class Controller extends \WebsiteConnect\Framework\Component\Component {

	const FRAMEWORK_NAME = 'Website Connect Framework';

	const DEFAULT_TYPE_CSS = 'text/css';
	const DEFAULT_TYPE_JS = 'text/javascript';
	const DEFAULT_TYPE_JSON = 'application/json';
	const DEFAULT_TYPE_XML = 'application/xml';

	const DATA_LAYER_PREFIX = 'website-connect-imports-';

	const PAGE_HEAD_BEGIN_1 = 0;
	const PAGE_HEAD_BEGIN_2 = 1;
	const PAGE_HEAD_BEGIN_3 = 2;
	const PAGE_HEAD_END_1 = 3;
	const PAGE_HEAD_END_2 = 4;
	const PAGE_BODY_BEGIN = 5;
	const PAGE_BODY_END_1 = 6;
	const PAGE_BODY_END_2 = 7;
	const PAGE_BODY_END_3 = 8;
	const PAGE_BODY_END_4 = 9;

	const NAVBAR_SIDE_LEFT = 0;
	const NAVBAR_SIDE_CENTER = 1;
	const NAVBAR_SIDE_RIGHT = 2;

	protected $_config;
	protected $_viewPath;
	protected $_layoutFile;
	protected $_styles;
	protected $_inlineStyles;
	protected $_scripts;
	protected $_inlineScripts;
	protected $_taskManager;
	protected $_runTaskManager;
	protected $_navbarWidgets;
	protected $_pluginManager;
	protected $_runPluginManager;

	public function __construct(array $params = array()){

		parent::__construct();

		@session_start();

		$this->_config = array();
		$ds = DIRECTORY_SEPARATOR;

		$this->addConfig('basePath', $params['basePath']);
		$this->addConfig('appPath', $params['basePath'] . 'app' . $ds);
		$this->addConfig('dataPath', $params['basePath'] . 'app' . $ds . 'Data' . $ds);
		$this->addConfig('frameworkPath', $params['frameworkPath']);
		$this->addConfig('frameworkDataPath', $params['frameworkPath'] . 'Data' . $ds);
		$this->addConfig('frameworkSkeletonPath', $params['frameworkPath'] . 'Component' . $ds . 'Generator' . $ds . 'Skeletons' . $ds);
		$this->loadConfig($this->_config['frameworkPath'] . 'Config');
		$this->loadConfig($this->_config['appPath'] . 'Config');

		$this->_viewPath = null;
		$this->_layoutFile = null;
		$this->_styles = array();
		$this->_inlineStyles = array();
		$this->_scripts = array();
		$this->_inlineScripts = array();
		$this->_taskManager = new TaskManager($this->_config['timeZone'], $this->_config['dataPath']);
		$this->_runTaskManager = array_key_exists('runTaskManager', $params) ? $params['runTaskManager'] : true;
		$this->_navbarWidgets = array();
		$this->_widgets = array();
		$this->_pluginManager = new \WebsiteConnect\Framework\Component\PluginManager($this);
		$this->_runPluginManager = array_key_exists('runPluginManager', $params) ? $params['runPluginManager'] : true;

	}

	public function getStyles(){
		return $this->_styles;
	}

	public function getScripts(){
		return $this->_scripts;
	}

	public function getInlineStyles(){
		return $this->_inlineStyles;
	}

	public function getInlineScripts(){
		return $this->_inlineScripts;
	}

	public function setViewPath($newValue){
		$this->_viewPath = $newValue;
		return $this;
	}

	public function getViewPath(){
		return $this->_viewPath;
	}

	public function getLayoutFile(){
		return $this->_layoutFile;
	}

	public function getConfig(){
		return $this->_config;
	}

	public function getTaskManager(){
		return $this->_taskManager;
	}

	public function getRunTaskManager(){
		return $this->_runTaskManager;
	}

	public function getPluginManager(){
		return $this->_pluginManager;
	}

	public function getRunPluginManager(){
		return $this->_runPluginManager;
	}

	public function clearNavbarWidgets($side = null){

		if (is_null($side))
			$this->_navbarWidgets = array();
		else
			$this->_navbarWidgets[$side] = '';

	}

	public function addNavbarWidget($widget, $side = self::NAVBAR_SIDE_CENTER){
		$this->_navbarWidgets[$side] = $widget;
	}

	public function getNavbarWidgets($side = null){
		return is_null($side) ? $this->_navbarWidgets : $this->_navbarWidgets[$side];
	}

	public function rules(){
		return array();
	}

	public static function getPostVars(array $keys, $sanitise = false){
		return self::getVars($_POST, $keys, $sanitise);
	}

	public static function getGetVars(array $keys, $sanitise = false){
		return self::getVars($_GET, $keys, $sanitise);
	}

	public static function getServerVars(array $keys, $sanitise = false){
		return self::getVars($_SERVER, $keys, $sanitise);
	}

	public static function getCookieVars(array $keys, $sanitise = false){
		return self::getVars($_COOKIE, $keys, $sanitise);
	}

	public static function getVars(array $subject, array $keys, $sanitise = false){

		$result = array();

		foreach ($keys as $key)
			$result[$key] = self::getVar($subject, $key, $sanitise);

		return $result;

	}

	public static function getPostVar($key, $sanitise = false){
		return self::getVar($_POST, $key, $sanitise);
	}

	public static function getGetVar($key, $sanitise = false){
		return self::getVar($_GET, $key, $sanitise);
	}

	public static function getServerVar($key, $sanitise = false){
		return self::getVar($_SERVER, $key, $sanitise);
	}

	public static function getCookieVar($key, $sanitise = false){
		return self::getVar($_COOKIE, $key, $sanitise);
	}

	public static function getVar(array $subject, $key, $sanitise = false){
		return array_key_exists($key, $subject) ? ($sanitise ? self::sanitiseString($subject[$key]) : $subject[$key]) : null;
	}

	public static function sanitiseString($str){
		return filter_var($str, FILTER_SANITIZE_STRING);
	}

	public function loadConfig($path = null){

		$result = false;
		$path = is_null($path) ? dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Config' : $path;

		if (!file_exists($path))
			return $result;

		$directory = new \RecursiveDirectoryIterator($path);
		$iterator = new \RecursiveIteratorIterator($directory);
		$regex = new \RegexIterator($iterator, '/^.+[^\.default]\.php$/i', \RecursiveRegexIterator::GET_MATCH);

		$main = $path . DIRECTORY_SEPARATOR . 'Main.php';
		if (file_exists($main))
			$this->_config = array_merge($this->_config, include $main);

		// Overwrite global config
		foreach ($regex as $file){
			if ($file[0] !== $main){
				try {
					$this->_config = array_merge($this->_config, include is_array($file) ? $file[0] : $file);
				} catch (\Exception $e){}
			}
		}

		return $result;

	}

	public function addConfig($key, $value){

		$this->_config[$key] = $value;

		return $this;

	}

	public function removeConfig($key){

		if (array_key_exists($key, $this->_config))
			unset($this->_config[$key]);

		return $this;

	}

	public function sendResponse($data, $isError = false){

		$accept = array_key_exists('HTTP_ACCEPT', $_SERVER) ? $_SERVER['HTTP_ACCEPT'] : '';
		//$response = $isError ? "Error: $data" : $data;
		$response = $data;

		if (strpos($accept, 'json') !== false){
			header('Content-Type: application/json; charset=utf-8');
			$response = json_encode($data);
//		} elseif (strpos($accept, 'xml') !== false){
//			header('Content-Type: text/xml');
		} elseif (strpos($accept, 'text/html') !== false){
			header('Content-Type: text/html; charset=utf-8');
		} elseif (strpos($accept, '*/*') !== false){
			header('Content-Type: text/plain; charset=utf-8');
		}

		echo $response;

	}

	public function redirect($url, $statusCode = 301){

		header("Location: $url", true, $statusCode);
		die('<h1>This page has moved.</h1><div>Click <a href="' . $url . '" title="' . $url . '">here</a> if you are not redirected</div></div>');

	}

	public static function isJsonRequest(){

		$s = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;

		if (is_null($s))
			$s = isset($_SERVER['X_REQUESTED_WITH']) ? $_SERVER['X_REQUESTED_WITH'] : null;

		return is_null($s) ? false : strtolower($s) === 'xmlhttprequest';

	}

	public static function keyExists($keys, $key){

		foreach ($keys as $keyObject)
			if ($keyObject['key'] === $key)
				return true;

		return false;

	}

	public static function splitAction($action){

		$result = array();
		$pos = strpos($action, '/');

		if ($pos !== false){
			$arr = explode('/', $action);
			$result['controller'] = $arr[0];
			$result['action'] = $arr[1];
		}

		return $result;

	}

	public function run(){

		$get = self::getGetVars(array(
			'action',
			'key'
		));

		$arr = self::splitAction($get['action']);
		count($arr) > 0 && ($get['action'] = $arr['action']);

		$authRequired = false;

		foreach ($this->rules() as $key => $rule){
			if (strtolower($get['action']) === strtolower($key)){
				if (array_key_exists('auth', $rule) && $rule['auth']){
					$authRequired = true;
					break;
				}
			}
		}

		if ($authRequired){
			if (is_null($get['key'])){
				$this->sendResponse('no key', true);
				return;
			} elseif (!self::keyExists($this->_config['keys'], $get['key'])){
				$this->sendResponse('key failed', true);
				return;
			}
		}

		$s = ucwords(str_replace('-', ' ', $get['action']));
		$s = str_replace(' ', '', $s);
		$action = "run{$s}Action";
		$exists = method_exists($this, $action) && is_callable(array($this, $action));

		$params = array(
			'action' => $action,
			'exists' => $exists,
			'get' => $get,
		);

		if ($this->_runTaskManager)
			$this->_taskManager->runTasks($params);

		if ($this->_runPluginManager)
			$this->_pluginManager->runPlugins($params);

		if ($this->onBeforeRun($params)){
			call_user_func_array(array($this, $exists ? $action : 'noAction'), array($get));
			$this->onAfterRun();
		}

	}

	public function clearCookies(){

		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}

	}

	public function render(array $params = array()){

		if (is_null($this->_viewPath))
			$this->_viewPath = $this->_config['appPath'] . 'View' . DIRECTORY_SEPARATOR;

		$layout = array_key_exists('layout', $params) ? $params['layout'] : null;
		$views = array_key_exists('views', $params) ? $params['views'] : null;
		$showControls = true;
		$eventParams = array(
			'layout' => $layout,
			'views' => $views,
			'params' => $params,
			'title' => self::FRAMEWORK_NAME,
			'showControls' => $showControls,
		);

		$this->dispatchEvent('onBeforeRender', $eventParams);

		if ($this->onBeforeRender($eventParams)){

			$navbar = '';
			$controls = '';

			extract($eventParams);

			if (is_null($layout))
				throw new \Exception('No layout specified');

			elseif (!is_array($views) || (is_array($views) && count($views) === 0))
				throw new \Exception('No view specified');

			$rendered = array();

			foreach ($views as $view)
				$rendered[$view['var']] = $this->renderPartial($this->_getView($view['view']), $view['viewParams']);

			ob_start();
			extract($rendered);

			if (count($this->_navbarWidgets) === 0)
				$navbar = '';

			$this->dispatchEvent('onBeforeRenderLayout', $eventParams);

			extract($eventParams);

			if (!$showControls)
				$controls = '';

			include $this->_getView($layout);

			$this->dispatchEvent('onAfterRenderLayout', $eventParams);

			$output = ob_get_contents();
			ob_end_clean();

			$output = self::normaliseLineBreaks($this->_reorderResources($output));

			echo $output;

		}

		$this->dispatchEvent('onAfterRender', $eventParams);
		$this->onAfterRender($eventParams);

	}

	private function _getResourcesForLocation($location, $matches){

		$results = array();

		for ($i = 0, $i_ = count($matches[0]); $i < $i_; $i++)
			if ((int)$location === (int)$matches[3][$i])
				$results[] = $matches[0][$i];

		return $results;

	}

	public static function convertLocationToString($location){

		$result = 'PAGE_HEAD_BEGIN_1';

		switch ($location){
			case self::PAGE_HEAD_BEGIN_1:
				$result = 'PAGE_HEAD_BEGIN_1';
				break;
			case self::PAGE_HEAD_BEGIN_2:
				$result = 'PAGE_HEAD_BEGIN_2';
				break;
			case self::PAGE_HEAD_BEGIN_3:
				$result = 'PAGE_HEAD_BEGIN_3';
				break;
			case self::PAGE_HEAD_END_1:
				$result = 'PAGE_HEAD_END_1';
				break;
			case self::PAGE_HEAD_END_2:
				$result = 'PAGE_HEAD_END_2';
				break;
			case self::PAGE_BODY_BEGIN:
				$result = 'PAGE_BODY_BEGIN';
				break;
			case self::PAGE_BODY_END_1:
				$result = 'PAGE_BODY_END_1';
				break;
			case self::PAGE_BODY_END_2:
				$result = 'PAGE_BODY_END_2';
				break;
			case self::PAGE_BODY_END_3:
				$result = 'PAGE_BODY_END_3';
				break;
			case self::PAGE_BODY_END_4:
				$result = 'PAGE_BODY_END_4';
				break;

		}

		return $result;

	}

	public static function convertStringToLocation($location){

		$result = self::PAGE_HEAD_BEGIN_1;

		switch ($location){
			case 'PAGE_HEAD_BEGIN_1':
				$result = self::PAGE_HEAD_BEGIN_1;
				break;
			case 'PAGE_HEAD_BEGIN_2':
				$result = self::PAGE_HEAD_BEGIN_2;
				break;
			case 'PAGE_HEAD_BEGIN_3':
				$result = self::PAGE_HEAD_BEGIN_3;
				break;
			case 'PAGE_HEAD_END_1':
				$result = self::PAGE_HEAD_END_1;
				break;
			case 'PAGE_HEAD_END_2':
				$result = self::PAGE_HEAD_END_2;
				break;
			case 'PAGE_BODY_BEGIN':
				$result = self::PAGE_BODY_BEGIN;
				break;
			case 'PAGE_BODY_END_1':
				$result = self::PAGE_BODY_END_1;
				break;
			case 'PAGE_BODY_END_2':
				$result = self::PAGE_BODY_END_2;
				break;
			case 'PAGE_BODY_END_3':
				$result = self::PAGE_BODY_END_3;
				break;
			case 'PAGE_BODY_END_4':
				$result = self::PAGE_BODY_END_4;
				break;

		}

		return $result;

	}

	public static function normaliseLineBreaks($data){

		$data = preg_replace("/(\r\n|\r|\n){2,}/", "\n", $data);
		$data = preg_replace("/(\r\n[ |\t]{1,}\r\n|\r[ |\t]{1,}\r|\n[ |\t]{1,}\n)/", "\n", $data);

		return $data;

	}

	private function _moveResources($data, $resources, $location, $stripLocation = true){

		for ($i = 0, $i_ = count($resources); $i < $i_; $i++){
			$data = str_replace($resources[$i], '', $data);
			$resources[$i] = preg_replace('/ data\-location\="\d+"/', '', $resources[$i]);

			if (preg_match('/ src\="((.*?)\.js)"/', $resources[$i], $matches))
				$resources[$i] = str_replace($matches[0], ' src="' . $this->_chooseFileName($matches[1]) . '"', $resources[$i]);

			if (preg_match('/ href\="((.*?)\.css)"/', $resources[$i], $matches))
				$resources[$i] = str_replace($matches[0], ' href="' . $this->_chooseFileName($matches[1]) . '"', $resources[$i]);

		}

		return str_replace($location, ($stripLocation ? '' : $location) . implode("\n", $resources), $data);

	}

	private function _reorderResources($data){

		$result = $this->_removeDuplicateResources($data);
		$scriptRegex = '/<script[^>]((.*?)data\-location\="(.*?)".*?)?>([\s\S]*?)<\/script>/';
		$styleRegex = '/<style[^>]((.*?)data\-location\="(.*?)".*?)?>([\s\S]*?)<\/style>/';
		$linkRegex = '/<link[^>]((.*?)data\-location\="(.*?)".*?)?(\s?)(\/?)>/';
		$locationRegex = '/<!\-\- (PAGE_.*?) \- DO NOT DELETE THIS COMMENT! \-\->/';

		if (preg_match_all($locationRegex, $result, $locations)){

			$hasScripts = preg_match_all($scriptRegex, $result, $scriptMatches);
			$hasStyles = preg_match_all($styleRegex, $result, $styleMatches);
			$hasLinks = preg_match_all($linkRegex, $result, $linkMatches);

			for ($i = 0, $i_ = count($locations[0]); $i < $i_; $i++){
				if (!$hasScripts && !$hasStyles && !$hasLinks){
					$result = str_replace($locations[0][$i], '', $result);
				} else {
					$styles = $this->_getResourcesForLocation(self::convertStringToLocation($locations[1][$i]), $styleMatches);
					$scripts = $this->_getResourcesForLocation(self::convertStringToLocation($locations[1][$i]), $scriptMatches);
					$links = $this->_getResourcesForLocation(self::convertStringToLocation($locations[1][$i]), $linkMatches);
					$result = $this->_moveResources($result, $styles, $locations[0][$i], false);
					$result = $this->_moveResources($result, $scripts, $locations[0][$i], false);
					$result = $this->_moveResources($result, $links, $locations[0][$i]);
				}
			}

		}

		return $result;

	}

	private function _removeDuplicateResources($resources){

		if (is_array($resources)){
			return array_unique($resources, SORT_REGULAR);
		} else {

			$result = $resources;
			$scriptRegex = '/<script([^>].*?)?>([\s\S]*?)<\/script>/';
			$styleRegex = '/<style([^>].*?)?>([\s\S]*?)<\/style>/';
			$linkRegex = '/<link[^>]((.*?)data\-location\="(.*?)".*?)?(\s?)(\/?)>/';
			$hasScripts = preg_match_all($scriptRegex, $result, $scriptMatches);
			$hasStyles = preg_match_all($styleRegex, $result, $styleMatches);
			$hasLinks = preg_match_all($linkRegex, $result, $linkMatches);

			if ($hasScripts)
				$result = $this->_removeDuplicateResourceFromString($result, $scriptMatches);

			if ($hasStyles)
				$result = $this->_removeDuplicateResourceFromString($result, $styleMatches);

			if ($hasLinks)
				$result = $this->_removeDuplicateResourceFromString($result, $linkMatches);

			return $result;

		}
	}

	private function _removeDuplicateResourceFromString($data, $matches){

		$s = '---%%%%%%REPLACEMENT%%%%%%---';
		$temp = array();
		$result = $data;

		for ($i = 0, $i_= count($matches[0]); $i < $i_; $i++){
			try {
				@$temp[$matches[0][$i]]++;
			} catch (\Exception $e){
				$temp[$matches[0][$i]] = 1;
			}
			for ($j = $i + 1, $j_= count($matches[0]); $j < $j_; $j++)
				if ($matches[0][$i] === $matches[0][$j])
					$temp[$matches[0][$i]]++;
		}

		foreach ($temp as $key => $value){
			if ($value > 1){
				$result = preg_replace('~' . preg_quote($key) . '~', $s, $result, 1);
				$result = str_replace($key, '', $result);
				$result = str_replace($s, $key, $result);
			}
		}

		return $result;

	}

	private function _getView($view){

		$ext = '.php';
		$v = $view;

		if (substr($v, strlen($v) - strlen($ext)) !== $ext)
			$v .= $ext;

		if (strpos($v, DIRECTORY_SEPARATOR) === false || !file_exists($v)){
			$f = $this->_viewPath . $v;
			if (!file_exists($f))
				$f = $this->_viewPath . 'Layout' . DIRECTORY_SEPARATOR . $v;
			if (!file_exists($f))
				throw new \Exception("View {$view} $f not found");
			$v = $f;
		}

		return $v;

	}

	public function renderPartial($view, $viewParams){

		$output = null;
		$params = array('view' => $view, 'params' => $viewParams);

		$this->dispatchEvent('onBeforeRenderPartial', $params);
		if ($this->onBeforeRenderPartial($params)){
			ob_start();
			extract($viewParams);
			include $view;
			$output = ob_get_contents();
			ob_end_clean();
		}

		$params['output'] = $output;
		$this->dispatchEvent('onAfterRenderPartial', $params);
		$output = $this->onAfterRenderPartial($params);

		return $output;

	}

	public function renderWidget(\WebsiteConnect\Framework\Widget\Widget $widget){

		$params = array('widget' => $widget);

		if ($this->dispatchEvent('onBeforeRenderWidget', $params)){
			$widget->render();
			$this->dispatchEvent('onAfterRenderWidget', $params);
		}

	}

	public function addInlineXml($code, $elementId, $location = self::PAGE_HEAD_END_1, $options = array()){

		$script = array(
			'code' => preg_replace('/(\r\n|\n|\r|\t)/', '', $code),
			'location' => $location,
			'type' => self::DEFAULT_TYPE_XML,
			'options' => array_merge($options, array(
				'id' => $elementId
			)),
		);

		$this->_inlineScripts[] = $script;

	}

	public function addInlineJson($code, $elementId, $location = self::PAGE_HEAD_END_1, $options = array()){

		$script = array(
			'code' => preg_replace('/(\r\n|\n|\r|\t)/', '', $code),
			'location' => $location,
			'type' => self::DEFAULT_TYPE_JSON,
			'options' => array_merge($options, array(
				'id' => $elementId
			)),
		);

		$this->_inlineScripts[] = $script;

	}

	public function addInlineXmlWithReceiver($name, $code, $elementId, $location = self::PAGE_HEAD_END_1, $options = array()){

		$this->addInlineScript('homeVision.dataLayer.imports.' . $name . 'Id = "' . $elementId . '";', self::PAGE_BODY_END_2);
		$this->addInlineXml($code, $elementId, $location, $options);

	}

	public function addInlineJsonWithReceiver($name, $code, $elementId, $location = self::PAGE_HEAD_END_1, $options = array()){

		$this->addInlineScript('homeVision.dataLayer.imports.' . $name . 'Id = "' . $elementId . '";', self::PAGE_BODY_END_2);
		$this->addInlineJson($code, $elementId, $location, $options);

	}

	public function addInlineScript($code, $location = self::PAGE_BODY_END_4, $type = self::DEFAULT_TYPE_JS, $options = array()){

		$script = array(
			'code' => $code,
			'location' => $location,
			'type' => $type,
			'options' => $options,
		);

		$this->_inlineScripts[] = $script;

	}

	public function addInlineStyle($code, $location = self::PAGE_BODY_END_4, $type = self::DEFAULT_TYPE_CSS, $options = array()){

		$style = array(
			'code' => $code,
			'location' => $location,
			'type' => $type,
			'options' => $options,
		);

		$this->_inlineStyles[] = $style;

	}

	public function addScript($file, $location = self::PAGE_BODY_END_4, $type = self::DEFAULT_TYPE_JS, $options = array()){

		$script = array(
			'file' => $file,
			'location' => $location,
			'type' => $type,
			'options' => $options,
		);

		$this->_scripts[] = $script;

	}

	public function addStyle($file, $location = self::PAGE_BODY_END_4, $type = self::DEFAULT_TYPE_CSS, $media = 'all', $rel = 'stylesheet', $options = array()){

		$script = array(
			'file' => $file,
			'location' => $location,
			'type' => $type,
			'media' => $media,
			'rel' => $rel,
			'options' => $options,
		);

		$this->_styles[] = $script;

	}

	private function _chooseFileName($fileName){

		$result = $fileName;
		$type = substr($fileName, strlen($fileName) - 3);

		if (substr($type, 0, 1) === '.')
			$type = substr($type, 1);

		if (!$this->_config['debug']){

			$s = ".$type";
			$m = ".min$s";

			if (substr($fileName, strlen($fileName) - strlen($m), strlen($m)) !== $m)
				$result = substr($fileName, 0, strlen($fileName) - strlen($s)) . $m;
		}

		return $result;

	}

	public function getScriptsForLocation($location){

		$result = array();

		foreach ($this->_scripts as $script){
			if ($script['location'] === $location){
				$output = '<script src="' . $this->_chooseFileName($script['file']) . '" type="' . $script['type'] . '"';
				foreach ($script['options'] as $key => $value)
					$output .= " $key=\"$value\"";
				$output .= "></script>\n";
				$result[] = $output;
			}
		}

		foreach ($this->_inlineScripts as $script){
			if ($script['location'] === $location){
				$output = '<script type="' . $script['type'] . '"';
				foreach ($script['options'] as $key => $value)
					$output .= " $key=\"$value\"";
				$output .= ">{$script['code']}</script>\n";
				$result[] = $output;
			}
		}

		return implode('', $result);

	}

	public function getStylesForLocation($location){

		$result = array();

		foreach ($this->_styles as $style){
			if ($style['location'] === $location){
				$output = '<link rel="' . $style['rel'] . '" type="' . $style['type'] . '" href="' . $this->_chooseFileName($style['file']) . '" media="' . $style['media'] . '"';
				foreach ($style['options'] as $key => $value)
					$output .= " $key=\"$value\"";
				$output .= ">\n";
				$result[] = $output;
			}
		}

		foreach ($this->_inlineStyles as $style){
			if ($style['location'] === $location){
				$output = '<style type="' . $style['type'] . '"';
				foreach ($style['options'] as $key => $value)
					$output .= " $key=\"$value\"";
				$output .= ">{$style['code']}</style>\n";
				$result[] = $output;
			}
		}

		return implode('', $result);

	}

	public function getResourcesForLocation($location){

		$resources = array(
			$this->getStylesForLocation($location),
			$this->getScriptsForLocation($location),
			"<!-- " . self::convertLocationToString($location) . " - DO NOT DELETE THIS COMMENT! -->",
		);

		return implode("\n", $resources);

	}

	/**
	 *
	 * Controller can be set in 1 of 3 ways:
	 * 1. Don't set anything at all, it will default to 'Main'
	 * 2. By specifying the controller in the url (eg. &controller=main)
	 * 3. By specifying it inside the action (eg. &action=user/view)
	 *
	 */
	public static function loadController(array $params = array()){

		$vars = self::getGetVars(array('controller', 'action'), true);

		if (!is_null($vars['action'])){
			$arr = self::splitAction($vars['action']);
			count($arr) > 0 && ($vars['controller'] = $arr['controller']);
		}

		if (is_string($vars['controller'])){

			$vars['controller'] = ucfirst($vars['controller']);

			try {
				$class = "\\Controller\\{$vars['controller']}";
				return new $class($params);
			} catch (\Exception $e){
				return new \Controller\Main($params);
			}

		} else {
			return new \Controller\Main($params);
		}

	}

	public function noAction(){

		$this->sendResponse('unknown action specified', true);

	}

	/*
	 * Hooks.
	 */
	public function onBeforeRun(array &$params = array()){
		return true;
	}

	public function onAfterRun(array $params = array()){}

	public function onBeforeRender(array &$params = array()){
		return true;
	}

	public function onAfterRender(array $params = array()){}

	public function onBeforeRenderPartial(array &$params = array()){
		return true;
	}

	public function onAfterRenderPartial(array $params = array()){
		return $params['output'];
	}

}