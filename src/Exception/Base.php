<?php
namespace ha\Exception;

class Base extends \Exception
{
 	public function __construct($message, $code, $previous) 
    {
        parent::__construct($message, $code, $previous);
    }
}