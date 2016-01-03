<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use PDO;
use Tonic\Resource;
use Tonic\Response;

/**
 * Created by IntelliJ IDEA.
 * User: mixmasteru
 * Date: 07.10.15
 * Time: 19:51
 */
abstract class BaseResource extends Resource
{
	/**
	 * table name
	 * @var string
	 */
	protected $table = "";

	/**
	 * @var Pimple
	 */
	public $container;

	/**
	 * @var Validator
	 */
	protected $validator;

	/**
	 * @var ApiDb
	 */
	protected $apidb;

	protected function setup()
	{
		parent::setup();
		$this->validator = new Validator();
		$this->apidb = $this->initDB();
	}

	/**
	 * @return \PDO
	 * @throws DatabaseException
	 */
	protected function initDB()
	{
		$dsn = $this->container['db_config']['dsn'];

		try {
			$pdo = new \PDO($dsn, $this->container['db_config']['username'], $this->container['db_config']['password']);
			$apidb = new ApiDb($pdo);
		} catch (\Exception $e) {
			throw new DatabaseException("db error:" . $e->getMessage(), 0, $e);
		}

		return $apidb;
	}

	/**
	 * returns OK Response if data is not empty or NOTFOUND if not
	 *
	 * @param $arr_data
	 * @return Response
	 */
	protected function createResponse($arr_data){
		if(!empty($arr_data)) {
			$response = new Response(Response::OK,json_encode($arr_data));
			$response->contentType = 'application/json';
		}else{
			$response = new Response(Response::NOTFOUND,'The server has not found anything matching the Request-URI');
		}
		return $response;
	}
}