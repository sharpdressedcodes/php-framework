<?php

namespace Controller;

class User extends \WebsiteConnect\Framework\Controller\Controller {

	public function __construct(array $params = array()){

		parent::__construct($params);

		$this->_layoutFile = ($this->_config['debug'] ? 'Debug' : 'Main') . '.php';

	}

	private function _sendError($csrf, $message){

		$this->sendResponse(array(
			'csrf' => $csrf,
			'error' => $message,
			'userData' => array(),
		), true);

	}

	private function _validateVars($vars){

		$required = array(
			'start',
			'limit',
			'sort',
			'order'
		);

		foreach ($required as $v) {
			if (!array_key_exists($v, $vars) || is_null($vars[$v]) || $vars[$v] === ''){
				return false;
			}
		}

		return true;

	}

	private function _buildUrl($vars){

		return sprintf(
			'%s?action=user/view&start=%s&limit=%s&sort=%s&order=%s%s',
			$_SERVER['PHP_SELF'],
			is_null($vars['start']) || $vars['start'] === '' ? 0 : $vars['start'],
			is_null($vars['limit']) || $vars['limit'] === '' ? $this->getConfig()['user']['maxRows'] : $vars['limit'],
			is_null($vars['sort']) || $vars['sort'] === '' ? 'user_id' : $vars['sort'],
			is_null($vars['order']) || $vars['order'] === '' ? 'asc' : $vars['order'],
			is_null($vars['query']) || $vars['query'] === '' ? '' : '&query=' . $vars['query']
		);
	}

	public function runViewAction(){

		$csrfName = 'token';
		$views = array();
		$varNames = array(
			'start',
			'limit',
			'query',
			'sort',
			'order',
			$csrfName,
			'reset',
			'action',
		);
		$vars = self::getGetVars($varNames);

		if (!$this->_validateVars($vars)){
			$this->redirect($this->_buildUrl($vars));
			return;
		}

		$csrf = '';

		if (count($_POST) > 0){

			$postVars = \WebsiteConnect\Framework\Controller\Controller::getPostVars($varNames);

			foreach ($postVars as $k => $v) {
				if (!is_null($v)){
					$vars[$k] = $v;
				}
			}

			if (is_null($vars[$csrfName]) || !is_string($vars[$csrfName])){
				$this->_sendError($csrf, 'Invalid csrf token.');
				return;
			} else if (!array_key_exists('csrf', $_SESSION)){
				$this->_sendError($csrf, 'Internal csrf token error.');
				return;
			} else if ($vars[$csrfName] !== $_SESSION['csrf']){
				$this->_sendError($csrf, 'Invalid csrf token.');
				return;
			}

		}

		$csrf = \WebsiteConnect\Framework\Utility\Uid::generate(64);
		$_SESSION['csrf'] = $csrf;

		$db = new \WebsiteConnect\Framework\Component\Database\Client($this->getConfig()['database']);

		if (!$db->connect()){
			$this->_sendError($csrf, "Can't connect to database.");
			return;
		} else {

			// Get max-length of search from descriptions of user table.
			$descriptions = \Model\User::getDescriptions($db);
			$queryMax = 0;
			$foundCount = 0;

			if (!is_array($descriptions)){
				$this->_sendError($csrf, 'Unable to fetch data from database.');
				return;
			}

			foreach ($descriptions as $description) {
				if (($description['Field'] === 'first_name' || $description['Field'] === 'last_name') &&
					preg_match('/^varchar\((\d+)\)$/i', $description['Type'], $matches)){
					$foundCount++;
					$i = (int)$matches[1];
					if ($i > $queryMax){
						$queryMax = $i;
					}
					if ($foundCount > 1){
						break;
					}
				}
			}

			$query = is_null($vars['query']) ? '' : trim($vars['query']);
			$query = urldecode(str_replace('+', ' ', $query));

			// Validate. Also pass the reg ex string to JS.
			$rx = str_replace('%d', $queryMax, \Model\User::QUERY_REG_EX_FIRST_NAME_LAST_NAME);

			if (!preg_match("/{$rx}/" . \Model\User::QUERY_REG_EX_FIRST_NAME_LAST_NAME_MODS, $query)){
				$query = '';
			} else {
				strlen($query) === 0 && array_key_exists('query', $_SESSION) && $vars['reset'] != 1 && ($query = $_SESSION['query']);
			}

			$realStart = \Model\User::getStart($db);

			$order = (is_string($vars['sort']) &&
				is_string($vars['order']) &&
				(strtolower($vars['order']) === 'desc' || strtolower($vars['order']) === 'asc')) ?
				$vars['sort'] . ' ' . $vars['order'] :
				'user_id asc';
			$criteria = $query === '' ? array() : array('first_name like :query or last_name like :query');
			$start = is_null($vars['start']) || !is_numeric($vars['start']) || (int)$vars['start'] < $realStart ? $realStart : (int)$vars['start'];
			$limit = is_null($vars['limit']) || !is_numeric($vars['limit']) ? $this->getConfig()['user']['maxRows'] : (int)$vars['limit'];
			$maxPages = $this->getConfig()['user']['maxPages'];

			$valuesToBind = array(':query' => "$query%");
			$total = \Model\User::getCount($db, $criteria, $valuesToBind);

			$start > $total - 1 && ($start = 0);
			$limit < 1 && ($limit = 1);

			$pages = (int)ceil($total / $limit);
			$current = (int)ceil($start + $limit)  / $limit;

			$columns = array(
				'user_id as id',
				"first_name as 'first name'",
				"last_name as 'last name'",
				'role',
				'department',
				"date_format(now(), '%Y') - date_format(dob, '%Y') - (date_format(now(), '00-%m-%d') < date_format(dob, '00-%m-%d')) as age",
				'dob',
				"street_address_1 as 'street address 1'",
				"street_address_2 as 'street address 2'",
				'suburb',
				'state',
				'postcode',
				'country',
			);
			$users = \Model\User::readAll($db, $columns, $criteria, $start, $limit, $order, $valuesToBind);

		}

		$userData = array();
		$json = array();

		foreach ($users as $user) {
			$json[] = json_encode($user);
			$user[' '] = '<button class="btn btn-xs btn-default user-options" title="View Information">
							<span class="glyphicon glyphicon-new-window"></span>
						</button>';
			$userData[] = $user;
		}

		$allowedFields = $this->getConfig()['user']['allowedFields'];

		if ($vars['reset'] == 1 || strlen($query) === 0){
			unset($_SESSION['query']);
		} else {
			$_SESSION['query'] = $query;
		}

		if ($this->isJsonRequest()){
			$this->sendResponse(array(
				'userData' => $userData,
				'allowedFields' => $allowedFields,
				'json' => $json,
				'total' => $total,
				'pages' => $pages,
				'start' => $start,
				'current' => $current,
				'limit' => $limit,
				'query' => $query,
				'sort' => $vars['sort'],
				'order' => $vars['order'],
				'csrf' => $csrf,
				'maxPages' => $maxPages,
				'queryRegEx' => $rx,
				'queryRegExMods' => \Model\User::QUERY_REG_EX_FIRST_NAME_LAST_NAME_MODS
			));
			return;
		}

		$setLoopVars = function() use (&$start, &$limit, &$pages, &$current, &$maxPages){

			$startIndex = 0;
			$startPage = 0;
			$endPage = 0;
			$lastPossibleStartIndex = $limit * ($pages - $maxPages);
			$lastPossibleStartIndex < 0 && ($lastPossibleStartIndex = 0);

			$setVars = function() use (&$startIndex, &$startPage, &$endPage, &$lastPossibleStartIndex, &$start, &$limit, &$pages, &$maxPages) {

				$startIndex > $lastPossibleStartIndex && ($startIndex = $lastPossibleStartIndex);
				$startIndex < 0 && ($startIndex = 0);

				$startPage = ($startIndex / $limit) + 1;

				$endPage = $startPage + $maxPages;
				$endPage > $pages + 1 && ($endPage = $pages + 1);

			};

			$setVars();

			($endPage - 1) * $limit <= $start && ($startIndex = $start);

			$setVars();

			return array(
				'startIndex' => $startIndex,
				'startPage' => $startPage,
				'endPage' => $endPage,
			);

		};
		$paginationLoopVars = $setLoopVars();

		$searchAction = sprintf(
			'%s?action=%s&start=0&limit=%s&sort=%s&order=%s',
			$_SERVER['PHP_SELF'],
			$vars['action'],
			$limit,
			$vars['sort'],
			$vars['order']
		);

		$views[] = array(
			'view' => 'User/view',
			'var' => 'content',
			'viewParams' => array(
				'data' => $userData,
				'total' => $total,
				'start' => $start,
				'limit' => $limit,
				'query' => $query,
				'pages' => $pages,
				'current' => $current,
				'queryMax' => $queryMax,
				'sort' => $vars['sort'],
				'order' => $vars['order'],
				'csrf' => $csrf,
				'allowedFields' => $allowedFields,
				'maxPages' => $maxPages,
				'searchAction' => $searchAction,
				'sortUrlCallback' => function($key, $order = 'asc'){

					$key = strtolower(str_replace('id', 'user_id', str_replace(' ', '_', $key)));

					$url = \WebsiteConnect\Framework\Utility\Url::replace($_SERVER['QUERY_STRING'], array('sort', 'order'), array($key, $order));
					$url = \WebsiteConnect\Framework\Utility\Url::strip($url, 'reset');
					$test = $url === $_SERVER['QUERY_STRING'];
					$url = \WebsiteConnect\Framework\Utility\Url::replace($url, 'start', 0);

					return $test ? null : '?' . $url;

				},
				'closeSearchUrl' => \WebsiteConnect\Framework\Utility\Url::strip($_SERVER['QUERY_STRING'], array('query', 'reset')) . '&reset=1',
				'closeSearchText' => 'Close search',
				'paginationUrlCallback' => function($current, $start, $limit, $pages, $index, $type = '') {

					$qs = $_SERVER['QUERY_STRING'];

					switch (strtolower($type)){
						case 'first':
							return $current === 1 ? '#' : '?' . \WebsiteConnect\Framework\Utility\Url::replace($qs, 'start', 0);

						case 'previous':
							return $current === 1 ? '#' : '?' . \WebsiteConnect\Framework\Utility\Url::replace($qs, 'start', $start - $limit);

						case 'next':
							return $current === $pages ? '#' : '?' . \WebsiteConnect\Framework\Utility\Url::replace($qs, 'start', $start + $limit);

						case 'last':
							return $current === $pages ? '#' : '?' . \WebsiteConnect\Framework\Utility\Url::replace($qs, 'start', ($pages - 1) * $limit);

						default:
							return $current === $index ? '#' : '?' . \WebsiteConnect\Framework\Utility\Url::replace($qs, 'start', ($index - 1) * $limit);
					}

				},
				'startPage' => $paginationLoopVars['startPage'],
				'endPage' => $paginationLoopVars['endPage'],
				'json' => array_map(function($value){return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');}, $json),
				'tableId' => 'user-table',
				'paginationId' => 'user-pagination',
				'searchFormId' => 'search-form',
				'closeSearchId' => 'close-search-container',
				'searchPlaceHolder' => $this->getConfig()['user']['searchPlaceHolder'],
				'csrfName' => $csrfName,
				'queryName' => 'query',
				'queryRegEx' => addslashes($rx),
				'queryRegExMods' => \Model\User::QUERY_REG_EX_FIRST_NAME_LAST_NAME_MODS,
				'noUsersMessage' => $this->getConfig()['user']['noResultsMessage'],
				'errorMessages' => $total === 0 ? array($this->getConfig()['user']['noResultsMessage']) : array(),
			),
		);

		$this->render(array(
			'layout' => $this->_layoutFile,
			'views' => $views,
		));

	}

}