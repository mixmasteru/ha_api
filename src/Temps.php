<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use PDO;
use Tonic\NotFoundException;

/**
 *  @uri /temps/:device/:offset/:limit/
 */
class Temps extends BaseResource
{
	protected $table = "temperature";

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
		$arr_data = $this->readTemps($device, $offset, $limit);

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
	protected function readTemps($device_id, $offset, $limit)
	{
		return $this->apidb->readValuesFromDb($this->table,"temp",$device_id,$offset,$limit);
	}
}
