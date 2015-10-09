<?php
namespace ha;

/**
 *  @uri /temps/
 *  @uri /temps/:limit/
 */
class Temps extends \Tonic\Resource
{

	/**
	 * @method get
	 */
	function read($limit = 10)
	{
		$arr_return = array();
		for ($i = 0; $i < $limit; $i++)
		{
			$arr_return['201501'.$i] = $i.".".$i;
		}
		return json_encode($arr_return);
	}
}
