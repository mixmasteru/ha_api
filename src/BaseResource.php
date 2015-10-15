<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use ha\Exception\SQL as SQLException;
use Tonic\Application;
use Tonic\Request;
use Tonic\Resource;

/**
 * Created by IntelliJ IDEA.
 * User: mixmasteru
 * Date: 07.10.15
 * Time: 19:51
 */
class BaseResource extends Resource
{
    /**
     * @var Pimple
     */
    public $container;

	/**
	 * @var Validator
	 */
	protected $validator;

	protected function setup()
	{
		parent::setup();
		$this->validator = new Validator();
	}

	/**
	 * @return \PDO
	 * @throws DatabaseException
	 */
    protected function getDB()
    {
    	$dsn = $this->container['db_config']['dsn'];

    	try
		{
			return new \PDO($dsn, $this->container['db_config']['username'], $this->container['db_config']['password']);
    	} catch (\Exception $e) {
    		throw new DatabaseException("db error:".$e->getMessage(), 0, $e);
    	}
    }

	/**
	 * @param \PDOStatement $statement
	 * @throws SQLException
	 */
	protected function checkForError(\PDOStatement $statement)
	{
		if($statement->errorCode() !== '00000')
		{
			$arr_error = $statement->errorInfo();
			throw new SQLException($arr_error[2],$statement->errorCode());
		}
	}
}