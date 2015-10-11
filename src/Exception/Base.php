<?php
namespace ha\Exception;

class Base extends \Exception
{
 	public function __construct($message, $code, $previous) 
    {
        parent::__construct($message, $code, $previous);
    }

    public function getAsJson()
    {
        return json_encode(array("msg" => $this->getMessage() ,"trace" => $this->getTrace()));
    }
}