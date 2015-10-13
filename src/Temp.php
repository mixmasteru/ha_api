<?php
namespace ha;
use ha\Exception\Database as DatabaseException;
use Tonic\Exception;
use PDO;
use Tonic\NotFoundException;

/**
 *  @uri /temp/:location/:date/
 *  @uri /temp/:location/:date/:temp/
 */
class Temp extends BaseResource
{
	protected $table = "location_stats";

    /**
     * @method get
     *
     * @param $date
     * @param $location
     * @return string
     * @throws DatabaseException
     * @throws Exception\Parameter
     * @throws NotFoundException
     */
    function read($location,$date)
    {
        $date = $this->checkDate($date);
        $arr_data = $this->readTempFromDb($location, $date);
        if(!empty($arr_data)) {
            return json_encode($arr_data);
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param int $location
     * @param \DateTime $date
     * @return array
     * @throws DatabaseException
     */
    protected function readTempFromDb($location, \DateTime $date)
    {
        $db = $this->getDB();
        $sql = "SELECT location, ts AS date, value AS temp FROM ".$this->table."
                WHERE ts = :date
                AND location = :location
                AND type = :type";
        try {
            $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':location' => $location,
                                ':date'     => $date->format("Y-m-d h:i:s"),
                                ':type'     => DataTypes::DB_TEMP));
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            throw new DatabaseException("read error:" . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @method put
     *
     * @param int $location
     * @param string $date
     * @param float $temp
     * @return string
     * @throws DatabaseException
     * @throws Exception\Parameter
     */
    function save($location,$date,$temp)
    {
        $date = $this->checkDate($date);
        $this->addTempToDb($location,$date,$temp);
        return json_encode(array($date->format("Y-m-d h:i:s") => $temp));
    }

    /**
     * @param $location
     * @param \DateTime $date
     * @param $temp
     * @throws DatabaseException
     */
    protected function addTempToDb($location, \DateTime $date, $temp)
    {
        $db = $this->getDB();
        $sql = "INSERT INTO ".$this->table." (`id`, `location`, `type`, `value`, `ts`)
                VALUES (NULL, :location, :type, :temp, :date);";
        try {
            $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':location' => $location,
                                ':type' => DataTypes::DB_TEMP,
                                ':temp' => $temp,
                                ':date' => $date->format("Y-m-d h:i:s")));
        } catch (Exception $e) {
            throw new DatabaseException("insert error:" . $e->getMessage(), 0, $e);
        }
    }

}
