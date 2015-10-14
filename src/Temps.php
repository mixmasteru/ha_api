<?php
namespace ha;

use ha\Exception\Database as DatabaseException;
use PDO;
use Tonic\NotFoundException;

/**
 *  @uri /temps/:location/:offset/:limit/
 */
class Temps extends BaseResource
{
	protected $table = "location_stats";

	/**
	 * @method get
	 *
	 * @param $location
	 * @param $offset
	 * @param $limit
	 * @return string
	 * @throws DatabaseException
	 * @throws NotFoundException
	 */
	function read($location,$offset,$limit)
	{
		$arr_data = $this->readTempsFromDb($location, $offset, $limit);

		if(!empty($arr_data)) {
			return json_encode($arr_data);
		}else{
			throw new NotFoundException();
		}
	}

	/**
	 * @param $location
	 * @param $offset
	 * @param $limit
	 * @return array
	 * @throws DatabaseException
	 * @throws Exception\Database
	 */
	protected function readTempsFromDb($location, $offset, $limit)
	{
		$db = $this->getDB();
		$sql = "SELECT location, ts AS date, value AS temp s FROM " . $this->table . "
                WHERE  location = :location
                LIMIT :offset, :limit";

		$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->bindValue(':location', $location);
		$sth->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
		$sth->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
		$sth->execute();
		$this->checkForError($sth);

		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}
