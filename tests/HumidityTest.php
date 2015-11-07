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

    public function testGET404()
    {
        $datetime   = "20151201-231100";
        $request = $this->client->get('/humi/1/'.$datetime.'/');
        $response = $request->send();

        $this->assertEquals(404, $response->getStatusCode(),"wrong response code, not 404: ".$request->getResponse());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody());
    }

    public function testPUTGET()
    {
        $datetime   = "20151204-231100";
        $temp       = 20.1;
        $json       = '[{"date":"2015-12-04 23:11:00","humidity":"20.1"}]';

        $request = $this->client->put('/humi/1/'.$datetime.'/'.$temp.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code, not 200: ".$request->getResponse());

        $request = $this->client->get('/humi/1/'.$datetime.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code, not 200: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }
}