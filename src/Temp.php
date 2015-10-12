<?php
namespace ha;
use ha\Exception\Database as DatabaseException;
use Tonic\Exception;
use PDO;
use Tonic\NotFoundException;

/**
 *  @uri /temp/:date/
 *  @uri /temp/:date/:temp/
 */
class Temp extends BaseResource
{
	protected $table = "room_stats";
	
    /**
     * @method get
     */
    function read($date)
    {
        $date = $this->checkDate($date);
        $arr_data = $this->readTempFromDb($date);
        if(!empty($arr_data)) {
            return json_encode($arr_data);
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param \DateTime $date
     * @return array
     * @throws DatabaseException
     */
    protected function readTempFromDb(\DateTime $date)
    {
        $db = $this->getDB();
        $sql = "SELECT ts AS date, value AS temp FROM `room_stats` WHERE `ts` = :date AND type = :type";
        try {
            $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':date' => $date->format("Y-m-d h:i:s"),':type' => DataTypes::DB_TEMP));
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            throw new DatabaseException("read error:" . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @method put
     * @param $date
     * @param $temp
     * @return string
     * @throws DatabaseException
     * @throws Exception\Parameter
     */
    function save($date,$temp)
    {
        $date = $this->checkDate($date);
        $this->addTempToDb($date,$temp);
        return json_encode(array($date->format("Y-m-d h:i:s") => $temp));
    }

    /**
     * @param \DateTime $date
     * @param $temp
     * @throws DatabaseException
     */
    protected function addTempToDb(\DateTime $date, $temp)
    {
        $db = $this->getDB();
        $sql = "INSERT INTO `room_stats` (`id`, `room`, `type`, `value`, `ts`)
                VALUES (NULL, :room, :type, :temp, :date);";
        try {
            $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(':room' => 1,
                ':type' => 1,
                ':temp' => $temp,
                ':date' => $date->format("Y-m-d h:i:s")));
        } catch (Exception $e) {
            throw new DatabaseException("insert error:" . $e->getMessage(), 0, $e);
        }
    }

}
