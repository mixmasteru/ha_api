<?php
namespace ha;
use ha\Exception\Database as DatabaseException;
use PDO;
use Tonic\NotFoundException;

/**
 *  @uri /temp/(\d)/:date/
 *  @uri /temp/(\d)/:date/:temp/
 */
class Temp extends BaseResource
{
	protected $table = "temperature";

    /**
     * @method get
     *
     * @param $date
     * @param $device
     * @return string
     * @throws DatabaseException
     * @throws Exception\Parameter
     * @throws NotFoundException
     */
    function read($device, $date)
    {
        $date = $this->validator->checkDate($date);
        $arr_data = $this->readTempFromDb($device, $date);

        if(!empty($arr_data)) {
            return json_encode($arr_data);
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param int $device_id
     * @param \DateTime $date
     * @return array
     * @throws DatabaseException
     */
    protected function readTempFromDb($device_id, \DateTime $date)
    {
        $db = $this->getDB();
        $sql = "SELECT ts AS date, value AS temp FROM ".$this->table."
                WHERE ts = :date
                AND device_id = :device
                LIMIT 1";

        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':device' => $device_id,
                            ':date'     => $date->format(self::DATEFORMAT))
                            );
        $this->checkForError($sth);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @method put
     *
     * @todo move $temp from uri to body
     * @param int $device
     * @param string $date
     * @param float $temp
     * @return string
     * @throws DatabaseException
     * @throws Exception\Parameter
     */
    function save($device, $date, $temp)
    {
        $date = $this->validator->checkDate($date);
        $temp = $this->validator->checkParam($temp,"temp",FILTER_VALIDATE_FLOAT);

        $this->addTempToDb($device,$date,$temp);
        return json_encode(array($date->format(self::DATEFORMAT) => $temp));
    }

    /**
     * @param $device_id
     * @param \DateTime $date
     * @param $temp
     * @throws DatabaseException
     */
    protected function addTempToDb($device_id, \DateTime $date, $temp)
    {
        $db = $this->getDB();
        $sql = "INSERT INTO ".$this->table." (id, device_id, value, ts)
                VALUES (NULL, :device_id, :temp, :date);";
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':device_id' => $device_id,
                            ':temp' => $temp,
                            ':date' => $date->format(self::DATEFORMAT)));

        $this->checkForError($sth);
    }

    /**
     * @method delete
     *
     * @param $device
     * @param \DateTime $date
     * @return string
     * @throws Exception\Parameter
     * @throws NotFoundException
     */
    function remove($device, $date)
    {
        $date = $this->validator->checkDate($date);
        $cnt = $this->deleteValue($device,$date);

        if($cnt !== 0) {
            return json_encode(array("deleted" => $cnt));
        }else{
            throw new NotFoundException();
        }
    }

}