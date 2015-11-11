<?php
namespace ha;
use Tonic\Application;

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

        $config = array(
            'load' => array(BASE.'/src/*.php') // load resources
        );

        $app = new Application($config);

        $arr_resources = $app->resources;
        $arr_data = array();
        foreach ($arr_resources as $name => $data)
        {
            $resource = $app->getResourceMetadata($name);
            $arr_data[$name] = $resource->getUri();
        }

        return json_encode($arr_data);
    }
}