<?php
namespace ha;
use Tonic\Exception;
use Tonic\NotAcceptableException;

/**
 *  @uri /temp/:date/
 *  @uri /temp/:date/:temp/
 */
class Temp extends BaseResource
{
	
	protected $db_name = "db";
	protected $table 	= "table1";
	
    /**
     * @method get
     */
    function read($date)
    {

        return json_encode(array($date => "1.0"));
    }

    /**
     * @method put
     */
    function save($date,$temp)
    {
    	return json_encode(array($date => $temp));
    }

    /**
     * @param $date
     * @param $temp
     * @throws Exception\Database
     */
    protected function addTempToDb($date,$temp)
    {
        $db = $this->getDB($this->db_name);
        $sql = "INSERT INTO `d016dba7`.`room_stats` (`id`, `room`, `type`, `value`, `ts`)
                VALUES (NULL, :room, :type, :temp, :date);";
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':room' => 1,
                            ':type' => 1,
                            ':temp' => $temp,
                            ':date' => $date));
    }

}
