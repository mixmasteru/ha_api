<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use ha\Exception\SQL as SQLException;
use ha\Exception\Parameter as ParameterException;

/**
 * Created by IntelliJ IDEA.
 * User: mixmasteru
 * Date: 07.10.15
 * Time: 19:51
 */
class BaseResource extends \Tonic\Resource
{
    /**
     * @var Pimple
     */
    public $container;

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
	 * @param $date_to_check
	 * @return \DateTime
	 * @throws ParameterException
	 */
	protected function checkDate($date_to_check){

		$date = \DateTime::createFromFormat("Ymd-his", $date_to_check);

		if($date === false || array_sum($date->getLastErrors()) > 0)
		{
			throw new ParameterException("no valid date: ".$date_to_check,0);
		}

		return $date;
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