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

    /**
     * @group humidity
     */
    public function testGET404()
    {
        $datetime   = "20151201-231100";
        $response   = $this->client->get('/humi/1/'.$datetime.'/');

        $this->assertEquals(404, $response->getStatusCode(),"wrong response code, not 404: ".$response->getStatusCode());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody()->getContents());
    }

    /**
     * @group humidity
     */
    public function testPUTGET()
    {
        $datetime   = "20151204-231100";
        $temp       = 20.1;
        $json       = '[{"date":"2015-12-04 23:11:00","humidity":"20.1"}]';

        $response = $this->client->put('/humi/1/'.$datetime.'/'.$temp.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code, not 200: ".$response->getStatusCode());

        $response = $this->client->get('/humi/1/'.$datetime.'/');
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code, not 200: ".$response->getStatusCode());
        $this->assertEquals($json, $response->getBody()->getContents());
    }

    /**
     * @group humidity
     */
    public function testDELETE200()
    {
        $datetime   = "20151212-121212";
        $humidity   = 20.0;
        $json       = '{"deleted":1}';

        $response = $this->client->put('/humi/1/'.$datetime.'/'.$humidity.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->delete('/humi/1/'.$datetime.'/');
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());
        $this->assertEquals($json, $response->getBody()->getContents());

        //delete again -> nothing to delete
        $response = $this->client->delete('/humi/1/'.$datetime.'/');
        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$response->getStatusCode());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody()->getContents());
    }
}