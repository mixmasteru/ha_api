<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use ha\Exception\SQL as SQLException;
use PDO;

/**
 * Class ApiDb
 * @package ha
 */
class ApiDb
{
    const DATEFORMAT = "Y-m-d H:i:s";

    /**
     * @var PDO
     */
    protected $db;

    /**
     * ApiDb constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db){
        $this->db = $db;
    }

    /**
     * @param \PDOStatement $statement
     * @throws SQLException
     */
    public function checkForError(\PDOStatement $statement)
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
     * @param $table
     * @param $device_id
     * @param \DateTime $date
     * @return int count of deleted rows
     * @throws DatabaseException
     * @throws SQLException
     */
    public function deleteValue($table, $device_id, \DateTime $date)
    {
        $sql = "DELETE FROM ".$table."
                WHERE ts = :date
                AND device_id = :device_id;";

        $sth = $this->db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $sth->execute(array(':device_id' => $device_id,
                            ':date' => $date->format(ApiDb::DATEFORMAT)));

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
    public function readValuesFromDb($table, $name, $device_id, $offset, $limit)
    {
        $sql = "SELECT ts AS date, value AS ".$name." FROM " . $table . "
                WHERE  device_id = :device_id
                ORDER BY date DESC
                LIMIT :offset, :limit";

        $sth = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindValue(':device_id', $device_id);
        $sth->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $sth->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $sth->execute();
        $this->checkForError($sth);

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @return PDO
     */
    public function getDb()
    {
        return $this->db;
    }
}