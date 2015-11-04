<?php
namespace ha;
use ha\Exception\Database as DatabaseException;
use PDO;
use Tonic\NotFoundException;

/**
 * Class Humidity
 * @package ha
 *
 *  @uri /humi/:device/:date/
 *  @uri /humi/:device/:date/:humidity/
 */
class Humidity extends BaseResource
{
    protected $table = "humidity";

    /**
     * @method get
     *
     * @param $date
     * @param $device
     * @return string
     *
     * @throws DatabaseException
     * @throws Exception\Parameter
     * @throws NotFoundException
     */
    function read($device, $date)
    {
        $device = $this->validator->checkParam($device,'device', FILTER_VALIDATE_INT);
        $date = $this->validator->checkDate($date);

        $arr_data = $this->readHumidityFromDb($device, $date);

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
    protected function readHumidityFromDb($device_id, \DateTime $date)
    {
        $db = $this->getDB();
        $sql = "SELECT ts AS date, value AS humidity FROM ".$this->table."
                WHERE ts = :date
                AND device_id = :device
                LIMIT 1";

        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':device'   => $device_id,
                            ':date'     => $date->format(self::DATEFORMAT))
        );

        $this->checkForError($sth);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @method put
     *
     * @param int $device
     * @param string $date
     * @param float $humidity
     * @return string
     * @throws DatabaseException
     * @throws Exception\Parameter
     */
    function save($device, $date, $humidity)
    {
        $date = $this->validator->checkDate($date);
        $this->addHumiToDb($device,$date,$humidity);
        return json_encode(array($date->format(self::DATEFORMAT) => $humidity));
    }

    /**
     * @param $device_id
     * @param \DateTime $date
     * @param $humidity
     * @throws DatabaseException
     */
    protected function addHumiToDb($device_id, \DateTime $date, $humidity)
    {
        $db = $this->getDB();
        $sql = "INSERT INTO ".$this->table." (id, device_id, value, ts)
                VALUES (NULL, :device_id, :humidity, :date);";
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':device_id' => $device_id,
                            ':humidity' => $humidity,
                            ':date' => $date->format(self::DATEFORMAT)));

        $this->checkForError($sth);
    }
}