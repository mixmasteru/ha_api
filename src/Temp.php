<?php
namespace ha;
use Tonic\Exception;
use Tonic\NotAcceptableException;

/**
 *  @uri /temp/:date/
 *  @uri /temp/:date/:temp/
 */
class Temp extends \Tonic\Resource
{

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

}
