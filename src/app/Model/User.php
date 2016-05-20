<?php

namespace Model;

class User extends \WebsiteConnect\Framework\Model\Model {

	const TABLE_NAME = 'user';

	protected $_id;
	protected $_last_name;
	protected $_first_name;
	protected $_email;
	protected $_role;
	protected $_department;
	protected $_dob;
	protected $_street_address_1;
	protected $_street_address_2;
	protected $_suburb;
	protected $_state;
	protected $_postcode;
	protected $_country;

	public function __construct(\WebsiteConnect\Framework\Component\Database\Client $client){
		parent::__construct(array(
			'table' => self::TABLE_NAME,
			'client' => $client,
		));
	}

	// Model implementation.
	public function create(){}
	public function read(/*Array $columns = array()*/){}
	public function update(){}
	public function delete(){}

	public static function readAll($client, Array $columns = array(), Array $criteria = array(), $start = 0, $limit = 0, $order = 'user_id asc', Array $valuesToBind = array()){

		$columns = count($columns) > 0 ? implode(',', $columns) : '*';
		$criteria = count($criteria) > 0 ? ' where ' . implode(' and ', $criteria) : '';
		$order = is_null($order) || $order === '' ? '' : " order by $order";
		$limit = " limit $start,$limit";
		$table = self::TABLE_NAME;
		$query = "select $columns from $table{$criteria}{$order}{$limit};";
		$ps = $client->prepare($query);

		if ($ps !== false){
			foreach ($valuesToBind as $k => $v) {
				if (is_string($v)){
					$ps->bindValue($k, $v);
				} else {
					$ps->bindParam($k, $v);
				}
			}
			$ps->execute();
			return $ps->fetchAll(\PDO::FETCH_ASSOC);
		}

		return false;

	}

	public static function getCount($client, Array $criteria = array(), Array $valuesToBind = array()){

		$table = self::TABLE_NAME;
		$criteria = count($criteria) > 0 ? ' where ' . implode(' and ', $criteria) : '';
		$query = "select count(*) from $table{$criteria};";
		$ps = $client->prepare($query);

		if ($ps !== false){
			foreach ($valuesToBind as $k => $v) {
				if (is_string($v)){
					$ps->bindValue($k, $v);
				} else {
					$ps->bindParam($k, $v);
				}
			}
			$ps->execute();
			return (int)$ps->fetchColumn();
		}

		return false;

	}

	public static function getDescriptions($client){

		$query = 'describe ' . self::TABLE_NAME . ';';
		$ps = $client->query($query);

		return $ps === false ? $ps : $ps->fetchAll(\PDO::FETCH_ASSOC);

	}

	public static function getStart($client){

		$table = self::TABLE_NAME;
		$query = "select user_id from $table order by user_id asc limit 1";
		$ps = $client->query($query);

		return $ps === false ? $ps : (int)$ps->fetchAll(\PDO::FETCH_ASSOC)[0]['user_id'];

	}

}