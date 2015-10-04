<?php

/**
 *  @uri /temp/
 */
class Temp extends Tonic\Resource
{

    /**
     * @method get
     */
    function geti()
    {
        return json_encode("world");
    }

}
