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
        $this->getDB($this->db_name);
    	return json_encode(array($date => $temp));
    }

}
