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

    public function testGET404()
    {
        $request = $this->client->get('/humis/2/10/10/');
        $response = $request->send();

        $this->assertEquals(404, $response->getStatusCode(),"wrong response: ".$request->getResponse());
        $this->assertEquals('The server has not found anything matching the Request-URI', $response->getBody());
    }

    public function testGET200()
    {
        $datetime1  = "20151210-110000";
        $datetime2  = "20151211-120000";
        $humi       = 20.0;
        $json       = '[{"date":"2015-12-11 12:00:00","humidity":"1"},{"date":"2015-12-10 11:00:00","humidity":"1"}]';

        $request = $this->client->put('/humi/2/'.$datetime1.'/'.$humi.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->put('/humi/2/'.$datetime2.'/'.$humi.'/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());

        $request = $this->client->get('/humis/2/0/10/');
        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode(),"wrong response code: ".$request->getResponse());
        $this->assertEquals($json, $response->getBody(true));
    }


}
{

}