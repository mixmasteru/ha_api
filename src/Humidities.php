<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use PDO;
use Tonic\NotFoundException;

/**
 *  @uri /humis/:device/:offset/:limit/
 */
class Humidities extends BaseResource
{
    protected $table = "humidity";

    /**
     * @method get
     *
     * @param $device
     * @param $offset
     * @param $limit
     * @return string
     * @throws DatabaseException
     * @throws NotFoundException
     */
    function read($device, $offset, $limit)
    {
        $arr_data = $this->readHumiditiesFromDb($device, $offset, $limit);

        if(!empty($arr_data)) {
            return json_encode($arr_data);
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * @param $device_id
     * @param $offset
     * @param $limit
     * @return array
     * @throws DatabaseException
     * @throws Exception\Database
     */
    protected function readHumiditiesFromDb($device_id, $offset, $limit)
    {
        $db = $this->getDB();
        $sql = "SELECT ts AS date, value AS temp FROM " . $this->table . "
                WHERE  device_id = :device_id
                ORDER BY date DESC
                LIMIT :offset, :limit";

        $sth = $db->prepare($sql, array(\PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->bindValue(':device_id', $device_id);
        $sth->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $sth->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $sth->execute();
        $this->checkForError($sth);

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}