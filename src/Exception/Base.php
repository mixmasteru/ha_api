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
        return json_encode(array("exception" => $this->whoAmI(),
                                "msg" => $this->getMessage() ,
                                "trace" => $this->getTrace()));
    }

    /**
     * returns class name of exception instance
     *
     * @return string
     */
    protected function whoAmI()
    {
        return get_class($this);
    }
}