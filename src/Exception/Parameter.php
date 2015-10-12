<?php
namespace ha\Exception;


class Parameter extends Base
{
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct("Parameter Exception: ".$message, $code, $previous);
    }
}