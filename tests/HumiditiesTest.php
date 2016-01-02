<?php
namespace ha;

/**
 * Class HumiditiesTest
 * @package ha
 */
class HumiditiesTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @group humidities
     */
    public function testGET404()
    {
        $response = $this->client->get('/humis/2/10/10/');

        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$response->getBody());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody());
    }

    /**
     * @group humidities
     */
    public function testGET200()
    {
        $datetime1  = "20151210-110000";
        $datetime2  = "20151211-120000";
        $humi       = 20.0;
        $json       = '[{"date":"2015-12-11 12:00:00","humidity":"20"},{"date":"2015-12-10 11:00:00","humidity":"20"}]';

        $response = $this->client->put('/humi/2/'.$datetime1.'/'.$humi.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->put('/humi/2/'.$datetime2.'/'.$humi.'/');
        $this->assertEquals(201, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());

        $response = $this->client->get('/humis/2/0/10/');
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$response->getStatusCode());
        $this->assertEquals($json, $response->getBody()->getContents());
    }


}
{

}