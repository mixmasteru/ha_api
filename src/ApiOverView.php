<?php
namespace ha;

/**
 *  @uri /
 */
class ApiOverView extends BaseResource
{
    /**
     * @method get
     */
    function overview()
    {
        return json_encode(array("dd","ddds"));
    }
}