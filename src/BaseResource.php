<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use ha\Exception\SQL as SQLException;
use PDO;
use Tonic\Resource;

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

	const DATEFORMAT = "Y-m-d H:i:s";

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

		try {
			return new \PDO($dsn, $this->container['db_config']['username'], $this->container['db_config']['password']);
		} catch (\Exception $e) {
			throw new DatabaseException("db error:" . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * @param \PDOStatement $statement
	 * @throws SQLException
	 */
	protected function checkForError(\PDOStatement $statement)
	{
		if ($statement->errorCode() !== '00000') {
			$arr_error = $statement->errorInfo();
			throw new SQLException($arr_error[2], $statement->errorCode());
		}
	}

	/**
	 * deletes entry from this->table with
	 * given device & datetime
	 *
	 * @param $device_id
	 * @param \DateTime $date
	 * @return int count of deleted rows
	 * @throws DatabaseException
	 * @throws SQLException
	 */
	protected function deleteValue($device_id, \DateTime $date)
	{
		$db = $this->getDB();
		$sql = "DELETE FROM ".$this->table."
                WHERE ts = :date
                AND device_id = :device_id;";

		$sth = $db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
		$sth->execute(array(':device_id' => $device_id,
							':date' => $date->format(self::DATEFORMAT)));

		$this->checkForError($sth);
		return $sth->rowCount();
	}

	/**
	 * generic select of values in table
	 *
	 * @param string $table table name
	 * @param string $name value name
	 * @param int $device_id
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 * @throws DatabaseException
	 * @throws Exception\Database
	 */
	protected function readValuesFromDb($table, $name, $device_id, $offset, $limit)
	{
		$db = $this->getDB();
		$sql = "SELECT ts AS date, value AS ".$name." FROM " . $table . "
                WHERE  device_id = :device_id
                ORDER BY date DESC
                LIMIT :offset, :limit";

		$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->bindValue(':device_id', $device_id);
		$sth->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
		$sth->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
		$sth->execute();
		$this->checkForError($sth);

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}