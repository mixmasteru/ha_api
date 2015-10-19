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
		$arr_data = $this->readTempsFromDb($device, $offset, $limit);

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
	protected function readTempsFromDb($device_id, $offset, $limit)
	{
		$db = $this->getDB();
		$sql = "SELECT ts AS date, value AS temp FROM " . $this->table . "
                WHERE  device_id = :device_id
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
