<?php
namespace ha;

/**
 * Class HumidityTests
 * @package ha
 */
class HumidityTests extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function testPUT()
    {
        $datetime   = "20151204-231100";
        $temp       = 20.1;
        $json       = '[{"date":"2015-12-04 23:11:00","humidity":"20.1"}]';

        $request = $this->client->put('/humi/1/'.$datetime.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->get('/humi/1/'.$datetime.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }
}