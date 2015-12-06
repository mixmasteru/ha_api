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
        $arr_data = $this->readHumidities($device, $offset, $limit);

        if(!empty($arr_data)) {
            return json_encode($arr_data);
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * returns array of last humidities
     *
     * @param $device_id
     * @param $offset
     * @param $limit
     * @return array
     * @throws DatabaseException
     * @throws Exception\Database
     */
    protected function readHumidities($device_id, $offset, $limit)
    {
        return $this->readValuesFromDb($this->table,"humidity",$device_id,$offset,$limit);
    }

}