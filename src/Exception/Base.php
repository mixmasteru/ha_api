<?php
namespace ha\Exception;

class Base extends \Exception
{
 	public function __construct($message, $code, $previous) 
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * json representation of exception
     *
     * @return string
     */
    public function getAsJson()
    {
        return json_encode(array("exception" => self::whoAmI(),
                                "msg" => $this->getMessage() ,
                                "trace" => $this->getTrace()));
    }

    /**
     * returns class name of exception instance
     *
     * @return string
     */
    protected static function whoAmI()
    {
        return get_called_class();
    }
}