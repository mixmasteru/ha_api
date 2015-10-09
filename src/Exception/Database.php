<?php
namespace ha\Exception;


class Database extends Base
{
 	public function __construct($message, $code, $previous) 
    {
        parent::__construct($message, $code, $previous);
    }
}